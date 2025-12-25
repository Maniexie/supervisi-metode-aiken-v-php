<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$getKategoriTindakLanjut = mysqli_query(
    $koneksi,
    "SELECT * FROM k_tindak_lanjut_hasil_supervisi"
);

?>

<!-- Content -->
<section>
    <div class="content">
        <h2 class="content-title text-center">Kategori Tindak Lanjut Hasil Supervisi</h2>
        <div class="container" style="margin-bottom: -20px;">
            <a href="index.php?page=tambah_kategori_tindak_lanjut_hasil_supervisi" class="btn btn-primary">+ Tambah
                Kategori</a>
        </div>
        <div class="container mt-4 table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table class="table table-striped table-hover" style="max-height:300px;">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Jenis RTL</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($getKategoriTindakLanjut as $row): ?>
                        <tr>
                            <th scope="row"><?= $no++ ?></th>
                            <td><?= $row['kode_tindak_lanjut'] ?></td>
                            <td><?= $row['nama_tindak_lanjut'] ?></td>
                            <td>
                                <a
                                    href="index.php?page=edit_kategori_tindak_lanjut_hasil_supervisi&kode_tindak_lanjut=<?= $row['kode_tindak_lanjut'] ?>"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <a href="javascript:void(0)"
                                    onclick="konfirmasiDelete('index.php?page=hapus_kategori_tindak_lanjut_hasil_supervisi&kode_tindak_lanjut=<?= $row['kode_tindak_lanjut'] ?>')"><i
                                        class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
</section>


<!-- CONFIRM DELETE ALERT -->
<script>
    function konfirmasiDelete(deleteUrl) {
        Swal.fire({
            title: 'Hapus Data Kategori Tindak Lanjut Hasil Supervisi',
            html: `Anda yakin ingin menghapus data Kategori Tindak Lanjut Hasil Supervisi?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        }
        );
    }
</script>
<?php if (isset($_SESSION['success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            html: '<?= $_SESSION['success'] ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= $_SESSION['error'] ?>',
            showConfirmButton: true
        });
    </script>
    <?php unset($_SESSION['error']); endif; ?>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
