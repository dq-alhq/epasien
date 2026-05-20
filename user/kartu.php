<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
$PNG_WEB_DIR = 'temp/';
include "assets/plugin/phpqrcode/qrlib.php";
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);
$filename = $PNG_TEMP_DIR . encrypt_decrypt($_SESSION["ses_pasien"], "d") . '.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 4;
QRcode::png(encrypt_decrypt($_SESSION["ses_pasien"], "d"), $filename, $errorCorrectionLevel, $matrixPointSize, 2) ?>
<div class="text-center mb-3 pagetitle">
    <h5 class="menu-header-title mb-3"><strong>KARTU PASIEN</strong></h5>
</div>
<style>
    @import url("assets/fonts/stylesheet.css");

    @page {
        margin: 0;
    }

    @media print {
        .pagetitle {
            display: none;
        }

        form {
            display: none;
        }
    }


    .kartu * {
        margin: 0;
        padding: 0;
        border: 0;
        font: inherit;
        vertical-align: baseline;
        line-height: 1;
    }

    .kartu {
        border: 1px solid black;
        width: 84mm;
        height: 54mm;
        border-radius: 10px;
        position: relative;
        padding: 1mm;
    }

    .kop {
        -webkit-print-color-adjust: exact !important;
        background: url("assets/images/background-kartu.jpg");
        background-size: cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 1.5cm;
        border-radius: 8px;
    }

    .kop h1 {
        font-family: "GT Walsheim Pro Condensed", sans-serif;
        margin-left: 2cm;
    }

    .kop h2 {
        font-family: "GT Walsheim Pro Condensed", sans-serif;
        margin-left: 2cm;
    }

    .foto {
        position: relative;
        width: 2cm;
        margin-left: 2mm;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: -13mm;
    }

    .foto img {
        width: 18mm;
        height: 18mm;
        border-radius: 15px;
    }

    .foto .nomor {
        margin-top: 2mm;
        font-family: "GT Walsheim Pro", sans-serif;
        font-weight: bold;
    }

    .nama {
        width: 57.5mm;
        padding: 4px;
        border-radius: 5px;
        position: absolute;
        font-family: "GT Walsheim Pro Condensed", sans-serif;
        font-size: 15px;
        top: 65px;
        left: 95px;
    }

    .identitas {
        margin-top: 7px;
        font-family: "GT Walsheim Pro Condensed", sans-serif;
        font-size: 9pt;
        padding: 5px 10px;
        display: inline-grid;
        grid-template-columns: auto auto;
        gap: 1px 4mm;
    }

    .qr {
        position: absolute;
        right: 10px;
        bottom: 40px;
        border-radius: 10px;
        border: 1px solid black;
        overflow: hidden;
    }

    .qr img {
        width: 20mm;
        height: 20mm;
    }
</style>
<div class='justify-content-center d-flex flex-column align-items-center shrink-0'>
    <div class="kartu">
        <div class="kop">
            <h1>KARTU PASIEN</h1>
            <h2>RSI MABARROT MWC NU BUNGAH</h2>
        </div>
        <div class="foto">
            <?php
            // Default image
            $defaultImage = 'assets/images/rsimnu.png';
            // Cek apakah session photo ada
            if (!empty($_SESSION['photo'])) {
                // Asumsikan foto disimpan di folder uploads/
                $photoPath = 'uploads/' . $_SESSION['photo'];
                // Cek apakah file benar-benar ada di server
                if (file_exists($photoPath)) {
                    $photo = $photoPath;
                } else {
                    $photo = $defaultImage; // fallback kalau file tidak ditemukan
                }
            } else {
                $photo = $defaultImage; // fallback kalau session kosong
            }
            ?>
            <!-- Tampilkan gambar -->
            <img src="<?= htmlspecialchars($photo) ?>" alt="Foto/Logo" width="100" height="100"
                class="img-fluid rounded-circle">
            <div class="nomor"><?= encrypt_decrypt($_SESSION["ses_pasien"], "d"); ?></div>
        </div>
        <div class="nama"><?= $_SESSION["nm_pasien"]; ?></div>
        <div class="identitas">
            <div class="atribut">Jenis Kelamin</div>
            <div class="isi"><?= $_SESSION["jk"] == "L" ? "Laki-Laki" : "Perempuan"; ?></div>
            <div class="atribut">TTL</div>
            <div class="isi"><?= ucwords(strtolower($_SESSION["tmp_lahir"])); ?>
                , <?= $_SESSION["tgl_lahir"]; ?></div>
            <div class="atribut">No. KTP / NIK</div>
            <div class="isi"><?= $_SESSION["no_ktp"]; ?></div>
            <div class="atribut">No. Asuransi/JKN</div>
            <div class="isi"><?= $_SESSION["no_peserta"]; ?></div>
            <div class="atribut">No. HP</div>
            <div class="isi"><?= $_SESSION["no_tlp"]; ?></div>
            <div class="atribut">Email</div>
            <div class="isi"><?= $_SESSION["email"]; ?></div>
            <div class="qr">
                <img src='user/<?= $PNG_WEB_DIR . basename($filename); ?>' alt="QR Code" />
            </div>
        </div>
    </div>
    <form action="" method="POST">
        <input name="BtnCetak" type="submit" class="btn btn-primary btn-transition btn-lg btn-block mt-2"
            value="CETAK">
    </form>
</div>
<!---->
<?php $BtnCetak = $_POST['BtnCetak'] ?? NULL;
if (isset($BtnCetak)) {
?>
    <script>
        window.print();
    </script>
<?php
}; ?>
