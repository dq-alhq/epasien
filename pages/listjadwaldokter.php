<?php
require_once('../conf/conf.php');

$keyword = isset($_POST['value']) ? trim((string) $_POST['value']) : '';
$keyword = cleankar(substr($keyword, 0, 20));
$filter = $keyword !== ''
    ? " and (dokter.nm_dokter like '%{$keyword}%' or jadwal.hari_kerja like '%{$keyword}%' or poliklinik.nm_poli like '%{$keyword}%')"
    : '';

$queryjadwal = bukaquery("
    SELECT 
        dokter.nm_dokter,
        jadwal.hari_kerja,
        jadwal.jam_mulai,
        jadwal.jam_selesai,
        poliklinik.nm_poli
    FROM jadwal
    INNER JOIN dokter 
        ON jadwal.kd_dokter = dokter.kd_dokter
    INNER JOIN poliklinik 
        ON jadwal.kd_poli = poliklinik.kd_poli
    WHERE dokter.status = '1' {$filter}
    ORDER BY 
        poliklinik.nm_poli,
        dokter.nm_dokter,
        FIELD(
            jadwal.hari_kerja,
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'
        ),
        jadwal.jam_mulai
");
$hasRows = false;
?>

<div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white">
    <div class="overflow-x-auto">
        <?php
        $hasRows = false;

        $currentPoli = '';
        $currentDokter = '';
        ?>

        <table class="section-table">
            <thead>
                <tr>
                    <th>Poliklinik</th>
                    <th>Nama Dokter</th>
                    <th>Hari Kerja</th>
                    <th>Jam Praktik</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = mysqli_fetch_array($queryjadwal)): ?>
                    <?php $hasRows = true; ?>

                    <?php
                    $poliBaru = $currentPoli !== $row['nm_poli'];
                    $dokterBaru = $currentDokter !== $row['nm_dokter'];

                    if ($poliBaru) {
                        $currentPoli = $row['nm_poli'];
                        $currentDokter = '';
                    }

                    if ($dokterBaru) {
                        $currentDokter = $row['nm_dokter'];
                    }
                    ?>

                    <tr>
                        <td>
                            <?= $poliBaru
                                ? htmlspecialchars($row['nm_poli'], ENT_QUOTES, 'UTF-8')
                                : '' ?>
                        </td>

                        <td>
                            <?= $dokterBaru
                                ? htmlspecialchars($row['nm_dokter'], ENT_QUOTES, 'UTF-8')
                                : '' ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['hari_kerja'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(date("H:i", strtotime($row['jam_mulai'])), ENT_QUOTES, 'UTF-8'); ?>
                            -
                            <?= htmlspecialchars(date("H:i", strtotime($row['jam_selesai'])), ENT_QUOTES, 'UTF-8'); ?>
                            WIB
                        </td>
                    </tr>

                <?php endwhile; ?>

                <?php if (!$hasRows): ?>
                    <tr>
                        <td colspan="4">
                            <div class="py-8 text-center text-sm text-slate-500">
                                Jadwal dokter tidak ditemukan untuk kata kunci tersebut.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>