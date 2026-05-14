<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<div class="text-center mb-3">
    <h5 class="menu-header-title mb-3"><strong>KOLEKSI E-BOOK</strong></h5>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="table-responsive table-bordered">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">Kode Ebook</th>
                        <th class="text-center">Judul Ebook</th>
                        <th class="text-center">Penerbit</th>
                        <th class="text-center">Pengarang</th>
                        <th class="text-center">Terbit</th>
                        <th class="text-center">File</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $queryperpustakaan = bukaquery("select perpustakaan_ebook.kode_ebook, perpustakaan_ebook.judul_ebook, perpustakaan_ebook.jml_halaman, 
                                perpustakaan_penerbit.nama_penerbit, perpustakaan_pengarang.nama_pengarang, perpustakaan_ebook.thn_terbit,perpustakaan_ebook.berkas 
                                from perpustakaan_ebook inner join perpustakaan_penerbit inner join perpustakaan_pengarang on perpustakaan_ebook.kode_penerbit=perpustakaan_penerbit.kode_penerbit 
                                and perpustakaan_ebook.kode_pengarang=perpustakaan_pengarang.kode_pengarang order by perpustakaan_ebook.kode_ebook ");
                    while ($rsqueryperpustakaan = mysqli_fetch_array($queryperpustakaan)) : ?>
                        <tr>
                            <td><?= $rsqueryperpustakaan["kode_ebook"]; ?></td>
                            <td><?= $rsqueryperpustakaan["judul_ebook"]; ?></td>
                            <td><?= $rsqueryperpustakaan["nama_penerbit"]; ?></td>
                            <td><?= $rsqueryperpustakaan["nama_pengarang"]; ?></td>
                            <td><?= $rsqueryperpustakaan["thn_terbit"]; ?></td>
                            <td class="text-center">
                                <a target=_blank
                                   href='/webapps/ebook/<?= $rsqueryperpustakaan["berkas"]; ?>'
                                   class='btn btn-primary btn-transition'>Baca</a>
                            </td>
                        </tr>
                    <?php endwhile;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>