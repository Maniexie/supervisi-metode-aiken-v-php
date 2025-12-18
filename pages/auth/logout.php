<?php
session_start();             // ⬅️ WAJIB panggil ini sebelum mengakses/menghapus sesi
session_unset();             // ⬅️ Hapus semua variabel sesi
session_destroy();           // ⬅️ Hancurkan sesi

// Hapus cookie session juga
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hentikan cache browser
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect
header("Location: index.php?page=login");
exit;
