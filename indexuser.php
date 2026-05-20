<?php
if (isset($_SESSION["ses_pasien"])) {
    $halaman = $_GET["hal"] ?? null;
    $subhalaman = $_GET["act"] ?? null;
    if (!isset($_SESSION["nm_pasien"])) {
        $queryuser = @bukaquery2("select pasien.nm_pasien,pasien.email,pasien.jk,personal_pasien.gambar,pasien.no_tlp,pasien.no_peserta,pasien.no_ktp,pasien.tmp_lahir,date_format(pasien.tgl_lahir,'%d/%m/%Y') as tgl_lahir from pasien inner join personal_pasien on personal_pasien.no_rkm_medis=pasien.no_rkm_medis where pasien.no_rkm_medis='" . cleankar(encrypt_decrypt($_SESSION["ses_pasien"], "d")) . "'");
        while ($rsqueryuser = mysqli_fetch_array($queryuser)) {
            $_SESSION["nm_pasien"] = $rsqueryuser["nm_pasien"];
            $_SESSION["email"] = $rsqueryuser["email"];
            $_SESSION["jk"] = $rsqueryuser["jk"];
            $_SESSION["no_tlp"] = $rsqueryuser["no_tlp"];
            $_SESSION["no_peserta"] = $rsqueryuser["no_peserta"];
            $_SESSION["no_ktp"] = $rsqueryuser["no_ktp"];
            $_SESSION["tmp_lahir"] = $rsqueryuser["tmp_lahir"];
            $_SESSION["tgl_lahir"] = $rsqueryuser["tgl_lahir"];
            if (($rsqueryuser["gambar"] ?? '') === "" || ($rsqueryuser["gambar"] ?? '') === "-") {
                $_SESSION["photo"] = $rsqueryuser["jk"] === "L" ? "assets/images/male.png" : "assets/images/female.png";
            } else {
                $_SESSION["photo"] = "http://" . host() . "/webapps/photopasien/" . $rsqueryuser["gambar"];
            }
        }
    }
} else {
    JSRedirect("index.php?act=Home");
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>EPasien <?= e($_SESSION["nama_instansi"] ?? ''); ?></title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="assets/fonts/stylesheet.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/landing/fontawesome.min.css">
</head>

<body class="app-user">
    <div class="min-h-screen">
        <?php include_once "layouts/sidebar.php"; ?>

        <div class="relative min-h-screen lg:pl-80">
            <?php include_once "layouts/navbar.php"; ?>

            <main class="px-4 pb-10 pt-6 sm:px-6 lg:px-8 lg:pb-12">
                <div class="mx-auto max-w-7xl">
                    <?php actionPages(); ?>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/plugin/jquery-3.7.1.min.js"></script>
    <script src="assets/plugin/count-to.js"></script>
    <script type="text/javascript" src="assets/dashboard/main.js"></script>
    <?php include_once "layouts/scripts.php"; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-target]').forEach(function(tabTrigger) {
                tabTrigger.addEventListener('click', function() {
                    var targetSelector = tabTrigger.getAttribute('data-target');
                    if (!targetSelector) {
                        return;
                    }

                    var nav = tabTrigger.closest('.nav');
                    if (nav) {
                        nav.querySelectorAll('.nav-link').forEach(function(link) {
                            link.classList.remove('active');
                        });
                    }

                    var contentRoot = tabTrigger.closest('.card, .dashboard-card, .dashboard-surface, body');
                    if (contentRoot) {
                        contentRoot.querySelectorAll('.tab-pane').forEach(function(pane) {
                            pane.classList.remove('active');
                        });
                    }

                    tabTrigger.classList.add('active');
                    var targetPane = document.querySelector(targetSelector);
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        });

        $(document).ready(function() {
            $(".timer").countTo();
        });
    </script>
</body>

</html>
