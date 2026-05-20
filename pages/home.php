<?php
if (strpos($_SERVER['REQUEST_URI'], 'pages')) {
    exit(header('Location:../index.php'));
}

$instansi = $_SESSION["nama_instansi"] ?? 'Rumah Sakit';
$kabupaten = $_SESSION["kabupaten"] ?? '';
$alamat = $_SESSION["alamat_instansi"] ?? '';
$kodePpk = $_SESSION["kode_ppkkemenkes"] ?? '';
?>
<section class="relative overflow-hidden">
    <div
        class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,rgba(18,130,75,0.12),transparent_34%),linear-gradient(135deg,rgba(255,255,255,0.96),rgba(220,252,231,0.94))]">
    </div>
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8 lg:py-20">
        <div class="max-w-3xl">
            <span
                class="inline-flex rounded-full border border-nu-200 bg-white/90 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Layanan
                Pasien Digital</span>
            <h1 class="mt-6 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl lg:text-3xl">
                Pengalaman layanan pasien yang profesional dan terpercaya.
            </h1>
            <p class="mt-6 text-base leading-8 text-slate-600 sm:text-lg">
                Hadir sebagai rumah sakit Islam di <?= e($kabupaten); ?> dengan semangat khidmah Nahdlatul Ulama,
                menghadirkan akses informasi dokter, layanan, dan booking yang lebih nyaman dari satu portal.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="?act=LoginPasien" class="btn-primary">Masuk dan Buat Janji</a>
                <a href="?act=CekBooking" class="btn-secondary">Lihat Status Booking</a>
            </div>
        </div>

        <div class="surface-card relative p-8">
            <div class="relative flex gap-0">
                <div class="relative">
                    <p class="font-bold leading-7 text-xl text-slate-600">
                        Melayani Setulus Hati
                    </p>
                    <div class="max-w-36 sm:max-w-none lg:max-w-48 grid gap-4 mt-4">
                        <div class="rounded-3xl bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nilai</p>
                            <p class="mt-2 text-sm font-medium text-slate-800">Amanah, profesional, dan penuh kepedulian.
                            </p>
                        </div>
                        <div class="rounded-3xl bg-nu-50 lg:max-w-none p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-nu-700">Fokus</p>
                            <p class="mt-2 text-sm font-medium text-slate-800">Pelayanan pasien yang cepat dan terarah.</p>
                        </div>
                    </div>
                </div>
            </div>
            <img src="assets/images/direktur-half.png" alt="Direktur" class="absolute shrink-0 bottom-0 w-48 drop-shadow-[0_0_15px_rgba(0,255,50,0.5)] h-auto right-0">
        </div>
    </div>

