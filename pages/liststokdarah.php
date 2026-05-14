<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$darah = clean_post('darah', 20);
$filter = $darah ? " and utd_komponen_darah.nama like '%{$darah}%'" : '';
$querydarah = bukaquery("select utd_komponen_darah.nama,utd_stok_darah.golongan_darah,utd_stok_darah.resus,count(utd_stok_darah.kode_komponen) as jumlah from utd_komponen_darah inner join utd_stok_darah on utd_stok_darah.kode_komponen=utd_komponen_darah.kode where utd_stok_darah.status='Ada' {$filter} group by utd_stok_darah.kode_komponen,utd_stok_darah.golongan_darah,utd_stok_darah.resus order by utd_stok_darah.golongan_darah");
visitor_page_intro('Stok Darah', 'Ketersediaan Komponen Darah', 'Informasi stok darah disajikan secara jelas untuk membantu pencarian kebutuhan komponen darah lebih cepat.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariDarah" name="frmcariDarah" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="darah" id="darah" class="field-input" placeholder="Cari nama komponen darah" autocomplete="off" value="<?= e((string)($darah ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnDarah" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Komponen Darah</th>
                    <th>Golongan Darah</th>
                    <th>Resus</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false; while ($row = mysqli_fetch_array($querydarah)) : $hasRows = true; ?>
                    <tr>
                        <td><?= e($row["nama"]); ?></td>
                        <td><?= e($row["golongan_darah"]); ?></td>
                        <td><?= e($row["resus"]); ?></td>
                        <td><?= e((string)$row["jumlah"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows) : ?>
        <div class="mt-6"><?php visitor_empty_state('Data stok darah tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>
