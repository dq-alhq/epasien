<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$asuransi = clean_post('asuransi', 20);
$filter = $asuransi ? " and (png_jawab like '%{$asuransi}%' or nama_perusahaan like '%{$asuransi}%')" : '';
$queryasuransi = bukaquery("select png_jawab, nama_perusahaan, alamat_asuransi, no_telp from penjab where status='1' and png_jawab <>'-' and png_jawab not like '%umum%' {$filter} order by png_jawab");
visitor_page_intro('Asuransi', 'Mitra Asuransi dan Penjamin', 'Informasi penjamin dan perusahaan asuransi yang bekerja sama dengan rumah sakit ditampilkan lebih terstruktur.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariAsuransi" name="frmCariAsuransi" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="asuransi" id="asuransi" class="field-input" placeholder="Cari nama asuransi atau perusahaan" autocomplete="off" value="<?= e((string)($asuransi ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnAsuransi" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Nama Asuransi</th>
                    <th>Perusahaan</th>
                    <th>Alamat Perusahaan</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false; while ($row = mysqli_fetch_array($queryasuransi)) : $hasRows = true; ?>
                    <tr>
                        <td><?= e($row["png_jawab"]); ?></td>
                        <td><?= e($row["nama_perusahaan"]); ?></td>
                        <td><?= e($row["alamat_asuransi"]); ?></td>
                        <td><?= e($row["no_telp"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows) : ?>
        <div class="mt-6"><?php visitor_empty_state('Data asuransi tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>
