<?php
require_once __DIR__ . '/../../pages/layouts/header.php';
require_once __DIR__ . '/../../koneksi.php';


// jumlah data per halaman
$limit = 10;

// halaman saat ini dari URL, default 1
$page_no = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
if ($page_no < 1)
    $page_no = 1;

// hitung offset
$offset = ($page_no - 1) * $limit;

$getDataAktor = mysqli_query(
    $koneksi,
    'SELECT *, k_jabatan.nama_jabatan FROM users 
JOIN k_jabatan ON users.kode_jabatan = k_jabatan.kode_jabatan
where role ="guru" or role="kepala_sekolah" or role="operator"
LIMIT ' . $limit . ' OFFSET ' . $offset . ''
);
// hitung total data untuk pagination
$totalDataResult = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) as total FROM users 
     WHERE role IN ('guru','kepala_sekolah','operator')
    "
);
$totalData = mysqli_fetch_assoc($totalDataResult)['total'];
$totalPages = ceil($totalData / $limit);

$no = $offset + 1; // nomor urut
?>

<!-- Content -->
<section>
    <div class="content">
        <h2 class="content-title text-center">Daftar Aktor Supervisi</h2>
        <div class="container mt-4 table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table class="table table-striped table-hover" style="max-height:300px;">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Validator</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getDataAktor as $row): ?>
                        <tr>
                            <th scope="row"><?= $no++ ?></th>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['nama_jabatan'] ?></td>
                            <td><?= $row['is_validator'] == "Ya" ? "✅" : "❌" ?>
                            </td>
                            <td>
                                <a href="index.php?page=edit_aktor&id_user=<?= $row['id_user'] ?>"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <a href="javascript:void(0)"
                                    onclick="confirmDelete('index.php?page=hapus_aktor&id_user=<?= $row['id_user'] ?>')"><i
                                        class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <!-- Previous -->
                    <li class="page-item <?= ($page_no <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=daftar_aktor&page_no=<?= $page_no - 1 ?>">Previous</a>
                    </li>

                    <!-- Nomor halaman -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=daftar_aktor&page_no=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?= ($page_no >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=daftar_aktor&page_no=<?= $page_no + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>

        </div>
</section>
</div>
</div>


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
require_once __DIR__ . '/../../pages/layouts/footer.php';
?>