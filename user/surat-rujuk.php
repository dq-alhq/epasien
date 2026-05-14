<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT RUJUKAN</strong></h5>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="table-responsive table-bordered">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No. Rujuk</th>
                        <th class="text-center">Dokter Perujuk</th>
                        <th class="text-center">Tujuan Rujuk</th>
                        <th class="text-center">Diagnosa</th>
                        <th class="text-center">Tgl. Rujuk</th>
                        <th class="text-center">Surat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $querysuratsakit = bukaquery("select rujuk.no_rujuk,rujuk.rujuk_ke,date_format(rujuk.tgl_rujuk,'%d/%m/%Y') as tgl_rujuk,rujuk.keterangan_diagnosa,dokter.nm_dokter from rujuk inner join dokter on rujuk.kd_dokter=dokter.kd_dokter inner join reg_periksa on reg_periksa.no_rawat=rujuk.no_rawat where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                    while ($rsquerysuratsakit = mysqli_fetch_array($querysuratsakit)) : ?>
                        <tr>
                            <td><?= $rsquerysuratsakit["no_rujuk"]; ?></td>
                            <td><?= $rsquerysuratsakit["nm_dokter"]; ?></td>
                            <td><?= $rsquerysuratsakit["rujuk_ke"]; ?></td>
                            <td><?= $rsquerysuratsakit["keterangan_diagnosa"]; ?></td>
                            <td><?= $rsquerysuratsakit["tgl_rujuk"]; ?></td>
                            <td>
                                <a href='index.php?act=TampilSuratRujuk&iyem=<?= encrypt_decrypt("{\"nosurat\":\"" . $rsquerysuratsakit["no_rujuk"] . "\"}", "e"); ?>'
                                   class='btn btn-warning waves-effect'>Tampilkan</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>