<?php
$halaman = $_GET["hal"] ?? null;
$subhalaman = $_GET["act"] ?? null;
$menuItems = dashboard_menu_items();
?>
<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-950/45 backdrop-blur-xs lg:hidden"></div>

<aside id="dashboard-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-80 -translate-x-full flex-col border-r border-white/70 bg-[linear-gradient(180deg,rgba(10,42,29,0.98),rgba(15,106,62,0.96))] text-white shadow-2xl transition-transform duration-300 lg:translate-x-0">
    <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
        <a href="?act=HomeUser&hal=Beranda" class="flex min-w-0 items-center gap-2">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white/90">
                <img src="assets/images/rsimnu.png" width="34" alt="Logo Rumah Sakit">
            </span>
            <span class="min-w-0">
                <span class="block text-[11px] font-semibold uppercase tracking-[0.32em] text-emerald-100">Portal
                    Pasien</span>
                <span
                    class="block truncate text-xs font-bold text-white"><?= e($_SESSION["nama_instansi"] ?? 'EPasien'); ?></span>
            </span>
        </a>
        <button id="close-sidebar" type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-white lg:hidden">
            <i class="fas fa-xmark"></i>
        </button>
    </div>

    <div class="px-6 pt-5">
        <div class="rounded-xl border border-white/10 bg-white/10 p-2 backdrop-blur-sm">
            <div class="flex items-start gap-2">
                <img src="<?= e($_SESSION['photo'] ?? 'assets/images/userlaki.png'); ?>" alt="Foto pasien"
                    class="size-14 rounded-lg object-cover ring-2 ring-white/20">
                <div class="min-w-0">
                    <p class="truncate text-xs text-white"><?= e($_SESSION['nm_pasien'] ?? '-'); ?></p>
                    <p class="truncate font-bold text-emerald-100">
                        <?= e(encrypt_decrypt($_SESSION["ses_pasien"] ?? '', "d")); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5">
        <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-emerald-100/80">Navigasi</p>
        <ul class="mt-4 space-y-2">
            <?php foreach ($menuItems as $index => $item): ?>
                <?php if ($item['type'] === 'link'): ?>
                    <?php $isActive = ($halaman === $item['hal']) || ($halaman === null && $item['hal'] === 'Beranda'); ?>
                    <li>
                        <a href="?act=<?= e($item['act']); ?>&hal=<?= e($item['hal']); ?>"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition <?= $isActive ? 'bg-white text-nu-700 shadow-lg shadow-black/10' : 'text-emerald-50 hover:bg-white/10 hover:text-white'; ?>">
                            <i class="<?= e($item['icon']); ?> w-5 text-center"></i>
                            <span class="truncate"><?= e($item['label']); ?></span>
                        </a>
                    </li>
                <?php else: ?>
                    <?php
                    $open = $halaman === $item['hal'];
                    $submenuId = 'submenu-' . $index;
                    ?>
                    <li>
                        <button type="button" data-submenu-toggle="<?= e($submenuId); ?>"
                            class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-medium transition <?= $open ? 'bg-white/12 text-white' : 'text-emerald-50 hover:bg-white/10 hover:text-white'; ?>">
                            <i class="<?= e($item['icon']); ?> w-5 text-center"></i>
                            <span class="truncate"><?= e($item['label']); ?></span>
                            <i
                                class="fas fa-chevron-down ml-auto text-xs transition-transform <?= $open ? 'rotate-180' : ''; ?>"></i>
                        </button>
                        <ul id="<?= e($submenuId); ?>" class="mt-2 space-y-2 pl-5 <?= $open ? '' : 'hidden'; ?>">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php $childActive = $subhalaman === $child['act']; ?>
                                <li>
                                    <a href="?act=<?= e($child['act']); ?>&hal=<?= e($child['hal']); ?>"
                                        class="flex items-center rounded-2xl px-4 py-2.5 text-sm transition <?= $childActive ? 'bg-white text-nu-700 shadow-lg shadow-black/10' : 'text-emerald-100 hover:bg-white/10 hover:text-white'; ?>">
                                        <?= e($child['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="border-t border-white/10 p-4">
        <form method="post">
            <input type="hidden" name="logout" value="logout">
            <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                <i class="fas fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-submenu-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var targetId = button.getAttribute('data-submenu-toggle');
                var submenu = document.getElementById(targetId);
                var icon = button.querySelector('.fa-chevron-down');

                if (!submenu) {
                    return;
                }

                submenu.classList.toggle('hidden');
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            });
        });
    });
</script>