<?php
function tanggal_indonesia($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecah = explode('-', $tanggal);
    $tahun = $pecah[0];
    $bulan_angka = $pecah[1];
    $hari = $pecah[2];

    // Untuk hari, bisa pakai date('l', strtotime($tanggal)) atau explode dulu
    $nama_hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
    $hari_ini = $nama_hari[date('l', strtotime($tanggal))];

    return $hari_ini . ', ' . $hari . ' ' . $bulan[(int) $bulan_angka] . ' ' . $tahun;
}
?>