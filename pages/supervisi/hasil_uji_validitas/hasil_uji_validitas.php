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
 ip.id_item_penilaian,
    ip.kode_item_penilaian,
    ip.pernyataan,
    ip.versi,
    COUNT(jv.id_validator) AS jumlah_validator,
    SUM(jv.jawaban - 1) AS total_jawaban,
    (SUM(jv.jawaban - 1)) / (COUNT(jv.id_validator) * 4) AS nilai_validitas,

    (
        SELECT MAX(ip2.versi)
        FROM item_penilaian ip2
        WHERE ip2.kode_item_penilaian = ip.kode_item_penilaian
    ) AS versi_terakhir

FROM jawaban_validator jv
JOIN item_penilaian ip 
    ON jv.id_item_penilaian = ip.id_item_penilaian
WHERE jv.versi = '$getVersi'
GROUP BY ip.id_item_penilaian
ORDER BY ip.id_item_penilaian ASC"
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
                                    <?php if ($_SESSION['role'] == 'operator'): ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            foreach ($getJawabanValidators as $data): ?>
                                <tbody class="table-border-bottom-0">
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['kode_item_penilaian'] ?></td>
                                        <td><?= $data['pernyataan'] ?></td>
                                        <td><?= number_format($data['nilai_validitas'], 3) ?></td>
                                        <?php $isValid = $data['nilai_validitas'] > 0.8; ?>
                                        <?php if ($data['nilai_validitas'] > 0.8) { ?>
                                            <td><span class="badge bg-success">Valid</span></td>
                                        <?php } else { ?>
                                            <td><span class="badge bg-danger">Tidak Valid</span></td>
                                        <?php } ?>
                                        <?php $sudahDirevisi = $data['versi_terakhir'] > $data['versi']; ?>
                                        <?php if ($_SESSION['role'] == 'operator'): ?>
                                            <td>
                                                <?php if ($isValid): ?>
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        Valid
                                                    </button>
                                                <?php elseif ($sudahDirevisi): ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        Sudah Direvisi
                                                    </button>
                                                <?php else: ?>
                                                    <a class=" btn btn-sm btn-primary"
                                                        href="index.php?page=revisi_item_penilaian&versi=<?= $data['versi'] ?>&id_item_penilaian=<?= $data['id_item_penilaian'] ?>">
                                                        Revisi
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
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