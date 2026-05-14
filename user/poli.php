<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

$querypoli = bukaquery("select nm_poli from poliklinik where status='1' AND nm_poli LIKE '%spesialis%' order by nm_poli");
dashboard_page_header('Poliklinik', 'Poli dan Unit Tersedia', 'Daftar poli tersedia untuk membantu Anda memilih tujuan pelayanan.');
?>
<div class="dashboard-card">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <?php while ($row = mysqli_fetch_array($querypoli)) : ?>
            <div class="rounded-[24px] border border-slate-200 bg-slate-50/80 p-5 text-sm font-semibold text-slate-800">
                <?= e($row["nm_poli"]); ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
