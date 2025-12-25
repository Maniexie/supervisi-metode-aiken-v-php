<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

/* =============================
   AMBIL DATA AWAL (GET)
============================= */
$getKodeTindakLanjut = $_GET['kode_tindak_lanjut'] ?? null;

$stmtGet = $koneksi->prepare(
    "SELECT * FROM k_tindak_lanjut_hasil_supervisi WHERE kode_tindak_lanjut = ?"
);
$stmtGet->bind_param("s", $getKodeTindakLanjut);
$stmtGet->execute();
$getKategoriTindakLanjut = $stmtGet->get_result();

if ($getKategoriTindakLanjut->num_rows === 0) {
    die("Data tidak ditemukan");
}

$data = $getKategoriTindakLanjut->fetch_assoc();

/* =============================
   DEFAULT VALUE FORM
============================= */
$kode_baru = $data['kode_tindak_lanjut'];
$nama_tindak_lanjut = $data['nama_tindak_lanjut'];
$deskripsi = $data['deskripsi'];

$error = false;
$error_message = '';
$success = false;

/* =============================
   PROSES POST
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kode_baru = trim($_POST['kode_tindak_lanjut']);
    $kode_lama = trim($_POST['kode_lama']);
    $nama_tindak_lanjut = trim($_POST['nama_tindak_lanjut']);
    $deskripsi = trim($_POST['deskripsi']);

    /* VALIDASI KOSONG */
    if ($kode_baru === '' || $nama_tindak_lanjut === '') {
        $error = true;
        $error_message = 'Semua field wajib diisi';
    }

    /* CEK DUPLICATE (KECUALI DIRI SENDIRI) */
    if (!$error) {
        $cek = $koneksi->prepare(
            "SELECT 1 FROM k_tindak_lanjut_hasil_supervisi 
             WHERE kode_tindak_lanjut = ?
             AND kode_tindak_lanjut != ?"
        );
        $cek->bind_param("ss", $kode_baru, $kode_lama);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = true;
            $error_message = 'Kode Kategori Tindak Lanjut Sudah Digunakan';
        }
    }

    /* UPDATE DATA */
    if (!$error) {
        $stmt = $koneksi->prepare(
            "UPDATE k_tindak_lanjut_hasil_supervisi 
             SET kode_tindak_lanjut = ?, nama_tindak_lanjut = ? , deskripsi = ?
             WHERE kode_tindak_lanjut = ?"
        );
        $stmt->bind_param(
            "ssss",
            $kode_baru,
            $nama_tindak_lanjut,
            $kode_lama,
            $deskripsi
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
        <h2 class="text-center">Edit Kategori Tindak Lanjut</h2>

        <form method="post">
            <input type="hidden" name="kode_lama" value="<?= htmlspecialchars($data['kode_tindak_lanjut']) ?>">

            <div class="col-md">
                <label class="form-label">Kode Kategori Tindak Lanjut</label>
                <input type="text" class="form-control" name="kode_tindak_lanjut"
                    value="<?= htmlspecialchars($kode_baru) ?>" required>
            </div>

            <div class="col-md mt-2">
                <label class="form-label">Nama Kategori Tindak Lanjut</label>
                <input type="text" class="form-control" name="nama_tindak_lanjut"
                    value="<?= htmlspecialchars($nama_tindak_lanjut) ?>" required>
            </div>
            <div class="col-md mt-2">
                <label for="deskripsi" class="form-label" style="margin-bottom: -10px;">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi"
                    class="form-control"><?= htmlspecialchars($deskripsi) ?? '-' ?></textarea>
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
            title: "Proses Perubahan Data Kategori Tindak Lanjut",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "error",
                title: "<?= $error_message ?>",
                text: "Silahkan gunakan kode kategori Tindak Lanjut yang lain.",
                showConfirmButton: true,
                timer: 5000
            });
        });
    </script>
<?php endif; ?>

<?php if ($success): ?>
    <script>
        Swal.fire({
            title: "Proses Perubahan Data Kategori Tindak Lanjut",
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
                window.location.href = "index.php?page=kategori_tindak_lanjut_hasil_supervisi";
            });
        });
    </script>
<?php endif; ?>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>