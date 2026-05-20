<?php
if (strpos($_SERVER['REQUEST_URI'], "conf")) {
    exit(header("Location:../index.php"));
}

function title(): void
{
    $judul = "E-Pasien SIMKES Khanza --)(*!!@#$%";
    $judul = preg_replace("[^A-Za-z0-9_\-\./,|]", " ", $judul);
    $judul = str_replace(array('.', '-', '/', ','), " ", $judul);
    $judul = trim($judul);
    echo "$judul";
}

function cekSessiPasien(): bool
{
    if (isset($_SESSION['ses_pasien'])) {
        return true;
    } else {
        return false;
    }
}

function PasienAktif()
{
    if (cekSessiPasien()) {
        return $_SESSION['ses_pasien'];
    }
}

function isPengunjung(): bool
{
    if (cekSessiPasien()) {
        return false;
    } else {
        return true;
    }
}


function formProtek(): void
{
    $aksi = isset($_GET['act']) ? $_GET['act'] : NULL;
    if (!cekSessiPasien()) {
        $form = [
            'HomeUser',
            'FasilitasKamarUser',
            'InformasiKamarUser',
            'FasilitasRadiologiUser',
            'FasilitasLaboratUser',
            'FasilitasOperasiUser',
            'FasilitasOnlineUser',
            'JadwalDokterUser',
            'CekPoliUser',
            'CekAsuransiUser',
            'RiwayatPeriksa',
            'CekResume',
            'CekBilling',
            'BuktiRegistrasi',
            'CekBilling2',
            'Pengaduan',
            'BookingRegistrasi',
            'SuratSakit',
            'TampilSuratSakit',
            'SuratHamil',
            'TampilSuratHamil',
            'SuratBebasNarkoba',
            'TampilSuratBebasNarkoba',
            'SuratRujuk',
            'TampilSuratRujuk',
            'SuratCovid',
            'TampilSuratCovid',
            'SuratKontrol',
            'TampilSuratKontrol',
            'SimpanBookingRegistrasi',
            'CekinRegistrasi',
            'CekinRegistrasi2',
            'BuktiRegistrasi2',
            'Perpustakaan',
            'KartuPasien',
            'RiwayatLaboratUser'
        ];
        foreach ($form as $page) {
            if ($aksi == $page) {
                echo "<META HTTP-EQUIV = 'Refresh' Content = '0; URL = ?act=Home'>";
                exit;
                break;
            }
        }
    }
}

