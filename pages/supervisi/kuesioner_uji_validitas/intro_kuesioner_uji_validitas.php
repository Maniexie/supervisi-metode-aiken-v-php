<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$getVersi = $_GET['versi'];

$getDataValidator = mysqli_query($koneksi, "SELECT *,nama_jabatan 
FROM users JOIN k_jabatan ON users.kode_jabatan = k_jabatan.kode_jabatan WHERE id_user = '" . $_SESSION['id_user'] . "'");
$dataValidator = mysqli_fetch_array($getDataValidator);

$day = date('D');
if ($day == 'Mon') {
    $day = 'Senin';
} elseif ($day == 'Tue') {
    $day = 'Selasa';
} elseif ($day == 'Wed') {
    $day = 'Rabu';
} elseif ($day == 'Thu') {
    $day = 'Kamis';
} elseif ($day == 'Fri') {
    $day = 'Jumat';
} elseif ($day == 'Sat') {
    $day = 'Sabtu';
} elseif ($day == 'Sun') {
    $day = 'Minggu';
}

// EKSEKUSI KUESIONER UJI VALIDITAS
// Query untuk memeriksa apakah ada status_item 'tidak_aktif' pada versi yang dimaksud
$getStatusItemPenilaian = mysqli_query($koneksi, "
    SELECT status_item 
    FROM item_penilaian
    WHERE versi = '$getVersi' AND status_item = 'tidak_aktif'
");

// Cek apakah ada hasil dari query
if (mysqli_num_rows($getStatusItemPenilaian) > 0) {
    // Jika ada status_item yang 'tidak_aktif'
    echo "<script>
        alert('Kuesioner Uji Validitas Belum Bisa Dimulai karena ada item yang Tidak Aktif.');
        window.location.href = 'index.php?page=daftar_versi_kuesioner_uji_validitas';
    </script>";
    exit(); // Stop eksekusi lebih lanjut
} else {
    // Jika tidak ada item yang statusnya 'tidak_aktif', proses bisa dilanjutkan
    // Anda bisa melanjutkan ke halaman uji validitas atau kode lainnya di sini
}

?>

<!-- Content -->
<section>
    <div class="container border p-4 mt-4 mb-4">
        <h3 class="text-center mb-4">Intro Kuesioner</h3>
        <div class="row mb-3 align-items-center">
            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $dataValidator['nama'] ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="nip" class="col-sm-3 col-form-label">NIP</label>
            <div class="col-sm-9">
                <input type="text" id="nip" name="nip" class="form-control" value="<?= $dataValidator['nip'] ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="kode_jabatan" class="col-sm-3 col-form-label">Jabatan</label>
            <div class="col-sm-9">
                <input type="text" id="kode_jabatan" name="kode_jabatan" class="form-control"
                    value="<?= $dataValidator['nama_jabatan'] ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="tanggal_pengisian" class="col-sm-3 col-form-label">Tanggal Pengisian</label>
            <div class="col-sm-9">
                <input type="text" id="tanggal_pengisian" name="tanggal_pengisian" class="form-control"
                    value="<?= $day . ', ' . date('d-m-y') ?>">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <label for="versi" class="col-sm-3 col-form-label">Versi</label>
            <div class="col-sm-9">
                <input type="text" id="versi" name="versi" class="form-control" value="<?= $getVersi ?>">
            </div>
        </div>
        <hr>

        <p style="margin-bottom: -1px;"><b>*Petunjuk</b></p>
        <p style="margin-bottom: -1px;">1. Peneliti sangat berharap pada bantuan bapak/ibu untuk berkenan memberikan
            tanggapan terhadap setiap
            pernyataan dengan memberikan tanda centang (√) pada setiap kolom pilihan jawaban yang tersedia.</p>
        <p style="margin-bottom: -1px;">2. Dalam respon yang bapak/ibu berikan tidak ada nilai benar atau salah dalam
            memberi centang (√) pada setiap
            kolom jawaban yang tersedia dan hasil tanggapan tidak ada kaitannya dengan karir bapak/ibu .</p>
        <p>3. Arti singkatan pada kolom pilihan jawaban yang tersedia:
            <br>
            <span class="m-3">a. STS = Sangat Tidak Setuju</span>
            <br>
            <span class="m-3">b. TS = Tidak Setuju</span>
            <br>
            <span class="m-3">c. N = Netral</span>
            <br>
            <span class="m-3">d. S = Setuju</span>
            <br>
            <span class="m-3">e. SS = Sangat Setuju</span>
            <br>
        </p>
        <p>4. Sebelum bapak/ibu mengisi item pernyataan, peneliti mengucapkan terimakasih atas bantuan dan partisipasi
            yang diberikan.</p>
        <div class="container d-flex justify-content-end">
            <a href="index.php?page=kuesioner_uji_validitas&versi=<?= $getVersi ?>"
                class="btn btn-primary px-4">Mulai</a>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>