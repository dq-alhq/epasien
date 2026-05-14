<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$queryperiksa = bukaquery("select reg_periksa.no_rawat,date_format(reg_periksa.tgl_registrasi,'%d/%m/%Y') as tgl_registrasi,dokter.nm_dokter,poliklinik.nm_poli,reg_periksa.status_lanjut from reg_periksa inner join dokter on reg_periksa.kd_dokter=dokter.kd_dokter inner join poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
dashboard_page_header('Riwayat Periksa', 'Riwayat Kunjungan dan Pemeriksaan', 'Akses ringkasan kunjungan terdahulu lengkap dengan tautan resume dan billing.');
?>
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Rawat</th>
                    <th>Poliklinik</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($queryperiksa)) : ?>
                    <tr>
                        <td><?= e($row["tgl_registrasi"]); ?></td>
                        <td class="font-semibold text-slate-900"><?= e($row["no_rawat"]); ?></td>
                        <td><?= e($row["nm_poli"]); ?></td>
                        <td><?= e($row["nm_dokter"]); ?></td>
                        <td><?= e($row["status_lanjut"]); ?></td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a href='?act=CekResume&iyem=<?= urlencode(encrypt_decrypt("{\"norawat\":\"" . $row["no_rawat"] . "\"}", "e")); ?>' class='dashboard-btn-primary'>Resume</a>
                                <a href='?act=CekBilling&iyem=<?= urlencode(encrypt_decrypt("{\"norawat\":\"" . $row["no_rawat"] . "\"}", "e")); ?>' class='dashboard-btn-danger'>Billing</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
