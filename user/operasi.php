<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryoperasi = bukaquery("select paket_operasi.nm_perawatan,paket_operasi.kelas from paket_operasi inner join penjab on penjab.kd_pj=paket_operasi.kd_pj where paket_operasi.status='1' and penjab.png_jawab like '%umum%' order by paket_operasi.kelas");
dashboard_page_header('Operasi', 'Paket Layanan Operasi', 'Daftar layanan operasi ditampilkan lebih ringkas dan mudah dipindai oleh pasien.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Paket Operasi</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryoperasi)) : ?>
                    <tr>
                        <td class="font-semibold text-slate-900"><?= e($row["nm_perawatan"]); ?></td>
                        <td><?= e($row["kelas"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
