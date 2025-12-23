<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];
$id_guru = $_GET['id_guru'];


$getDaftarJawabanSupervisi = mysqli_query(
    $koneksi,
    "SELECT *, 
    jadwal_supervisi.id_jadwal_supervisi, 
    jadwal_supervisi.nama_periode,
    users.nama AS nama_guru,
    SUM(jawaban_supervisi.jawaban) AS total_nilai,
    COUNT(item_penilaian.id_item_penilaian) AS total_item
    FROM jawaban_supervisi 
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    JOIN users ON jawaban_supervisi.id_guru = users.id_user
    JOIN item_penilaian ON jawaban_supervisi.id_item_penilaian = item_penilaian.id_item_penilaian    
    WHERE jawaban_supervisi.id_jadwal_supervisi = '$id_jadwal_supervisi'
    AND jawaban_supervisi.id_guru = '$id_guru'
"
);

$jawabanSupervisi = mysqli_fetch_assoc($getDaftarJawabanSupervisi);

$getKategoriTindakLanjut = mysqli_query(
    $koneksi,
    "SELECT * FROM k_tindak_lanjut_hasil_supervisi"
);

$getDataSupervisi = mysqli_query(
    $koneksi,
    "SELECT jawaban_supervisi.id_jawaban_supervisi, 
    users.nama AS nama_guru, 
    users.id_user AS id_guru,
    jadwal_supervisi.nama_periode
    FROM jawaban_supervisi
    JOIN users ON jawaban_supervisi.id_guru = users.id_user
    JOIN jadwal_supervisi ON jawaban_supervisi.id_jadwal_supervisi = jadwal_supervisi.id_jadwal_supervisi
    WHERE jawaban_supervisi.id_jadwal_supervisi = '$id_jadwal_supervisi'
    AND jawaban_supervisi.id_guru = '$id_guru'
    "
);

$dataSupervisi = mysqli_fetch_assoc($getDataSupervisi);
$id_jawaban_supervisi = $dataSupervisi['id_jawaban_supervisi'];
$id_guru = $dataSupervisi['id_guru'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_tindak_lanjut = $_POST['kode_tindak_lanjut'];
    $id_jadwal_supervisi = $_POST['id_jadwal_supervisi'];
    $id_guru = $_POST['id_guru'];
    $nilai = $_POST['nilai'];
    $umpan_balik = $_POST['umpan_balik'];

    $cekKodeTindakLanjut = mysqli_query($koneksi, "SELECT * FROM k_tindak_lanjut_hasil_supervisi WHERE kode_tindak_lanjut = '$kode_tindak_lanjut'");
    if (mysqli_num_rows($cekKodeTindakLanjut) == 0) {
        // Jika tidak ada, beri peringatan atau tangani error
        echo "<script>alert('Kode Tindak Lanjut tidak valid. Silakan pilih kode tindak lanjut yang benar.');</script>";
        exit;

    } else {
        // Lakukan INSERT jika valid
        $stmt = $koneksi->prepare(
            "INSERT INTO hasil_supervisi (kode_tindak_lanjut, id_jadwal_supervisi, id_guru, nilai ,umpan_balik) VALUES (?, ?, ?, ? ,?)"
        );
        $stmt->bind_param(
            "siids",
            $kode_tindak_lanjut,
            $id_jadwal_supervisi,
            $id_guru,
            $nilai,
            $umpan_balik
        );
        $stmt->execute();


        echo "<script>
    window.location.href = 'index.php?page=umpan_balik_hasil_supervisi&id_jadwal_supervisi=$id_jadwal_supervisi&id_guru=$id_guru';
</script>";
        exit;


        // var_dump($_POST);
    }
}

?>

<section>
    <div class="container">
        <form action="" method="post">
            <input type="hidden" name="id_jadwal_supervisi" value="<?= $id_jadwal_supervisi ?>">

            <input type="hidden" name="id_guru" value="<?= $dataSupervisi['id_guru'] ?>">

            <div class="mb-3">
                <label for="id_jadwal_supervisi"> Nama Periode </label>
                <input type="text" class="form-control" id="id_jadwal_supervisi"
                    value="<?= $dataSupervisi['nama_periode'] ?>" readonly>
            </div>


            <div class="mb-3">
                <label for="id_guru"> ID Guru</label>
                <input type="text" class="form-control" id="id_guru" value="<?= $dataSupervisi['nama_guru'] ?>"
                    readonly>
            </div>
            <div class="mb-3">
                <label for="nilai"> Nilai</label>
                <input type="text" class="form-control" id="nilai" name="nilai"
                    value="<?= $jawabanSupervisi['total_nilai'] * 20 / $jawabanSupervisi['total_item'] ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="kode_tindak_lanjut" class="form-label">Kode Tindak Lanjut</label>
                <select class="form-control" id="kode_tindak_lanjut" name="kode_tindak_lanjut" required autofocus>
                    <option value="">Pilih Kode Tindak Lanjut</option>
                    <?php
                    while ($kategoriTindakLanjut = mysqli_fetch_assoc($getKategoriTindakLanjut)) {
                        echo "<option value='" . $kategoriTindakLanjut['kode_tindak_lanjut'] . "'>" . $kategoriTindakLanjut['kode_tindak_lanjut'] . "</option>";
                    }
                    ?>

                </select>
            </div>
            <div class="mb-3">
                <label for="umpan_balik">Umpan balik</label>
                <textarea class="form-control" id="umpan_balik" name="umpan_balik" required>

                </textarea>
            </div>

            <button class="btn btn-primary" type="submit">Kirim</button>
        </form>
    </div>
</section>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>