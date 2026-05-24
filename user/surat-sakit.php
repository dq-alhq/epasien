<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT CUTI SAKIT</strong></h5>
</div>
<div class="card card-body">
    <div class="table-responsive table-bordered">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center">No. Surat</th>
                    <th class="text-center">Dokter Yang Merawat</th>
                    <th class="text-center">Tgl. Mulai</th>
                    <th class="text-center">Tgl. Selesai</th>
                    <th class="text-center">Lamanya</th>
                    <th class="text-center">Surat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $querysuratsakit = bukaquery("select suratsakit.no_surat,suratsakit.no_rawat,date_format(suratsakit.tanggalawal,'%d/%m/%Y') as tanggalawal,date_format(suratsakit.tanggalakhir,'%d/%m/%Y') as tanggalakhir,suratsakit.lamasakit,dokter.nm_dokter from suratsakit inner join reg_periksa on suratsakit.no_rawat=reg_periksa.no_rawat inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis inner join dokter on dokter.kd_dokter=reg_periksa.kd_dokter where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                while ($rsquerysuratsakit = mysqli_fetch_array($querysuratsakit)) : ?>
                    <tr>
                        <td><?= $rsquerysuratsakit["no_surat"]; ?></td>
                        <td><?= $rsquerysuratsakit["nm_dokter"]; ?></td>
                        <td><?= $rsquerysuratsakit["tanggalawal"]; ?></td>
                        <td><?= $rsquerysuratsakit["tanggalakhir"]; ?></td>
                        <td><?= $rsquerysuratsakit["lamasakit"]; ?></td>
                        <td>
                            <a href='index.php?act=TampilSuratSakit&iyem=<?= encrypt_decrypt("{\"nosurat\":\"" . $rsquerysuratsakit["no_surat"] . "\"}", "e"); ?>'
                                class='btn btn-warning btn-transition'>Tampilkan</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
