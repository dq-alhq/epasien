<?php
ob_start();
session_start();
require_once('../conf/conf.php');

$sisaAntrian = 0;
switch (URUTNOREG) {
    case "poli":
        $sisaAntrian = (int)getOne("select count(no_rawat) from reg_periksa where stts = 'Belum' and kd_poli='" . cleankar2($_SESSION["kd_poli"] ?? '') . "' and tgl_registrasi='" . cleankar2($_SESSION["tgl_registrasi"] ?? '') . "' and no_reg < '" . cleankar2($_SESSION["no_reg"] ?? '') . "'");
        break;
    case "dokter + poli":
        $sisaAntrian = (int)getOne("select count(no_rawat) from reg_periksa where stts = 'Belum' and kd_poli='" . cleankar2($_SESSION["kd_poli"] ?? '') . "' and kd_dokter='" . cleankar2($_SESSION["kd_dokter"] ?? '') . "' and tgl_registrasi='" . cleankar2($_SESSION["tgl_registrasi"] ?? '') . "' and no_reg < '" . cleankar2($_SESSION["no_reg"] ?? '') . "'");
        break;
    default:
        $sisaAntrian = (int)getOne("select count(no_rawat) from reg_periksa where stts = 'Belum' and kd_dokter='" . cleankar2($_SESSION["kd_dokter"] ?? '') . "' and tgl_registrasi='" . cleankar2($_SESSION["tgl_registrasi"] ?? '') . "' and no_reg < '" . cleankar2($_SESSION["no_reg"] ?? '') . "'");
        break;
}
?>
<p class="text-sm font-semibold text-rose-600"><?= $sisaAntrian; ?> antrian lagi</p>
