<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT KETERANGAN HAMIL</strong></h5>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card card-body">
            <div class="table-responsive table-bordered">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No. Surat</th>
                        <th class="text-center">Dokter Yang Memeriksa</th>
                        <th class="text-center">Tgl. Periksa</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Surat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $querysurathamil = bukaquery("select surat_hamil.no_surat,surat_hamil.no_rawat,date_format(surat_hamil.tanggalperiksa,'%d/%m/%Y') as tanggalperiksa,surat_hamil.hasilperiksa,dokter.nm_dokter from surat_hamil inner join reg_periksa on surat_hamil.no_rawat=reg_periksa.no_rawat inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis inner join dokter on dokter.kd_dokter=reg_periksa.kd_dokter where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                    while ($rsquerysurathamil = mysqli_fetch_array($querysurathamil)) :?>
                        <tr>
                            <td><?= $rsquerysurathamil["no_surat"]; ?></td>
                            <td><?= $rsquerysurathamil["nm_dokter"]; ?></td>
                            <td><?= $rsquerysurathamil["tanggalperiksa"]; ?></td>
                            <td><?= $rsquerysurathamil["hasilperiksa"]; ?></td>
                            <td>
                                <a href='index.php?act=TampilSuratHamil&iyem=<?= encrypt_decrypt("{\"nosurat\":\"" . $rsquerysurathamil["no_surat"] . "\"}", "e"); ?>'
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