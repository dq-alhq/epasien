<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryKelas = bukaquery("SELECT nama.kelas,(SELECT COUNT(*) FROM kamar WHERE kelas = nama.kelas AND statusdata='1') AS total,(SELECT COUNT(*) FROM kamar WHERE kelas = nama.kelas AND statusdata='1' AND status='ISI') AS isi,(SELECT COUNT(*) FROM kamar WHERE kelas = nama.kelas AND statusdata='1' AND status='KOSONG') AS kosong FROM (SELECT DISTINCT kelas FROM kamar WHERE statusdata='1') AS nama");
$queryBangsal = bukaquery("SELECT nama.nm_bangsal, nama.kd_bangsal,(SELECT COUNT(*) FROM kamar WHERE kd_bangsal = nama.kd_bangsal AND statusdata='1') AS total,(SELECT COUNT(*) FROM kamar WHERE kd_bangsal = nama.kd_bangsal AND statusdata='1' AND status='ISI') AS isi,(SELECT COUNT(*) FROM kamar WHERE kd_bangsal = nama.kd_bangsal AND statusdata='1' AND status='KOSONG') AS kosong FROM (SELECT DISTINCT nm_bangsal, kd_bangsal FROM bangsal WHERE status='1' AND kd_bangsal IN(SELECT kd_bangsal FROM kamar)) AS nama");
dashboard_page_header('Ketersediaan Kamar', 'Monitoring Bed dan Ruang Perawatan', 'Informasi kapasitas kamar ditampilkan berdasarkan kelas dan nama bangsal agar pasien bisa memahami ketersediaan lebih cepat.');
?>
<div class="space-y-8">
    <div class="dashboard-card">
        <h2 class="text-2xl font-bold text-slate-900">Rekap Berdasarkan Kelas</h2>
        <div class="mt-6 table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Kelas Kamar</th>
                        <th>Jumlah Bed</th>
                        <th>Bed Terisi</th>
                        <th>Bed Kosong</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($queryKelas)) : ?>
                        <tr>
                            <td class="font-semibold text-slate-900"><?= e($row["kelas"]); ?></td>
                            <td><?= e((string)$row["total"]); ?></td>
                            <td><?= e((string)$row["isi"]); ?></td>
                            <td><?= e((string)$row["kosong"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card">
        <h2 class="text-2xl font-bold text-slate-900">Rekap Berdasarkan Bangsal</h2>
        <div class="mt-6 table-responsive">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Nama Kamar</th>
                        <th>Jumlah Bed</th>
                        <th>Bed Terisi</th>
                        <th>Bed Kosong</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($queryBangsal)) : ?>
                        <tr>
                            <td class="font-semibold text-slate-900"><?= e($row["nm_bangsal"]); ?></td>
                            <td><?= e((string)$row["total"]); ?></td>
                            <td><?= e((string)$row["isi"]); ?></td>
                            <td><?= e((string)$row["kosong"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
