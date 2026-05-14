<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryradiologi = bukaquery("select jns_perawatan_radiologi.nm_perawatan,jns_perawatan_radiologi.kelas from jns_perawatan_radiologi inner join penjab on penjab.kd_pj=jns_perawatan_radiologi.kd_pj where jns_perawatan_radiologi.status='1' and penjab.png_jawab like '%umum%' order by jns_perawatan_radiologi.kelas");
dashboard_page_header('Radiologi', 'Layanan Radiologi', 'Daftar pemeriksaan radiologi yang tersedia untuk pasien.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nama Pemeriksaan</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryradiologi)) : ?>
                    <tr>
                        <td class="font-semibold text-slate-900"><?= e($row["nm_perawatan"]); ?></td>
                        <td><?= e($row["kelas"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
