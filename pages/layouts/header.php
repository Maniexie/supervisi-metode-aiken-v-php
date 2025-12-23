<?php
require_once __DIR__ . '/../../koneksi.php';
// define('BASE_URL', 'http://localhost/supervisi');
// $BASE_URL = BASE_URL;

$role = '';
if ($_SESSION['role'] == 'kepala_sekolah') {
    $role = 'Kepala Sekolah';
} else if ($_SESSION['role'] == 'operator') {
    $role = 'Operator';
} else if ($_SESSION['role'] == 'guru') {
    $role = 'Guru';
}

$isValidator = $_SESSION['is_validator'];


// for mulai supervisi
$hariini = date('Y-m-d');
$getJadwalSupervisi = mysqli_query($koneksi, "SELECT * FROM jadwal_supervisi WHERE '$hariini' BETWEEN tanggal_mulai AND tanggal_selesai ORDER BY tanggal_mulai DESC LIMIT 1");
$jadwalSupervisi = mysqli_fetch_array($getJadwalSupervisi);

// ===================================================//


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOj1EOiFqcLo7l6rXmdpmOZFTobbGdgm225pTqoa0UCEhSh+Q5x8vnX2cvX/7+/Lw==" crossorigin="anonymous">
    <!-- CDN Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar ">
            <h4>Supervisi</h4>
            <!-- <p><?= BASE_URL ?></p> -->
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php?page=dashboard" class="nav-link"
                        aria-current="page">Dashboard</a></li>
                <li class="nav-item"><a href="index.php?page=profil" class="nav-link">Profil</a></li>

                <?php if ($_SESSION['role'] == 'operator'): ?>
                    <li class="nav-item"><a href="index.php?page=daftar_aktor" class="nav-link">Daftar Aktor</a></li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#supervisi" role="button" aria-expanded="false"
                        aria-controls="supervisi">
                        <span>Supervisi</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>

                    <div class="collapse ps-3 mt-1" id="supervisi">
                        <ul class="nav flex-column">
                            <?php if ($isValidator && ($_SESSION['role'] == 'kepala_sekolah') || $_SESSION['role'] == 'guru'): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list"
                                        href="index.php?page=daftar_versi_kuesioner_uji_validitas">
                                        Kuesioner Uji Validitas
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($_SESSION['role'] == 'operator') { ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=kategori_penilaian">
                                        Kategori Item Penilaian
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=item_penilaian">
                                        Item Penilaian
                                    </a>
                                </li>
                                <li class="nav-item test">
                                    <a class="nav-link text-white sub-list"
                                        href="index.php?page=daftar_versi_hasil_uji_validitas">
                                        Hasil Uji Validitas
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=kelola_item_penilaian">
                                        Kelola Item Penilaian
                                    </a>
                                </li> -->
                            <?php } ?>

                            <?php if ($isValidator && $_SESSION['role'] == 'kepala_sekolah'): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list"
                                        href="index.php?page=daftar_versi_hasil_uji_validitas">
                                        Hasil Uji Validitas
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($_SESSION['role'] == 'operator' || $_SESSION['role'] == 'guru'): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=jadwal_supervisi">
                                        Jadwal Supervisi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=hasil_supervisi_for_guru">
                                        Hasil Supervisi
                                    </a>
                                <?php endif; ?>

                                <?php if ($_SESSION['role'] == 'kepala_sekolah'): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list" href="index.php?page=jadwal_supervisi">
                                        Jadwal Supervisi
                                    </a>
                                </li>

                                <?php if ($jadwalSupervisi): ?>
                                    <?php $isAktif = ($hariini >= $jadwalSupervisi['tanggal_mulai']
                                        && $hariini <= $jadwalSupervisi['tanggal_selesai']); ?>
                                    <?php if ($isAktif): ?>
                                        <li class="nav-item">
                                            <a class="nav-link text-white sub-list" href="index.php?page=daftar_mulai_supervisi">
                                                Mulai Supervisi (Aktif)
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="nav-item">
                                            <a class="nav-link text-white sub-list" disabled>
                                                Mulai Supervisi (Tidak Aktif)
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Jika tidak ada jadwal yang ditemukan -->
                                    <li class="nav-item">
                                        <a class="nav-link text-white sub-list" disabled>
                                            Mulai Supervisi (Jadwal tidak ditemukan)
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white sub-list"
                                        href="index.php?page=hasil_supervisi_for_kepsek">
                                        Hasil Supervisi
                                    </a>
                                <?php endif; ?>
                        </ul>
                    </div>
                </li>

                <!-- <li class="nav-item"><a class="nav-link" href="#">Pengguna</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produk</a></li> -->
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