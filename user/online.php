<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryonline = bukaquery("select jns_perawatan.nm_perawatan,jns_perawatan.total_byrdr,poliklinik.nm_poli from jns_perawatan inner join penjab on penjab.kd_pj=jns_perawatan.kd_pj inner join poliklinik on poliklinik.kd_poli=jns_perawatan.kd_poli inner join set_tarif_online on set_tarif_online.kd_jenis_prw=jns_perawatan.kd_jenis_prw where jns_perawatan.status='1' and penjab.png_jawab like '%umum%' order by jns_perawatan.nm_perawatan");
dashboard_page_header('Konsultasi Online', 'Tarif Konsultasi Online', 'Informasi tarif konsultasi online untuk membantu pasien menyiapkan layanan yang dibutuhkan.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nama Tarif</th>
                    <th>Unit / Poliklinik</th>
                    <th>Tarif</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryonline)) : ?>
                    <tr>
                        <td class="font-semibold text-slate-900"><?= e($row["nm_perawatan"]); ?></td>
                        <td><?= e($row["nm_poli"]); ?></td>
                        <td><?= e(formatDuit($row["total_byrdr"])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
