<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];

$hapusJadwalSupervisi = mysqli_query($koneksi, "DELETE FROM jadwal_supervisi WHERE id_jadwal_supervisi = '$id_jadwal_supervisi'");

if ($hapusJadwalSupervisi) {
    echo "<script>alert('Jadwal Supervisi berhasil dihapus'); window.location.href='index.php?page=jadwal_supervisi';</script>";
} else {
    echo "<script>alert('Gagal menghapus Jadwal Supervisi'); window.location.href='index.php?page=edit_jadwal_supervisi&id_jadwal_supervisi=$id_jadwal_supervisi';</script>";
}

?>