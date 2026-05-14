<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$resultType = null;
$resultTitle = '';
$resultMessage = '';
$bookingDetail = null;

if (isset($_POST['btnCekBooking'])) {
    $nohp = clean_post('nohp', 20);
    $nobooking = clean_post('nobooking', 20);

    if ($nohp && $nobooking) {
        $querycekbooking = bukaquery("select count(no_booking) as noboking, if(tanggal>current_date,'aman','kadaluarsa') as status_tanggal, status from booking_periksa where no_booking='$nobooking' and no_telp='$nohp'");
        if ($rsquerycekbooking = mysqli_fetch_array($querycekbooking)) {
            if ((int) $rsquerycekbooking["noboking"] === 0) {
                $resultType = 'warning';
                $resultTitle = 'Booking tidak ditemukan';
                $resultMessage = 'Periksa kembali nomor booking dan nomor HP/Telp yang Anda masukkan.';
            } elseif ($rsquerycekbooking["status_tanggal"] === 'kadaluarsa') {
                $resultType = 'warning';
                $resultTitle = 'Booking sudah kadaluarsa';
                $resultMessage = 'Silakan melakukan booking ulang untuk jadwal pemeriksaan yang baru.';
            } elseif ($rsquerycekbooking["status"] === 'Belum Dibalas') {
                $resultType = 'info';
                $resultTitle = 'Booking masih ditinjau';
                $resultMessage = 'Booking Anda sedang menunggu verifikasi admin. Silakan cek kembali beberapa saat lagi.';
            } elseif ($rsquerycekbooking["status"] === 'Ditolak') {
                $balasan = getOne2("select balasan from booking_periksa_balasan where no_booking='$nobooking'");
                $resultType = 'danger';
                $resultTitle = 'Booking ditolak';
                $resultMessage = 'Mohon maaf, booking Anda belum dapat diproses.' . ($balasan ? ' Catatan admin: ' . $balasan : '') . ' Silakan ajukan booking baru untuk tanggal atau poli lain.';
            } elseif ($rsquerycekbooking["status"] === 'Diterima') {
                $querycekbookingperiksa = bukaquery("select booking_registrasi.tanggal_booking,booking_registrasi.jam_booking,booking_registrasi.no_rkm_medis,booking_periksa.nama,booking_periksa.alamat,booking_periksa.no_telp,booking_periksa.email,booking_registrasi.tanggal_periksa,dokter.nm_dokter,poliklinik.nm_poli,booking_registrasi.no_reg,aes_decrypt(personal_pasien.password,'windi') as pass from booking_registrasi inner join dokter on booking_registrasi.kd_dokter=dokter.kd_dokter inner join poliklinik on booking_registrasi.kd_poli=poliklinik.kd_poli inner join booking_periksa_diterima on booking_periksa_diterima.no_rkm_medis=booking_registrasi.no_rkm_medis inner join booking_periksa on booking_periksa_diterima.no_booking=booking_periksa.no_booking inner join personal_pasien on booking_registrasi.no_rkm_medis=personal_pasien.no_rkm_medis where booking_periksa.no_booking='$nobooking'");
                if ($rsquerycekbookingperiksa = mysqli_fetch_array($querycekbookingperiksa)) {
                    $balasan = getOne2("select balasan from booking_periksa_balasan where no_booking='$nobooking'");
                    $resultType = 'success';
                    $resultTitle = 'Booking diterima';
                    $resultMessage = 'Booking Anda telah diverifikasi admin.' . ($balasan ? ' Catatan admin: ' . $balasan : '');
                    $bookingDetail = $rsquerycekbookingperiksa;
                } else {
                    $resultType = 'danger';
                    $resultTitle = 'Terjadi kesalahan';
                    $resultMessage = 'Detail booking tidak dapat dimuat saat ini.';
                }
            }
        } else {
            $resultType = 'danger';
            $resultTitle = 'Terjadi kesalahan';
            $resultMessage = 'Proses pengecekan booking gagal dilakukan.';
        }
    } else {
        $resultType = 'warning';
        $resultTitle = 'Data belum lengkap';
        $resultMessage = 'Nomor booking dan nomor HP/Telp wajib diisi.';
    }
}

$alertClass = [
    'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    'info' => 'border-sky-200 bg-sky-50 text-sky-800',
    'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    'danger' => 'border-rose-200 bg-rose-50 text-rose-800',
];

visitor_page_intro('Cek Booking', 'Pantau Status Booking Anda', 'Masukkan nomor booking dan nomor HP/Telp untuk melihat status verifikasi booking secara cepat.');
visitor_section_open();
?>
<div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
    <div class="surface-card p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Form Pengecekan</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Gunakan data yang sama seperti saat melakukan pengajuan
            booking.</p>

        <form method="post" action="" class="mt-8 space-y-5">
            <div>
                <label for="nobooking" class="field-label">Nomor Booking</label>
                <input type="text" class="field-input" pattern="[A-Z0-9]{1,20}"
                    title="Maksimal 20 karakter huruf kapital atau angka" required name="nobooking" id="nobooking"
                    placeholder="Contoh: BP202605120001" autocomplete="off"
                    value="<?= e((string) ($_POST['nobooking'] ?? '')); ?>" autofocus>
            </div>
            <div>
                <label for="nohp" class="field-label">Nomor HP / Telepon</label>
                <input type="tel" class="field-input" id="nohp" pattern="[0-9]{1,20}" title="Maksimal 20 digit angka"
                    required name="nohp" placeholder="Nomor yang digunakan saat booking" autocomplete="off"
                    value="<?= e((string) ($_POST['nohp'] ?? '')); ?>">
            </div>
            <button type="submit" class="btn-primary w-full" name="btnCekBooking">Cek Status Booking</button>
        </form>
    </div>

    <div class="surface-card p-6 space-y-4 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Hasil Pengecekan</h2>
        <?php if ($resultType !== null): ?>
            <div class="mt-6 rounded-3xl border px-5 py-5 <?= $alertClass[$resultType] ?? $alertClass['info']; ?>">
                <h3 class="text-lg font-bold"><?= e($resultTitle); ?></h3>
                <p class="mt-2 text-sm leading-7"><?= e($resultMessage); ?></p>
            </div>

            <?php if ($bookingDetail): ?>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tgl. Booking</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800">
                            <?= e($bookingDetail["tanggal_booking"] . ' ' . $bookingDetail["jam_booking"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tgl. Periksa</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["tanggal_periksa"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">No. Rekam Medis</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["no_rkm_medis"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nama Pasien</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["nama"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">No. HP / Telepon</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["no_telp"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["email"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5 md:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["alamat"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Poliklinik</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["nm_poli"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Dokter</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["nm_dokter"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nomor Antrian</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["no_reg"]); ?></p>
                    </div>
                    <div class="rounded-3xl bg-nu-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-nu-700">Password Login</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingDetail["pass"]); ?></p>
                    </div>
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="index.php?act=LoginPasien" class="btn-primary">Masuk ke EPasien</a>
                    <a href="pages/CetakBooking.php?iyem=<?= urlencode(encrypt_decrypt(json_encode(['nobooking' => $nobooking]), 'e')); ?>"
                        target="_blank" rel="noreferrer" class="btn-secondary">Cetak Bukti Booking</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php visitor_empty_state('Hasil pengecekan booking akan tampil di area ini setelah Anda mengirim formulir.'); ?>
        <?php endif; ?>
    </div>
</div>
<?php visitor_section_close(); ?>