function actionPages(): void
{
    $aksi = isset($_REQUEST['act']) ? $_REQUEST['act'] : NULL;
    formProtek();
    if (!cekSessiPasien()) {
        switch ($aksi) {
            case "Home":
                include_once("pages/home.php");
                break;
            case "HomePasien":
                include_once("pages/index.php");
                break;
            case "LoginPasien":
                include_once("pages/login.php");
                break;
            case "DokterKami":
                include_once("pages/listsemuadokter.php");
                break;
            case "FasilitasKamar":
                include_once("pages/listkamar.php");
                break;
            case "FasilitasRadiologi":
                include_once("pages/listradiologi.php");
                break;
            case "FasilitasLaborat":
                include_once("pages/listlaborat.php");
                break;
            case "FasilitasOperasi":
                include_once("pages/listoperasi.php");
                break;
            case "FasilitasOnline":
                include_once("pages/listonline.php");
                break;
            case "PendaftaranPeriksa":
                include_once("pages/listperiksa.php");
                break;
            case "CekPoli":
                include_once("pages/listpoli.php");
                break;
            case "CekAsuransi":
                include_once("pages/listcarabayar.php");
                break;
            case "CekBooking":
                include_once("pages/listbooking.php");
                break;
            case "CekStokDarah":
                include_once("pages/liststokdarah.php");
                break;
            default:
                include_once("pages/login.php");
        }
    } else {
        switch ($aksi) {
            case "HomeUser":
                include_once("user/home.php");
                break;
            case "FasilitasKamarUser":
                include_once("user/kamar.php");
                break;
            case "InformasiKamarUser":
                include_once("user/informasi-kamar.php");
                break;
            case "FasilitasRadiologiUser":
                include_once("user/radiologi.php");
                break;
            case "FasilitasLaboratUser":
                include_once("user/laborat.php");
                break;
            case "FasilitasOperasiUser":
                include_once("user/operasi.php");
                break;
            case "FasilitasOnlineUser":
                include_once("user/online.php");
                break;
            case "JadwalDokterUser":
                include_once("user/jadwal-dokter.php");
                break;
            case "CekPoliUser":
                include_once("user/poli.php");
                break;
            case "CekAsuransiUser":
                include_once("user/cara-bayar.php");
                break;
            case "RiwayatPeriksa":
                include_once("user/riwayat-periksa.php");
                break;
            case "CekResume":
                include_once("user/list/resume.php");
                break;
            case "CekBilling":
                include_once("user/list/billing.php");
                break;
            case "CekBilling2":
                include_once("user/list/billing-2.php");
                break;
            case "Pengaduan":
                include_once("user/pengaduan.php");
                break;
            case "BookingRegistrasi":
                include_once("user/booking-registrasi.php");
                break;
            case "SimpanBookingRegistrasi":
                include_once("user/simpan-booking-registrasi.php");
                break;
            case "CekinRegistrasi":
                include_once("user/cek-in-registrasi.php");
                break;
            case "CekinRegistrasi2":
                include_once("user/cek-in-registrasi-2.php");
                break;
            case "BuktiRegistrasi":
                include_once("user/bukti-registrasi.php");
                break;
            case "BuktiRegistrasi2":
                include_once("user/bukti-registrasi-2.php");
                break;
            case "SuratSakit":
                include_once("user/surat-sakit.php");
                break;
            case "TampilSuratSakit":
                include_once("user/list/surat-sakit.php");
                break;
            case "SuratHamil":
                include_once("user/surat-hamil.php");
                break;
            case "TampilSuratHamil":
                include_once("user/list/surat-hamil.php");
                break;
            case "SuratBebasNarkoba":
                include_once("user/surat-narkoba.php");
                break;
            case "TampilSuratBebasNarkoba":
                include_once("user/list/surat-narkoba.php");
                break;
            case "SuratRujuk":
                include_once("user/surat-rujuk.php");
                break;
            case "TampilSuratRujuk":
                include_once("user/list/surat-rujuk.php");
                break;
            case "SuratCovid":
                include_once("user/surat-covid.php");
                break;
            case "TampilSuratCovid":
                include_once("user/list/surat-covid.php");
                break;
            case "SuratKontrol":
                include_once("user/surat-kontrol.php");
                break;
            case "TampilSuratKontrol":
                include_once("user/list/surat-kontrol.php");
                break;
            case "Perpustakaan":
                include_once("user/perpustakaan.php");
                break;
            case "AntrianPemeriksaanLab":
                include_once("user/riwayat-laborat.php");
                break;
            case "KartuPasien":
                include_once("user/kartu.php");
                break;
            case "TampilPermintaanLab":
                include_once("user/list/permintaan-lab.php");
                break;
            case "TampilHasilLab":
                include_once("pages/listtampilhasillab.php");
                break;
            default:
                include_once("user/home.php");
        }
    }
}

