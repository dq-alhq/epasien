<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$querykamar = bukaquery("select kamar.kd_kamar,bangsal.nm_bangsal,kamar.kelas,kamar.status from bangsal inner join kamar on kamar.kd_bangsal=bangsal.kd_bangsal where kamar.statusdata='1' order by kamar.kelas");
dashboard_page_header('Fasilitas', 'Data Kamar Rawat', 'Ringkasan data kamar rawat ditampilkan dalam tabel yang lebih bersih dan mudah dipindai.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nomor Bed</th>
                    <th>Nama Kamar</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($querykamar)) : ?>
                    <tr>
                        <td><?= e($row["kd_kamar"]); ?></td>
                        <td class="font-semibold text-slate-900"><?= e($row["nm_bangsal"]); ?></td>
                        <td><?= e($row["kelas"]); ?></td>
                        <td><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $row["status"] === 'ISI' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'; ?>"><?= e($row["status"]); ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
