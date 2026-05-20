<?php
if (isset($_POST['logout']) && $_POST['logout'] === 'logout') {
    $_SESSION["ses_pasien"] = null;
    unset($_SESSION["ses_pasien"]);
    unset($_SESSION["nm_pasien"]);
    unset($_SESSION["email"]);
    unset($_SESSION["jk"]);
    unset($_SESSION["no_tlp"]);
    unset($_SESSION["no_peserta"]);
    unset($_SESSION["no_ktp"]);
    unset($_SESSION["tmp_lahir"]);
    unset($_SESSION["tgl_lahir"]);
    unset($_SESSION["photo"]);
    session_destroy();
    exit(header("Location:/epasien"));
}

$menuSearchItems = [];
foreach (dashboard_menu_items() as $item) {
    if ($item['type'] === 'link') {
        $menuSearchItems[] = [
            'label' => $item['label'],
            'url' => "index.php?act={$item['act']}&hal={$item['hal']}",
        ];
    } else {
        foreach ($item['children'] as $child) {
            $menuSearchItems[] = [
                'label' => $child['label'],
                'url' => "index.php?act={$child['act']}&hal={$child['hal']}",
            ];
        }
    }
}
?>
<header class="sticky top-0 z-30 border-b border-white/80 bg-white/80 backdrop-blur-xl print:hidden">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button id="open-sidebar" type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 lg:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-nu-700">Dashboard Pasien</p>
                <h1 class="truncate text-sm font-bold text-slate-900"><?= e($_SESSION['nm_pasien'] ?? 'Pasien'); ?></h1>
            </div>
        </div>

        <div class="hidden flex-1 justify-center xl:flex">
            <div class="relative w-full max-w-xl">
                <input id="dashboard-search-input" type="text" class="dashboard-input pl-12"
                    placeholder="Cari menu dashboard...">
                <i
                    class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <div id="dashboard-search-results"
                    class="absolute left-0 right-0 top-[calc(100%+12px)] hidden overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-soft">
                    <div id="dashboard-search-list" class="max-h-80 overflow-y-auto py-2">
                        <?php foreach ($menuSearchItems as $searchItem): ?>
                            <a href="<?= e($searchItem['url']); ?>"
                                class="dashboard-search-item block px-4 py-3 text-sm text-slate-700 transition hover:bg-nu-50 hover:text-nu-700"
                                data-label="<?= e(strtolower($searchItem['label'])); ?>">
                                <?= e($searchItem['label']); ?>
                            </a>
                        <?php endforeach; ?>
                        <div id="dashboard-search-empty" class="hidden px-4 py-4 text-sm text-slate-500">Menu tidak
                            ditemukan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('dashboard-sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        var openButton = document.getElementById('open-sidebar');
        var closeButton = document.getElementById('close-sidebar');

        function openSidebar() {
            if (!sidebar || !overlay) {
                return;
            }
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            if (!sidebar || !overlay) {
                return;
            }
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (openButton) {
            openButton.addEventListener('click', openSidebar);
        }
        if (closeButton) {
            closeButton.addEventListener('click', closeSidebar);
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        var searchInput = document.getElementById('dashboard-search-input');
        var results = document.getElementById('dashboard-search-results');
        var empty = document.getElementById('dashboard-search-empty');
        var items = document.querySelectorAll('.dashboard-search-item');

        if (searchInput && results && empty) {
            searchInput.addEventListener('input', function() {
                var keyword = searchInput.value.trim().toLowerCase();
                var found = 0;

                if (keyword === '') {
                    results.classList.add('hidden');
                    empty.classList.add('hidden');
                    items.forEach(function(item) {
                        item.classList.remove('hidden');
                    });
                    return;
                }

                results.classList.remove('hidden');
                items.forEach(function(item) {
                    var matches = item.dataset.label.indexOf(keyword) !== -1;
                    item.classList.toggle('hidden', !matches);
                    if (matches) {
                        found += 1;
                    }
                });

                empty.classList.toggle('hidden', found !== 0);
            });

            document.addEventListener('click', function(event) {
                if (!results.contains(event.target) && event.target !== searchInput) {
                    results.classList.add('hidden');
                }
            });
        }
    });
</script>
