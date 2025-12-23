<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';
require_once __DIR__ . '/../../../assets/php/tanggal_indonesia.php';

$getJadwalSupervisi = mysqli_query($koneksi, "SELECT * FROM jadwal_supervisi ORDER BY id_jadwal_supervisi DESC");

?>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">
        <h2 class="text-center">Jadwal Supervisi</h2>
        <div class="container" style="margin-bottom: -15px;">
            <a href="index.php?page=tambah_jadwal_supervisi" class="btn btn-primary">+ Tambah Jadwal Supervisi</a>
        </div>
        <div class="container mt-4 table-responsive" style="max-height: 700px; overflow-y: auto;">
            <table class="table table-striped table-hover text-center" style="max-height:300px;">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Periode</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <?php $no = 1; ?>
                <?php foreach ($getJadwalSupervisi as $data): ?>
                    <tbody>
                        <td><?= $no++; ?></td>
                        <td><?= $data['nama_periode']; ?></td>
                        <td>(<b><?= tanggal_indonesia($data['tanggal_mulai']) ?></b> s/d
                            <b><?= tanggal_indonesia($data['tanggal_selesai']); ?></b>)
                        <td>
                            <a
                                href="index.php?page=detail_jadwal_supervisi&id_jadwal_supervisi=<?= $data['id_jadwal_supervisi'] ?>">Detail</a>
                            |
                            <a
                                href="index.php?page=edit_jadwal_supervisi&id_jadwal_supervisi=<?= $data['id_jadwal_supervisi'] ?>"><i
                                    class="fa-solid fa-pen-to-square"></i></a>
                            |
                            <a href="index.php?page=hapus_jadwal_supervisi&id_jadwal_supervisi=<?= $data['id_jadwal_supervisi'] ?>"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal supervisi ini?')"><i
                                    class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
