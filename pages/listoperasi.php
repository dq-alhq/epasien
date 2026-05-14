<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$operasi = clean_post('operasi', 20);
$filter = $operasi ? " and (paket_operasi.nm_perawatan like '%{$operasi}%' or paket_operasi.kelas like '%{$operasi}%')" : '';
$queryoperasi = bukaquery("select paket_operasi.nm_perawatan,paket_operasi.kelas from paket_operasi inner join penjab on penjab.kd_pj=paket_operasi.kd_pj where paket_operasi.status='1' and penjab.png_jawab like '%umum%' {$filter} order by paket_operasi.kelas,paket_operasi.nm_perawatan");
visitor_page_intro('Operasi', 'Paket Layanan Operasi', 'Halaman ini merangkum paket tindakan operasi secara lebih bersih dan mudah dicari oleh pasien.');
visitor_section_open();
?>
<div class="surface-card p-6 lg:p-8">
    <form id="cariOperasi" name="frmCariOperasi" method="post" action="" class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="operasi" id="operasi" class="field-input"
                placeholder="Cari paket operasi atau kelas" autocomplete="off"
                value="<?= e((string) ($operasi ?? '')); ?>" autofocus>
            <button class="btn-primary sm:min-w-32" name="BtnOperasi" type="submit">Cari</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="section-table">
            <thead>
                <tr>
                    <th>Paket Operasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $hasRows = false;
                while ($row = mysqli_fetch_array($queryoperasi)):
                    $hasRows = true; ?>
                    <tr>
                        <td><?= e($row["nm_perawatan"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$hasRows): ?>
        <div class="mt-6"><?php visitor_empty_state('Data paket operasi tidak ditemukan.'); ?></div>
    <?php endif; ?>
</div>
<?php visitor_section_close(); ?>