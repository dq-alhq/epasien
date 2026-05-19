<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$jamSekarang = date("H:i");

$besok = date("Y-m-d", strtotime("+1 day"));
$tanggalRegistrasiInput = trim((string) ($_POST['tgl_registrasi'] ?? $besok));
$tanggalRegistrasi = DateTime::createFromFormat('Y-m-d', $tanggalRegistrasiInput);

if (!$tanggalRegistrasi || $tanggalRegistrasi->format('Y-m-d') !== $tanggalRegistrasiInput) {
    $tanggalRegistrasi = new DateTime($besok);
}

$tanggalRegistrasiFormatted = $tanggalRegistrasi->format('Y-m-d');
$thncari = $tanggalRegistrasi->format('Y');
$blncari = $tanggalRegistrasi->format('m');
$tglcari = $tanggalRegistrasi->format('d');

$tglDipilih = $tanggalRegistrasiFormatted;
$tglSekarang = date("Y-m-d");
$tglKemarin = date("Y-m-d", strtotime("-1 day"));
?>

<div class="mb-3 text-center">
    <h5 class="menu-header-title mb-3"><strong>BOOKING REGISTRASI</strong></h5>
</div>
<div class="grid gap-6">
    <div class="dashboard-card space-y-6">
        <div>
            <ul class="nav">
                <li class="nav-item">
                    <a role="tab" class="nav-link active" id="tab-c-0" data-toggle="tab" data-target="#tab-1"
                        aria-selected="false">
                        <span>PILIH</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a role="tab" class="nav-link" id="tab-c-1" data-toggle="tab" data-target="#tab-2"
                        aria-selected="false">
                        <span>RIWAYAT BOOKING</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-1" role="tabpanel">
                    <form id="form_validation"
                        class="mb-6 flex flex-col gap-4 rounded-[24px] border border-slate-200 bg-slate-50/70 p-4 sm:flex-row sm:items-end"
                        action="" method="POST">
                        <div class="w-full">
                            <label for="tgl_registrasi" class="dashboard-label">Tanggal Rencana Periksa</label>
                            <input type="date" class="form-control" name="tgl_registrasi" id="tgl_registrasi"
                                min="<?= e($besok); ?>" value="<?= e($tanggalRegistrasiFormatted); ?>" required>
                        </div>
                        <button class="btn btn-danger" type="submit" name="pilihpoli">
                            TAMPILKAN JADWAL TERSEDIA
                        </button>
                    </form>

                    <div class="mb-2">
                        <div class="my-3 text-lg font-normal capitalize">
                            <?php $hari = getOne("select DAYNAME('$thncari-$blncari-$tglcari')"); ?>
                            <div
                                class="flex w-full items-center justify-center rounded-lg bg-emerald-700 text-center text-white">
                                <?= konversiHari($hari) ?>,
                                <?= $tglcari ?>
                                <?= konversiBulan($blncari) ?>
                                <?= $thncari ?>
                            </div>
                        </div>
                        <div class="table-responsive table-bordered">
                            <table class="mb-0 table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">POLIKLINIK</th>
                                        <th scope="col" class="text-center">JAM</th>
                                        <th scope="col" class="text-center">KUOTA</th>
                                        <th scope="col" class="text-center">PILIHAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $queryjadwal = bukaquery("Select dokter.nm_dokter,poliklinik.nm_poli,jadwal.jam_mulai,jadwal.jam_selesai,poliklinik.kd_poli,jadwal.kuota,dokter.kd_dokter from jadwal inner join dokter on dokter.kd_dokter=jadwal.kd_dokter inner join poliklinik on jadwal.kd_poli=poliklinik.kd_poli inner join pegawai on dokter.kd_dokter=pegawai.nik where jadwal.hari_kerja like '%" . konversiHari($hari) . "%'");
                                    while ($rsqueryjadwal = mysqli_fetch_array($queryjadwal)):
                                        $daftar = getOne("select count(no_rkm_medis) from booking_registrasi where tanggal_periksa='$thncari-$blncari-$tglcari' and kd_dokter='" . $rsqueryjadwal["kd_dokter"] . "' and kd_poli='" . $rsqueryjadwal["kd_poli"] . "'");
                                        $terdaftar = getOne("select count(no_rkm_medis) from booking_registrasi where tanggal_periksa='$thncari-$blncari-$tglcari' and kd_dokter='" . $rsqueryjadwal["kd_dokter"] . "' and kd_poli='" . $rsqueryjadwal["kd_poli"] . "' and no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");

                                        $photoName = trim((string) $rsqueryjadwal["kd_dokter"]) . ".jpg";
                                        $defaultPhoto = "assets/images/avatar.png";
                                        $photo = photo_url . $photoName;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="flex gap-2 items-center h-auto">
                                                    <img width="40" class="rounded" src="<?= e($photo); ?>" alt="Foto" onerror="this.src='<?= e($defaultPhoto); ?>'">
                                                    <div class="grid gap-0 pr-5">
                                                        <span class="text-sm whitespace-nowrap font-medium">
                                                            <?= e($rsqueryjadwal["nm_poli"]); ?>
                                                        </span>
                                                        <span
                                                            class="whitespace-nowrap text-xs"><?= e($rsqueryjadwal["nm_dokter"]); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-sm whitespace-nowrap">
                                                <?= e(date("H:i", strtotime($rsqueryjadwal["jam_mulai"]))); ?>
                                                - <?= e(date("H:i", strtotime($rsqueryjadwal["jam_selesai"]))); ?> WIB
                                            </td>
                                            <td>
                                                <div class="flex relative w-full h-4 bg-slate-300 rounded-full overflow-hidden"
                                                    role="progressbar" aria-valuenow="<?= $daftar ?>" aria-valuemin="0"
                                                    aria-valuemax="<?= $rsqueryjadwal["kuota"]; ?>">
                                                    <div class="flex flex-col justify-center rounded-full overflow-hidden bg-red-600 text-xs text-center whitespace-nowrap transition duration-500"
                                                        style="width: <?php
                                                                        $persen = 0;
                                                                        if ($rsqueryjadwal["kuota"] > 0) {
                                                                            $persen = ($daftar / $rsqueryjadwal["kuota"]) * 100;
                                                                        }
                                                                        echo round($persen, 2);
                                                                        ?>%">
                                                    </div>
                                                    <span
                                                        class="absolute left-1/2 font-bold text-xs -translate-x-1/2 text-emerald-500"><?= $daftar ?>/<?= $rsqueryjadwal["kuota"]; ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php if (($rsqueryjadwal["kuota"] > 0) && ($daftar >= $rsqueryjadwal["kuota"])) {
                                                    if ($terdaftar > 0) { ?>
                                                        <button class="btn btn-primary" disabled>Terdaftar</button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50"
                                                            disabled>Penuh</button>
                                                    <?php }
                                                } else {
                                                    if ($terdaftar > 0) { ?>
                                                        <button class="btn btn-primary" disabled>Terdaftar</button>
                                                    <?php } else if ($tglDipilih == $tglSekarang && $jamSekarang > $rsqueryjadwal["jam_selesai"]) { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50" disabled>Sudah
                                                            Lewat</button>
                                                    <?php } else if ($tglDipilih == $tglKemarin) { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50" disabled>Sudah
                                                            Lewat</button>
                                                    <?php } else if ($daftar >= $rsqueryjadwal["kuota"]) { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50"
                                                            disabled>Penuh</button>
                                                <?php } else {
                                                        echo "<a href='?act=SimpanBookingRegistrasi&iyem=" . encrypt_decrypt("{\"kd_dokter\":\"" . $rsqueryjadwal["kd_dokter"] . "\",\"kd_poli\":\"" . $rsqueryjadwal["kd_poli"] . "\",\"tanggal\":\"$thncari-$blncari-$tglcari\",\"kuota\":\"" . $rsqueryjadwal["kuota"] . "\"}", "e") . "' class='btn btn-success'>Booking</a>";
                                                    }
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-2" role="tabpanel">
                    <div class="my-2">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Tgl. Booking</th>
                                        <th class="text-center">Tgl. Periksa</th>
                                        <th class="text-center">Nama Pasien</th>
                                        <th class="text-center">Dokter</th>
                                        <th class="text-center">Poli</th>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $queryriwayat = bukaquery("
                                        SELECT 
                                            booking_registrasi.tanggal_booking,
                                            booking_registrasi.jam_booking,
                                            booking_registrasi.tanggal_periksa,
                                            booking_registrasi.kd_dokter,
                                            dokter.nm_dokter,
                                            booking_registrasi.kd_poli,
                                            poliklinik.nm_poli,
                                            booking_registrasi.no_reg,
                                            booking_registrasi.kd_pj,
                                            penjab.png_jawab,
                                            booking_registrasi.status,
                                            pasien.nm_pasien
                                        FROM booking_registrasi
                                        INNER JOIN dokter 
                                            ON booking_registrasi.kd_dokter = dokter.kd_dokter
                                        INNER JOIN poliklinik 
                                            ON booking_registrasi.kd_poli = poliklinik.kd_poli
                                        INNER JOIN penjab 
                                            ON booking_registrasi.kd_pj = penjab.kd_pj
                                        INNER JOIN pasien 
                                            ON booking_registrasi.no_rkm_medis = pasien.no_rkm_medis
                                        WHERE booking_registrasi.no_rkm_medis = '" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'
                                        ORDER BY booking_registrasi.tanggal_periksa DESC
                                    ");
                                    while ($rsqueryriwayat = mysqli_fetch_array($queryriwayat)): ?>
                                        <tr>
                                            <td class="text-center"><?= $rsqueryriwayat["tanggal_booking"]; ?>
                                                <?= $rsqueryriwayat["jam_booking"]; ?>
                                            </td>
                                            <td class="text-center"><?= $rsqueryriwayat["tanggal_periksa"]; ?></td>
                                            <td class="text-left"><?= $rsqueryriwayat["nm_pasien"]; ?></td>
                                            <td class="text-left"><?= $rsqueryriwayat["nm_dokter"]; ?></td>
                                            <td class="text-left"><?= $rsqueryriwayat["nm_poli"]; ?></td>
                                            <td class="text-center"><?= $rsqueryriwayat["no_reg"]; ?></td>
                                            <?php if ($rsqueryriwayat["status"] == "Belum") { ?>
                                                <td class='text-center'>
                                                    <a href="javascript:void(0)"
                                                        data-href='?act=CekinRegistrasi&iyem=<?= encrypt_decrypt("{\"kd_dokter\":\"" . $rsqueryriwayat["kd_dokter"] . "\",\"kd_poli\":\"" . $rsqueryriwayat["kd_poli"] . "\",\"tanggal\":\"" . $rsqueryriwayat["tanggal_periksa"] . "\",\"kd_pj\":\"" . $rsqueryriwayat["kd_pj"] . "\",\"no_reg\":\"" . $rsqueryriwayat["no_reg"] . "\",\"status\":\"batal\"}", "e"); ?>'
                                                        class='btn btn-danger btn-transition batalRegistrasi'>Batal</a>
                                                </td>
                                            <?php } else if ($rsqueryriwayat["status"] == "Terdaftar") { ?>
                                                <td class='text-center'>
                                                    <a href='?act=BuktiRegistrasi&iyem=<?= encrypt_decrypt("{\"kd_dokter\":\"" . $rsqueryriwayat["kd_dokter"] . "\",\"kd_poli\":\"" . $rsqueryriwayat["kd_poli"] . "\",\"tanggal\":\"" . $rsqueryriwayat["tanggal_periksa"] . "\"}", "e"); ?>'
                                                        class='btn btn-success btn-transition'>Terdaftar</a>
                                                </td>
                                            <?php } else { ?>
                                                <td class='text-center'>
                                                    <button class='btn-secondary btn'
                                                        disabled><?= $rsqueryriwayat["status"]; ?></button>
                                                </td>
                                        <?php }
                                        endwhile; ?>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/plugin/moment-with-locales.js"></script>
<script src="assets/plugin/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.batalRegistrasi');
        if (!button) {
            return;
        }

        e.preventDefault();

        const targetUrl = button.getAttribute('data-href');
        const originalText = button.textContent;

        if (!targetUrl) {
            return;
        }

        button.textContent = 'Proses';

        if (typeof Swal === 'undefined') {
            const isConfirmed = window.confirm('Pembatalan akan menyebabkan data booking registrasi tidak dapat diakses. Lanjutkan pembatalan?');
            if (isConfirmed) {
                window.location.href = targetUrl;
            } else {
                button.textContent = originalText;
            }
            return;
        }

        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Pembatalan akan menyebabkan data booking registrasi tidak dapat diakses!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = targetUrl;
            } else {
                button.textContent = originalText;
            }
        });
    });
</script>
