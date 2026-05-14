<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}

dashboard_page_header('Pengaduan', 'Komunikasi Pengaduan Pasien', 'Sampaikan pesan dengan lebih nyaman melalui tampilan percakapan yang lebih bersih dan fokus.');
?>
<div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="dashboard-card">
        <h2 class="text-2xl font-bold text-slate-900">Percakapan</h2>
        <div id="screen" class="mt-6 h-[28rem] overflow-y-auto rounded-[24px] border border-slate-200 bg-slate-50/70 p-4"></div>
    </div>

    <div class="dashboard-card">
        <h2 class="text-2xl font-bold text-slate-900">Kirim Pesan</h2>
        <p class="mt-3 text-sm leading-7 text-slate-600">Masukkan pertanyaan, saran, atau pengaduan Anda. Tim kami akan merespons secepat mungkin.</p>
        <form id="frmPesan" name="frmPesan" method="post" action="" class="mt-6 space-y-4">
            <div>
                <label for="pesan" class="dashboard-label">Pesan</label>
                <textarea name="pesan" id="pesan" rows="6" class="dashboard-input" autocomplete="off" autofocus required placeholder="Ketik pengaduan atau masukan Anda di sini..."></textarea>
            </div>
            <button type="submit" name="BtnPesan" class="dashboard-btn-primary w-full">Kirim Pesan</button>
        </form>
    </div>
</div>
<script src="assets/plugin/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        function loadMessages() {
            $('#screen').load('user/list/daftar-pengaduan.php');
        }

        loadMessages();
        setInterval(loadMessages, 60000);

        $('#frmPesan').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Kirim pesan?',
                text: 'Kami akan merespons secepat mungkin.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('user/form/pengaduan.php', { pesan: $('#pesan').val() }, function () {
                        $('#pesan').val('');
                        loadMessages();
                    });
                }
            });
        });
    });
</script>
