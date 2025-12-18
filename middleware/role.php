<?php

function checkRoleAccess($page)
{
    $role = $_SESSION['role'] ?? null;

    $rolePages = [
        'operator' => ['dashboard'],
        'guru' => ['dashboard'],
        'kepala_sekolah' => ['dashboard']
    ];

    if (!$role || !isset($rolePages[$role]) || !in_array($page, $rolePages[$role])) {
        http_response_code(403);
        require_once __DIR__ . '/../pages/errors/forbidden.php';
        // header("Location: index.php?page=forbidden");
        exit;
    }
}
