<?php
if (strpos($_SERVER['REQUEST_URI'], 'pages')) {
    exit(header('Location:../index.php'));
}

$errorLogin = false;
$errorCaptcha = false;

if (isset($_POST['login'])) {
    $noRkmMedis = preg_replace('/\D+/', '', (string) ($_POST['no_rkm_medis'] ?? ''));
    $nik = preg_replace('/\D+/', '', (string) ($_POST['nik'] ?? ''));
    $num1 = (int) ($_POST['num1'] ?? 0);
    $num2 = (int) ($_POST['num2'] ?? 0);
    $result = (int) ($_POST['result'] ?? 0);

    if (($num1 + $num2) !== $result) {
        $errorCaptcha = true;
    } elseif ($noRkmMedis !== '' && $nik !== '' && getOne2("select count(*) from pasien where no_rkm_medis='{$noRkmMedis}' and no_ktp='{$nik}'") > 0) {
        $_SESSION['ses_pasien'] = encrypt_decrypt($noRkmMedis, 'e');
        exit(header('Location:index.php'));
    } else {
        $errorLogin = true;
    }
}

$captchaA = random_int(5, 20);
$captchaB = random_int(1, 10);
?>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div id="mjkn-warning" class="border relative space-y-1 p-4 bg-white/40 rounded-lg border-red-700 mb-8">
        <button type="button" onclick="document.getElementById('mjkn-warning').style.display='none';" class="border size-7 p-1 rounded-md bg-red-100 hover:bg-red-200 flex items-center justify-center transition-all absolute right-2 top-2"><i  class="fas fa-close m-0 text-red-700"></i></button>
        <h1 class="text-xl font-semibold tracking-tight text-red-800">PERHATIAN !!!</h1>
        <p class="text-base text-red-700">Registrasi Pasien BPJS wajib melalui aplikasi <a href="https://play.google.com/store/apps/details?id=app.bpjs.mobile&hl=id" target="_blank" class="text-blue-600 font-bold hover:text-blue-700">Mobile JKN</a></p>
        <p class="text-base text-red-700">Butuh panduan? Hubungi  <a class="font-semibold text-nu-700 hover:text-nu-800"
                                                                     href="https://wa.me/6281239909455?text=Halo%2C%20saya%20butuh%20panduan%20untuk%20Pendaftaran%20Pasien%20melalui%20Mobile%20JKN."
                                                                     target="_blank" rel="noreferrer">WhatsApp Pusat Informasi</a></p>
    </div>
    <div class="flex flex-col-reverse md:flex-row gap-8">
        <div class="surface-card relative overflow-hidden p-8 lg:p-10">
            <div class="absolute right-0 top-0 size-44 rounded-full bg-nu-100 blur-3xl"></div>
            <div class="relative">
                <span
                    class="inline-flex rounded-full border border-nu-200 bg-white/90 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Portal
                    Pasien</span>
                <h1 class="mt-5 text-4xl font-semibold tracking-tight text-slate-900">Masuk ke layanan pasien online.
                </h1>
                <p class="mt-5 text-base leading-8 text-slate-600">
                    Gunakan nomor rekam medis dan NIK untuk mengakses layanan pasien, riwayat, serta pendaftaran yang
                    lebih praktis.
                </p>
                <div class="mt-8 grid gap-4">
                    <div class="rounded-3xl bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pasien Lama</p>
                        <p class="mt-2 text-sm leading-7 text-slate-700">Masuk dengan nomor rekam medis dan NIK yang
                            terdaftar.</p>
                    </div>
                    <div class="rounded-3xl bg-nu-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-nu-700">Belum Punya Akun</p>
                        <p class="mt-2 text-sm leading-7 text-slate-700">Daftar pasien mandiri melalui <a class="font-semibold text-nu-700 hover:text-nu-800"
                                                                                                          href="https://wa.me/6281239909455?text=Halo%2C%20saya%20ingin%20mendaftar%20Pasien%20mandiri%20melalui%20E-Pasien%20RSI%20Mabarrot%2C%20namun%20belum%20memiliki%20Nomor%20Rekam%20Medis.%0A%0AMohon%20Bantuannya!"
                                                                                                          target="_blank" rel="noreferrer">WhatsApp Pusat Informasi</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="surface-card p-8 lg:p-10">
            <h2 class="text-2xl font-bold text-slate-900">Masuk Pasien</h2>
            <p class="mt-2 text-sm leading-7 text-slate-600">Lengkapi data berikut untuk melanjutkan ke dashboard
                pasien.</p>

            <form method="post" action="" class="mt-8 space-y-5">
                <div>
                    <label for="no_rkm_medis" class="field-label">Nomor Rekam Medis</label>
                    <input type="password" class="field-input" name="no_rkm_medis" id="no_rkm_medis" autocomplete="off"
                        required autofocus>
                </div>

                <div>
                    <label for="nik" class="field-label">Nomor KTP / NIK</label>
                    <input type="text" class="field-input" name="nik" id="nik" autocomplete="off" required
                        pattern="[0-9]{16}" title="NIK harus 16 digit angka">
                    <?php if ($errorLogin) { ?>
                        <p class="mt-2 text-sm font-medium text-rose-600">Nomor rekam medis atau NIK tidak sesuai.</p>
                    <?php } ?>
                </div>

                <div>
                    <label class="field-label">Verifikasi Penjumlahan</label>
                    <div class="flex items-center gap-3">
                        <input type="text" class="field-input max-w-[84px] text-center" tabindex="-1" readonly
                            name="num1" value="<?= $captchaA; ?>" autocomplete="off">
                        <span class="text-lg font-semibold text-slate-500">+</span>
                        <input type="text" class="field-input max-w-[84px] text-center" tabindex="-1" readonly
                            name="num2" value="<?= $captchaB; ?>" autocomplete="off">
                        <span class="text-lg font-semibold text-slate-500">=</span>
                        <input type="text" class="field-input max-w-[110px] text-center" name="result" id="result"
                            autocomplete="off" required>
                    </div>
                    <?php if ($errorCaptcha) { ?>
                        <p class="mt-2 text-sm font-medium text-rose-600">Hasil perhitungan belum benar.</p>
                    <?php } ?>
                </div>

                <button type="submit" class="btn-primary w-full" name="login">
                    <i class="fas fa-right-to-bracket mr-2"></i>Masuk ke EPasien
                </button>
            </form>

            <div
                class="mt-8 rounded-3xl border border-dashed border-nu-200 bg-nu-50/70 p-5 text-sm leading-7 text-slate-600">
                Jika belum memiliki nomor rekam medis, silakan daftar melalui
                <a class="font-semibold text-nu-700 hover:text-nu-800"
                    href="https://wa.me/6281239909455?text=Halo%2C%20saya%20ingin%20mendaftar%20Pasien%20mandiri%20melalui%20E-Pasien%20RSI%20Mabarrot%2C%20namun%20belum%20memiliki%20Nomor%20Rekam%20Medis.%0A%0AMohon%20Bantuannya!"
                    target="_blank" rel="noreferrer">WhatsApp admin pendaftaran</a>.
            </div>
        </div>
    </div>
</section>
