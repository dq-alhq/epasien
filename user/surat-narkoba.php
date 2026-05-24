<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>SURAT KETERANGAN BEBAS NARKOBA</strong></h5>
</div>
<div class="card card-body">
    <div class="table-bordered table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center">
                        No. Surat
                    </th>
                    <th class="text-center">
                        Dokter Yang Memeriksa
                    </th>
                    <th class="text-center">
                        Tanggal
                    </th>
                    <th class="text-center">
                        Kategori
                    </th>
                    <th class="text-center">
                        Keperluan
                    </th>
                    <th class="text-center">
                        Surat
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $querysuratbebasnarkoba = bukaquery("select surat_skbn.no_surat,surat_skbn.no_rawat,date_format(surat_skbn.tanggalsurat,'%d/%m/%Y') as tanggalsurat,surat_skbn.kategori,surat_skbn.keperluan,dokter.nm_dokter from surat_skbn inner join reg_periksa on surat_skbn.no_rawat=reg_periksa.no_rawat inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis inner join dokter on dokter.kd_dokter=reg_periksa.kd_dokter where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                while ($rsquerysuratbebasnarkoba = mysqli_fetch_array($querysuratbebasnarkoba)) : ?>
                    <tr>
                        <td><?= $rsquerysuratbebasnarkoba["no_surat"]; ?></td>
                        <td><?= $rsquerysuratbebasnarkoba["nm_dokter"]; ?></td>
                        <td><?= $rsquerysuratbebasnarkoba["tanggalsurat"]; ?></td>
                        <td><?= $rsquerysuratbebasnarkoba["kategori"]; ?></td>
                        <td><?= $rsquerysuratbebasnarkoba["keperluan"]; ?></td>
                        <td>
                            <a href='index.php?act=TampilSuratBebasNarkoba&iyem=<?= encrypt_decrypt("{\"nosurat\":\"" . $rsquerysuratbebasnarkoba["no_surat"] . "\"}", "e"); ?>'
                                class='btn btn-warning btn-transition'>Tampilkan</a>
                        </td>
                    </tr>
                <?php endwhile;
                ?>
            </tbody>
        </table>
    </div>
</div>
