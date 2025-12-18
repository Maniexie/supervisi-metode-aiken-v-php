<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


// belum login → hanya boleh ke login
if (!isset($_SESSION['id_user']) && !in_array($page, ['login'])) {
    header("Location: index.php?page=login");
    exit;
}

// sudah login → cegah balik ke login
if (isset($_SESSION['id_user']) && $page === 'login') {
    header("Location: index.php?page=dashboard");
    exit;
}
