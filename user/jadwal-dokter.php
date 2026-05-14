<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryjadwal = bukaquery("select dokter.nm_dokter,jadwal.hari_kerja,jadwal.jam_mulai,jadwal.jam_selesai,poliklinik.nm_poli from jadwal inner join poliklinik inner join dokter on jadwal.kd_dokter=dokter.kd_dokter and jadwal.kd_poli=poliklinik.kd_poli where dokter.status='1' order by jadwal.hari_kerja,jadwal.kd_dokter");
dashboard_page_header('Jadwal Dokter', 'Jadwal Praktek Dokter', 'Lihat jadwal praktek dokter secara lengkap dengan tampilan tabel yang lebih nyaman dibaca.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Hari Kerja</th>
                    <th>Poliklinik</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryjadwal)) : ?>
                    <tr>
                        <td class="font-semibold text-slate-900"><?= e($row["nm_dokter"]); ?></td>
                        <td><?= e($row["hari_kerja"]); ?></td>
                        <td><?= e($row["nm_poli"]); ?></td>
                        <td><?= e($row["jam_mulai"]); ?></td>
                        <td><?= e($row["jam_selesai"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