</section>
<section id="team" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <span class="text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Dokter Kami</span>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Tim medis terbaik untuk layanan yang
                lebih dekat.</h2>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">Perkenalan singkat tenaga medis kami untuk
                membantu pasien mengenal layanan secara lebih personal dan profesional.</p>
        </div>
        <a href="?act=DokterKami" class="btn-secondary">Lihat Semua Dokter</a>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <?php
        $querydokter = bukaquery("select dokter.kd_dokter,dokter.nm_dokter as dokter,spesialis.nm_sps,dokter.no_ijn_praktek,pegawai.photo from dokter inner join spesialis on dokter.kd_sps=spesialis.kd_sps inner join pegawai on dokter.kd_dokter=pegawai.nik where dokter.status='1' and dokter.kd_dokter<>'-' and dokter.no_ijn_praktek<>'-' and dokter.no_ijn_praktek<>'' and spesialis.nm_sps NOT LIKE '%Umum%' and spesialis.nm_sps<>'Perawat' order by spesialis.nm_sps limit 6");
        while ($rsquerydokter = mysqli_fetch_array($querydokter)):
            $photoName = trim((string) $rsquerydokter["kd_dokter"]) . ".webp?v=4";
            $defaultPhoto = "assets/images/avatar.png";
            $photo = photo_url . $photoName;
            $nama_dokter = preg_replace_callback(
                '/^(dr\.\s*)([^,]+)(.*)$/i',
                function ($match) {
                    return $match[1] . ucwords(strtolower($match[2])) . $match[3];
                },
                $nama_dokter = $rsquerydokter["dokter"]
            );
        ?>
            <article class="surface-card p-5">
                <div class="flex items-start gap-4">
                    <img alt="Foto dokter" src="<?= e($defaultPhoto); ?>" data-src="<?= e($photo); ?>"
                        class="size-24 rounded-3xl object-cover ring-1 ring-slate-200 lazy-load">
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900"><?= e($nama_dokter); ?></h3>
                        <p class="mt-1 text-sm text-slate-600"><?= e($rsquerydokter["nm_sps"]); ?></p>
                        <div class="mt-2 inline-flex rounded-full text-xs font-semibold text-nu-700">
                            SIP: <?= e($rsquerydokter["no_ijn_praktek"]); ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<section id="news" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="border-b border-slate-100 pb-6">
        <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Jadwal Praktek</span>
        <h2 class="mt-2 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Cari Jadwal Dokter</h2>
        <p class="mt-2 text-sm text-slate-600">
            Temukan informasi jadwal dokter, hari praktek, atau poliklinik dengan cepat dan mudah.
        </p>

        <form id="carikeyword" class="mt-6" onsubmit="return false;">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search h-4 w-4"></i>
                </div>
                <input name="keyword" type="text" placeholder="Ketik nama dokter atau poliklinik..." id="keyword"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition-all"
                    maxlength="40" autocomplete="off">
            </div>
        </form>
    </div>

    <?php
    $queryjadwal = bukaquery("
            SELECT 
                dokter.kd_dokter,
                dokter.nm_dokter,
                jadwal.hari_kerja,
                jadwal.jam_mulai,
                jadwal.jam_selesai,
                poliklinik.nm_poli
            FROM jadwal
            INNER JOIN dokter ON jadwal.kd_dokter = dokter.kd_dokter
            INNER JOIN poliklinik ON jadwal.kd_poli = poliklinik.kd_poli
            WHERE dokter.status = '1'
            ORDER BY poliklinik.nm_poli, dokter.nm_dokter
        ");

    // Kita kelompokkan data jadwal berdasarkan dokter agar penataan card menjadi bersih
    $jadwalGrouped = [];
    while ($row = mysqli_fetch_array($queryjadwal)) {
        $idDokter = $row['kd_dokter'];
        if (!isset($jadwalGrouped[$idDokter])) {
            $photoName = trim((string) $row["kd_dokter"]) . ".webp?v=4";
            $defaultPhoto = "assets/images/avatar.png";
            $photo = photo_url . $photoName;
            $jadwalGrouped[$idDokter] = [
                'nama' => $row['nm_dokter'],
                'poli' => $row['nm_poli'],
                'photo' => $photo,
                'sesi' => []
            ];
        }
        $jadwalGrouped[$idDokter]['sesi'][] = [
            'hari' => $row['hari_kerja'],
            'jam'  => date("H:i", strtotime($row['jam_mulai'])) . " - " . date("H:i", strtotime($row['jam_selesai']))
        ];
    }
    ?>

    <!-- Grid Container untuk Card Dokter -->
    <div id="dokterGrid" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($jadwalGrouped)): ?>
            <?php foreach ($jadwalGrouped as $dokter): ?>
                <!-- Card Item -->
                <div class="doctor-card bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all flex flex-col justify-between"
                    data-poli="<?= htmlspecialchars(strtolower($dokter['poli']), ENT_QUOTES, 'UTF-8') ?>"
                    data-dokter="<?= htmlspecialchars(strtolower($dokter['nama']), ENT_QUOTES, 'UTF-8') ?>">

                    <div>
                        <!-- Header Card: Profil Dokter -->
                        <div class="flex items-center gap-4">
                            <!-- Avatar dengan Inisial/Placeholder foto beraksen Hijau NU -->
                            <img alt="Foto dokter" src="<?= e($defaultPhoto); ?>" data-src="<?= e($dokter['photo']); ?>"
                                class="size-14 rounded-2xl object-cover border border-emerald-700/20 shadow-sm lazy-load">
                            <div>
                                <h3 class="font-bold text-slate-800 leading-tight line-clamp-1"><?= htmlspecialchars($dokter['nama'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <span class="inline-block mt-1 text-xs px-2.5 py-1 bg-emerald-50 text-emerald-800 font-semibold rounded-lg border border-emerald-100">
                                    <?= htmlspecialchars($dokter['poli'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Divider interior card -->
                        <div class="my-4 border-t border-dashed border-slate-100"></div>

                        <!-- List Jadwal Praktik -->
                        <div class="space-y-2.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Praktik</span>
                            <?php foreach ($dokter['sesi'] as $sesi): ?>
                                <div class="flex items-center justify-between text-sm bg-slate-50/70 p-2 rounded-xl border border-slate-100/50">
                                    <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                                        <!-- Bullet point beraksen emas NU -->
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <?= htmlspecialchars($sesi['hari'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                    <span class="text-xs font-medium text-slate-600 bg-white px-2 py-0.5 rounded-md border border-slate-200/60 shadow-xs"><?= $sesi['jam'] ?> WIB</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- State Data Kosong -->
    <div id="noDataCard" class="hidden py-12 text-center">
        <div class="inline-flex p-4 bg-slate-50 rounded-full text-slate-400 mb-3">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-sm font-semibold text-slate-800">Dokter tidak ditemukan</h3>
        <p class="text-xs text-slate-500 mt-1">Coba periksa kembali ejaan kata kunci Anda.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="surface-card overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-6 lg:px-10">
            <span class="text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Lokasi</span>
            <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Temukan kami dengan mudah.</h2>
        </div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31678.848019044606!2d112.53642909999999!3d-7.0262069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77e32ae7de3303%3A0x1913161fe0903272!2sRSI%20MABARROT%20MWC%20NU%20Bungah!5e0!3m2!1sen!2sid!4v1778582397809!5m2!1sen!2sid"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>
