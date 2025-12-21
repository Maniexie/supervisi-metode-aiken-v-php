<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

// mengambil data versi kuesioner uji validitas dari tabel item_penilaian
$getVersiItemPenilaian = mysqli_query($koneksi, "SELECT DISTINCT versi,(
SELECT COUNT(*) FROM jawaban_validator WHERE jawaban_validator.versi = item_penilaian.versi) AS 'sudah_diisi'
FROM item_penilaian ORDER BY versi ASC");

?>

<style>
    a:hover {
        font-weight: bold;
        text-decoration: none;
        font-size: large;
    }
</style>

<!-- Content -->
<section>
    <div class="container">
        <h2 class="text-center">Daftar Versi Kuesioner Uji Validitas</h2>
        <?php foreach ($getVersiItemPenilaian as $data): ?>
            <?php if ($data['sudah_diisi'] == 0): ?>
                <div class="cards allowed">
                    <a href="index.php?page=intro_kuesioner_uji_validitas&versi=<?= $data['versi'] ?>" class="card"
                        style="text-decoration: none;">Kuesioner
                        Uji Validitas
                        (Versi
                        <?= $data['versi'] ?> ) - Mulai 🚀</a>
                </div>
            <?php else: ?>
                <div class="cards">
                    <a href="#" class="card disabled text-muted"
                        style="cursor: not-allowed; text-decoration: none; opacity: 0.5; ">Kuesioner
                        Uji Validitas (Versi
                        <?= $data['versi'] ?> ) -
                        Sudah
                        Diisi ✅</a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>