<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT KETERANGAN RAPID TEST</strong></h5>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="table-responsive table-bordered">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">No. Surat</th>
                        <th class="text-center">Dokter P.J.</th>
                        <th class="text-center">Petugas</th>
                        <th class="text-center">Hasil IgM</th>
                        <th class="text-center">Hasil IgG</th>
                        <th class="text-center">Berlaku</th>
                        <th class="text-center">Sampai</th>
                        <th class="text-center">Surat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $querysuratcovid = bukaquery("select surat_keterangan_covid.no_surat,surat_keterangan_covid.no_rawat,surat_keterangan_covid.kd_dokter,dokter.nm_dokter,surat_keterangan_covid.nip,petugas.nama,surat_keterangan_covid.igm,surat_keterangan_covid.igg,surat_keterangan_covid.sehat,surat_keterangan_covid.tidaksehat,date_format(surat_keterangan_covid.berlakumulai,'%d/%m/%Y') as berlakumulai,date_format(surat_keterangan_covid.berlakuselsai,'%d/%m/%Y') as berlakuselsai from surat_keterangan_covid inner join reg_periksa on surat_keterangan_covid.no_rawat=reg_periksa.no_rawat inner join dokter on surat_keterangan_covid.kd_dokter=dokter.kd_dokter inner join petugas on surat_keterangan_covid.nip=petugas.nip where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                    while ($rsquerysuratcovid = mysqli_fetch_array($querysuratcovid)) :?>
                        <tr>
                            <td><?= $rsquerysuratcovid["no_surat"]; ?></td>
                            <td><?= $rsquerysuratcovid["nm_dokter"]; ?></td>
                            <td><?= $rsquerysuratcovid["nama"]; ?></td>
                            <td><?= $rsquerysuratcovid["igm"]; ?></td>
                            <td><?= $rsquerysuratcovid["igg"]; ?></td>
                            <td><?= $rsquerysuratcovid["berlakumulai"]; ?></td>
                            <td><?= $rsquerysuratcovid["berlakuselsai"]; ?></td>
                            <td>
                                <a href='index.php?act=TampilSuratCovid&iyem=<?= encrypt_decrypt("{\"nosurat\":\"" . $rsquerysuratcovid["no_surat"] . "\"}", "e"); ?>'
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