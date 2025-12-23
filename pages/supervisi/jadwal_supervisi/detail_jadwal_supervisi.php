<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];

$getJadwalSupervisi = mysqli_query($koneksi, "SELECT * , users.nama AS nama_kepala_sekolah FROM jadwal_supervisi JOIN users ON jadwal_supervisi.id_kepala_sekolah = users.id_user WHERE id_jadwal_supervisi = '$id_jadwal_supervisi'");

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
?>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">
        <div class="row">
            <div class="cards">
                <div class="card">
                    <h1>Detail Jadwal Supervisi </h1>
                    <div class="card-body">
                        <?php
                        $jadwalSupervisi = mysqli_fetch_array($getJadwalSupervisi);
                        ?>
                        <div class="col">
                            <p>Supervisor: <?= $jadwalSupervisi['nama_kepala_sekolah'] ?></p>
                        </div>
                        <div class="col">
                            <p>Nama Periode : <?= $jadwalSupervisi['nama_periode'] ?></p>
                        </div>
                        <div class="col">
                            <p>Tanggal Berlangsung: <?= $jadwalSupervisi['tanggal_mulai'] ?> s/d
                                <?= $jadwalSupervisi['tanggal_selesai'] ?>
                            </p>
                        </div>
                        <div class="col">
                            <p>Keterangan: <?= $jadwalSupervisi['deskripsi'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
