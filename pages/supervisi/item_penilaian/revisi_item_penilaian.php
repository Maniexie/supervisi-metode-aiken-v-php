<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$getVersi = $_GET['versi'];
$getIdItemPenilaian = $_GET['id_item_penilaian'];

// ambil data item
$item = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT kode_item_penilaian, versi FROM item_penilaian
    WHERE id_item_penilaian='$getIdItemPenilaian'
"));

// cek versi terakhir
$cekVersi = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT MAX(versi) AS versi_terakhir
    FROM item_penilaian
    WHERE kode_item_penilaian='{$item['kode_item_penilaian']}'
"));

if ($cekVersi['versi_terakhir'] > $item['versi']) {
    die('Item ini sudah direvisi, tidak dapat direvisi ulang.');
}


$error = false;
$error_message = '';

$old_pernyataan = $_POST['pernyataan'] ?? '';

$getDataItemPenilaian = mysqli_query($koneksi, " SELECT * , nama_kategori_penilaian FROM item_penilaian JOIN k_penilaian ON item_penilaian.kode_kategori_penilaian = k_penilaian.kode_kategori_penilaian where id_item_penilaian = '$getIdItemPenilaian'"); // mengambil data item penilaian dari tabel item_penilaian
$dataItemPenilaian = mysqli_fetch_array($getDataItemPenilaian);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_item_penilaian = $_POST['kode_item_penilaian'];
    $kode_kategori_penilaian = $_POST['kode_kategori_penilaian'];
    $id_operator = $_SESSION['id_user'];
    $pernyataan = trim($_POST['pernyataan']);
    $status_item = $_POST['status_item'];
    $versi = $getVersi + 1;


    if ($error) {
        // Jika ada error, simpan nilai lama untuk ditampilkan kembali di form
        $old_pernyataan = $pernyataan;

    } else {
        // Jika tidak ada error, lanjutkan proses penyimpanan data
        $stmt = $koneksi->prepare("INSERT INTO item_penilaian (kode_item_penilaian, kode_kategori_penilaian, id_operator, pernyataan, versi, status_item) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisis", $kode_item_penilaian, $kode_kategori_penilaian, $id_operator, $pernyataan, $versi, $status_item);
        $stmt->execute();
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Revisi Item Penilaian",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "success",
            title: "Item Penilaian Berhasil di Revisi",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = `index.php?page=daftar_versi_hasil_uji_validitas`;
        });
    });
    </script>';
    }

} else if ($_SERVER['REQUEST_METHOD'] == null || $_SERVER['REQUEST_METHOD'] == '') {
    // Tampilkan form tambah aktor
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Item Penilaian",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "error",
            title: "Data Item Penilaian Gagal di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=daftar_hasil_uji_validitas&versi=";
        });
    });
    </script>';
}
?>

<!-- Content -->
<section>
    <div class="container border rounded p-4 mb-4 mt-2">
        <!-- start get data value -->
        <h2 class="text-center">Tambah Item Penilaian</h2>

        <!-- Form -->
        <form class="needs-validation" method="post">
            <input type="hidden" name="kode_item_penilaian" value="<?= $dataItemPenilaian['kode_item_penilaian'] ?>">
            <div class="col-md">
                <label for="kode_item_penilaian" class="form-label">Kode Item Penilaian</label>
                <input type="text" class="form-control" id="kode_item_penilaian" name="kode_item_penilaian"
                    value="<?= $dataItemPenilaian['kode_item_penilaian'] ?>" disabled>
            </div>

            <input type="hidden" name="kode_kategori_penilaian"
                value="<?= $dataItemPenilaian['kode_kategori_penilaian'] ?>">
            <div class="col-md">
                <label for="kode_kategori_penilaian" class="form-label">Kategori Penilaian</label>
                <input type="text" class="form-control" id="kode_kategori_penilaian" name="kode_kategori_penilaian"
                    value="<?= $dataItemPenilaian['nama_kategori_penilaian'] ?>" disabled>
            </div>

            <input type="hidden" name="id_operator" value="<?= $old_id_operator ?>">
            <div class="col-md mt-2">
                <label for="pernyataan" class="form-label" style="margin-bottom: -10px;">Pernyataan</label>
                <textarea type="textarea" class="form-control" id="pernyataan" name="pernyataan" value="" required
                    autofocus>
                    <?= htmlspecialchars($old_pernyataan) ?>
                </textarea>
            </div>
            <div class="col-md mt-2">
                <label for="versi" class="form-label" style="margin-bottom: -10px;">Versi</label>
                <input type="text" class="form-control" id="versi" name="versi" value="<?= $getVersi + 1 ?>">
            </div>

            <div class="col-md mt-2">
                <label for="status_item" class="form-label" style="margin-bottom: -10px;">Status</label>

                <?php
                $status_item = [
                    'aktif' => 'AKTIF',
                    'tidak_aktif' => 'TIDAK AKTIF'
                ];
                ?>

                <select class=" form-select" id="status_item" name="status_item" required>
                    <option selected disabled value="">== Status Item ==</option>
                    <?php foreach ($status_item as $value => $label): ?>
                        <option value="<?= $value ?>">
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
            title: "Proses Penambahan Data Item Penilaian",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "error",
                title: "<?= $error_message ?>",
                text: "Silahkan gunakan kode item penilaian yang lain.",
                showConfirmButton: true,
                timer: 3000
            });
        });
    </script>
<?php endif; ?>


<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>