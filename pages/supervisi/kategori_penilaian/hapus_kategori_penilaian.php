<?php
require_once __DIR__ . '/../../../koneksi.php';
$getKodeKategoriPenilaian = $_GET['kode_kategori_penilaian'] ?? null;

if ($getKodeKategoriPenilaian) {
    // 1️⃣ Ambil data kategori penilaian SEBELUM dihapus
    $getQuery = "SELECT nama_kategori_penilaian FROM k_penilaian WHERE kode_kategori_penilaian = ?";
    $getStmt = $koneksi->prepare($getQuery);
    $getStmt->bind_param("i", $getKodeKategoriPenilaian);
    $getStmt->execute();
    $result = $getStmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Kode Kategori Penilaian " . $getKodeKategoriPenilaian . " tidak ditemukan.";
        header("Location: index.php?page=kategori_penilaian");
        exit;
    } else {
        $data = $result->fetch_assoc();
        $namaKategoriPenilaian = $data['nama_kategori_penilaian']; // 👈 SIMPAN NAMA
        $getStmt->close();


        // 2️⃣ Hapus data kategori penilaian
        $deleteQuery = "DELETE FROM k_penilaian WHERE kode_kategori_penilaian = ?";
        $deleteStmt = $koneksi->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $getKodeKategoriPenilaian);


        if ($deleteStmt->execute()) {
            $_SESSION['success'] = "ID kategori penilaian <b>$getKodeKategoriPenilaian</b> dengan nama <b>$namaKategoriPenilaian</b> berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menghapus data kategori penilaian.";
        }
    }
}

header("Location: index.php?page=kategori_penilaian");
exit;
?>