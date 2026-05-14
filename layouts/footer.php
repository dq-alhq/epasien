<footer class="px-4 pb-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="dashboard-surface flex flex-col gap-3 px-6 py-5 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
            <p>&copy; <?= e(date('Y')); ?> <?= e($_SESSION["nama_instansi"] ?? ''); ?></p>
            <p>EPasien menghadirkan pengalaman dashboard pasien yang lebih bersih dan modern.</p>
        </div>
    </div>
</footer>
