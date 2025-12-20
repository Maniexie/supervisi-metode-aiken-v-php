<?php

function checkRoleAccess($page)
{
    $role = $_SESSION['role'] ?? null;

    $rolePages = [
        'operator' => [
            'dashboard',
            'profil',
            'item_penilaian',
            'hasil_uji_validitas',
            'kelola_item_penilaian',
            'daftar_aktor',
            'edit_aktor',
            'hapus_aktor',
            'tambah_aktor',
            'kategori_penilaian',
            'edit_kategori_penilaian',
            'hapus_kategori_penilaian',
            'tambah_kategori_penilaian'
        ],
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
