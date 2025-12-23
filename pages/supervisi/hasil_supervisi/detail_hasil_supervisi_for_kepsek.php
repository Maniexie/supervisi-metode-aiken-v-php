<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];
// echo "Detail Hasil Supervisi untuk Jadwal Supervisi ID: " . $id_jadwal_supervisi;

$getDaftarHasilSupervisi = mysqli_query(
    $koneksi,

    "SELECT *, users.nama  AS nama_guru, 
    kepala_sekolah.nama AS nama_kepala_sekolah,
    jadwal_supervisi.nama_periode,
    SUM(jawaban_supervisi.jawaban) AS nilai_total,
    COUNT(item_penilaian.id_item_penilaian) AS jumlah_item_penilaian,
    jawaban_supervisi.tanggal_pengisian
    FROM jawaban_supervisi   
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    JOIN users ON jawaban_supervisi.id_guru = users.id_user 
    JOIN users AS kepala_sekolah ON jawaban_supervisi.id_kepala_sekolah = kepala_sekolah.id_user
    JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian
    WHERE jawaban_supervisi.id_jadwal_supervisi = '$id_jadwal_supervisi'
    GROUP BY jawaban_supervisi.id_guru
    "
);

// Ambil data pengguna (guru atau operator)
$getDataUsers = mysqli_query($koneksi, "
    SELECT * , nama_jabatan 
    FROM users 
    JOIN k_jabatan ON users.kode_jabatan = k_jabatan.kode_jabatan 
    WHERE role IN ('guru', 'operator')
    ORDER BY users.id_user DESC
");

// // Ambil data jawaban supervisi berdasarkan jadwal yang sedang berlangsung
// $getJawabanSupervisi = mysqli_query($koneksi, "SELECT id_guru FROM jawaban_supervisi WHERE id_jadwal_supervisi = '$id_jadwal_supervisi' GROUP BY id_guru");

// // Simpan ID guru yang sudah disupervisi dalam array
// $guruSudahSupervisi = [];
// while ($jawaban = mysqli_fetch_assoc($getDaftarHasilSupervisi)) {
//     $guruSudahSupervisi[] = $jawaban['id_guru'];
// }

$periodeSupervisi = [];
$totalGuruSudahSupervisi = 0;
foreach ($getDaftarHasilSupervisi as $data) {
    $periodeSupervisi[] = $data['nama_periode'];
    $totalGuruSudahSupervisi++;
}

$jumlahSemuaGuru = [];
$semuaNamaGuru = [];
foreach ($getDataUsers as $dataUser) {
    $jumlahSemuaGuru[] = $dataUser['id_user'];
    $semuaNamaGuru[] = $dataUser['nama'];
}


?>


<section>
    <div class="container">
        <h3 class="text-center">Hasil Supervisi</h3>
        <div class="cards col-md-4">
            <div class="card">
                <span>Periode :
                    <?= !empty($periodeSupervisi) ? $periodeSupervisi[0] : 'Periode tidak ditemukan.' ?></span>
                <span>Jumlah Guru : <?= count($jumlahSemuaGuru) ?></span>
                <span>Sudah Supervisi : <?= $totalGuruSudahSupervisi ?></span>
                <span>Belum Supervisi : <?= count($jumlahSemuaGuru) - $totalGuruSudahSupervisi ?></span>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover text-center">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Guru</th>
                    <th scope="col">Supervisor</th>
                    <th scope="col">Nilai</th>
                    <th scope="col">Tanggal Pengisian</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($getDaftarHasilSupervisi) == 0): ?>
                    <tr>
                        <td colspan="6">Tidak ada data hasil supervisi.</td>
                    </tr>
                    <?php
                endif;
                ?>
                <?php $no = 1; ?>
                <?php foreach ($getDaftarHasilSupervisi as $data): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['nama_guru'] ?></td>
                        <td><?= $data['nama_kepala_sekolah'] ?></td>
                        <?php
                        $hasilNilai = $data['nilai_total'] * 20 / $data['jumlah_item_penilaian'];
                        $formattedNilai = number_format($hasilNilai, 0);


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
                        <td>
                            <?= $formattedNilai ?>(<?= $predikat ?>)
                        </td>
                        <td><?= $data['tanggal_pengisian'] ?></td>
                        <td>
                            <a
                                href="index.php?page=umpan_balik_hasil_supervisi&id_jadwal_supervisi=<?= $data['id_jadwal_supervisi'] ?>&id_guru=<?= $data['id_guru'] ?>">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>