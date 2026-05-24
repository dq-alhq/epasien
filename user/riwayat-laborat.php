<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>RIWAYAT PERMINTAAN LAB</strong></h5>
</div>
<div class="card card-body">
    <div class="table-responsive table-bordered">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center">Tanggal & Jam</th>
                    <th class="text-center">No. Permintaan</th>
                    <th class="text-center">Cara Bayar</th>
                    <th class="text-center">Dokter Perujuk</th>
                    <th class="text-center">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $queryperiksa = bukaquery("select permintaan_lab.noorder,permintaan_lab.no_rawat,date_format(permintaan_lab.tgl_permintaan,'%d/%m/%Y') as tanggal,permintaan_lab.jam_permintaan,dokter.nm_dokter,permintaan_lab.diagnosa_klinis,penjab.png_jawab,permintaan_lab.tgl_hasil from permintaan_lab inner join dokter on permintaan_lab.dokter_perujuk=dokter.kd_dokter inner join reg_periksa on reg_periksa.no_rawat=permintaan_lab.no_rawat inner join penjab on reg_periksa.kd_pj=penjab.kd_pj where reg_periksa.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
                while ($rsqueryperiksa = mysqli_fetch_array($queryperiksa)) : ?>
                    <tr>
                        <td class="text-center"><?= $rsqueryperiksa["tanggal"]; ?> <?= $rsqueryperiksa["jam_permintaan"]; ?></td>
                        <td class="text-center"><?= $rsqueryperiksa["noorder"]; ?></td>
                        <td class="text-center"><?= $rsqueryperiksa["png_jawab"]; ?></td>
                        <td class="text-center"><?= $rsqueryperiksa["nm_dokter"]; ?></td>
                        <td class="text-center">
                            <?php if ($rsqueryperiksa["tgl_hasil"] == "0000-00-00") { ?>
                                <a href='?act=CekRiwayatLab&iyem=<?= encrypt_decrypt("{\"noorder\":\"" . $rsqueryperiksa["noorder"] . "\"}", "e"); ?>'
                                    class='btn btn-primary btn-transition'>Permintaan</a>
                            <?php } else { ?>
                                <a href='?act=CekHasilLab&iyem=<?= encrypt_decrypt("{\"norawat\":\"" . $rsqueryperiksa["no_rawat"] . "\",\"noorder\":\"" . $rsqueryperiksa["noorder"] . "\"}", "e"); ?>'
                                    class='btn btn-danger btn-transition'>Hasil</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
