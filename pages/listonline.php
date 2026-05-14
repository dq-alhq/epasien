<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$online = clean_post('online', 20);
$filter = $online ? " and (jns_perawatan.nm_perawatan like '%{$online}%' or poliklinik.nm_poli like '%{$online}%')" : '';
$queryonline = bukaquery("select jns_perawatan.nm_perawatan,jns_perawatan.total_byrdr,poliklinik.nm_poli from jns_perawatan inner join penjab on penjab.kd_pj=jns_perawatan.kd_pj inner join poliklinik on poliklinik.kd_poli=jns_perawatan.kd_poli inner join set_tarif_online on set_tarif_online.kd_jenis_prw=jns_perawatan.kd_jenis_prw where jns_perawatan.status='1' and penjab.png_jawab like '%umum%' {$filter} order by jns_perawatan.nm_perawatan");
visitor_page_intro('Konsultasi Online', 'Tarif Layanan Konsultasi Online', 'Daftar tarif konsultasi online untuk membantu pasien memperkirakan layanan yang tersedia sebelum melakukan pendaftaran.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariOnline" name="frmCariOnline" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input name="online" type="text" id="online" pattern="[a-zA-Z0-9, ./@_]{1,20}" title="Maksimal 20 karakter" class="field-input" maxlength="20" autocomplete="off" value="<?= e((string)($online ?? '')); ?>" autofocus placeholder="Cari nama tarif atau poliklinik">
            <button class="btn-primary sm:min-w-32" name="BtnOnline" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Nama Tarif</th>
                    <th>Unit / Poliklinik</th>
                    <th>Tarif</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false; while ($row = mysqli_fetch_array($queryonline)) : $hasRows = true; ?>
                    <tr>
                        <td><?= e($row["nm_perawatan"]); ?></td>
                        <td><?= e($row["nm_poli"]); ?></td>
                        <td><?= e(formatDuit($row["total_byrdr"])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows) : ?>
        <div class="mt-6"><?php visitor_empty_state('Data konsultasi online tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>
