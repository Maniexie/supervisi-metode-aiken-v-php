<?php
session_start();

$allowedPages = [
    'login' => 'pages/auth/login.php',
    'logout' => 'pages/auth/logout.php',
    'dashboard' => 'pages/dashboard.php',
    'profil' => 'pages/profil.php',

    //daftar aktor
    'daftar_aktor' => 'pages/daftar_aktor/daftar_aktor.php',
    'edit_aktor' => 'pages/daftar_aktor/edit_aktor.php',
    'hapus_aktor' => 'pages/daftar_aktor/hapus_aktor.php',
    'tambah_aktor' => 'pages/daftar_aktor/tambah_aktor.php',

    // Sidebar -> Supervisi

    //Supervisi -> Kuesioner Uji Validitas
    'kuesioner_uji_validitas' => 'pages/supervisi/kuesioner_uji_validitas/kuesioner_uji_validitas.php',
    'daftar_versi_kuesioner_uji_validitas' => 'pages/supervisi/kuesioner_uji_validitas/daftar_versi_kuesioner_uji_validitas.php',
    'intro_kuesioner_uji_validitas' => 'pages/supervisi/kuesioner_uji_validitas/intro_kuesioner_uji_validitas.php',

    //Supervisi -> Kategori Penilaian
    'kategori_penilaian' => 'pages/supervisi/kategori_penilaian/kategori_penilaian.php',
    'edit_kategori_penilaian' => 'pages/supervisi/kategori_penilaian/edit_kategori_penilaian.php',
    'hapus_kategori_penilaian' => 'pages/supervisi/kategori_penilaian/hapus_kategori_penilaian.php',
    'tambah_kategori_penilaian' => 'pages/supervisi/kategori_penilaian/tambah_kategori_penilaian.php',

    //Supervisi -> Item Penilaian
    'item_penilaian' => 'pages/supervisi/item_penilaian/item_penilaian.php',
    'edit_item_penilaian' => 'pages/supervisi/item_penilaian/edit_item_penilaian.php',
    'hapus_item_penilaian' => 'pages/supervisi/item_penilaian/hapus_item_penilaian.php',
    'tambah_item_penilaian' => 'pages/supervisi/item_penilaian/tambah_item_penilaian.php',
    'detail_item_penilaian' => 'pages/supervisi/item_penilaian/detail_item_penilaian.php',
    'revisi_item_penilaian' => 'pages/supervisi/item_penilaian/revisi_item_penilaian.php',

    //Supervisi -> Hasil Uji Validitas
    'hasil_uji_validitas' => 'pages/supervisi/hasil_uji_validitas/hasil_uji_validitas.php',
    'daftar_versi_hasil_uji_validitas' => 'pages/supervisi/hasil_uji_validitas/daftar_versi_hasil_uji_validitas.php',
    //Supervisi -> Kelola Item Penilaian
    'kelola_item_penilaian' => 'pages/supervisi/kelola_item_penilaian.php',

];

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        $title = "Dashboard";
        // $content = "pages/dashboard.php";
        break;
    case 'login':
        $title = "Login Page";
        break;
    case 'profil':
        $title = "Profil Page";
        break;
    case 'kuesioner_uji_validitas':
        $title = "Kuesioner Uji Validitas Page";
        break;
    case 'daftar_versi_kuesioner_uji_validitas':
        $title = "Daftar Versi Kuesioner Uji Validitas Page";
        break;
    case 'intro_kuesioner_uji_validitas':
        $title = "Intro Kuesioner Uji Validitas Page";
        break;
    case 'kategori_penilaian':
        $title = "Kategori Penilaian Page";
        break;
    case 'edit_kategori_penilaian':
        $title = "Edit Kategori Penilaian Page";
        break;
    case 'hapus_kategori_penilaian':
        $title = "Hapus Kategori Penilaian Page";
        break;
    case 'tambah_kategori_penilaian':
        $title = "Tambah Kategori Penilaian Page";
        break;
    case 'item_penilaian':
        $title = "Item Penilaian Page";
        break;
    case 'revisi_item_penilaian':
        $title = "Revisi Item Penilaian Page";
        break;

    case 'kelola_item_penilaian':
        $title = "Kelola Item Penilaian Page";
        break;
    case 'daftar_aktor':
        $title = "Daftar Aktor Page";
        break;
    case 'edit_aktor':
        $title = "Edit Aktor Page";
        break;
    case 'tambah_aktor':
        $title = "Tambah Aktor Page";
        break;
    case 'hapus_aktor':
        $title = "Hapus Aktor Page";
        break;
    case 'edit_item_penilaian':
        $title = "Edit Item Penilaian Page";
        break;
    case 'hapus_item_penilaian':
        $title = "Hapus Item Penilaian Page";
        break;
    case 'tambah_item_penilaian':
        $title = "Tambah Item Penilaian Page";
        break;
    case 'detail_item_penilaian':
        $title = "Detail Item Penilaian Page";
        break;

    case 'hasil_uji_validitas':
        $title = "Hasil Uji Validitas Page";
        break;
    case 'daftar_versi_hasil_uji_validitas':
        $title = "Daftar Versi Hasil Uji Validitas Page";
        break;
    case 'logout':
        $title = "Logout Page";
        break;
    case 'forbidden':
        $title = "Forbidden 403 Page";
        break;
    default:
        $title = "404 Page Not Found";
        break;
}
if (!isset($allowedPages[$page])) {
    echo "<h3>404 - Halaman tidak ditemukan</h3>";
    exit;
}


// 2️⃣ cek role (kecuali login & logout)
if (!in_array($page, ['login', 'logout'])) {
    require_once __DIR__ . '/middleware/role.php';
    checkRoleAccess($page);
}

require_once $allowedPages[$page];
