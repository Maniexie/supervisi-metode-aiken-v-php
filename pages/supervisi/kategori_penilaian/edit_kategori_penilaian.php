<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

/* =============================
   AMBIL DATA AWAL (GET)
============================= */
$getKodeKategoriPenilaian = $_GET['kode_kategori_penilaian'] ?? null;

$stmtGet = $koneksi->prepare(
    "SELECT * FROM k_penilaian WHERE kode_kategori_penilaian = ?"
);
$stmtGet->bind_param("s", $getKodeKategoriPenilaian);
$stmtGet->execute();
$getKategoriPenilaian = $stmtGet->get_result();

if ($getKategoriPenilaian->num_rows === 0) {
    die("Data tidak ditemukan");
}

$data = $getKategoriPenilaian->fetch_assoc();

/* =============================
   DEFAULT VALUE FORM
============================= */
$kode_baru = $data['kode_kategori_penilaian'];
$nama_kategori_penilaian = $data['nama_kategori_penilaian'];

$error = false;
$error_message = '';
$success = false;

/* =============================
   PROSES POST
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kode_baru = trim($_POST['kode_kategori_penilaian']);
    $kode_lama = trim($_POST['kode_lama']);
    $nama_kategori_penilaian = trim($_POST['nama_kategori_penilaian']);

    /* VALIDASI KOSONG */
    if ($kode_baru === '' || $nama_kategori_penilaian === '') {
        $error = true;
        $error_message = 'Semua field wajib diisi';
    }

    /* CEK DUPLICATE (KECUALI DIRI SENDIRI) */
    if (!$error) {
        $cek = $koneksi->prepare(
            "SELECT 1 FROM k_penilaian 
             WHERE kode_kategori_penilaian = ?
             AND kode_kategori_penilaian != ?"
        );
        $cek->bind_param("ss", $kode_baru, $kode_lama);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = true;
            $error_message = 'Kode Kategori Penilaian Sudah Digunakan';
        }
    }

    /* UPDATE DATA */
    if (!$error) {
        $stmt = $koneksi->prepare(
            "UPDATE k_penilaian 
             SET kode_kategori_penilaian = ?, nama_kategori_penilaian = ?
             WHERE kode_kategori_penilaian = ?"
        );
        $stmt->bind_param(
            "sss",
            $kode_baru,
            $nama_kategori_penilaian,
            $kode_lama
        );
        $stmt->execute();
        $success = true;
    }
}
?>

<!-- =============================
     CONTENT
============================= -->
<section>
    <div class="container border rounded p-4 mb-4 mt-2">
        <h2 class="text-center">Edit Kategori Penilaian</h2>

        <form method="post">
            <input type="hidden" name="kode_lama" value="<?= htmlspecialchars($data['kode_kategori_penilaian']) ?>">

            <div class="col-md">
                <label class="form-label">Kode Kategori Penilaian</label>
                <input type="text" class="form-control" name="kode_kategori_penilaian"
                    value="<?= htmlspecialchars($kode_baru) ?>" required>
            </div>

            <div class="col-md mt-2">
                <label class="form-label">Nama Kategori Penilaian</label>
                <input type="text" class="form-control" name="nama_kategori_penilaian"
                    value="<?= htmlspecialchars($nama_kategori_penilaian) ?>" required>
            </div>

            <div class="col-12 mt-3">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    </div>
</section>

<!-- =============================
     ALERT
============================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($error): ?>
    <script>
        Swal.fire({
            title: "Proses Perubahan Data Kategori Penilaian",
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
                timer: 5000
            });
        });
    </script>
<?php endif; ?>

<?php if ($success): ?>
    <script>
        Swal.fire({
            title: "Proses Perubahan Data Kategori Penilaian",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "success",
                title: "Data berhasil diubah",
                showConfirmButton: true,
                timer: 5000
            }).then(() => {
                window.location.href = "index.php?page=kategori_penilaian";
            });
        });
    </script>
<?php endif; ?>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>