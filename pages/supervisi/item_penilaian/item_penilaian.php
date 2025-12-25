<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

// jumlah data per halaman
$limit = 10;

// halaman saat ini dari URL, default 1
$page_no = isset($_GET['page_no']) ? (int) $_GET['page_no'] : 1;
if ($page_no < 1)
    $page_no = 1;

// hitung offset
$offset = ($page_no - 1) * $limit;


// Query to get the latest version of each kode_item_penilaian
$queryItemPenilaian = "SELECT item_penilaian.*, k_penilaian.nama_kategori_penilaian
FROM item_penilaian 
JOIN (
    SELECT kode_item_penilaian, MAX(versi) AS versi_terakhir
    FROM item_penilaian
    GROUP BY kode_item_penilaian
) v ON item_penilaian.kode_item_penilaian = v.kode_item_penilaian
   AND item_penilaian.versi = v.versi_terakhir
JOIN k_penilaian
  ON item_penilaian.kode_kategori_penilaian = k_penilaian.kode_kategori_penilaian
ORDER BY item_penilaian.kode_item_penilaian ASC LIMIT $offset, $limit
";

$getItemPenilaian = mysqli_query($koneksi, $queryItemPenilaian); // mengambil data item penilaian dari tabel item_penilaian


$queryTotal = "
SELECT COUNT(*) AS total FROM (
    SELECT item_penilaian.kode_item_penilaian
    FROM item_penilaian 
    JOIN (
        SELECT kode_item_penilaian, MAX(versi) AS versi_terakhir
        FROM item_penilaian
        GROUP BY kode_item_penilaian
    ) v ON item_penilaian.kode_item_penilaian = v.kode_item_penilaian
       AND item_penilaian.versi = v.versi_terakhir
    JOIN k_penilaian
      ON item_penilaian.kode_kategori_penilaian = k_penilaian.kode_kategori_penilaian
) total_data
";

$resultTotal = mysqli_query($koneksi, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);

$totalData = $rowTotal['total'];
$totalPages = ceil($totalData / $limit);
$no = $offset + 1;

?>



<!-- Content -->
<section>
    <div class="content">
        <h2 class="content-title text-center">Item Penilaian</h2>
        <div class="container" style="margin-bottom: -20px;">
            <a href="index.php?page=tambah_item_penilaian" class="btn btn-primary">+ Tambah Item Penilaian</a>
        </div>
        <div class="container mt-4 table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table class="table table-striped table-hover" style="max-height:300px;">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Item Penilaian</th>
                        <th scope="col">Versi</th>
                        <th scope="col">Status</th>
                        <!-- <th scope="col">Validitas</th> -->
                        <th scope="col">Aksi</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getItemPenilaian as $row): ?>
                        <tr>
                            <th scope="row"><?= $no++ ?></th>
                            <td><?= $row['kode_item_penilaian'] ?></td>
                            <td><?= $row['nama_kategori_penilaian'] ?></td>
                            <td><?= $row['pernyataan'] ?></td>
                            <td><?= $row['versi'] ?></td>
                            <?php
                            if ($row['status_item'] == 'aktif') {
                                $row['status_item'] = '<span class="badge bg-white">✅</span>';
                            } else {
                                $row['status_item'] = '<span class="badge bg-white">❌</span>';
                            }
                            ?>
                            <td><?= $row['status_item'] ?></td>
                            <td>
                                <a
                                    href="index.php?page=edit_item_penilaian&id_item_penilaian=<?= $row['id_item_penilaian'] ?>"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <a href="javascript:void(0)"
                                    onclick="konfirmasiDelete('index.php?page=hapus_item_penilaian&id_item_penilaian=<?= $row['id_item_penilaian'] ?>')"><i
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
                        <a class="page-link" href="?page=item_penilaian&page_no=<?= $page_no - 1 ?>">Previous</a>
                    </li>

                    <!-- Nomor halaman -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page_no) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=item_penilaian&page_no=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?= ($page_no >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=item_penilaian&page_no=<?= $page_no + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
</section>

<!-- CONFIRM DELETE ALERT -->
<script>
    function konfirmasiDelete(deleteUrl) {
        Swal.fire({
            title: 'Hapus Data Item Penilaian',
            html: `Anda yakin ingin menghapus data item penilaian?`,
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
?>