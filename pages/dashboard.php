<?php
require_once __DIR__ . '/../pages/layouts/header.php';
// $hariini = date('Y-m-d');
// echo $hariini;

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php?page=login');
    exit;
}
?>
<!-- Cards/Content -->

<section class="cards">

    <div class="card">Data 1</div>
    <div class="card">Data 2</div>
    <div class="card">Data 3</div>
</section>


<?php
require_once __DIR__ . '/../pages/layouts/footer.php';
?>