<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';


$old_kode = $_POST['kode_kategori_penilaian'] ?? '';
$old_nama = $_POST['nama_kategori_penilaian'] ?? '';

$error = false;
$error_message = '';


$getKategoriPenilaian = mysqli_query($koneksi, " SELECT * FROM k_penilaian "); // mengambil data kategori penilaian dari tabel k_penilaian


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_kategori_penilaian = $_POST['kode_kategori_penilaian'];
    $nama_kategori_penilaian = $_POST['nama_kategori_penilaian'];


    $cekDataKategoriPenilaian = $koneksi->prepare(
        "SELECT 1 FROM k_penilaian WHERE kode_kategori_penilaian = ?"
    );
    $cekDataKategoriPenilaian->bind_param("s", $kode_kategori_penilaian);
    $cekDataKategoriPenilaian->execute();
    $cekDataKategoriPenilaian->store_result();

    if ($cekDataKategoriPenilaian->num_rows > 0) {
        $error = true;
        $error_message = 'Kode Kategori Penilaian Sudah Ada';
    }


    if ($error) {
        // Jika ada error, simpan nilai lama untuk ditampilkan kembali di form
        $old_kode = $kode_kategori_penilaian;
        $old_nama = $nama_kategori_penilaian;
    } else {
        // Jika tidak ada error, lanjutkan proses penyimpanan data
        $stmt = $koneksi->prepare("INSERT INTO k_penilaian (kode_kategori_penilaian, nama_kategori_penilaian) VALUES (?, ?)");
        $stmt->bind_param("ss", $kode_kategori_penilaian, $nama_kategori_penilaian);
        $stmt->execute();
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Kategori Penilaian",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "success",
            title: "Data Kategori Penilaian Berhasil di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=kategori_penilaian";
        });
    });
    </script>';
    }

} else if ($_SERVER['REQUEST_METHOD'] == null || $_SERVER['REQUEST_METHOD'] == '') {
    // Tampilkan form tambah aktor
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Kategori Penilaian",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "error",
            title: "Data Kategori Penilaian Gagal di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=tambah_kategori_penilaian";
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
                <label for="kode_kategori_penilaian" class="form-label" style="margin-bottom: -10px;">Kode Kategori
                    Penilaian</label>
                <input type="text" class="form-control" id="kode_kategori_penilaian" name="kode_kategori_penilaian"
                    value="<?= htmlspecialchars($old_kode) ?>" required autofocus>
            </div>
            <div class="col-md mt-2">
                <label for="nama_kategori_penilaian" class="form-label" style="margin-bottom: -10px;">Nama Kategori
                    Penilaian</label>
                <input type="text" class="form-control" id="nama_kategori_penilaian" name="nama_kategori_penilaian"
                    value="<?= htmlspecialchars($old_nama) ?>" required autofocus>
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
            title: "Proses Penambahan Data Kategori Penilaian",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "error",
                title: "<?= $error_message ?>",
                text: "Silahkan gunakan kode kategori penilaian yang lain.",
                showConfirmButton: true,
                timer: 3000
            });
        });
    </script>
<?php endif; ?>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>