<?php
// echo 'ini adalah halaman Dashboard';
// echo '$_SESSION[id_user] : ' . $_SESSION['csrf_token'];
$BASE_URL = 'http://localhost/supervisi';

$role = '';
if ($_SESSION['role'] == 'kepala_sekolah') {
    $role = 'Kepala Sekolah';
} else if ($_SESSION['role'] == 'operator') {
    $role = 'Operator';
} else if ($_SESSION['role'] == 'guru') {
    $role = 'Guru';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?php echo $BASE_URL ?>/assets/css/style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOj1EOiFqcLo7l6rXmdpmOZFTobbGdgm225pTqoa0UCEhSh+Q5x8vnX2cvX/7+/Lw==" crossorigin="anonymous">
    <!-- CDN Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<style>

</style>

<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar ">
            <h4>Supervisi</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php?page=dashboard" class="nav-link"
                        aria-current="page">Dashboard</a></li>
                <li class="nav-item"><a href="index.php?page=profil" class="nav-link">Profil</a></li>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#supervisi" role="button" aria-expanded="false"
                        aria-controls="supervisi">
                        <span>Supervisi</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>

                    <div class="collapse ps-3 mt-1" id="supervisi">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white sub-list" href="index.php?page=kategori_item_penilaian">
                                    Kategori Item Penilaian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white sub-list" href="index.php?page=item_penilaian">
                                    Item Penilaian
                                </a>
                            </li>
                            <li class="nav-item test">
                                <a class="nav-link text-white sub-list" href="index.php?page=hasil_uji_validitas">
                                    Hasil Uji Validitas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white sub-list" href="index.php?page=kelola_item_penilaian">
                                    Kelola Item Penilaian
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item"><a class="nav-link" href="#">Pengguna</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="logoutAlert()">Logout</a>
                </li>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Header -->
            <header>
                <h4><?= $title ?></h4>
                <div class="user-profile text-capitalize">Selamat datang,
                    <?php echo $_SESSION['nama']; ?>(<span class=""><?php echo $role ?></span>)
                </div>
            </header>