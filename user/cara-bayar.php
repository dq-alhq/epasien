<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryasuransi = bukaquery("select png_jawab, nama_perusahaan, alamat_asuransi, no_telp from penjab where status='1' and png_jawab <>'-' and png_jawab not like '%umum%' order by png_jawab");
dashboard_page_header('Asuransi', 'Mitra Asuransi dan Penjamin', 'Daftar perusahaan penjamin dan asuransi yang bekerja sama dengan rumah sakit.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nama Asuransi</th>
                    <th>Perusahaan</th>
                    <th>Alamat Perusahaan</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryasuransi)): ?>
                    <tr>
                        <td class="font-semibold text-slate-900"><?= e($row["png_jawab"]); ?></td>
                        <td><?= e($row["nama_perusahaan"]); ?></td>
                        <td><?= e($row["alamat_asuransi"]); ?></td>
                        <td><?= e($row["no_telp"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>