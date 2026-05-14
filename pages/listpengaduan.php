<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

visitor_page_intro('Pengaduan', 'Layanan Pengaduan Pasien', 'Saluran pengaduan pasien disiapkan untuk membantu penyampaian masukan dengan tetap menjaga komunikasi yang tertib dan profesional.');
visitor_section_open();
?>
<div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
    <div class="surface-card p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Informasi Layanan</h2>
        <p class="mt-4 text-sm leading-7 text-slate-600">
            Modul percakapan pengaduan real-time pada project ini belum memiliki endpoint backend aktif di folder `pages`, sehingga halaman lama berpotensi menimbulkan error saat dibuka langsung.
        </p>
        <p class="mt-4 text-sm leading-7 text-slate-600">
            Untuk menjaga stabilitas aplikasi, halaman ini sementara diarahkan menjadi pusat informasi kanal pengaduan resmi sampai backend pengaduan selesai disiapkan kembali.
        </p>
        <div class="mt-6 rounded-3xl border border-dashed border-nu-200 bg-nu-50/70 p-5 text-sm leading-7 text-slate-700">
            Anda dapat menghubungi petugas melalui kontak resmi rumah sakit untuk menyampaikan pertanyaan, keluhan, atau saran layanan.
        </div>
    </div>

    <div class="surface-card p-6 lg:p-8">
        <h2 class="text-2xl font-bold text-slate-900">Kanal Resmi</h2>
        <div class="mt-6 space-y-4">
            <div class="rounded-3xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Telepon</p>
                <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($_SESSION["kontak"] ?? '-'); ?></p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email</p>
                <p class="mt-2 text-sm font-semibold text-slate-800"><?= e($_SESSION["email"] ?? '-'); ?></p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">WhatsApp</p>
                <a href="https://wa.me/message/TA3D4D6J52LJM1" target="_blank" rel="noreferrer" class="mt-2 inline-flex text-sm font-semibold text-nu-700 hover:text-nu-800">Hubungi Kanal WhatsApp Resmi</a>
            </div>
        </div>
    </div>
</div>
<?php visitor_section_close(); ?>
