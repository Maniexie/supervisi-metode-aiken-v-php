<?php

function checkRoleAccess($page)
{
    $role = $_SESSION['role'] ?? null;

    $rolePages = [
        'operator' => [
            'dashboard',
            'profil',
            'hasil_uji_validitas',
            'kelola_item_penilaian',

            //daftar aktor
            'daftar_aktor',
            'edit_aktor',
            'hapus_aktor',
            'tambah_aktor',

            //Supervisi -> Kategori Penilaian
            'kategori_penilaian',
            'edit_kategori_penilaian',
            'hapus_kategori_penilaian',
            'tambah_kategori_penilaian',

            //Supervisi -> Item Penilaian
            'item_penilaian',
            'edit_item_penilaian',
            'hapus_item_penilaian',
            'tambah_item_penilaian',
            'detail_item_penilaian',
            'revisi_item_penilaian',

            //Supervisi -> Hasil Uji Validitas
            'hasil_uji_validitas',
            'daftar_versi_hasil_uji_validitas',

            //Supervisi -> Jadwal Supervisi
            'jadwal_supervisi',
            'detail_jadwal_supervisi',
            'tambah_jadwal_supervisi',
            'edit_jadwal_supervisi',
            'hapus_jadwal_supervisi',

            // Supervisi -> Hasil Supervisi
            'hasil_supervisi_for_guru',
            'detail_hasil_supervisi_for_guru',

            // Supervisi -> Kategori Tindak Lanjut Hasil Supervisi
            'kategori_tindak_lanjut_hasil_supervisi',
            'edit_kategori_tindak_lanjut_hasil_supervisi',
            'hapus_kategori_tindak_lanjut_hasil_supervisi',
            'tambah_kategori_tindak_lanjut_hasil_supervisi',
        ],

        'kepala_sekolah' => [
            'dashboard',
            'profil',

            //Supervisi -> Kuesioner Uji Validitas
            'kuesioner_uji_validitas',
            'daftar_versi_kuesioner_uji_validitas',
            'intro_kuesioner_uji_validitas',

            //Supervisi -> Hasil Uji Validitas
            'hasil_uji_validitas',
            'daftar_versi_hasil_uji_validitas',

            //Supervisi -> Jadwal Supervisi
            'jadwal_supervisi',
            'detail_jadwal_supervisi',
            'tambah_jadwal_supervisi',
            'edit_jadwal_supervisi',
            'hapus_jadwal_supervisi',

            // Supervisi -> Mulai Supervisi
            'mulai_supervisi',
            'daftar_mulai_supervisi',

            // Supervisi -> Hasil Supervisi
            'hasil_supervisi',
            'hasil_supervisi_for_kepsek',
            'detail_hasil_supervisi_for_kepsek',

            // Supervisi -> Tindak Lanjut Hasil Supervisi for Kepsek
            'umpan_balik_hasil_supervisi',
            'kirim_umpan_balik_hasil_supervisi',
            'edit_umpan_balik_hasil_supervisi',


        ],
        'guru' => [
            'dashboard',
            'profil',

            //Supervisi -> Kuesioner Uji Validitas
            'kuesioner_uji_validitas',
            'daftar_versi_kuesioner_uji_validitas',
            'intro_kuesioner_uji_validitas',

            //Supervisi -> Jadwal Supervisi
            'jadwal_supervisi',
            'detail_jadwal_supervisi',
            'tambah_jadwal_supervisi',
            'edit_jadwal_supervisi',
            'hapus_jadwal_supervisi',

            // Supervisi -> Hasil Supervisi
            'hasil_supervisi_for_guru',
            'detail_hasil_supervisi_for_guru',
        ],
    ];

    if (!$role || !isset($rolePages[$role]) || !in_array($page, $rolePages[$role])) {
        http_response_code(403);
        require_once __DIR__ . '/../pages/errors/forbidden.php';
        // header("Location: index.php?page=forbidden");
        exit;
    }
}
