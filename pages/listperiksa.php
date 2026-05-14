<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$bookingStatus = null;
$bookingTitle = '';
$bookingMessage = '';
$bookingData = null;

if (isset($_POST['btnBooking'])) {
    $captchaInput = cleankar2((string)($_POST["inputcaptcha"] ?? ''));
    $captchaCheck = $captchaInput !== '' ? getOne2("select aes_encrypt('{$captchaInput}','windi')") : '';

    if (empty($_SESSION["Capcay"]) || $_SESSION["Capcay"] !== $captchaCheck) {
        $bookingStatus = 'danger';
        $bookingTitle = 'Captcha tidak sesuai';
        $bookingMessage = 'Silakan ulangi verifikasi captcha sebelum mengirim booking.';
    } else {
        $nama = strtoupper((string)clean_post('nama', 120, true));
        $alamat = strtoupper((string)clean_post('alamat', 255, true));
        $nohp = clean_post('nohp', 20, true);
        $email = clean_post('email', 120, true);
        $pesan = clean_post('pesan', 255, true);
        $tglDaftar = clean_post('TglDaftar', 2, true);
        $blnDaftar = clean_post('BlnDaftar', 2, true);
        $thnDaftar = clean_post('ThnDaftar', 4, true);
        $poli = clean_post('poli', 20, true);
        $tanggalPeriksa = "{$thnDaftar}-{$blnDaftar}-{$tglDaftar}";
        $tanggalHariIni = date('Y-m-d');
        $sekarang = date("Y-m-d H:i:s");

        if (!$nama || !$alamat || !$nohp || !$email || !$pesan || !$tglDaftar || !$blnDaftar || !$thnDaftar || !$poli) {
            $bookingStatus = 'warning';
            $bookingTitle = 'Data belum lengkap';
            $bookingMessage = 'Semua field wajib diisi sebelum booking dikirim.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $bookingStatus = 'warning';
            $bookingTitle = 'Email tidak valid';
            $bookingMessage = 'Gunakan alamat email yang valid agar konfirmasi booking bisa dikirim.';
        } elseif ($tanggalPeriksa <= $tanggalHariIni) {
            $bookingStatus = 'warning';
            $bookingTitle = 'Tanggal belum memenuhi syarat';
            $bookingMessage = 'Booking publik hanya dapat dilakukan minimal 1 hari sebelum tanggal periksa.';
        } else {
            $max = getOne2("select ifnull(MAX(CONVERT(RIGHT(no_booking,4),signed)),0)+1 from booking_periksa where tanggal='$tanggalPeriksa'");
            $noUrut = "BP{$thnDaftar}{$blnDaftar}{$tglDaftar}" . sprintf("%04s", $max);
            $insert = Tambah4("booking_periksa", "'$noUrut','$tanggalPeriksa','$nama','$alamat','$nohp','$email','$poli','$pesan','Belum Dibalas','$sekarang'");

            if ($insert) {
                $bookingStatus = 'success';
                $bookingTitle = 'Booking berhasil dikirim';
                $bookingMessage = 'Simpan nomor booking Anda. Tim admin akan melakukan verifikasi jadwal dan kuota dokter terlebih dahulu.';
                $bookingData = [
                    'no_booking' => $noUrut,
                    'tanggal_booking' => $sekarang,
                    'tanggal_periksa' => $tanggalPeriksa,
                    'nama' => $nama,
                    'no_telp' => $nohp,
                    'email' => $email,
                    'alamat' => $alamat,
                    'nm_poli' => getOne("select nm_poli from poliklinik where kd_poli='$poli'"),
                ];
            } else {
                $bookingStatus = 'danger';
                $bookingTitle = 'Booking gagal diproses';
                $bookingMessage = 'Nomor HP/Telp tersebut kemungkinan sudah digunakan untuk booking pada tanggal yang sama.';
            }
        }
    }
}

$alertClass = [
    'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    'danger' => 'border-rose-200 bg-rose-50 text-rose-800',
];

