<?php
require_once __DIR__ . '/../../pages/layouts/header.php';
require_once __DIR__ . '/../../koneksi.php';


$getDataAktor = mysqli_query($koneksi, 'SELECT * FROM users');// mengambil data aktor dari tabel users)

$getKategoriJabatan = mysqli_query($koneksi, " SELECT * FROM k_jabatan "); // mengambil data jabatan dari tabel k_jabatan
$getKategoriGolongan = mysqli_query($koneksi, "SELECT * FROM k_golongan"); // mengambil data golongan dari tabel k_golongan
$getKategoriStatusPegawai = mysqli_query($koneksi, "SELECT * FROM k_status_pegawai"); // mengambil data status pegawai dari tabel k_status_pegawai
$getKategoriValidator = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'is_validator'"); // mengambil data validator dari tabel users



$old_nip = $_POST['nip'] ?? '';
$old_username = $_POST['username'] ?? '';
$old_password = $_POST['password'] ?? '';
$old_nama = $_POST['nama'] ?? '';
$old_kode_golongan = $_POST['kode_golongan'] ?? '';
$old_kode_jabatan = $_POST['kode_jabatan'] ?? '';
$old_kode_status_pegawai = $_POST['kode_status_pegawai'] ?? '';
$old_role = $_POST['role'] ?? '';
$old_is_validator = $_POST['is_validator'] ?? '';

$error = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_POST['nip'] ?? null;
    $nama = $_POST['nama'];
    $kode_golongan = $_POST['kode_golongan'];
    $kode_jabatan = $_POST['kode_jabatan'];
    $kode_status_pegawai = $_POST['kode_status_pegawai'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $is_validator = $_POST['is_validator'];

    $cekUsername = $koneksi->prepare(
        "SELECT 1 FROM users WHERE username = ?"
    );
    $cekUsername->bind_param("s", $username);
    $cekUsername->execute();
    $cekUsername->store_result();

    $cekNip = $koneksi->prepare(
        "SELECT 1 FROM users WHERE nip = ?"
    );
    $cekNip->bind_param("s", $nip);
    $cekNip->execute();
    $cekNip->store_result();

    if ($cekUsername->num_rows > 0) {
        $error = true;
        $error_message = 'Username Sudah Digunakan';
    }

    if ($cekNip->num_rows > 0) {
        $error = true;
        $error_message = 'NIP Sudah Digunakan';
    }

    // Validasi password
    if (!$error) {
        if (strlen($old_password) < 8) {
            $error = true;
            $error_message = 'Password minimal 8 karakter!';
        }
    }

    if ($error) {
        $old_nip = $nip;
        $old_username = $username;
        $old_password = $_POST['password'];
        $old_nama = $nama;
        $old_kode_golongan = $kode_golongan;
        $old_kode_jabatan = $kode_jabatan;
        $old_kode_status_pegawai = $kode_status_pegawai;
        $old_role = $role;
        $old_is_validator = $is_validator;
    } else {
        $stmt = $koneksi->prepare("INSERT INTO users (nip, nama, kode_golongan, kode_jabatan, kode_status_pegawai, username, password, role, is_validator) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nip, $nama, $kode_golongan, $kode_jabatan, $kode_status_pegawai, $username, $password, $role, $is_validator);
        $stmt->execute();
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Aktor",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "success",
            title: "Data Aktor Berhasil di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=daftar_aktor";
        });
    });
    </script>';

    }
} else if ($_SERVER['REQUEST_METHOD'] == null || $_SERVER['REQUEST_METHOD'] == '') {
    // Tampilkan form tambah aktor
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Penambahan Data Aktor",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        Swal.fire({
            icon: "error",
            title: "Data Aktor Gagal di Tambahkan",
            showConfirmButton: true,
            timer: 3000
        }).then(() => {
            window.location.href = "index.php?page=tambah_aktor";
        });
    });
    </script>';
}
?>

<!-- Content -->
<section>
    <div class="container">
        <!-- start get data value -->

        <!-- Form -->
        <form class="row g-2 needs-validation" method="post">
            <div class="col-md-6">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= $old_nip ?? '' ?>" required
                    autofocus>
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $old_username ?? '' ?>"
                    required autofocus>
            </div>
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $old_nama ?? '' ?>" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control disabled" id="password" name="password"
                    value="<?= $old_password ?? '' ?>" required>
            </div>

            <div class="col-md-6">
                <label for="kode_golongan" class="form-label">Golongan</label>
                <select class="form-select" id="kode_golongan" name="kode_golongan" required>
                    <option selected disabled value="">==Golongan==</option>
                    <?php while ($golongan = mysqli_fetch_assoc($getKategoriGolongan)): ?>
                        <option value="<?= $golongan['kode_golongan'] ?>"
                            <?= ($old_kode_golongan == $golongan['kode_golongan']) ? 'selected' : '' ?>>
                            (<?= $golongan['kode_golongan'] ?>) <?= $golongan['nama_golongan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <?php $role_item = ['kepala_sekolah', 'guru', 'operator']; ?>
                <select class="form-select" id="role" name="role" required>
                    <option selected disabled value="">==Role==</option>
                    <?php foreach ($role_item as $role): ?>
                        <option value="<?= $role ?>" <?= ($old_role == $role) ? 'selected' : '' ?>>
                            <?= ucfirst($role) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="col-md-6">
                <label for="kode_jabatan" class="form-label">Jabatan</label>
                <select class="form-select" id="kode_jabatan" name="kode_jabatan" required>
                    <option selected disabled value="">==Jabatan==</option>
                    <?php while ($jabatan = mysqli_fetch_assoc($getKategoriJabatan)): ?>
                        <option value="<?= $jabatan['kode_jabatan'] ?>" <?= ($old_kode_jabatan == $jabatan['kode_jabatan']) ? 'selected' : '' ?>>
                            <?= $jabatan['nama_jabatan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="col-md-6">
                <label for="is_validator" class="form-label">Validator</label>
                <?php $isValidator = ['Ya', 'Tidak']; ?>
                <select class="form-select" id="is_validator" name="is_validator" required>
                    <option selected disabled value="">==Validator==</option>
                    <?php foreach ($isValidator as $validator): ?>
                        <option value="<?= $validator ?>" <?= ($old_is_validator == $validator) ? 'selected' : '' ?>>
                            <?= ucfirst($validator) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="col-md-6">
                <label for="kode_status_pegawai" class="form-label">Status Pegawai</label>
                <select class="form-select" id="kode_status_pegawai" name="kode_status_pegawai" required>
                    <option selected disabled value="">==Status Pegawai==</option>
                    <?php while ($status_pegawai = mysqli_fetch_assoc($getKategoriStatusPegawai)): ?>
                        <option value="<?= $status_pegawai['kode_status_pegawai'] ?>"
                            <?= ($old_kode_status_pegawai == $status_pegawai['kode_status_pegawai']) ? 'selected' : '' ?>>
                            (<?= $status_pegawai['kode_status_pegawai'] ?>) <?= $status_pegawai['nama_status_pegawai'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
</section>


<?php if ($error): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Proses Penambahan Data Aktor",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                icon: "error",
                title: "<?= $error_message ?>",
                showConfirmButton: true,
                timer: 3000
            });
        });
    </script>
<?php endif; ?>

<?php
require_once __DIR__ . '/../../pages/layouts/footer.php';
?>