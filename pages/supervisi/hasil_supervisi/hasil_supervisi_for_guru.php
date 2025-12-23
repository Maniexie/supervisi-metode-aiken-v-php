<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

// sepertinya ini mendapatkan data per ID guru~
// $getHasilSupervisi = mysqli_query(
//     $koneksi,
//     "SELECT * FROM jawaban_supervisi  
//     JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
// JOIN kepala_sekolah ON jawaban_supervisi.id_kepala_sekolah = kepala_sekolah.id_kepala_sekolah
// JOIN guru ON jawaban_supervisi.id_guru = guru.id_guru
// WHERE jawaban_supervisi.id_guru = '$id_guru' AND jawaban_supervisi.id_kepala_sekolah = '$id_kepala_sekolah' AND jawaban_supervisi.id_jadwal_supervisi = '$id_jadwal_supervisi'"
// );

// hasil daftar supervisi untuk semua guru
$getDaftarHasilSupervisi = mysqli_query(
    $koneksi,
    "SELECT *, users.nama  AS nama_guru , kepala_sekolah.nama AS nama_kepala_sekolah FROM jawaban_supervisi   
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    JOIN users ON jawaban_supervisi.id_guru = users.id_user 
    JOIN users AS kepala_sekolah ON jawaban_supervisi.id_kepala_sekolah = kepala_sekolah.id_user
    JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian
    GROUP BY jawaban_supervisi.id_jadwal_supervisi, jawaban_supervisi.id_guru
    "
);

// akumulasi nilai per guru per jadwal supervisi
$getAkumulasiNilai = mysqli_query(
    $koneksi,
    "SELECT 
    jawaban_supervisi.id_jadwal_supervisi,
    jawaban_supervisi.id_guru, 
    users.nama AS nama_guru,
    jadwal_supervisi.nama_periode,
    SUM(jawaban_supervisi.jawaban) AS nilai_total,
    COUNT(item_penilaian.id_item_penilaian) AS jumlah_item_penilaian,
    jawaban_supervisi.tanggal_pengisian
     FROM jawaban_supervisi
    JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian
    JOIN users ON jawaban_supervisi.id_guru = users.id_user
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    GROUP BY jawaban_supervisi.id_jadwal_supervisi, jawaban_supervisi.id_guru"
);
// $getAkumulasiNilai = mysqli_query(
//     $koneksi,
//     "SELECT 
//     jawaban_supervisi.id_jadwal_supervisi,
//     jawaban_supervisi.id_guru, 
//     SUM(jawaban_supervisi.jawaban) AS nilai_total,

//      FROM jawaban_supervisi
//     JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian
//     GROUP BY jawaban_supervisi.id_jadwal_supervisi, jawaban_supervisi.id_guru"
// );

?>

<section>
    <div class="container border p-4 mt-4 mb-4">
        <div class="row">
            <h2 class="text-center">Hasil Supervisi</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jadwal Supervisi</th>
                        <th>Nama Guru</th>
                        <th>Nilai</th>
                        <th>Jumlah Item</th>
                        <th>Tanggal Pengisian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($getAkumulasiNilai as $data): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $data['nama_periode'] ?></td>
                            <td><?= $data['nama_guru'] ?></td>
                            <td><?= $data['nilai_total'] ?></td>
                            <td><?= $data['jumlah_item_penilaian'] ?></td>
                            <td>
                                <?=
                                    $hasilNilai = $data['nilai_total'] * 20 / $data['jumlah_item_penilaian']
                                    ?>
                            </td>
                            <?php
                            if ($hasilNilai >= 85) {
                                $predikat = "A";
                            } elseif ($hasilNilai >= 70) {
                                $predikat = "B";
                            } elseif ($hasilNilai >= 55) {
                                $predikat = "C";
                            } elseif ($hasilNilai >= 40) {
                                $predikat = "D";
                            } else {
                                $predikat = "E";
                            }
                            ?>
                            <td><?= $predikat ?></td>
                            <td><?= $data['tanggal_pengisian'] ?></td>
                        </tr>
                        <?php $no++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
