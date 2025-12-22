<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$getJawabanValidator = mysqli_query(
    $koneksi,
    "SELECT DISTINCT versi FROM jawaban_validator ORDER BY versi ASC"
);
?>

<!-- Content -->
<section>
    <div class="container">
        <h2 class="text-center">Daftar Versi Hasil Uji Validitas</h2>

        <?php foreach ($getJawabanValidator as $data): ?>
            <div class="cards">
                <a class="card" href="index.php?page=hasil_uji_validitas&versi=<?= $data['versi'] ?>">
                    Hasil Uji Validitas Versi <?= $data['versi'] ?>
                </a>
            </div>
        <?php endforeach; ?>

    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>