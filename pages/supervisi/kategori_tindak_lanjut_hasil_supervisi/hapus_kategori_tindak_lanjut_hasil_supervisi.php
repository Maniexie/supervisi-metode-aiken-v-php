<?php
require_once __DIR__ . '/../../../koneksi.php';

$kode_tindak_lanjut = $_GET['kode_tindak_lanjut'];


$query = "SELECT * FROM k_tindak_lanjut_hasil_supervisi WHERE kode_tindak_lanjut = '$kode_tindak_lanjut'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);
$kode_tindak_lanjut = $row['kode_tindak_lanjut'];



if ($kode_tindak_lanjut) {
    $query = "DELETE FROM k_tindak_lanjut_hasil_supervisi WHERE kode_tindak_lanjut = '$kode_tindak_lanjut'";
    $dataStmt = mysqli_query($koneksi, $query);

    if ($dataStmt) {
        $_SESSION['success'] = "ID kategori penilaian <b>$getKodeKategoriPenilaian</b> dengan nama <b>$namaKategoriPenilaian</b> berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus data kategori penilaian.";
    }
}


header("Location: index.php?page=kategori_tindak_lanjut_hasil_supervisi");
exit;
?>