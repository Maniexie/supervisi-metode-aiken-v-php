<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];
$id_guru = $_GET['id_guru'];



$getDaftarJawabanSupervisi = mysqli_query(
    $koneksi,
    "SELECT *, 
    jadwal_supervisi.id_jadwal_supervisi, 
    jadwal_supervisi.nama_periode,
    users.nama AS nama_guru,
    SUM(jawaban_supervisi.jawaban) AS total_nilai,
    COUNT(item_penilaian.id_item_penilaian) AS total_item
    FROM jawaban_supervisi 
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    JOIN users ON jawaban_supervisi.id_guru = users.id_user
    JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian    
    WHERE jawaban_supervisi.id_jadwal_supervisi = '$id_jadwal_supervisi'
    AND jawaban_supervisi.id_guru = '$id_guru'
"
);

$jawabanSupervisi = mysqli_fetch_assoc($getDaftarJawabanSupervisi);

if (!$jawabanSupervisi) {
    echo "<div class='alert alert-danger text-center'>Data supervisi tidak ditemukan</div>";
    exit;
}


$getJawabanSupervisi111 = mysqli_query(
    $koneksi,
    "SELECT * FROM jawaban_supervisi WHERE id_jadwal_supervisi = '$id_jadwal_supervisi' AND id_guru = '$id_guru'"
);

$jawabanSupervisi111 = mysqli_fetch_assoc($getJawabanSupervisi111);

$getNamaKepsek = mysqli_query(
    $koneksi,
    "SELECT users.nama AS nama_kepala_sekolah, 
    jadwal_supervisi.id_jadwal_supervisi 
    FROM jadwal_supervisi JOIN users ON users.id_user = jadwal_supervisi.id_kepala_sekolah WHERE id_jadwal_supervisi = '$id_jadwal_supervisi'"
);

$namaKepsek = mysqli_fetch_assoc($getNamaKepsek);


$getDataHasilSupervisiTable = mysqli_query(
    $koneksi,
    "SELECT *,nama_tindak_lanjut 
    FROM hasil_supervisi 
    JOIN k_tindak_lanjut_hasil_supervisi ON k_tindak_lanjut_hasil_supervisi.kode_tindak_lanjut = hasil_supervisi.kode_tindak_lanjut 
    WHERE id_jadwal_supervisi = '$id_jadwal_supervisi' AND id_guru = '$id_guru' ORDER BY id_hasil_supervisi "
);

$dataHasilSupervisi = mysqli_fetch_assoc($getDataHasilSupervisiTable);
$id_hasil_supervisi = $dataHasilSupervisi['id_hasil_supervisi'] ?? '';




?>


<section>
    <div class="container">
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h4 class="text-center mb-3">Informasi Supervisi</h4>
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Supervisor</th>
                        <td class="text-capitalize"><?= $namaKepsek['nama_kepala_sekolah'] ?></td>
                    </tr>
                    <tr>
                        <th>Periode</th>
                        <td><?= $jawabanSupervisi['nama_periode'] ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?= $jawabanSupervisi['tanggal_mulai'] ?> s/d <?= $jawabanSupervisi['tanggal_selesai'] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center mb-3">Hasil Supervisi Guru</h4>

                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Guru</th>
                        <td class="text-capitalize"><?= $jawabanSupervisi['nama_guru'] ?></td>
                    </tr>

                    <tr>
                        <th>Nilai Total</th>
                        <td>
                            <?php
                            $nilaiAsli = $jawabanSupervisi['total_nilai'] * 20 / $jawabanSupervisi['total_item'];
                            if ($nilaiAsli >= 85) {
                                $predikat = "Sangat Baik";
                            } elseif ($nilaiAsli >= 70) {
                                $predikat = "Baik";
                            } elseif ($nilaiAsli >= 55) {
                                $predikat = "Cukup";
                            } elseif ($nilaiAsli >= 40) {
                                $predikat = "Kurang";
                            } else {
                                $predikat = "Sangat Kurang";
                            }
                            // Memeriksa jika total_item bernilai 0
                            if ($jawabanSupervisi['total_item'] > 0) {
                                echo number_format($nilaiAsli, 1) . ' (' . $predikat . ')';
                            } else {
                                // Jika total_item 0, set nilaiAsli ke 0 atau nilai default lain yang sesuai
                                echo $nilaiAsli = 0;
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Tindak Lanjut</th>
                        <td><?= empty($dataHasilSupervisi['nama_tindak_lanjut']) ? '-' : $dataHasilSupervisi['nama_tindak_lanjut'] . ' (' . $dataHasilSupervisi['kode_tindak_lanjut'] . ')' ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Umpan Balik</th>
                        <td><?= empty($dataHasilSupervisi['umpan_balik']) ? '-' : $dataHasilSupervisi['umpan_balik'] ?>
                        </td>
                    </tr>
                </table>
                <?php if ($_SESSION['role'] == 'kepala_sekolah'): ?>
                    <?php if (!$getDataHasilSupervisiTable->num_rows > 0) { ?>
                        <a
                            href="index.php?page=kirim_umpan_balik_hasil_supervisi&id_jadwal_supervisi=<?= $id_jadwal_supervisi ?>&id_guru=<?= $id_guru ?>">
                            <button class="btn btn-primary">Berikan Umpan Balik</button>
                        </a>
                    <?php } else { ?>
                        <a
                            href="index.php?page=edit_umpan_balik_hasil_supervisi&id_jadwal_supervisi=<?= $id_jadwal_supervisi ?>&id_guru=<?= $id_guru ?>&id_hasil_supervisi=<?= $id_hasil_supervisi ?>">
                            <button class="btn btn-warning">Edit Umpan Balik</button>
                        </a>
                    <?php } ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>