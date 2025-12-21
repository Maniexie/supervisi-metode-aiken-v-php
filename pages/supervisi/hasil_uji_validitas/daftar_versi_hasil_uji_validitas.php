<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$getJawabanValidator = mysqli_query($koneksi, "SELECT * FROM jawaban_validator");
$jawabanValidator = mysqli_fetch_array($getJawabanValidator);
?>

<!-- Content -->
<section>
    <div class="container">
        <h2 class="text-center">Daftar Versi Hasil Uji Validitas</h2>
        <div class="cards">
            <a class="card" href="index.php?page=hasil_uji_validitas&versi=<?= $jawabanValidator['versi'] ?>">Hasil Uji
                Validitas Versi
                <?= $jawabanValidator['versi'] ?></a>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>