function actionMenu(): void
{
    $aksi = isset($_REQUEST['act']) ? $_REQUEST['act'] : "LoginPasien";
    if (!cekSessiPasien()):
        if ($aksi == "Home") { ?>
            <li class='nav-item'><a href='#top' class='smoothScroll active nav-link'>Home</a></li>
            <li class='nav-item'><a href='#team' class='nav-link'>Dokter Kami</a></li>
            <li class='nav-item'><a href='#news' class='nav-link'>Jadwal Praktek</a></li>
            <li class='nav-item'><a href='?act=FasilitasKamar' class='nav-link'>Fasilitas</a></li>
        <?php } else if (($aksi == "FasilitasKamar") || ($aksi == "FasilitasRadiologi") || ($aksi == "FasilitasLaborat") || ($aksi == "FasilitasOperasi") || ($aksi == "FasilitasOnline")) { ?>
                <li class='nav-item'><a class='nav-link' href='?act=Home'>Home</a></li>
                <li class='nav-item'><a href='?act=FasilitasKamar' class='nav-link'>Kamar</a></li>
                <li class='nav-item'><a href='?act=FasilitasRadiologi' class='nav-link'>Radiologi</a></li>
                <li class='nav-item'><a href='?act=FasilitasLaborat' class='nav-link'>Laborat</a></li>
                <li class='nav-item'><a href='?act=FasilitasOperasi' class='nav-link'>Operasi</a></li>
        <?php } else { ?>
                <li class='nav-item'><a href='?act=Home#top' class='nav-link'>Home</a></li>
                <li class='nav-item'><a href='?act=Home#about' class='nav-link'>Tentang Kami</a></li>
                <li class='nav-item'><a href='?act=Home#team' class='nav-link'>Dokter Kami</a></li>
                <li class='nav-item'><a href='?act=Home#news' class='nav-link'>Jadwal Praktek</a></li>
                <li class='nav-item'><a href='?act=FasilitasKamar' class='nav-link'>Fasilitas</a></li>
        <?php }
    endif;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function post_value(string $key, ?int $maxLength = null, bool $strict = false): ?string
{
    if (!isset($_POST[$key])) {
        return null;
    }

    $value = trim((string) $_POST[$key]);
    if ($value === '') {
        return null;
    }

    if ($maxLength !== null && strlen($value) > $maxLength) {
        if ($strict) {
            redirect('https://www.google.com');
            exit;
        }

        $value = substr($value, 0, $maxLength);
    }

    return $value;
}

function get_value(string $key, ?int $maxLength = null, bool $strict = false): ?string
{
    if (!isset($_GET[$key])) {
        return null;
    }

    $value = trim((string) $_GET[$key]);
    if ($value === '') {
        return null;
    }

    if ($maxLength !== null && strlen($value) > $maxLength) {
        if ($strict) {
            redirect('https://www.google.com');
            exit;
        }

        $value = substr($value, 0, $maxLength);
    }

    return $value;
}

function clean_post(string $key, ?int $maxLength = null, bool $allowSpecialChars = false): ?string
{
    $value = post_value($key, $maxLength, true);
    if ($value === null) {
        return null;
    }

    return $allowSpecialChars ? cleankar2($value) : cleankar($value);
}

function visitor_page_intro(string $eyebrow, string $title, string $description): void
{
    echo "<section class='relative overflow-hidden border-b border-emerald-100/80 bg-[radial-gradient(circle_at_top_left,rgba(22,163,74,0.18),transparent_38%),linear-gradient(180deg,rgba(255,255,255,0.98),rgba(240,253,244,0.96))]'>";
    echo "<div class='mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20'>";
    echo "<div class='max-w-3xl'>";
    echo "<span class='inline-flex items-center rounded-full border border-emerald-200 bg-white/90 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700'>" . e($eyebrow) . "</span>";
    echo "<h1 class='mt-5 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl'>" . e($title) . "</h1>";
    echo "<p class='mt-4 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg'>" . e($description) . "</p>";
    echo "</div>";
    echo "</div>";
    echo "</section>";
}

function visitor_section_open(): void
{
    echo "<section class='mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14'>";
}

function visitor_section_close(): void
{
    echo "</section>";
}

function visitor_surface_open(string $extraClasses = ''): void
{
    $classes = trim("overflow-hidden rounded-[28px] border border-white/70 bg-white/95 shadow-[0_24px_80px_-32px_rgba(15,23,42,0.28)] ring-1 ring-emerald-100/70 {$extraClasses}");
    echo "<div class='" . e($classes) . "'>";
}

function visitor_surface_close(): void
{
    echo "</div>";
}

function visitor_empty_state(string $message): void
{
    echo "<div class='rounded-2xl border border-dashed border-emerald-200 bg-emerald-50/70 px-6 py-10 text-center text-sm text-slate-600'>" . e($message) . "</div>";
}

function dashboard_menu_items(): array
{
    return [
        [
            'type' => 'link',
            'label' => 'Beranda',
            'icon' => 'fas fa-house',
            'act' => 'HomeUser',
            'hal' => 'Beranda',
        ],
        [
            'type' => 'link',
            'label' => 'Booking Registrasi',
            'icon' => 'fas fa-calendar-check',
            'act' => 'BookingRegistrasi',
            'hal' => 'Booking',
        ],
        [
            'type' => 'link',
            'label' => 'Riwayat Periksa',
            'icon' => 'fas fa-notes-medical',
            'act' => 'RiwayatPeriksa',
            'hal' => 'RiwayatPeriksa',
        ],
        [
            'type' => 'group',
            'label' => 'Permintaan & Tindakan',
            'icon' => 'fas fa-list-check',
            'hal' => 'AntrianTindakan',
            'children' => [
                [
                    'label' => 'Pemeriksaan Laborat',
                    'act' => 'AntrianPemeriksaanLab',
                    'hal' => 'AntrianTindakan',
                ],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Permintaan Surat',
            'icon' => 'fas fa-file-signature',
            'hal' => 'Surat',
            'children' => [
                ['label' => 'Cuti Sakit', 'act' => 'SuratSakit', 'hal' => 'Surat'],
                ['label' => 'Hamil / Tidak', 'act' => 'SuratHamil', 'hal' => 'Surat'],
                ['label' => 'Bebas Narkoba', 'act' => 'SuratBebasNarkoba', 'hal' => 'Surat'],
                ['label' => 'Kontrol / SKDP', 'act' => 'SuratKontrol', 'hal' => 'Surat'],
                ['label' => 'Rujukan', 'act' => 'SuratRujuk', 'hal' => 'Surat'],
                ['label' => 'Keterangan Covid', 'act' => 'SuratCovid', 'hal' => 'Surat'],
            ],
        ],
        [
            'type' => 'group',
            'label' => 'Fasilitas',
            'icon' => 'fas fa-hospital',
            'hal' => 'Fasilitas',
            'children' => [
                ['label' => 'Kamar', 'act' => 'FasilitasKamarUser', 'hal' => 'Fasilitas'],
                ['label' => 'Radiologi', 'act' => 'FasilitasRadiologiUser', 'hal' => 'Fasilitas'],
                ['label' => 'Laborat', 'act' => 'FasilitasLaboratUser', 'hal' => 'Fasilitas'],
                ['label' => 'Operasi', 'act' => 'FasilitasOperasiUser', 'hal' => 'Fasilitas'],
                ['label' => 'Poli Tersedia', 'act' => 'CekPoliUser', 'hal' => 'Fasilitas'],
                ['label' => 'Asuransi', 'act' => 'CekAsuransiUser', 'hal' => 'Fasilitas'],
                ['label' => 'Konsultasi Online', 'act' => 'FasilitasOnlineUser', 'hal' => 'Fasilitas'],
            ],
        ],
        [
            'type' => 'link',
            'label' => 'Jadwal Dokter',
            'icon' => 'fas fa-user-doctor',
            'act' => 'JadwalDokterUser',
            'hal' => 'JadwalDokter',
        ],
        [
            'type' => 'link',
            'label' => 'Ketersediaan Kamar',
            'icon' => 'fas fa-bed',
            'act' => 'InformasiKamarUser',
            'hal' => 'InformasiKamar',
        ],
        [
            'type' => 'link',
            'label' => 'Pengaduan',
            'icon' => 'fas fa-comments',
            'act' => 'Pengaduan',
            'hal' => 'Pengaduan',
        ],
        [
            'type' => 'link',
            'label' => 'Kartu Pasien',
            'icon' => 'fas fa-id-card',
            'act' => 'KartuPasien',
            'hal' => 'KartuPasien',
        ],
        [
            'type' => 'link',
            'label' => 'Perpustakaan',
            'icon' => 'fas fa-book-open',
            'act' => 'Perpustakaan',
            'hal' => 'Perpustakaan',
        ],
    ];
}

function dashboard_page_header(string $eyebrow, string $title, string $description = ''): void
{
    echo "<div class='mb-8'>";
    echo "<span class='inline-flex rounded-full border border-emerald-200 bg-white px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700'>" . e($eyebrow) . "</span>";
    echo "<h1 class='mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl'>" . e($title) . "</h1>";
    if ($description !== '') {
        echo "<p class='mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base'>" . e($description) . "</p>";
    }
    echo "</div>";
}

function dashboard_empty_state(string $message): void
{
    echo "<div class='rounded-3xl border border-dashed border-emerald-200 bg-emerald-50/70 px-6 py-12 text-center text-sm leading-7 text-slate-600'>" . e($message) . "</div>";
}
