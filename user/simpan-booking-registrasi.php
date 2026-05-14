<?php
if (strpos($_SERVER['REQUEST_URI'], "pages"))
    exit(header("Location:../index.php", ));

function Proses(): void
{
    if (getOne2("select count(pasien.no_rkm_medis) from pasien inner join reg_periksa on reg_periksa.no_rkm_medis=pasien.no_rkm_medis inner join kamar_inap on reg_periksa.no_rawat=kamar_inap.no_rawat where kamar_inap.stts_pulang='-' and pasien.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'") > 0) { ?>
        <div class="text-center mb-3">
            <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <p>Pasien sedang dalam masa perawatan di kamar inap..!!</p>
                    <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                </div>
            </div>
        </div>
        <?php JSRedirect2("?act=BookingRegistrasi&hal=Booking", 5);
    } else {
        extracted();
    }
}

/**
 * @return void
 */
function extracted(): void
{
    $json = trim((string) ($_GET['iyem'] ?? ''));
    $json = json_decode(encrypt_decrypt($json, "d"), true);
    $kd_dokter = $json["kd_dokter"];
    if (isset($kd_dokter)) {
        $kd_poli = $json["kd_poli"];
        $tanggal = $json["tanggal"];
        $thncari = substr($tanggal, 0, 4);
        $blncari = substr($tanggal, 5, 2);
        $tglcari = substr($tanggal, 8, 2);
        $hari = getOne("select DAYNAME('$thncari-$blncari-$tglcari')");
        $kuota = $json["kuota"];
        $sekarang = date("Y-m-d");
        $interval = getOne2("select (TO_DAYS('$tanggal')-TO_DAYS('$sekarang'))");
        if ($interval >= 0) {
            $daftar = getOne("select count(no_rkm_medis) from booking_registrasi where tanggal_periksa='$tanggal' and kd_dokter='$kd_dokter' and kd_poli='$kd_poli'");
            if (($kuota > 0) && ($daftar >= $kuota)) { ?>
                <div class="text-center mb-3">
                    <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <p>Kuota sudah terpenuhi</p>
                            <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                        </div>
                    </div>
                </div>
                <?php JSRedirect2("?act=BookingRegistrasi&hal=Booking", 5);
            } else { ?>
                <div class="text-center mb-3">
                    <h5 class="menu-header-title mb-3"><strong>VERIFIKASI BOOKING</strong></h5>
                </div>
                <div class="grid gap-6">
                    <div class="dashboard-card">
                            <form action="" id="formBookingRegistrasi" method="POST">
                                <div class="grid md:grid-cols-[200px_1fr] items-center gap-4 mb-3">
                                    <label for="tgl_rencana_periksa" class="col-form-label">Tanggal Rencana
                                        Periksa</label>
                                    <div>
                                        <input id="tgl_rencana_periksa" readonly class="form-control"
                                            value="<?= konversiHari($hari); ?>, <?= $tglcari ?> <?= konversiBulan($blncari) ?> <?= $thncari ?>">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-[200px_1fr] items-center gap-4 mb-3">
                                    <label for="dokter_pilihan" class="col-form-label">Dokter
                                        Pilihan</label>
                                    <div>
                                        <input id="dokter_pilihan" readonly class="form-control"
                                            value="<?= getOne2("select nm_dokter from dokter where kd_dokter='$kd_dokter'"); ?>">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-[200px_1fr] items-center gap-4 mb-3">
                                    <label for="poli_dipilih" class="col-form-label">Poli/Unit
                                        Pilihan</label>
                                    <div>
                                        <input id="poli_dipilih" readonly class="form-control"
                                            value="<?= getOne2("select nm_poli from poliklinik where kd_poli='$kd_poli'"); ?>">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-[200px_1fr] items-center gap-4 mb-3">
                                    <label for="penjab" class="col-form-label">Pilihan Jenis Bayar</label>
                                    <div>
                                        <select id="penjab" name="penjab" class="form-control">
                                            <option value="A02">Umum</option>
                                            <option disabled value="BPJ">BPJS (Lewat Mobile JKN)</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="kd_poli" value="<?= $kd_poli; ?>" />
                                <input type="hidden" name="tanggal" value="<?= $tanggal; ?>" />
                                <input type="hidden" name="kd_dokter" value="<?= $kd_dokter; ?>" />
                                <button class="btn btn-primary mt-4" type="submit" id="BtnSimpan" name="BtnSimpan">
                                    Sudah Benar
                                </button>
                                <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-secondary">Kembali</a>
                            </form>
                    </div>
                </div>
                <?php
            }
        } else { ?>
            <div class="text-center mb-3">
                <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <p>Batas booking 1 hari sebelum periksa</p>
                        <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                    </div>
                </div>
            </div>
            <?php JSRedirect2("?act=BookingRegistrasi&hal=Booking", 5);
        }
    } else { ?>
        <div class="text-center mb-3">
            <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <p>Kami tidak menemukan data booking anda</p>
                    <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                </div>
            </div>
        </div>
        <?php JSRedirect2("?act=BookingRegistrasi&hal=Booking", 5);
    }
}

