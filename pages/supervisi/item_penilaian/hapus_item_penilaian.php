<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_item_penilaian = $_GET['id_item_penilaian'];
// Hapus data item penilaian berdasarkan id_item_penilaian

$hapusItemPenilaian = mysqli_query($koneksi, "DELETE FROM item_penilaian WHERE id_item_penilaian = '$id_item_penilaian'");
if ($hapusItemPenilaian) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Hapus Item Penilaian",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "success",
            title: "Item Penilaian Berhasil di Hapus",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = `index.php?page=item_penilaian`;
        });
    });
    </script>';
} else {
    echo "Error deleting record: " . mysqli_error($koneksi);
}
?>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>