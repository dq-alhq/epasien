<?php
include_once '../conf/conf.php';

$iyem = isset($_GET['iyem']) ? trim((string)$_GET['iyem']) : '';
$decoded = $iyem !== '' ? json_decode(encrypt_decrypt($iyem, "d"), true) : null;
$nobooking = isset($decoded["nobooking"]) ? cleankar2($decoded["nobooking"]) : '';
$querybooking = $nobooking !== '' ? bukaquery("select booking_periksa.no_booking,DATE_FORMAT(booking_periksa.tanggal,'%d-%m-%Y') as tanggal,booking_periksa.nama,booking_periksa.alamat,booking_periksa.no_telp,booking_periksa.email,poliklinik.nm_poli,DATE_FORMAT(booking_periksa.tanggal_booking,'%d-%m-%Y %H:%i:%s') as tanggal_booking from booking_periksa inner join poliklinik on booking_periksa.kd_poli=poliklinik.kd_poli where booking_periksa.no_booking='$nobooking'") : null;
$booking = ($querybooking && mysqli_num_rows($querybooking) > 0) ? mysqli_fetch_array($querybooking) : null;
$setting = mysqli_fetch_array(bukaquery("select setting.nama_instansi,setting.alamat_instansi,setting.kabupaten,setting.propinsi,setting.kontak,setting.email,setting.logo from setting"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Bukti Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 32px;
        }
        .sheet {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dbe7df;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #12824b;
        }
        .logo {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }
        .muted {
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
        }
        .title {
            margin: 28px 0 20px;
            text-align: center;
        }
        .title h1 {
            margin: 0;
            font-size: 24px;
        }
        .badge {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 14px;
            border-radius: 999px;
            background: #dcfce7;
            color: #0f6a3e;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }
        .grid {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 14px 18px;
            margin-top: 18px;
        }
        .label {
            color: #475569;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .value {
            color: #0f172a;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer-note {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .sheet {
                box-shadow: none;
                border: none;
                border-radius: 0;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <?php if ($booking) : ?>
            <div class="header">
                <img class="logo" src="data:image/jpeg;base64,<?= base64_encode($setting['logo']); ?>" alt="Logo Instansi">
                <div>
                    <h2 style="margin:0; font-size:24px;"><?= htmlspecialchars($setting["nama_instansi"], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="muted" style="margin:6px 0 0;">
                        <?= htmlspecialchars($setting["alamat_instansi"] . ', ' . $setting["kabupaten"] . ', ' . $setting["propinsi"], ENT_QUOTES, 'UTF-8'); ?><br>
                        <?= htmlspecialchars($setting["kontak"] . ' | ' . $setting["email"], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            </div>

            <div class="title">
                <h1>Bukti Booking Pasien</h1>
                <span class="badge">Nahdlatul Ulama Service</span>
            </div>

            <div class="grid">
                <div class="label">No. Booking</div><div class="value"><?= htmlspecialchars($booking["no_booking"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Tgl. Booking</div><div class="value"><?= htmlspecialchars($booking["tanggal_booking"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Tgl. Periksa</div><div class="value"><?= htmlspecialchars($booking["tanggal"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Nama</div><div class="value"><?= htmlspecialchars($booking["nama"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">No. HP / Telepon</div><div class="value"><?= htmlspecialchars($booking["no_telp"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Email</div><div class="value"><?= htmlspecialchars($booking["email"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Alamat</div><div class="value"><?= htmlspecialchars($booking["alamat"], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="label">Poliklinik / Unit</div><div class="value"><?= htmlspecialchars($booking["nm_poli"], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="footer-note">
                Simpan bukti booking ini dengan baik. Nomor booking diperlukan untuk proses pengecekan status dan tindak lanjut verifikasi admin.
            </div>
        <?php else : ?>
            <div class="title">
                <h1>Data Booking Tidak Ditemukan</h1>
                <p class="muted">Pastikan tautan cetak yang digunakan masih valid.</p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
