<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';

$getVersi = $_GET['versi'];

// $getJawabanValidator = mysqli_query($koneksi, "SELECT *, 
// item_penilaian.id_item_penilaian,item_penilaian.kode_kategori_penilaian,item_penilaian.pernyataan FROM jawaban_validator JOIN item_penilaian ON jawaban_validator.id_item_penilaian = item_penilaian.id_item_penilaian WHERE jawaban_validator.versi = '$getVersi'");
// $jawabanValidator = mysqli_fetch_array($getJawabanValidator);




// Hitung Rumus Validitas Metode Aiken's V
$getJawabanValidators = mysqli_query(
    $koneksi,
    "SELECT 
item_penilaian.id_item_penilaian,
item_penilaian.kode_kategori_penilaian,
item_penilaian.pernyataan,
COUNT(jawaban_validator.id_validator) AS 'jumlah_validator',
SUM(jawaban_validator.jawaban - 1) AS 'total_jawaban',
(SUM(jawaban_validator.jawaban - 1)) / (COUNT(jawaban_validator.id_validator) * (4)) AS 'nilai_validitas'

FROM jawaban_validator
JOIN item_penilaian
ON jawaban_validator.id_item_penilaian = item_penilaian.id_item_penilaian
WHERE jawaban_validator.versi = '$getVersi'
Group BY item_penilaian.id_item_penilaian
ORDER BY item_penilaian.id_item_penilaian ASC"
);



// $getNilaiValiditas = mysqli_query(
//     $koneksi,
//     $getJawabanValidators
// );
?>

<!-- Content -->
<section>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Hasil Uji Validitas (Versi <?= $getVersi ?>)</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Pernyataan</th>
                                    <th>Nilai Validitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            foreach ($getJawabanValidators as $data): ?>
                                <tbody class="table-border-bottom-0">
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['id_item_penilaian'] ?></td>
                                        <td><?= $data['pernyataan'] ?></td>
                                        <td><?= number_format($data['nilai_validitas'], 3) ?></td>
                                        <?php if ($data['nilai_validitas'] > 0.8) { ?>
                                            <td><span class="badge bg-success">Valid</span></td>
                                        <?php } else { ?>
                                            <td><span class="badge bg-danger">Tidak Valid</span></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            <?php endforeach; ?>
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