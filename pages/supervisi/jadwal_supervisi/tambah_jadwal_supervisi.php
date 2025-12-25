<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kepala_sekolah = $_SESSION['id_user'];
    $nama_periode = $_POST['nama_periode'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $deskripsi = $_POST['deskripsi'];

    $insertJadwalSupervisi = mysqli_query($koneksi, "INSERT INTO 
    jadwal_supervisi (id_kepala_sekolah, nama_periode, tanggal_mulai, tanggal_selesai, deskripsi) 
    VALUES ('$id_kepala_sekolah', '$nama_periode', '$tanggal_mulai', '$tanggal_selesai', '$deskripsi')");

    if ($insertJadwalSupervisi) {
        echo "<script>alert('Jadwal Supervisi berhasil ditambahkan'); window.location.href='index.php?page=jadwal_supervisi';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan Jadwal Supervisi'); window.location.href='index.php?page=tambah_jadwal_supervisi';</script>";
    }
}


?>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">

        <h2>Tambah Jadwal Supervisi</h2>
        <form action="" method="post">

            <input type="hidden" name="id_kepala_sekolah">

            <div class="form-group">
                <label for="nama_periode">Nama Periode</label>
                <input type="text" class="form-control" id="nama_periode" name="nama_periode"
                    placeholder="Masukkan tanggal supervisi" value="" required>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                    value="<?= date('d-m-y') ?>" placeholder="Masukkan Tanggal mulai supervisi" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                    value="<?= date('d-m-y') ?>" placeholder="Masukkan Tanggal selesai supervisi" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                    placeholder="Masukkan deskripsi jadwal supervisi">Supervisi akan dilaksanakan pada tanggal tersebut. Harap persiapkan segala sesuatunya dengan baik.</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="submit">Simpan</button>
        </form>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
