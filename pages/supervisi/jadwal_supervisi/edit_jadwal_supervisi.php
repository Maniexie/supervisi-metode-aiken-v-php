<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];

$getJadwalSupervisi = mysqli_query($koneksi, "SELECT * FROM jadwal_supervisi WHERE id_jadwal_supervisi = '$id_jadwal_supervisi'");

if (mysqli_num_rows($getJadwalSupervisi) == 0) {
    echo "
    <div class='container mt-5'>
        <div class='alert alert-danger text-center'>
            <h4>Akses Ditolak</h4>
            <p>Tidak ada jadwal supervisi.</p>
        </div>
    </div>
    ";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kepala_sekolah = $_POST['id_kepala_sekolah'];
    $nama_periode = $_POST['nama_periode'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $deskripsi = $_POST['deskripsi'];

    $updateJadwalSupervisi = mysqli_query($koneksi, "UPDATE jadwal_supervisi SET 
    id_kepala_sekolah = '$id_kepala_sekolah',
    nama_periode = '$nama_periode',
    tanggal_mulai = '$tanggal_mulai',
    tanggal_selesai = '$tanggal_selesai',
    deskripsi = '$deskripsi'
    WHERE id_jadwal_supervisi = '$id_jadwal_supervisi'");

    if ($updateJadwalSupervisi) {
        echo "<script>alert('Jadwal Supervisi berhasil diubah'); window.location.href='index.php?page=jadwal_supervisi';</script>";
    } else {
        echo "<script>alert('Gagal mengubah Jadwal Supervisi'); window.location.href='index.php?page=edit_jadwal_supervisi&id_jadwal_supervisi=$id_jadwal_supervisi';</script>";
    }
}

?>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">
        <h2 class='text-center'>Edit Jadwal Supervisi</h2>
        <?php foreach ($getJadwalSupervisi as $jadwal): ?>
            <form action="" method="post">

                <input type="hidden" name="id_kepala_sekolah" value="<?= $jadwal['id_kepala_sekolah'] ?>">

                <div class="form-group">
                    <label for="nama_periode">Nama Periode</label>
                    <input type="text" class="form-control" id="nama_periode" name="nama_periode"
                        placeholder="Masukkan tanggal supervisi" value="<?= $jadwal['nama_periode'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                        value="<?= $jadwal['tanggal_mulai'] ?>" placeholder="Masukkan Tanggal mulai supervisi" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                        value="<?= $jadwal['tanggal_selesai'] ?>" placeholder="Masukkan Tanggal selesai supervisi" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                        placeholder="Masukkan deskripsi jadwal supervisi"><?= $jadwal['deskripsi'] ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3" name="submit">Simpan</button>
            </form>
        <?php endforeach; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
