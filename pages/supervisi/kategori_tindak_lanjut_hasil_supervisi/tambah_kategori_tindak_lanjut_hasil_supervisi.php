<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';


$old_kode_tindak_lanjut = $_POST['kode_tindak_lanjut'] ?? '';
$old_nama_tindak_lanjut = $_POST['nama_tindak_lanjut'] ?? '';
$old_deskripsi = $_POST['deskripsi'] ?? '';

$error = false;
$error_message = '';


// mengambil data kategori penilaian dari tabel k_tindak_lanjut_hasil_supervisi
$getKategoriTindakLanjutHasilSupervisi = mysqli_query($koneksi, " SELECT * FROM k_tindak_lanjut_hasil_supervisi ");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_tindak_lanjut = $_POST['kode_tindak_lanjut'];
    $nama_tindak_lanjut = $_POST['nama_tindak_lanjut'];
    $deskripsi = $_POST['deskripsi'];


    $cekDataKategoriTindakLanjut = $koneksi->prepare(
        "SELECT 1 FROM k_tindak_lanjut_hasil_supervisi WHERE kode_tindak_lanjut = ?"
    );
    $cekDataKategoriTindakLanjut->bind_param("s", $kode_tindak_lanjut);
    $cekDataKategoriTindakLanjut->execute();
    $cekDataKategoriTindakLanjut->store_result();

    if ($cekDataKategoriTindakLanjut->num_rows > 0) {
        $error = true;
        $error_message = 'Kode Kategori Tindak Lanjut Sudah Ada';
    }


    if ($error) {
        // Jika ada error, simpan nilai lama untuk ditampilkan kembali di form
        $old_kode_tindak_lanjut = $kode_tindak_lanjut;
        $old_nama_tindak_lanjut = $nama_tindak_lanjut;
        $old_deskripsi = $deskripsi;
    } else {
        // Jika tidak ada error, lanjutkan proses penyimpanan data
        $stmt = $koneksi->prepare("INSERT INTO k_tindak_lanjut_hasil_supervisi (kode_tindak_lanjut, nama_tindak_lanjut, deskripsi) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kode_tindak_lanjut, $nama_tindak_lanjut, $deskripsi);
        $stmt->execute();
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Kategori Tindak Lanjut Hasil Supervisi",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "success",
            title: "Data Kategori Tindak Lanjut Hasil Supervisi Berhasil di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=kategori_tindak_lanjut_hasil_supervisi";
        });
    });
    </script>';
    }

} else if ($_SERVER['REQUEST_METHOD'] == null || $_SERVER['REQUEST_METHOD'] == '') {
    // Tampilkan form tambah aktor
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Kategori Tindak Lanjut Hasil Supervisi",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "error",
            title: "Data Kategori Tindak Lanjut Hasil Supervisi Gagal di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=tambah_tindak_lanjut_hasil_supervisi";
        });
    });
    </script>';
}
?>

<!-- Content -->
<section>
    <div class="container border rounded p-4 mb-4 mt-2">
        <!-- start get data value -->
        <h2 class="text-center">Tambah Kategori Penilaian</h2>

        <!-- Form -->
        <form class="needs-validation" method="post">
            <div class="col-md">
                <label for="kode_tindak_lanjut" class="form-label" style="margin-bottom: -10px;">Kode Kategori
                    Penilaian</label>
                <input type="text" class="form-control" id="kode_tindak_lanjut" name="kode_tindak_lanjut"
                    value="<?= htmlspecialchars($old_kode_tindak_lanjut) ?>" required autofocus>
            </div>
            <div class="col-md mt-2">
                <label for="nama_tindak_lanjut" class="form-label" style="margin-bottom: -10px;">Nama Kategori
                    Penilaian</label>
                <input type="text" class="form-control" id="nama_tindak_lanjut" name="nama_tindak_lanjut"
                    value="<?= htmlspecialchars($old_nama_tindak_lanjut) ?>" required autofocus>
            </div>
            <div class="col-md mt-2">
                <label for="deskripsi" class="form-label" style="margin-bottom: -10px;">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi"
                    class="form-control"><?= htmlspecialchars($old_deskripsi) ?? '-' ?></textarea>
            </div>
            <div class="col-12 mt-2">
                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
</section>

<?php if ($error): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Proses Penambahan Data Kategori Tindak Lanjut Hasil Supervisi",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "error",
                title: "<?= $error_message ?>",
                text: "Silahkan gunakan kode kategori Tindak Lanjut Hasil Supervisi yang lain.",
                showConfirmButton: true,
                timer: 3000
            });
        });
    </script>
<?php endif; ?>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>