$queryPoli = bukaquery("select kd_poli,nm_poli from poliklinik where status='1' order by nm_poli");
visitor_page_intro('Booking Publik', 'Form Booking Pemeriksaan', 'Silakan isi data booking untuk mengajukan pemeriksaan. Konfirmasi diteruskan setelah admin memverifikasi ketersediaan jadwal.');
visitor_section_open();
?>
<div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]">
    <div class="surface-card p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Isi Data Booking</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Pastikan nomor telepon dan email aktif agar proses konfirmasi berjalan lancar.</p>

        <form method="post" action="" class="mt-8 grid gap-5">
            <div>
                <label for="nama" class="field-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="field-input" value="<?= e((string)($_POST['nama'] ?? '')); ?>" required>
            </div>
            <div>
                <label for="alamat" class="field-label">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" class="field-input" required><?= e((string)($_POST['alamat'] ?? '')); ?></textarea>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="nohp" class="field-label">Nomor HP / Telepon</label>
                    <input type="text" name="nohp" id="nohp" class="field-input" pattern="[0-9]{8,20}" value="<?= e((string)($_POST['nohp'] ?? '')); ?>" required>
                </div>
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input type="email" name="email" id="email" class="field-input" value="<?= e((string)($_POST['email'] ?? '')); ?>" required>
                </div>
            </div>
            <div>
                <label for="poli" class="field-label">Poliklinik / Unit Tujuan</label>
                <select name="poli" id="poli" class="field-input" required>
                    <option value="">Pilih poli</option>
                    <?php while ($poliRow = mysqli_fetch_array($queryPoli)) : ?>
                        <option value="<?= e($poliRow['kd_poli']); ?>" <?= (($_POST['poli'] ?? '') === $poliRow['kd_poli']) ? 'selected' : ''; ?>>
                            <?= e($poliRow['nm_poli']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                <div>
                    <label for="TglDaftar" class="field-label">Tanggal</label>
                    <select name="TglDaftar" id="TglDaftar" class="field-input" required>
                        <option value="">DD</option>
                        <?php for ($i = 1; $i <= 31; $i++) : $val = str_pad((string)$i, 2, '0', STR_PAD_LEFT); ?>
                            <option value="<?= $val; ?>" <?= (($_POST['TglDaftar'] ?? '') === $val) ? 'selected' : ''; ?>><?= $val; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="BlnDaftar" class="field-label">Bulan</label>
                    <select name="BlnDaftar" id="BlnDaftar" class="field-input" required>
                        <option value="">MM</option>
                        <?php for ($i = 1; $i <= 12; $i++) : $val = str_pad((string)$i, 2, '0', STR_PAD_LEFT); ?>
                            <option value="<?= $val; ?>" <?= (($_POST['BlnDaftar'] ?? '') === $val) ? 'selected' : ''; ?>><?= $val; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="ThnDaftar" class="field-label">Tahun</label>
                    <select name="ThnDaftar" id="ThnDaftar" class="field-input" required>
                        <option value="">YYYY</option>
                        <?php for ($i = (int)date('Y'); $i <= (int)date('Y') + 1; $i++) : ?>
                            <option value="<?= $i; ?>" <?= (($_POST['ThnDaftar'] ?? '') === (string)$i) ? 'selected' : ''; ?>><?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div>
                <label for="pesan" class="field-label">Pesan Tambahan</label>
                <textarea name="pesan" id="pesan" rows="4" class="field-input" required><?= e((string)($_POST['pesan'] ?? '')); ?></textarea>
            </div>
            <div>
                <label for="inputcaptcha" class="field-label">Verifikasi Captcha</label>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <img src="pages/captcha.php?ts=<?= time(); ?>" alt="Captcha" class="h-16 rounded-2xl border border-slate-200 bg-white p-2">
                    <input type="text" name="inputcaptcha" id="inputcaptcha" class="field-input sm:max-w-xs" autocomplete="off" required>
                </div>
            </div>
            <button type="submit" class="btn-primary w-full" name="btnBooking">Kirim Booking</button>
        </form>
    </div>

    <div class="surface-card p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Status Pengajuan</h2>
        <?php if ($bookingStatus !== null) : ?>
            <div class="mt-6 rounded-3xl border px-5 py-5 <?= $alertClass[$bookingStatus] ?? $alertClass['warning']; ?>">
                <h3 class="text-lg font-bold"><?= e($bookingTitle); ?></h3>
                <p class="mt-2 text-sm leading-7"><?= e($bookingMessage); ?></p>
            </div>

            <?php if ($bookingData) : ?>
                <div class="mt-6 grid gap-4">
                    <div class="rounded-3xl bg-slate-50 p-5"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nomor Booking</p><p class="mt-2 text-base font-bold text-slate-900"><?= e($bookingData['no_booking']); ?></p></div>
                    <div class="rounded-3xl bg-slate-50 p-5"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tanggal Booking</p><p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingData['tanggal_booking']); ?></p></div>
                    <div class="rounded-3xl bg-slate-50 p-5"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tanggal Periksa</p><p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingData['tanggal_periksa']); ?></p></div>
                    <div class="rounded-3xl bg-slate-50 p-5"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nama Pasien</p><p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingData['nama']); ?></p></div>
                    <div class="rounded-3xl bg-slate-50 p-5"><p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Poliklinik</p><p class="mt-2 text-sm font-semibold text-slate-800"><?= e($bookingData['nm_poli']); ?></p></div>
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="pages/CetakBooking.php?iyem=<?= urlencode(encrypt_decrypt(json_encode(['nobooking' => $bookingData['no_booking']]), 'e')); ?>" target="_blank" rel="noreferrer" class="btn-primary">Cetak Bukti Booking</a>
                    <a href="index.php?act=CekBooking" class="btn-secondary">Cek Status Booking</a>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <?php visitor_empty_state('Setelah form booking dikirim, status pengajuan dan ringkasan booking akan tampil di sini.'); ?>
        <?php endif; ?>
    </div>
</div>
<?php visitor_section_close(); ?>
