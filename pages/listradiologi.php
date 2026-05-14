<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$radiologi = clean_post('radiologi', 20);
$filter = $radiologi ? " and (jns_perawatan_radiologi.nm_perawatan like '%{$radiologi}%' or jns_perawatan_radiologi.kelas like '%{$radiologi}%')" : '';
$queryradiologi = bukaquery("select jns_perawatan_radiologi.nm_perawatan,jns_perawatan_radiologi.kelas from jns_perawatan_radiologi inner join penjab on penjab.kd_pj=jns_perawatan_radiologi.kd_pj where jns_perawatan_radiologi.status='1' and penjab.png_jawab like '%umum%' {$filter} order by jns_perawatan_radiologi.kelas,jns_perawatan_radiologi.nm_perawatan");
visitor_page_intro('Radiologi', 'Paket Layanan Radiologi', 'Daftar layanan radiologi ditampilkan dengan ringkas agar pasien lebih mudah menelusuri jenis pemeriksaan yang tersedia.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariRadiologi" name="frmCariRadiologi" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="radiologi" id="radiologi" class="field-input" placeholder="Cari nama pemeriksaan atau kelas" autocomplete="off" value="<?= e((string)($radiologi ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnRadiologi" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Nama Pemeriksaan</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false; while ($row = mysqli_fetch_array($queryradiologi)) : $hasRows = true; ?>
                    <tr>
                        <td><?= e($row["nm_perawatan"]); ?></td>
                        <td><?= e($row["kelas"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows) : ?>
        <div class="mt-6"><?php visitor_empty_state('Data radiologi tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>
