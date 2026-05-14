<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../../index.php"));
}
$norawat = trim((string)($_GET['iyem'] ?? ''));
$norawat = json_decode(encrypt_decrypt($norawat, "d"), true);
if (isset($norawat["norawat"])) {
    $norawat = cleankar2($norawat["norawat"]);
    $querybilling = bukaquery("select no,nm_perawatan, if(biaya<>0,biaya,null) as satu, if(jumlah<>0,jumlah,null) as dua,
                        if(tambahan<>0,tambahan,null) as tiga, if(totalbiaya<>0,totalbiaya,null) as empat,pemisah,status 
                        from billing where no_rawat='$norawat' order by noindex");
    if (mysqli_num_rows($querybilling) != 0) { ?>
        <div class="text-center mb-3">
            <h5 class="menu-header-title mb-1"><strong>BILLING PERAWATAN</strong></h5>
            <h6 class="menu-header-title mb-3">No. <?= $norawat; ?></h6>
        </div>
        <div class='row'>
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='card card-body'>
                    <div class='table-responsive table-bordered'>
                        <table class='table table-hover'>
                            <?php
                            $z = 1;
                            $total = 0;
                            while ($rsquerybilling = mysqli_fetch_array($querybilling)) {
                                if (($rsquerybilling["status"] != "Tagihan") && ($rsquerybilling["status"] != "TtlObat")) {
                                    $total = $total + $rsquerybilling["empat"];
                                    if ($z <= 6) { ?>
                                        <tr>
                                        <td><?= $rsquerybilling["no"]; ?></td>
                                        <td colspan='4'><?= $rsquerybilling["nm_perawatan"]; ?></td>
                                        </tr><?php
                                    } else { ?>
                                        <tr>
                                            <td><?= $rsquerybilling["no"]; ?></td>
                                            <td><?= $rsquerybilling["nm_perawatan"]; ?></td>
                                            <td align='right'><?= $rsquerybilling["pemisah"]; ?></td>
                                            <td align='right'><?= $rsquerybilling["dua"]; ?></td>
                                            <td align='right'><?= ($rsquerybilling["empat"] > 0 ? formatDuit($rsquerybilling["empat"]) : "") ?></td>
                                        </tr>
                                    <?php }
                                    $z++;
                                }
                            } ?>
                            <tr>
                                <td><b>TOTAL BILLING</b></td>
                                <td>:</td>
                                <td align='right'></td>
                                <td align='right'></td>
                                <td align='right'><b><?= formatDuit($total); ?></b></td>
                            </tr>
                        </table>
                    </div>
                    <center><a href='?act=HomeUser&hal=Beranda'
                               class='btn btn-danger btn-transition'>Kembali</a>
                    </center>
                </div>
            </div>
        </div>
        <?php
    } else { ?>
        <div class="text-center mb-3">
            <h5 class="menu-header-title mb-3"><strong>BILLING PERAWATAN</strong></h5>
        </div>
        <div class='row'>
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='card card-body text-center'>
                    <p>Maaf billing perawatan dengan No.Rawat <?= $norawat; ?> belum keluar, masih menunggu dikeluarkan
                        oleh kasir.</p>
                    <a href='?act=HomeUser&hal=Beranda' class='btn btn-danger btn-transition'>Kembali</a>
                </div>
            </div>
        </div>
        <?php
        JSRedirect2("?act=HomeUser&hal=Beranda", 7);
    }
} else { ?>
    <div class="text-center mb-3">
        <h5 class="menu-header-title mb-3"><strong>BILLING PERAWATAN</strong></h5>
    </div>
    <div class='row clearfix'>
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
            <div class='card card-body text-center'>
                <p>Maaf billing perawatan tidak ditemukan</p>
                <a href='?act=HomeUser&hal=Beranda' class='btn btn-danger btn-transition'>Kembali</a>
            </div>
        </div>
    </div>
    <?php
    JSRedirect2("?act=HomeUser&hal=Beranda", 4);
}
?>
