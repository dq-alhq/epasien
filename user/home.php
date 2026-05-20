<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$noRm = cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d"));
$jumlahKunjungan = (int) getOne("SELECT count(reg_periksa.no_rkm_medis) FROM reg_periksa WHERE reg_periksa.no_rkm_medis = '$noRm'");
$jumlahRalan = (int) getOne("SELECT count(reg_periksa.no_rkm_medis) FROM reg_periksa WHERE reg_periksa.no_rkm_medis = '$noRm' AND reg_periksa.status_lanjut = 'Ralan'");
$jumlahRanap = (int) getOne("SELECT count(reg_periksa.no_rkm_medis) FROM reg_periksa WHERE reg_periksa.no_rkm_medis = '$noRm' AND reg_periksa.status_lanjut = 'Ranap'");
$jumlahBulanIni = (int) getOne("SELECT count(reg_periksa.no_rkm_medis) FROM reg_periksa WHERE reg_periksa.tgl_registrasi LIKE '%" . date('Y-m') . "%' AND reg_periksa.no_rkm_medis = '$noRm'");

$hari = getOne("select DAYNAME(current_date())");
$queryjadwal = bukaquery("Select dokter.nm_dokter, poliklinik.nm_poli, jadwal.jam_mulai, jadwal.jam_selesai, poliklinik.kd_poli, jadwal.kuota, dokter.kd_dokter, pegawai.photo from jadwal inner join dokter on dokter.kd_dokter=jadwal.kd_dokter inner join poliklinik on jadwal.kd_poli=poliklinik.kd_poli inner join pegawai on dokter.kd_dokter=pegawai.nik where jadwal.hari_kerja like '%" . konversiHari($hari) . "%'");
$querypengumuman = bukaquery("SELECT pegawai.nama, DATE_FORMAT(pengumuman_epasien.tanggal,'%d/%m/%Y') AS tanggal, pengumuman_epasien.pengumuman FROM pengumuman_epasien INNER JOIN pegawai ON pengumuman_epasien.nik = pegawai.nik ORDER BY pengumuman_epasien.tanggal DESC LIMIT 3");
$querybooking = bukaquery("select booking_registrasi.tanggal_booking,booking_registrasi.jam_booking,booking_registrasi.tanggal_periksa,booking_registrasi.kd_dokter,dokter.nm_dokter,booking_registrasi.kd_poli,poliklinik.nm_poli,booking_registrasi.no_reg,booking_registrasi.kd_pj,penjab.png_jawab,booking_registrasi.status from booking_registrasi inner join dokter on booking_registrasi.kd_dokter=dokter.kd_dokter inner join poliklinik on booking_registrasi.kd_poli=poliklinik.kd_poli inner join penjab on booking_registrasi.kd_pj=penjab.kd_pj where booking_registrasi.no_rkm_medis='$noRm' and booking_registrasi.status='Belum' and booking_registrasi.tanggal_periksa=current_date()");
$queryregistrasi = bukaquery("select reg_periksa.no_reg,reg_periksa.no_rawat,reg_periksa.tgl_registrasi,reg_periksa.jam_reg,reg_periksa.kd_dokter,dokter.nm_dokter,reg_periksa.kd_poli,poliklinik.nm_poli,reg_periksa.stts_daftar,penjab.png_jawab from reg_periksa inner join dokter inner join poliklinik inner join penjab on reg_periksa.kd_dokter=dokter.kd_dokter and reg_periksa.kd_pj=penjab.kd_pj and reg_periksa.kd_poli=poliklinik.kd_poli where reg_periksa.no_rkm_medis='$noRm' and reg_periksa.tgl_registrasi=current_date()");

dashboard_page_header('Beranda Pasien', 'Ringkasan Layanan Anda', 'Pantau aktivitas kunjungan, jadwal, booking aktif, dan pendaftaran hari ini dalam satu dashboard yang lebih rapi.');
?>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="dashboard-card bg-[linear-gradient(135deg,#dc2626,#ef4444)] text-white">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Total Kunjungan</p>
        <p class="mt-4 text-4xl font-bold timer" data-to="<?= $jumlahKunjungan; ?>"></p>
    </div>
    <div class="dashboard-card bg-[linear-gradient(135deg,#0f766e,#14b8a6)] text-white">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Rawat Jalan</p>
        <p class="mt-4 text-4xl font-bold timer" data-to="<?= $jumlahRalan; ?>"></p>
    </div>
    <div class="dashboard-card bg-[linear-gradient(135deg,#2563eb,#3b82f6)] text-white">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Rawat Inap</p>
        <p class="mt-4 text-4xl font-bold timer" data-to="<?= $jumlahRanap; ?>"></p>
    </div>
    <div class="dashboard-card bg-[linear-gradient(135deg,#d97706,#f59e0b)] text-white">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Bulan Ini</p>
        <p class="mt-4 text-4xl font-bold timer" data-to="<?= $jumlahBulanIni; ?>"></p>
    </div>
