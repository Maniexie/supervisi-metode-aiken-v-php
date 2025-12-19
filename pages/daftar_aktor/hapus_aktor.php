<?php
require_once __DIR__ . '/../../koneksi.php';

$id_user = $_GET['id_user'];

// 1️⃣ Ambil data aktor SEBELUM dihapus
$getQuery = "SELECT nama FROM users WHERE id_user = ?";
$getStmt = $koneksi->prepare($getQuery);
$getStmt->bind_param("i", $id_user);
$getStmt->execute();
$result = $getStmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data aktor tidak ditemukan.";
    header("Location: index.php?page=daftar_aktor");
    exit;
}

$data = $result->fetch_assoc();
$namaAktor = $data['nama']; // 👈 SIMPAN NAMA
$getStmt->close();

// 2️⃣ Hapus data aktor
$deleteQuery = "DELETE FROM users WHERE id_user = ?";
$deleteStmt = $koneksi->prepare($deleteQuery);
$deleteStmt->bind_param("i", $id_user);

if ($deleteStmt->execute()) {
    $_SESSION['success'] = "ID aktor <b>$id_user</b> dengan nama <b>$namaAktor</b> berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus aktor <b>$namaAktor</b>.";
}

$deleteStmt->close();

// 3️⃣ Redirect
header("Location: index.php?page=daftar_aktor");
exit;
