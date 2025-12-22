<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$old_kode_kategori_penilaian = $_POST['kode_kategori_penilaian'] ?? '';
$old_nama_kategori_penilaian = $_POST['nama_kategori_penilaian'] ?? '';
$old_status_item = $_POST['status_item'] ?? '';
$old_pernyataan = $_POST['pernyataan'] ?? '';
$old_kode_item_penilaian = $_POST['kode_item_penilaian'] ?? '';
$old_id_operator = $_SESSION['id_operator'] ?? '';
$old_versi = $_POST['versi'] ?? '';

$error = false;
$error_message = '';


$getDataItemPenilaian = mysqli_query($koneksi, " SELECT * FROM item_penilaian "); // mengambil data item penilaian dari tabel item_penilaian
$getDataKategoriPenilaian = mysqli_query($koneksi, " SELECT * FROM k_penilaian "); // mengambil data kategori penilaian dari tabel k_penilaian


$getKodeItemPenilaian = mysqli_query($koneksi, "SELECT kode_item_penilaian FROM item_penilaian WHERE kode_kategori_penilaian = '$old_kode_kategori_penilaian' ORDER BY kode_item_penilaian DESC LIMIT 1");
$dataKodeItemPenilaian = mysqli_fetch_assoc($getKodeItemPenilaian);

if ($dataKodeItemPenilaian) {
    // Ambil kode item penilaian terakhir
    $last_kode_item_penilaian = $dataKodeItemPenilaian['kode_item_penilaian'];

    // Ambil nomor urut dari kode item penilaian (misalnya KP-01-001 -> 001)
    $last_id = (int) substr($last_kode_item_penilaian, 7, 3) + 1;  // Ambil 3 digit terakhir dan tambahkan 1

    // Format kode baru dengan menambahkan angka urut yang sudah diincrement
    $last_id = $old_kode_kategori_penilaian . '-' . str_pad($last_id, 3, '0', STR_PAD_LEFT);

    echo "Kode Item Penilaian Baru: " . $last_id;
} else {
    // Jika tidak ada data ditemukan, buat kode item penilaian baru dengan urutan 001
    $last_id = $old_kode_kategori_penilaian . '-001';
    echo "Kode Item Penilaian Baru: " . $last_id;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_item_penilaian = $_POST['kode_item_penilaian'];
    $kode_kategori_penilaian = $_POST['kode_kategori_penilaian'];
    $id_operator = $_SESSION['id_user'];
    $pernyataan = trim($_POST['pernyataan']);
    $status_item = $_POST['status_item'];
    $versi = 1; // Set versi default ke 1 saat menambah item penilaian


    $cekDataItemPenilaian = $koneksi->prepare(
        "SELECT 1 FROM item_penilaian WHERE kode_item_penilaian = ?"
    );
    $cekDataItemPenilaian->bind_param("s", $kode_item_penilaian);
    $cekDataItemPenilaian->execute();
    $cekDataItemPenilaian->store_result();

    if ($cekDataItemPenilaian->num_rows > 0) {
        $error = true;
        $error_message = "Kode Item Penilaian sudah digunakan.";
    }
    if ($error) {
        // Jika ada error, simpan nilai lama untuk ditampilkan kembali di form
        $old_kode_kategori_penilaian = $kode_kategori_penilaian;
        $old_pernyataan = $pernyataan;
        $old_status_item = $status_item;
        $old_versi = $versi;
        $old_last_id = $last_id;
        $old_id_operator = $id_operator;
    } else {
        // Jika tidak ada error, lanjutkan proses penyimpanan data
        $stmt = $koneksi->prepare("INSERT INTO item_penilaian (kode_item_penilaian, kode_kategori_penilaian, id_operator, pernyataan, status_item, versi) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $last_id, $kode_kategori_penilaian, $id_operator, $pernyataan, $status_item, $versi);
        $stmt->execute();
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
            icon: "success",
            title: "Data Item Penilaian Berhasil di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=item_penilaian";
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
            window.location.href = "index.php?page=tambah_item_penilaian";
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
            <input type="hidden" name="kode_item_penilaian" value="<?= $last_id ?>">
            <div class="col-md">
                <label for="kode_kategori_penilaian" class="form-label">Kategori Penilaian</label>
                <select class="form-select" id="kode_kategori_penilaian" name="kode_kategori_penilaian" required>
                    <option selected disabled value="">==Kategori Penilaian==</option>
                    <?php while ($namaKategoriPenilaian = mysqli_fetch_assoc($getDataKategoriPenilaian)): ?>
                        <option value="<?= $namaKategoriPenilaian['kode_kategori_penilaian'] ?>"
                            <?= ($old_kode_kategori_penilaian == $namaKategoriPenilaian['kode_kategori_penilaian']) ? 'selected' : '' ?>>
                            (<?= $namaKategoriPenilaian['kode_kategori_penilaian'] ?>)
                            <?= $namaKategoriPenilaian['nama_kategori_penilaian'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="hidden" name="id_operator" value="<?= $old_id_operator ?>">
            <div class="col-md mt-2">
                <label for="pernyataan" class="form-label" style="margin-bottom: -10px;">Pernyataan</label>
                <textarea type="textarea" class="form-control" id="pernyataan" name="pernyataan" required autofocus>
                    <?= htmlspecialchars($old_pernyataan) ?>
                </textarea>
            </div>
            <div class="col-md mt-2">
                <label for="versi" class="form-label" style="margin-bottom: -10px;">Versi</label>
                <input type="text" class="form-control" id="versi" name="versi" value="1" disabled>
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
                        <option value="<?= $value ?>" <?= ($old_status_item == $value) ? 'selected' : '' ?>>
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