</div>

<div class="mt-8 flex flex-col gap-8">
    <div class="space-y-8">
        <div class="dashboard-card">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-nu-700">Pengumuman</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">Informasi Terbaru</h2>
            <div class="mt-6 space-y-4">
                <?php $hasAnnouncement = false;
                while ($pengumuman = mysqli_fetch_array($querypengumuman)):
                    $hasAnnouncement = true; ?>
                    <article class="rounded-[24px] border border-slate-200 bg-slate-50/80 p-5">
                        <p class="text-sm leading-7 text-slate-700"><?= e($pengumuman["pengumuman"]); ?></p>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            <?= e($pengumuman["tanggal"]); ?> • <?= e($pengumuman["nama"]); ?>
                        </p>
                    </article>
                <?php endwhile; ?>
                <?php if (!$hasAnnouncement): ?>
                    <?php dashboard_empty_state('Belum ada pengumuman baru.'); ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($registrasi = mysqli_fetch_array($queryregistrasi)):
            $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
            $PNG_WEB_DIR = 'temp/';
            include_once "assets/plugin/phpqrcode/qrlib.php";
            if (!file_exists($PNG_TEMP_DIR)) {
                mkdir($PNG_TEMP_DIR);
            }
            $filename = $PNG_TEMP_DIR . str_replace("/", "", $registrasi["no_rawat"]) . '.png';
            QRcode::png($registrasi["no_rawat"], $filename, 'L', 4, 2);
            $_SESSION["kd_poli"] = $registrasi["kd_poli"];
            $_SESSION["kd_dokter"] = $registrasi["kd_dokter"];
            $_SESSION["tgl_registrasi"] = $registrasi["tgl_registrasi"];
            $_SESSION["no_reg"] = $registrasi["no_reg"];
        ?>
            <div class="dashboard-card">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-nu-700">Pendaftaran Hari Ini</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Antrian Anda Sedang Aktif</h2>
                <div class="mt-6 grid gap-4">
                    <div class="rounded-[24px] border border-slate-200 bg-slate-50/80 p-5">
                        <div class="grid gap-3 sm:grid-cols-[auto_1fr] sm:items-center">
                            <img src="user/<?= e($PNG_WEB_DIR . basename($filename)); ?>" alt="QR Rawat"
                                class="h-28 w-28 rounded-2xl border border-slate-200 bg-white p-2">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nomor Antrian
                                </p>
                                <p class="mt-2 text-4xl font-bold text-slate-900"><?= e($registrasi["no_reg"]); ?></p>
                                <div id="screen" class="mt-2 text-sm font-semibold text-rose-600"></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-[24px] bg-white p-5 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tanggal</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                <?= e($registrasi["tgl_registrasi"] . ' ' . $registrasi["jam_reg"]); ?>
                            </p>
                        </div>
                        <div class="rounded-[24px] bg-white p-5 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">No. Rawat</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900"><?= e($registrasi["no_rawat"]); ?></p>
                        </div>
                        <div class="rounded-[24px] bg-white p-5 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Jenis Bayar</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900"><?= e($registrasi["png_jawab"]); ?></p>
                        </div>
                        <div class="rounded-[24px] bg-white p-5 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Poliklinik</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900"><?= e($registrasi["nm_poli"]); ?></p>
                        </div>
                        <div class="rounded-[24px] bg-white p-5 ring-1 ring-slate-200 md:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Dokter</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900"><?= e($registrasi["nm_dokter"]); ?></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href='index.php?act=BuktiRegistrasi2&iyem=<?= urlencode(encrypt_decrypt("{\"norawat\":\"" . $registrasi["no_rawat"] . "\"}", "e")); ?>'
                            class="dashboard-btn-primary">Detail Registrasi</a>
                        <a href='index.php?act=CekBilling2&iyem=<?= urlencode(encrypt_decrypt("{\"norawat\":\"" . $registrasi["no_rawat"] . "\"}", "e")); ?>'
                            class="dashboard-btn-danger">Billing</a>
                        <a href="index.php?act=Perpustakaan&hal=Perpustakaan"
                            class="dashboard-btn-secondary">Perpustakaan</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="space-y-8">
        <div class="dashboard-card">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-nu-700">Jadwal Hari Ini</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">
                        <?= e(konversiHari($hari) . ', ' . date('d') . ' ' . konversiBulan(date('m')) . ' ' . date('Y')); ?>
                    </h2>
                </div>
                <a href="?act=JadwalDokterUser&hal=JadwalDokter"
                    class="p-2 border rounded-lg bg-emerald-600 text-white">Lihat
                    Semua</a>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Poliklinik</th>
                            <th>Jam</th>
                            <th>Kuota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($queryjadwal)):
                            $terdaftar = (int) getOne("select count(*) from reg_periksa where kd_poli='" . $row["kd_poli"] . "' and kd_dokter='" . $row["kd_dokter"] . "' and tgl_registrasi='" . date("Y-m-d") . "'");
                            $persen = $row["kuota"] > 0 ? min(100, ($terdaftar / $row["kuota"]) * 100) : 0;
                            $photoName = trim((string) $row["kd_dokter"]) . ".webp";
                            $defaultPhoto = "assets/images/avatar.png";
                            $photo = photo_url . $photoName;
                        ?>
                            <tr>
                                <td>
                                    <div class="flex gap-2 items-center h-auto">
                                        <img width="40" class="rounded-sm" src="<?= e($photo); ?>" alt="Foto" onerror="this.src='<?= e($defaultPhoto); ?>'">
                                        <div class="grid gap-0 pr-5">
                                            <span class="text-sm whitespace-nowrap font-medium">
                                                <?= e($row["nm_poli"]); ?>
                                            </span>
                                            <span class="whitespace-nowrap text-xs"><?= e($row["nm_dokter"]); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-center text-sm">
                                    <?= e(date("H:i", strtotime($row["jam_mulai"])) . ' - ' . date("H:i", strtotime($row["jam_selesai"]))); ?>
                                    WIB
                                </td>
                                <td>
                                    <div class="min-w-40">
                                        <div
                                            class="mb-2 flex items-center justify-between text-xs font-semibold text-slate-500">
                                            <span><?= $terdaftar; ?> terdaftar</span>
                                            <span><?= e((string) $row["kuota"]); ?> kuota</span>
                                        </div>
                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-rose-500" style="width: <?= $persen; ?>%;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (mysqli_num_rows($querybooking) > 0): ?>
            <div class="dashboard-card">
                <div class="mb-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-nu-700">Booking Aktif</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Booking Anda Hari Ini</h2>
                </div>
                <div class="grid gap-4">
                    <?php while ($booking = mysqli_fetch_array($querybooking)): ?>
                        <div class="rounded-[24px] border border-slate-200 bg-slate-50/70 p-5">
                            <div class="grid gap-3 md:grid-cols-2">
                                <div><span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tgl.
                                        Booking</span>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        <?= e($booking["tanggal_booking"] . ' ' . $booking["jam_booking"]); ?>
                                    </p>
                                </div>
                                <div><span
                                        class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Dokter</span>
                                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= e($booking["nm_dokter"]); ?></p>
                                </div>
                                <div><span
                                        class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Poliklinik</span>
                                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= e($booking["nm_poli"]); ?></p>
                                </div>
                                <div><span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">No.
                                        Reg</span>
                                    <p class="mt-1 text-sm font-semibold text-slate-900"><?= e($booking["no_reg"]); ?></p>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="index.php?act=CekinRegistrasi&iyem=<?= urlencode(encrypt_decrypt("{\"kd_dokter\":\"" . $booking["kd_dokter"] . "\",\"kd_poli\":\"" . $booking["kd_poli"] . "\",\"tanggal\":\"" . $booking["tanggal_periksa"] . "\",\"kd_pj\":\"" . $booking["kd_pj"] . "\",\"no_reg\":\"" . $booking["no_reg"] . "\",\"status\":\"cekin\"}", "e")); ?>"
                                    class="dashboard-btn-primary">Cekin</a>
                                <a href="index.php?act=CekinRegistrasi&iyem=<?= urlencode(encrypt_decrypt("{\"kd_dokter\":\"" . $booking["kd_dokter"] . "\",\"kd_poli\":\"" . $booking["kd_poli"] . "\",\"tanggal\":\"" . $booking["tanggal_periksa"] . "\",\"kd_pj\":\"" . $booking["kd_pj"] . "\",\"no_reg\":\"" . $booking["no_reg"] . "\",\"status\":\"batal\"}", "e")); ?>"
                                    class="dashboard-btn-danger">Batal</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
