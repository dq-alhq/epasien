<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$laborat = clean_post('laborat', 20);
$filter = $laborat ? " and (jns_perawatan_lab.nm_perawatan like '%{$laborat}%' or jns_perawatan_lab.kelas like '%{$laborat}%')" : '';
$querylaborat = bukaquery("select jns_perawatan_lab.kd_jenis_prw,jns_perawatan_lab.nm_perawatan,jns_perawatan_lab.kelas from jns_perawatan_lab inner join penjab on penjab.kd_pj=jns_perawatan_lab.kd_pj where jns_perawatan_lab.status='1' and penjab.png_jawab like '%umum%' {$filter} order by jns_perawatan_lab.kelas,jns_perawatan_lab.nm_perawatan");
visitor_page_intro('Laboratorium', 'Paket Pemeriksaan Laboratorium', 'Informasi paket laboratorium disusun lebih rapi lengkap dengan detail pemeriksaan pada tiap paket.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariLaborat" name="frmCariLaborat" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="laborat" id="laborat" class="field-input"
                placeholder="Cari paket pemeriksaan atau kelas" autocomplete="off"
                value="<?= e((string) ($laborat ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnLaborat" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Paket Pemeriksaan</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false;
                while ($row = mysqli_fetch_array($querylaborat)):
                    $hasRows = true; ?>
                    <tr class="bg-white">
                        <td class="font-semibold text-slate-900"><?= e($row["nm_perawatan"]); ?></td>
                    </tr>
                    <?php
                    $querydetail = bukaquery("select Pemeriksaan from template_laboratorium where kd_jenis_prw='" . $row["kd_jenis_prw"] . "' and biaya_item>0 order by urut");
                    while ($detail = mysqli_fetch_array($querydetail)):
                        ?>
                        <tr>
                            <td class="pl-10 text-slate-600">• <?= e($detail["Pemeriksaan"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows): ?>
        <div class="mt-6"><?php visitor_empty_state('Data laboratorium tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>