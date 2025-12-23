<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';
$id_validator = $_SESSION['id_user'];

// mengambil data versi kuesioner uji validitas dari tabel item_penilaian
// $getVersiItemPenilaian = mysqli_query($koneksi, "SELECT DISTINCT versi,(
// SELECT 1 , id_validator FROM jawaban_validator WHERE id_validator = '$id_validator' AND jawaban_validator.versi = item_penilaian.versi) AS 'sudah_diisi'
// FROM item_penilaian  ORDER BY versi ASC");
$getVersiItemPenilaian = mysqli_query($koneksi, "SELECT DISTINCT item_penilaian.versi,
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM jawaban_validator
            WHERE jawaban_validator.id_validator = '$id_validator' 
            AND jawaban_validator.versi = item_penilaian.versi
        ) THEN 1 
        ELSE 0 
    END AS 'sudah_diisi'
FROM item_penilaian ORDER BY versi ASC");

?>

<style>
    .card:hover {
        font-weight: bold;
        text-decoration: none;
        font-size: large;
    }
</style>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">
        <div class="row">
            <h2 class="text-center">Daftar Versi Kuesioner Uji Validitas</h2>
            <?php foreach ($getVersiItemPenilaian as $data): ?>
                <?php if ($data['sudah_diisi'] == 0): ?>
                    <div class=" col-md-4 cards allowed">
                        <a href="index.php?page=intro_kuesioner_uji_validitas&versi=<?= $data['versi'] ?>" class="card"
                            style="text-decoration: none;">
                            Kuesioner
                            Uji Validitas
                            (Versi
                            <?= $data['versi'] ?> ) - Mulai 🚀</a>
                    </div>
                <?php else: ?>
                    <div class="cards col-md-4 ">
                        <a href="#" class="card disabled text-muted "
                            style="cursor: not-allowed; text-decoration: none; opacity: 0.5; ">Kuesioner
                            Uji Validitas (Versi
                            <?= $data['versi'] ?> ) -
                            Sudah
                            Diisi ✅</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>