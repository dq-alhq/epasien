<?php
ob_start();
session_start();
require_once('../../conf/conf.php');
?>
<div class="chat-wrapper">
    <?php
    $sqlpengaduan = "SELECT DATE_FORMAT(pengaduan.tanggal,'%d/%m/%y %H:%i:%s') as tanggal,pengaduan.pesan,pengaduan.id FROM pengaduan where pengaduan.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "' ORDER BY tanggal limit 30";
    $resultpengaduan = bukaquery2($sqlpengaduan);
    while ($rowpengaduan = mysqli_fetch_array($resultpengaduan)) :?>
        <div class="chat-box-wrapper chat-box-wrapper-right float-right">
            <div>
                <div class="chat-box">
                    <?= $rowpengaduan["pesan"]; ?>
                </div>
                <small class="opacity-6">
                    <i class="fa fa-calendar-alt mr-1"></i>
                    <?= $rowpengaduan["tanggal"]; ?>
                </small>
            </div>
            <div>
                <div class="avatar-icon-wrapper ml-1">
                    <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg">
                    </div>
                    <div class="avatar-icon avatar-icon-lg rounded">
                        <img src="<?= $_SESSION["photo"]; ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
        <?php
        $balasan = getOne("select pesan_balasan from balasan_pengaduan where id_pengaduan='" . $rowpengaduan["id"] . "'");
        if (!empty($balasan)) :?>
            <div class="chat-box-wrapper">
                <div>
                    <div class="avatar-icon-wrapper mr-1">
                        <div class="avatar-icon avatar-icon-lg rounded">
                            <img src="assets/images/rsimnu.png" alt="">
                        </div>
                    </div>
                </div>
                <div>
                    <div class="chat-box"><?= $balasan; ?></div>
                </div>
            </div>
        <?php
        endif; endwhile;
    if (mysqli_num_rows($resultpengaduan) == 0) : ?>
        <h5 class="text-center">Kosong</h5>
    <?php endif; ?>
</div>


