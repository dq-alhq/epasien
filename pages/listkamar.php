<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$kamar = clean_post('kamar', 20);
$filter = $kamar ? " and (kamar.kd_kamar like '%{$kamar}%' or bangsal.nm_bangsal like '%{$kamar}%' or kamar.kelas like '%{$kamar}%' or kamar.status like '%{$kamar}%')" : '';
$querykamar = bukaquery("select kamar.kd_kamar,bangsal.nm_bangsal,kamar.kelas,kamar.status from bangsal inner join kamar on kamar.kd_bangsal=bangsal.kd_bangsal where kamar.statusdata='1' {$filter} order by kamar.kelas");
visitor_page_intro('Fasilitas', 'Informasi Kamar Rawat', 'Lihat ketersediaan kamar dan status bed secara cepat dengan tampilan yang lebih mudah dibaca.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariKamar" name="frmCariKamar" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="kamar" id="kamar" class="field-input"
                placeholder="Cari nomor bed, nama kamar, atau kelas" autocomplete="off"
                value="<?= e((string) ($kamar ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnKamar" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Kamar</th>
                    <th>Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false;
                while ($rsquerykamar = mysqli_fetch_array($querykamar)):
                    $hasRows = true; ?>
                    <tr>
                        <td><?= e($rsquerykamar["nm_bangsal"]); ?></td>
                        <td><?= e($rsquerykamar["kelas"]); ?></td>
                        <td>
                            <span
                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $rsquerykamar["status"] === 'ISI' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'; ?>">
                                <?= e($rsquerykamar["status"]); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if (!$hasRows): ?>
        <div class="mt-6"><?php visitor_empty_state('Data kamar tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>