<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$hariini = date('Y-m-d');

// Ambil data jadwal supervisi yang berlaku
$getJadwalSupervisi = mysqli_query($koneksi, "SELECT * FROM jadwal_supervisi WHERE '$hariini' BETWEEN tanggal_mulai AND tanggal_selesai ORDER BY tanggal_mulai DESC LIMIT 1");
$jadwalSupervisi = mysqli_fetch_array($getJadwalSupervisi);

//error atau data tidak ditemukan
// bisa menggunakan text halaman atau alert
if (!$jadwalSupervisi || mysqli_num_rows($getJadwalSupervisi) == 0) {
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

// Ambil data pengguna (guru atau operator)
$getDataUsers = mysqli_query($koneksi, "
    SELECT * , nama_jabatan 
    FROM users 
    JOIN k_jabatan ON users.kode_jabatan = k_jabatan.kode_jabatan 
    WHERE role IN ('guru', 'operator')
    ORDER BY users.id_user DESC
");

// Ambil data jawaban supervisi berdasarkan jadwal yang sedang berlangsung
$getJawabanSupervisi = mysqli_query($koneksi, "SELECT id_guru FROM jawaban_supervisi WHERE id_jadwal_supervisi = '$jadwalSupervisi[id_jadwal_supervisi]'");

// Simpan ID guru yang sudah disupervisi dalam array
$guruSudahSupervisi = [];
while ($jawaban = mysqli_fetch_assoc($getJawabanSupervisi)) {
    $guruSudahSupervisi[] = $jawaban['id_guru'];
}

?>

<!-- Content -->
<section>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h2 class="text-center">Daftar Mulai Supervisi</h2>
                    <h5 class="card-header">Periode : <?= $jadwalSupervisi['nama_periode'] ?></h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <?php foreach ($getDataUsers as $data): ?>
                                <tbody>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['nama'] ?></td>
                                        <td><?= $data['nama_jabatan'] ?></td>
                                        <?php
                                        // Cek apakah guru ini sudah disupervisi
                                        if (in_array($data['id_user'], $guruSudahSupervisi)) {
                                            echo '<td><span class="badge bg-success">Sudah Supervisi</span></td>';
                                        } else {
                                            echo '<td><span class="badge bg-warning">Belum Supervisi</span></td>';
                                        }
                                        ?>
                                        <?php if (in_array($data['id_user'], $guruSudahSupervisi)): ?>
                                            <td>
                                                <a>✅</a>
                                            </td>
                                        <?php else: ?>
                                            <td>
                                                <a href="index.php?page=mulai_supervisi&id_jadwal_supervisi=<?= $jadwalSupervisi['id_jadwal_supervisi'] ?>&id_guru=<?= $data['id_user'] ?>"
                                                    class="btn btn-primary">Mulai</a>
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                </tbody>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>