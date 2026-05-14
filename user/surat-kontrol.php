<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT KONTROL / SKDP</strong></h5>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="table-responsive table-bordered">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No. Surat</th>
                        <th class="text-center">Dokter</th>
                        <th class="text-center">Diagnosa</th>
                        <th class="text-center">Rencana Tindak Lanjut</th>
                        <th class="text-center">Kembali</th>
                        <th class="text-center">Surat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $querysuratsakit = bukaquery("select skdp_bpjs.no_rkm_medis,skdp_bpjs.diagnosa,skdp_bpjs.rtl1,skdp_bpjs.tanggal_datang,skdp_bpjs.no_antrian,dokter.nm_dokter,skdp_bpjs.tahun,(TO_DAYS(skdp_bpjs.tanggal_datang)-TO_DAYS(current_date())) as kadaluarsa from skdp_bpjs inner join dokter on skdp_bpjs.kd_dokter=dokter.kd_dokter where skdp_bpjs.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                    while ($rsquerysuratsakit = mysqli_fetch_array($querysuratsakit)) :
                        if ($rsquerysuratsakit["kadaluarsa"] >= 0) { ?>
                            <tr>
                                <td><?= $rsquerysuratsakit["no_antrian"]; ?></td>
                                <td><?= $rsquerysuratsakit["nm_dokter"]; ?></td>
                                <td><?= $rsquerysuratsakit["diagnosa"]; ?></td>
                                <td><?= $rsquerysuratsakit["rtl1"]; ?></td>
                                <td><?= $rsquerysuratsakit["tanggal_datang"]; ?></td>
                                <td>
                                    <a href='index.php?act=TampilSuratKontrol&iyem=<?= encrypt_decrypt("{\"noantrian\":\"" . $rsquerysuratsakit["no_antrian"] . "\",\"tahun\":\"" . $rsquerysuratsakit["tahun"] . "\"}", "e"); ?>'
                                       class='btn btn-warning waves-effect'>Tampilkan</a>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td><?= $rsquerysuratsakit["no_antrian"]; ?></td>
                                <td><?= $rsquerysuratsakit["nm_dokter"]; ?></td>
                                <td><?= $rsquerysuratsakit["diagnosa"]; ?></td>
                                <td><?= $rsquerysuratsakit["rtl1"]; ?></td>
                                <td><?= $rsquerysuratsakit["tanggal_datang"]; ?></td>
                                <td>Kadaluarsa</td>
                            </tr>
                        <?php } endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>