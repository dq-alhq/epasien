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
        class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top_right,_rgba(18,130,75,0.12),_transparent_34%),linear-gradient(135deg,_rgba(255,255,255,0.96),_rgba(220,252,231,0.94))]">
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

        <div class="surface-card relative overflow-hidden p-8">
            <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-nu-100 blur-3xl"></div>
            <div class="relative">
                <div class="flex items-center gap-4">
                    <img src="assets/images/author-image.jpg" class="size-20 rounded-3xl object-cover ring-4 ring-white"
                        alt="Direktur">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-nu-700">Direktur</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-900">dr. Izzuddin Syahbana, M.K.M</h2>
                    </div>
                </div>
                <p class="mt-6 font-bold leading-7 text-slate-600">
                    Melayani Setulus Hati
                </p>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    kami hadirkan melalui pelayanan yang tertata, responsif, dan mudah diakses
                    oleh pasien serta keluarga.
                </p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Nilai</p>
                        <p class="mt-2 text-sm font-medium text-slate-800">Amanah, profesional, dan penuh kepedulian.
                        </p>
                    </div>
                    <div class="rounded-3xl bg-nu-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-nu-700">Fokus</p>
                        <p class="mt-2 text-sm font-medium text-slate-800">Pelayanan pasien yang cepat dan terarah.</p>
                    </div>
                </div>
            </div>
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
        $querydokter = bukaquery("select dokter.kd_dokter,left(dokter.nm_dokter,36) as dokter,spesialis.nm_sps,dokter.no_ijn_praktek,pegawai.photo from dokter inner join spesialis on dokter.kd_sps=spesialis.kd_sps inner join pegawai on dokter.kd_dokter=pegawai.nik where dokter.status='1' and dokter.kd_dokter<>'-' and dokter.no_ijn_praktek<>'-' and dokter.no_ijn_praktek<>'' and spesialis.nm_sps NOT LIKE '%Umum%' and spesialis.nm_sps<>'Perawat' order by spesialis.nm_sps limit 6");
        while ($rsquerydokter = mysqli_fetch_array($querydokter)):
             $photoName = trim((string) $rsquerydokter["kd_dokter"]) . ".jpg";
            $defaultPhoto = "assets/images/avatar.png";

            $photo = (
                $photoName !== '' &&
                file_exists($_SESSION["host_url"] . "/webapps/penggajian/" . $photoName)
            )
                ? $_SESSION["host_url"] . "/webapps/penggajian/$photoName"
                : $defaultPhoto;
            ?>
            <article class="surface-card p-5">
                <div class="flex items-start gap-4">
                    <img alt="Foto dokter" src="<?= e($photo); ?>"
                        class="size-24 rounded-3xl object-cover ring-1 ring-slate-200">
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900"><?= e($rsquerydokter["dokter"]); ?></h3>
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
    <div class="surface-card">
        <div class="flex flex-col gap-8 p-6 lg:p-10">
            <div>
                <span class="text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Jadwal Praktek</span>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Cari jadwal dokter dengan cepat.
                </h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    Gunakan pencarian untuk menemukan dokter, hari praktek, atau poliklinik yang Anda butuhkan.
                </p>
                <form action="" id="carikeyword" method="post" name="frmCariJadwal" class="mt-6">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input name="keyword" type="text" placeholder="Cari dokter atau poliklinik" id="keyword"
                            pattern="[a-zA-Z0-9, ./@_]{1,20}" title="[a-zA-Z0-9, ./@_] maksimal 20 karakter"
                            class="field-input" value="" maxlength="20" autocomplete="off">
                        <button class="btn-primary sm:min-w-36" name="BtnKeyword" type="submit">Cari Jadwal</button>
                    </div>
                </form>
            </div>
            <div id="hasilcari" class="rounded-[24px] border border-slate-200 bg-slate-50/70 p-4">
                <div
                    class="rounded-2xl border border-dashed border-nu-200 bg-white px-6 py-10 text-center text-sm text-slate-500">
                    Hasil jadwal dokter akan tampil di sini setelah pencarian dilakukan.
                </div>
            </div>
        </div>
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
