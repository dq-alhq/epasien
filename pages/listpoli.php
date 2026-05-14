<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$poli = clean_post('poli', 20);
$filter = $poli ? " and nm_poli like '%{$poli}%'" : '';
$querypoli = bukaquery("select nm_poli from poliklinik where status='1' {$filter} order by nm_poli");
visitor_page_intro('Poliklinik', 'Poli dan Unit Layanan', 'Jelajahi unit pelayanan yang tersedia untuk membantu pasien menemukan layanan yang paling sesuai.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariPoli" name="frmCariPoli" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="poli" id="poli" class="field-input" placeholder="Cari nama poli atau unit" autocomplete="off" value="<?= e((string)($poli ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnPoli" type="submit">Cari</button>
        </div>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php $hasRows = false; while ($row = mysqli_fetch_array($querypoli)) : $hasRows = true; ?>
            <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-5 text-sm font-medium text-slate-700">
                <?= e($row["nm_poli"]); ?>
            </div>
        <?php endwhile; ?>
    </div>
    <?php if (!$hasRows) : ?>
        <div class="mt-6"><?php visitor_empty_state('Data poli atau unit tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>
