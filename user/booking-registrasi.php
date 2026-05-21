<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$jamSekarang = date("H:i");

$besok = date("Y-m-d", strtotime("+1 day"));
$hari_ini = date("Y-m-d");
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
                                min="<?= e($hari_ini); ?>" value="<?= e($tanggalRegistrasiFormatted); ?>" required>
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

                                        $photoName = trim((string) $rsqueryjadwal["kd_dokter"]) . ".webp";
                                        $defaultPhoto = "assets/images/avatar.png";
                                        $photo = photo_url . $photoName;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="flex gap-2 items-center h-auto">
                                                    <img width="40" class="rounded-sm" src="<?= e($photo); ?>" alt="Foto" onerror="this.src='<?= e($defaultPhoto); ?>'">
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
                                                        <?php } else if ($tglDipilih == $tglSekarang) {
                                                        $jam_mulai_minus_1h = date("H:i", strtotime($rsqueryjadwal["jam_mulai"] . " - 1 hour"));
                                                        if ($jamSekarang >= $jam_mulai_minus_1h) { ?>
                                                            <button class="btn btn-danger pointer-events-none opacity-50" disabled>Sudah Lewat</button>
                                                        <?php } else if ($daftar >= $rsqueryjadwal["kuota"]) { ?>
                                                            <button class="btn btn-danger pointer-events-none opacity-50" disabled>Penuh</button>
                                                        <?php } else {
                                                            echo "<a href='?act=SimpanBookingRegistrasi&iyem=" . encrypt_decrypt("{\"kd_dokter\":\"" . $rsqueryjadwal["kd_dokter"] . "\",\"kd_poli\":\"" . $rsqueryjadwal["kd_poli"] . "\",\"tanggal\":\"$thncari-$blncari-$tglcari\",\"kuota\":\"" . $rsqueryjadwal["kuota"] . "\"}", "e") . "' class='btn btn-success'>Booking</a>";
                                                        }
                                                    } else if ($tglDipilih == $tglKemarin) { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50" disabled>Sudah Lewat</button>
                                                    <?php } else if ($daftar >= $rsqueryjadwal["kuota"]) { ?>
                                                        <button class="btn btn-danger pointer-events-none opacity-50" disabled>Penuh</button>
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
                                        <th class="text-center">Poli/Dokter</th>
                                        <th class="text-center">No. Reg</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $queryriwayat = bukaquery("
                                        SELECT 
                                            DATE_FORMAT(booking_registrasi.tanggal_booking, '%d-%m-%Y') AS tanggal_booking,
                                            booking_registrasi.no_rkm_medis,
                                            TIME_FORMAT(booking_registrasi.jam_booking, '%H:%i') AS jam_booking,
                                            DATE_FORMAT(booking_registrasi.tanggal_periksa, '%d-%m-%Y') AS tanggal_periksa,
                                            booking_registrasi.kd_dokter,
                                            dokter.nm_dokter,
                                            booking_registrasi.kd_poli,
                                            poliklinik.nm_poli,
                                            booking_registrasi.no_reg,
                                            booking_registrasi.kd_pj,
                                            penjab.png_jawab,
                                            booking_registrasi.status,
                                            pasien.nm_pasien,
                                            CONCAT(TIME_FORMAT(jadwal.jam_mulai, '%H:%i'), ' - ', TIME_FORMAT(jadwal.jam_selesai, '%H:%i')) AS jam_periksa
                                        FROM booking_registrasi
                                        INNER JOIN dokter 
                                            ON booking_registrasi.kd_dokter = dokter.kd_dokter
                                        INNER JOIN poliklinik 
                                            ON booking_registrasi.kd_poli = poliklinik.kd_poli
                                        INNER JOIN penjab 
                                            ON booking_registrasi.kd_pj = penjab.kd_pj
                                        INNER JOIN pasien 
                                            ON booking_registrasi.no_rkm_medis = pasien.no_rkm_medis
                                        INNER JOIN jadwal
                                            ON booking_registrasi.kd_dokter = jadwal.kd_dokter 
                                            AND booking_registrasi.kd_poli = jadwal.kd_poli
                                            AND ELT(DAYOFWEEK(booking_registrasi.tanggal_periksa), 'AKHAD', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU') = jadwal.hari_kerja
                                        WHERE booking_registrasi.no_rkm_medis = '" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'
                                        ORDER BY booking_registrasi.tanggal_periksa DESC
                                    ");
                                    while ($rsqueryriwayat = mysqli_fetch_array($queryriwayat)): ?>
                                        <tr>
                                            <td class="text-left">
                                                <div class="flex">
                                                    <button type="button"
                                                        class="cetak_reg rounded-sm shrink-0 border px-2 py-1 mr-2 bg-emerald-600 text-white transition hover:bg-emerald-700 active:translate-y-px active:bg-emerald-800"
                                                        data-booking="<?= e($rsqueryriwayat["tanggal_booking"]); ?>"
                                                        data-booking-time="<?= e($rsqueryriwayat["jam_booking"]); ?>"
                                                        data-periksa="<?= e($rsqueryriwayat["tanggal_periksa"]); ?>"
                                                        data-pasien="<?= e($rsqueryriwayat["nm_pasien"]); ?>"
                                                        data-norm="<?= e($rsqueryriwayat["no_rkm_medis"]); ?>"
                                                        data-poli="<?= e($rsqueryriwayat["nm_poli"]); ?>"
                                                        data-dokter="<?= e($rsqueryriwayat["nm_dokter"]); ?>"
                                                        data-noreg="<?= e($rsqueryriwayat["no_reg"]); ?>"
                                                        data-status="<?= e($rsqueryriwayat["status"]); ?>"
                                                        data-jam-periksa="<?= e($rsqueryriwayat["jam_periksa"]); ?>"><i class="fas fa-print"></i></button>
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold"><?= $rsqueryriwayat["tanggal_booking"]; ?></span>
                                                        <span><?= $rsqueryriwayat["jam_booking"]; ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold"><?= $rsqueryriwayat["tanggal_periksa"]; ?></span>
                                                    <span><?= $rsqueryriwayat["jam_periksa"]; ?></span>
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold">No. RM: <?= $rsqueryriwayat["no_rkm_medis"]; ?></span>
                                                    <span><?= $rsqueryriwayat["nm_pasien"]; ?></span>
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <div class="flex flex-col">
                                                    <span class="font-semibold"><?= $rsqueryriwayat["nm_poli"]; ?></span>
                                                    <span><?= $rsqueryriwayat["nm_dokter"]; ?></span>
                                                </div>
                                            </td>
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

<div id="ticketModal" class="fixed inset-0 z-50 hidden items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-2xl border border-slate-200 bg-white shadow-2xl">
        <div class="flex flex-col gap-4 rounded-b-[28px] bg-[linear-gradient(270deg,rgba(10,42,29,0.98),rgba(15,106,62,0.96))] via-slate-800 to-slate-900 px-6 py-5 text-white sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase font-bold text-slate-200">Tiket Pendaftaran</p>
                <h3 class="mt-2 text-xl font-semibold">Booking Registrasi</h3>
            </div>
        </div>
        <div class="px-6 py-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">No. Registrasi</p>
                    <p id="ticketNoReg" class="mt-4 text-3xl font-mono font-semibold text-emerald-700"></p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Nama Pasien / No. RM</p>
                    <p id="ticketPasien" class="mt-2 text-base font-semibold text-slate-900"></p>
                    <p id="ticketNoRM" class="mt-1 text-sm text-slate-600"></p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Poliklinik / Dokter</p>
                    <p id="ticketPoli" class="mt-2 text-base font-semibold text-slate-900"></p>
                    <p id="ticketDokter" class="mt-1 text-sm text-slate-600"></p>
                </div>
            </div>
            <div class="mt-6 grid gap-4 rounded-[30px] border border-slate-200 bg-slate-100 p-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Tgl. Booking</p>
                    <div class="flex gap-1 sm:gap-2 mt-2 text-base font-semibold text-slate-900">
                        <span id="ticketBooking"></span>
                        <span class="">|</span>
                        <span id="ticketBookingTime"></span><span>WIB</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Tgl. Periksa</p>
                    <div class="flex gap-1 sm:gap-2 mt-2 text-base font-semibold text-slate-900">
                        <span id="ticketPeriksa"></span>
                        <span class="">|</span>
                        <span id="ticketPeriksaTime"></span><span>WIB</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3 border-t px-6 py-4 sm:flex-row sm:justify-end sm:items-center">
            <button type="button" class="modal-close btn rounded-full border border-slate-300 bg-slate-100 px-4 py-2 text-slate-700 transition hover:bg-slate-200">Tutup</button>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #ticketModal,
        #ticketModal * {
            visibility: visible;
        }

        #ticketModal {
            position: absolute;
            inset: 0;
            width: 100%;
            padding: 0.5rem;
        }

        #ticketModal>div {
            box-shadow: none;
            border: none;
        }
    }