$BtnSimpan = $_POST['BtnSimpan'] ?? NULL;
if (isset($BtnSimpan)) {
    $penjab = validTeks($_POST['penjab'] ?? NULL);
    $kd_poli = validTeks($_POST['kd_poli'] ?? NULL);
    $tanggal = validTeks($_POST['tanggal'] ?? NULL);
    $kd_dokter = validTeks($_POST['kd_dokter'] ?? NULL);
    if ((!empty($penjab)) && (!empty($kd_poli)) && (!empty($tanggal)) && (!empty($kd_dokter))) {
        $nourut = "";
        switch (URUTNOREG) {
            case "poli":
                $max = getOne("select ifnull(MAX(CONVERT(no_reg,signed)),0)+1 from booking_registrasi where kd_poli='$kd_poli' and tanggal_periksa='$tanggal'");
                $nourut = sprintf("%03s", $max);
                break;
            case "dokter":
                $max = getOne("select ifnull(MAX(CONVERT(no_reg,signed)),0)+1 from booking_registrasi where kd_dokter='$kd_dokter' and tanggal_periksa='$tanggal'");
                $nourut = sprintf("%03s", $max);
                break;
            case "dokter + poli":
                $max = getOne("select ifnull(MAX(CONVERT(no_reg,signed)),0)+1 from booking_registrasi where kd_poli='$kd_poli' and kd_dokter='$kd_dokter' and tanggal_periksa='$tanggal'");
                $nourut = sprintf("%03s", $max);
                break;
            default:
                $max = getOne("select ifnull(MAX(CONVERT(no_reg,signed)),0)+1 from booking_registrasi where kd_dokter='$kd_dokter' and tanggal_periksa='$tanggal'");
                $nourut = sprintf("%03s", $max);
                break;
        }
        $nourut = sprintf("%03s", $max);
        $insert = Tambah4("booking_registrasi", "CURRENT_DATE(),CURRENT_TIME(),'" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "','$tanggal','$kd_dokter','$kd_poli','$nourut','$penjab','1','$tanggal 00:00:00','Belum'");
        if ($insert) { ?>
            <div class="text-center mb-3">
                <h5 class="menu-header-title mb-3"><strong>PROSES BOOKING BERHASIL</strong></h5>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <p>Anda dapat melakukan cekin 1 x 24 jam sebelum pemeriksaan. Silahkan buka riwayat booking
                            untuk melakukan cekin</p>
                        <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="text-center mb-3">
                <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <p>Pendaftaran booking registrasi hanya diijinkan satu kali per tanggal pemeriksaan</p>
                        <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                    </div>
                </div>
            </div>
        <?php }
        JSRedirect2("?act=BookingRegistrasi&hal=Booking", 7);
    } else { ?>
        <div class="text-center mb-3">
            <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <p>Semua field wajib diisi</p>
                    <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                </div>
            </div>
        </div>
        <?php JSRedirect2("?act=SimpanBookingRegistrasi", 5);
    }
} else {
    $validasiregistrasi = getOne2("select wajib_closing_kasir from set_validasi_registrasi");
    if ($validasiregistrasi == "Yes") {
        if (getOne("select count(no_rkm_medis) from reg_periksa where no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "' and status_bayar='Belum Bayar' and stts<>'Batal'") > 0) { ?>
            <div class="text-center mb-3">
                <h5 class="menu-header-title mb-3"><strong>GAGAL MELAKUKAN BOOKING</strong></h5>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <p>Maaf, No. RM Anda pada kunjungan sebelumnya memiliki tagihan yang belum di closing.\n
                            Silahkan konfirmasi dengan pihak admin.. !!</p>
                        <a href="?act=BookingRegistrasi&hal=Booking" class="btn btn-danger btn-transition">Kembali</a>
                    </div>
                </div>
            </div>
            <?php JSRedirect2("?act=BookingRegistrasi&hal=Booking", 9);
        } else {
            Proses();
        }
    } else {
        Proses();
    }
}
?>
<script src="assets/plugin/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        $('#BtnSimpan').bind('click', function (e) {
            e.preventDefault();
            $(this).attr('disabled', 'disabled');
            $(this).text('Proses Booking...');
            Swal.fire({
                title: 'Simpan booking registrasi?',
                text: "Anda yakin ingin menyimpan booking registrasi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).removeAttr('disabled');
                    $(this).unbind().click();
                } else {
                    $(this).removeAttr('disabled');
                    $(this).text("Sudah Benar");
                }
            });
        })
    })
</script>
