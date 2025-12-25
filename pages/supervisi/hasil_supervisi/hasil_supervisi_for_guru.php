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


$id_guru = $_SESSION['id_user'];

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

// akumulasi nilai guru berdasarkan id_jadwal_supervisi dan id_hasil_supervisi
$getAkumulasiNilai = mysqli_query(
    $koneksi,
    "SELECT 
        jawaban_supervisi.id_jadwal_supervisi,
        jawaban_supervisi.id_guru, 
        hasil_supervisi.id_hasil_supervisi,
        users.nama AS nama_guru,
        jadwal_supervisi.nama_periode,
        SUM(jawaban_supervisi.jawaban) AS nilai_total,
        COUNT(item_penilaian.id_item_penilaian) AS jumlah_item_penilaian,
        jawaban_supervisi.tanggal_pengisian
     FROM jawaban_supervisi
     JOIN item_penilaian 
        ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian
     JOIN users 
        ON jawaban_supervisi.id_guru = users.id_user
     JOIN jadwal_supervisi 
        ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
     JOIN hasil_supervisi
        ON hasil_supervisi.id_jadwal_supervisi = jawaban_supervisi.id_jadwal_supervisi
        AND hasil_supervisi.id_guru = jawaban_supervisi.id_guru
     WHERE jawaban_supervisi.id_guru = $id_guru
     GROUP BY jawaban_supervisi.id_jadwal_supervisi, jawaban_supervisi.id_guru"
);

?>

<section>
    <div class="container border p-4 mt-4 mb-4">
        <div class="row">
            <h2 class="text-center">Hasil Supervisi</h2>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode Supervisi</th>
                        <th>Nama</th>
                        <th>Nilai</th>
                        <th>Tanggal Pengisian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($getAkumulasiNilai as $data): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $data['nama_periode'] ?></td>
                            <td><?= $data['nama_guru'] ?></td>
                            <?php
                            $hasilNilai = $data['nilai_total'] * 20 / $data['jumlah_item_penilaian']
                                ?>
                            <?php
                            if ($hasilNilai >= 85) {
                                $predikat = "Sangat Baik";
                            } elseif ($hasilNilai >= 70) {
                                $predikat = "Baik";
                            } elseif ($hasilNilai >= 55) {
                                $predikat = "Cukup";
                            } elseif ($hasilNilai >= 40) {
                                $predikat = "Kurang";
                            } else {
                                $predikat = "Sangat Kurang";
                            }
                            ?>
                            <td><?= $data['nilai_total'] ?>(<?= $predikat ?>)</td>
                            <td><?= $data['tanggal_pengisian'] ?></td>
                            <td>
                                <a
                                    href="index.php?page=detail_hasil_supervisi_for_guru&id_jadwal_supervisi=<?= trim($data['id_jadwal_supervisi']) ?>&id_guru=<?= $data['id_guru'] ?>&id_hasil_supervisi=<?= $data['id_hasil_supervisi'] ?>">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        <?php $no++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
