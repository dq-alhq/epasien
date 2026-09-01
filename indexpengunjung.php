<!DOCTYPE html>
<html lang="id">

<head>
    <title>EPasien <?= e($_SESSION["nama_instansi"] ?? ''); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="assets/fonts/stylesheet.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/landing/fontawesome.min.css">
</head>

<body class="app-guest">
    <div id="page-loader" class="fixed inset-0 z-100 flex items-center justify-center bg-white/90 backdrop-blur-sm">
        <div class="flex items-center gap-4 rounded-full border border-nu-100 bg-white px-6 py-4 shadow-soft">
            <span class="h-3 w-3 animate-pulse rounded-full bg-nu-600"></span>
            <span class="text-sm font-semibold tracking-[0.22em] text-nu-700">MEMUAT EPASIEN</span>
        </div>
    </div>

    <div class="page-shell relative">
        <nav class="sticky top-0 left-0 z-50 border-b border-white/80 bg-white/20 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-6 py-4">
                    <a href="?act=Home" class="flex items-center gap-4">
                        <span
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-xs ring-1 ring-slate-200">
                            <img src="assets/images/rsimnu.png" width="34" alt="Logo Rumah Sakit">
                        </span>
                        <span class="min-w-0">
                            <span
                                class="block text-xs font-semibold uppercase tracking-[0.3em] text-nu-700">E-PASIEN</span>
                            <span
                                class="block truncate text-sm font-bold text-slate-900"><?= e($_SESSION["nama_instansi"] ?? 'EPasien'); ?></span>
                        </span>
                    </a>

                    <div class="hidden items-center gap-3 lg:flex">
                        <ul
                            class="flex shrink-0 items-center gap-1 rounded-full border border-slate-200/80 bg-nu-50/70 p-1.5">
                            <?php actionMenu(); ?>
                        </ul>
                        <a href="?act=LoginPasien" class="btn-primary">Login</a>
                        <a href="?act=CekBooking" class="btn-secondary">Cek Booking</a>
                    </div>

                    <button id="mobile-menu-button" type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 lg:hidden"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div id="mobile-menu" class="hidden border-t border-slate-200 py-4 lg:hidden">
                    <ul class="flex flex-col gap-2">
                        <?php actionMenu(); ?>
                    </ul>
                    <div class="mt-4 flex flex-col gap-3">
                        <a href="?act=LoginPasien" class="btn-primary">Login</a>
                        <a href="?act=CekBooking" class="btn-secondary">Cek Booking</a>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <?php actionPages(); ?>
        </main>

        <footer class="mt-16 border-t border-nu-100 bg-white/90">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[1.3fr_1fr_1fr]">
                    <div class="surface-card p-7">
                        <h4 class="text-lg font-bold text-slate-900">Informasi Kontak</h4>

                        <div
                            class="mt-6 grid items-center grid-cols-[max-content_1fr] gap-x-3 gap-y-2 text-sm text-slate-600">
                            <i class="fas fa-location-dot text-nu-600"></i>
                            <p>
                                <?= e(($_SESSION["alamat_instansi"] ?? '-') . ', ' . ($_SESSION["kabupaten"] ?? '-')); ?>
                            </p>
                            <i class="fas fa-phone text-nu-600"></i>
                            <p><?= e($_SESSION["kontak"] ?? '-'); ?></p>
                            <i class="fas fa-envelope text-nu-600"></i>
                            <p><?= e($_SESSION["email"] ?? '-'); ?></p>
                        </div>
                    </div>

                    <div class="surface-card p-7">
                        <h4 class="text-lg font-bold text-slate-900">Pengumuman</h4>
                        <div class="mt-4 text-sm leading-7 text-slate-600">
                            <?php
                            $querypengumuman = bukaquery("
                                SELECT
                                    pegawai.nama,
                                    DATE_FORMAT(pengumuman_epasien.tanggal,'%d-%m-%Y') AS tanggal,
                                    pengumuman_epasien.pengumuman
                                FROM pengumuman_epasien
                                INNER JOIN pegawai ON pengumuman_epasien.nik=pegawai.nik
                                ORDER BY pengumuman_epasien.tanggal DESC
                                LIMIT 3
                            ");

                            if (mysqli_num_rows($querypengumuman) > 0):
                                while ($pengumuman = mysqli_fetch_array($querypengumuman)):
                            ?>
                                    <div class="mb-4">
                                        <p class="text-slate-700">
                                            <?= e($pengumuman["pengumuman"]) ?>
                                        </p>
                                        <p class="mt-2 text-xs font-medium text-slate-500">
                                            <?= e($pengumuman["tanggal"]) ?> oleh: <?= e($pengumuman["nama"]) ?>
                                        </p>
                                    </div>

                                    <hr class="my-3" />
                                <?php
                                endwhile;
                            else:
                                ?>
                                <p>Tidak ada pengumuman terbaru saat ini.</p>
                                <p class="mt-3 text-xs font-medium text-slate-500">
                                    <?= e(date('d/m/Y')) ?> • Admin
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="surface-card p-7">
                        <h4 class="text-lg font-bold text-slate-900">Kanal Resmi</h4>
                        <p class="mt-3 text-sm leading-7 text-slate-600">IGD buka 24 jam. Ikuti kanal resmi untuk
                            informasi layanan terbaru.</p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="https://www.facebook.com/rsimabarrotnu.bungah" target="_blank" rel="noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#1877F2] text-white transition hover:-translate-y-0.5">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/rsimabarrotnubungah" target="_blank" rel="noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#E4405F] text-white transition hover:-translate-y-0.5">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@rsimabarrotnubungah" target="_blank" rel="noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white transition hover:-translate-y-0.5">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="https://wa.me/message/TA3D4D6J52LJM1" target="_blank" rel="noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-[#25D366] text-white transition hover:-translate-y-0.5">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-6 text-center text-sm text-slate-500 md:flex-row">
                    <p>Copyright &copy; <?= e(date('Y')); ?> <?= e($_SESSION["nama_instansi"] ?? ''); ?></p>
                    <p>layanan pasien yang profesional dan terpercaya.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="assets/plugin/jquery-3.7.1.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            var loader = document.getElementById('page-loader');
            if (loader) {
                loader.classList.add('opacity-0', 'pointer-events-none', 'transition-opacity', 'duration-300');
                setTimeout(function() {
                    loader.remove();
                }, 320);
            }
        });

        var mobileMenuButton = document.getElementById('mobile-menu-button');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();

                var menu = document.getElementById('mobile-menu');
                if (!menu) {
                    return;
                }

                menu.classList.toggle('hidden');
                this.setAttribute(
                    'aria-expanded',
                    String(!menu.classList.contains('hidden'))
                );
            });

            document.addEventListener('click', function(e) {
                var menu = document.getElementById('mobile-menu');

                if (
                    menu &&
                    !menu.classList.contains('hidden') &&
                    !mobileMenuButton.contains(e.target)
                ) {
                    menu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        $(document).ready(function() {

            const $searchInput = $('#keyword');
            const $doctorCards = $('.doctor-card');
            const $noDataCard = $('#noDataCard');

            let debounceTimer;


            /*
            |--------------------------------------------------------------------------
            | GET SELECTED DAYS
            |--------------------------------------------------------------------------
            */
            function getSelectedDays() {
                return $('.option-checkbox:checked')
                    .map(function() {
                        return $(this).val().toLowerCase();
                    })
                    .get();
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE SELECTED TEXT
            |--------------------------------------------------------------------------
            */
            function updateSelected() {

                const selected = [];

                $('.option-checkbox:checked').each(function() {

                    selected.push({
                        value: $(this).val(),
                        text: $(this)
                            .closest('label')
                            .find('span')
                            .text()
                            .trim()
                    });

                });


                let buttonText = 'Filter Hari';

                if (selected.length === 1) {

                    buttonText = selected[0].text;

                } else if (selected.length === 2) {

                    buttonText = selected
                        .map(item => item.text)
                        .join(', ');

                } else if (selected.length > 2 && selected.length < 7) {

                    buttonText = `${selected.length} hari dipilih`;

                } else if (selected.length === 7) {

                    buttonText = 'Semua hari';

                }


                $('#selectedText')
                    .text(buttonText)
                    .toggleClass('text-slate-400', selected.length === 0)
                    .toggleClass('text-slate-900', selected.length > 0);


                filterCards($searchInput.val(), true);
            }



            /*
            |--------------------------------------------------------------------------
            | FILTER DOCTOR CARDS
            |--------------------------------------------------------------------------
            */
            function filterCards(keyword, immediate = false) {

                const filter = keyword.toLowerCase().trim();
                const selectedDays = getSelectedDays();

                let hasVisibleCard = false;

                $doctorCards.each(function() {

                    const $card = $(this);

                    const namaPoli = String(
                        $card.data('poli') || ''
                    ).toLowerCase();

                    const namaDokter = String(
                        $card.data('dokter') || ''
                    ).toLowerCase();

                    const hariIni =
                        $card.data('hari-ini') === 1 ||
                        $card.data('hari-ini') === '1';


                    /*
                    |--------------------------------------------------------------------------
                    | FILTER KEYWORD
                    |--------------------------------------------------------------------------
                    */
                    let keywordMatch = true;

                    if (filter !== '') {
                        keywordMatch =
                            namaPoli.includes(filter) ||
                            namaDokter.includes(filter);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FILTER JADWAL DI DALAM CARD
                    |--------------------------------------------------------------------------
                    */

                    let hasMatchingSchedule = false;


                    $card.find('.doctor-schedule').each(function() {

                        const $schedule = $(this);

                        const scheduleDay = String(
                            $schedule.data('hari') || ''
                        ).toLowerCase();


                        /*
                        |--------------------------------------------------------------------------
                        | Tidak ada filter hari
                        |--------------------------------------------------------------------------
                        |
                        | Semua jadwal ditampilkan.
                        |
                        */
                        if (selectedDays.length === 0) {

                            $schedule.show();

                            hasMatchingSchedule = true;

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Ada filter hari
                        |--------------------------------------------------------------------------
                        |
                        | Hanya jadwal dengan hari yang dipilih
                        | yang ditampilkan.
                        |
                        */
                        if (selectedDays.includes(scheduleDay)) {

                            $schedule.show();

                            hasMatchingSchedule = true;

                        } else {

                            $schedule.hide();

                        }

                    });


                    /*
                    |--------------------------------------------------------------------------
                    | TENTUKAN CARD DITAMPILKAN ATAU TIDAK
                    |--------------------------------------------------------------------------
                    */

                    let dayMatch = true;


                    if (selectedDays.length === 0) {

                        /*
                         * Default:
                         * jika keyword kosong → hanya hari ini
                         * jika keyword diisi → semua hari
                         */
                        dayMatch = filter === '' ?
                            hariIni :
                            true;

                    } else {

                        /*
                         * Jika filter hari aktif,
                         * card harus mempunyai jadwal pada hari tersebut.
                         */
                        dayMatch = hasMatchingSchedule;

                    }


                    const isMatch = keywordMatch && dayMatch;


                    /*
                    |--------------------------------------------------------------------------
                    | SHOW / HIDE CARD
                    |--------------------------------------------------------------------------
                    */

                    if (isMatch) {

                        if (immediate) {
                            $card.show();
                        } else {
                            $card.stop(true, true).fadeIn(150);
                        }

                        hasVisibleCard = true;

                    } else {

                        if (immediate) {
                            $card.hide();
                        } else {
                            $card.stop(true, true).fadeOut(100);
                        }

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | NO DATA
                |--------------------------------------------------------------------------
                */

                if (hasVisibleCard) {
                    $noDataCard.addClass('hidden');
                } else {
                    $noDataCard.removeClass('hidden');
                }
            }


            /*
            |--------------------------------------------------------------------------
            | TOGGLE DROPDOWN
            |--------------------------------------------------------------------------
            */
            $('#filterHari').on('click', function(e) {

                e.stopPropagation();

                $('#listHari').toggleClass('hidden');

            });


            /*
            |--------------------------------------------------------------------------
            | CHECKBOX CHANGE
            |--------------------------------------------------------------------------
            */
            $('.option-checkbox').on('change', function() {

                updateSelected();

            });


            /*
            |--------------------------------------------------------------------------
            | SELECT ALL
            |--------------------------------------------------------------------------
            */
            $('#selectAll').on('click', function(e) {

                e.preventDefault();
                e.stopPropagation();

                $('.option-checkbox').prop('checked', true);

                updateSelected();

            });


            /*
            |--------------------------------------------------------------------------
            | CLEAR ALL
            |--------------------------------------------------------------------------
            */
            $('#clearAll').on('click', function(e) {

                e.preventDefault();
                e.stopPropagation();

                $('.option-checkbox').prop('checked', false);

                updateSelected();

            });


            /*
            |--------------------------------------------------------------------------
            | CLICK OUTSIDE DROPDOWN
            |--------------------------------------------------------------------------
            */
            $(document).on('click', function(e) {

                if (!$(e.target).closest('#multiselect').length) {

                    $('#listHari').addClass('hidden');

                }

            });


            /*
            |--------------------------------------------------------------------------
            | SEARCH KEYWORD
            |--------------------------------------------------------------------------
            */
            $searchInput.on('input', function() {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {

                    filterCards($(this).val());

                }, 100);

            });


            /*
            |--------------------------------------------------------------------------
            | INITIAL STATE
            |--------------------------------------------------------------------------
            */
            updateSelected();

        });

        // Lazy loading untuk gambar
        const lazyImages = document.querySelectorAll('.lazy-load');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy-load');
                        img.onerror = function() {
                            this.src = 'assets/images/avatar.png';
                        };
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback untuk browser lama
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
                img.onerror = function() {
                    this.src = 'assets/images/avatar.png';
                };
            });
        }
    </script>
    <?php include_once 'layouts/chatbot.php'; ?>
</body>

</html>
