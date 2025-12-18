<?php
session_start();

$allowedPages = [
    'login' => 'pages/auth/login.php',
    'logout' => 'pages/auth/logout.php',
    'dashboard' => 'pages/dashboard.php',

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
