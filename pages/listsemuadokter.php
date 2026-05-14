<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$querysemuadokter = bukaquery("select dokter.kd_dokter,left(dokter.nm_dokter,40) as dokter,spesialis.nm_sps,dokter.no_ijn_praktek,pegawai.photo from dokter inner join spesialis on dokter.kd_sps=spesialis.kd_sps inner join pegawai on dokter.kd_dokter=pegawai.nik where dokter.status='1' and dokter.kd_dokter<>'-' and dokter.no_ijn_praktek<>'-' and dokter.no_ijn_praktek<>'' order by spesialis.nm_sps,dokter.nm_dokter");
visitor_page_intro('Dokter', 'Seluruh Dokter Kami', 'Profil dokter disusun dalam kartu yang lebih modern agar informasi spesialis dan SIP lebih mudah ditelusuri.');
visitor_section_open();
?>
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <?php while ($row = mysqli_fetch_array($querysemuadokter)):
        $photoName = trim((string) $row["kd_dokter"]) . ".jpg";
        $defaultPhoto = "assets/images/avatar.png";

        $photo = ( $photoName !== '' && file_exists($_SESSION["host_url"] . "/webapps/penggajian/pages/pegawai/photo/" . $photoName) )
            ? $_SESSION["host_url"] . "/webapps/penggajian/pages/pegawai/photo/$photoName"
            : $defaultPhoto; ?>
        <article class="surface-card p-5">
            <div class="flex gap-4">
                <img alt="Foto dokter" src="<?= e($photo); ?>"
                    class="size-24 rounded-3xl object-cover ring-1 ring-slate-200">
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-slate-900"><?= e($row["dokter"]); ?></h3>
                    <p class="mt-1 text-sm text-slate-600"><?= e($row["nm_sps"]); ?></p>
                    <div class="mt-2 inline-flex rounded-full text-xs font-semibold text-nu-700">
                        SIP: <?= e($row["no_ijn_praktek"]); ?>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>
<?php visitor_section_close(); ?>
