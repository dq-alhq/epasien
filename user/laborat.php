<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$querylaborat = bukaquery("select jns_perawatan_lab.kd_jenis_prw,jns_perawatan_lab.nm_perawatan,jns_perawatan_lab.total_byr,jns_perawatan_lab.kelas from jns_perawatan_lab inner join penjab on penjab.kd_pj=jns_perawatan_lab.kd_pj where jns_perawatan_lab.status='1' and penjab.png_jawab like '%umum%' order by jns_perawatan_lab.kelas");
dashboard_page_header('Laboratorium', 'Paket Pemeriksaan Laboratorium', 'Informasi paket laboratorium beserta rincian pemeriksaannya ditampilkan lebih terstruktur.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Paket Pemeriksaan</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($querylaborat)) :
                    if ((float)$row["total_byr"] > 0) : ?>
                        <tr>
                            <td class="font-semibold text-slate-900"><?= e($row["nm_perawatan"]); ?></td>
                            <td><?= e($row["kelas"]); ?></td>
                        </tr>
                    <?php endif;
                    $querydetail = bukaquery("select Pemeriksaan,biaya_item from template_laboratorium where kd_jenis_prw='{$row[0]}' and biaya_item>0 order by urut");
                    while ($detail = mysqli_fetch_array($querydetail)) : ?>
                        <tr>
                            <td class="pl-10 text-slate-600">• <?= e($row["nm_perawatan"] . ' - ' . $detail["Pemeriksaan"]); ?></td>
                            <td><?= e($row["kelas"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