</style>

<script>
    function openTicketModal(data) {
        const modal = document.getElementById('ticketModal');
        if (!modal) return;

        document.getElementById('ticketPasien').textContent = data.pasien || '-';
        document.getElementById('ticketNoRM').textContent = data.norm || '-';
        document.getElementById('ticketPoli').textContent = data.poli || '-';
        document.getElementById('ticketDokter').textContent = data.dokter ? 'Dr. ' + data.dokter : '-';
        document.getElementById('ticketBooking').textContent = data.booking || '-';
        document.getElementById('ticketBookingTime').textContent = data.bookingTime || '-';
        document.getElementById('ticketPeriksa').textContent = data.periksa || '-';
        document.getElementById('ticketNoReg').textContent = data.noreg || '-';
        document.getElementById('ticketPeriksaTime').textContent = data.jamPeriksa || '-';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTicketModal() {
        const modal = document.getElementById('ticketModal');
        if (!modal) return;
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    document.addEventListener('click', function(event) {
        const button = event.target.closest('.cetak_reg');
        if (button) {
            event.preventDefault();
            openTicketModal({
                booking: button.dataset.booking,
                bookingTime: button.dataset.bookingTime,
                periksa: button.dataset.periksa,
                pasien: button.dataset.pasien,
                norm: button.dataset.norm,
                poli: button.dataset.poli,
                dokter: button.dataset.dokter,
                noreg: button.dataset.noreg,
                status: button.dataset.status,
                jamPeriksa: button.dataset.jamPeriksa,
            });
        }
    });

    document.querySelectorAll('.modal-close').forEach(function(button) {
        button.addEventListener('click', function() {
            closeTicketModal();
        });
    });

    document.getElementById('ticketModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeTicketModal();
        }
    });
</script>
