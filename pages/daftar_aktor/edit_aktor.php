<?php
require_once __DIR__ . '/../../pages/layouts/header.php';
require_once __DIR__ . '/../../koneksi.php';

$id_user = $_GET['id_user'] ?? null;
if (!$id_user) {
    echo "<h3 class='text-center mt-5'>ID User tidak valid</h3>";
    exit;
}

// Ambil data aktor
$getDataAktor = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user");
if ($getDataAktor->num_rows == 0) {
    echo "<h3 class='text-center mt-5'>Data tidak ditemukan</h3>";
    exit;
}
$data = mysqli_fetch_assoc($getDataAktor);

// Ambil kategori
$getKategoriJabatan = mysqli_query($koneksi, "SELECT * FROM k_jabatan");
$getKategoriGolongan = mysqli_query($koneksi, "SELECT * FROM k_golongan");

// Old value (untuk kembali ditampilkan jika ada error)
$old_nip = $data['nip'];
$old_nama = $data['nama'];
$old_kode_golongan = $data['kode_golongan'];
$old_kode_jabatan = $data['kode_jabatan'];
$old_is_validator = $data['is_validator'];
$old_password = $data['password'];
$old_role = $data['role'];
$old_status_pegawai = $data['kode_status_pegawai'];

$error = false;
$error_message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $status_pegawai = $_POST['status_pegawai'] ?? '';
    $kode_golongan = $_POST['kode_golongan'] ?? '';
    $kode_jabatan = $_POST['kode_jabatan'] ?? '';
    $is_validator = $_POST['is_validator'] ?? '';

    // Validasi NIP duplicate, kecuali untuk data ini
    if (!$error) {
        $cekNip = $koneksi->prepare("SELECT 1 FROM users WHERE nip = ? AND id_user != ?");
        $cekNip->bind_param("si", $nip, $id_user);
        $cekNip->execute();
        $cekNip->store_result();

        if ($cekNip->num_rows > 0) {
            $error = true;
            $error_message = "NIP sudah digunakan oleh user lain!";
        }
    }

    // Validasi username duplicate, kecuali untuk data ini
    if (!$error) {
        $cekUsername = $koneksi->prepare(
            "SELECT 1 FROM users WHERE username = ? AND id_user != ?"
        );
        $cekUsername->bind_param("si", $username, $id_user);
        $cekUsername->execute();
        $cekUsername->store_result();

        if ($cekUsername->num_rows > 0) {
            $error = true;
            $error_message = 'Username Sudah Digunakan oleh User Lain!';
        }
    }

    // Validasi password
    if ($error) {
        if (strlen($old_password) < 8) {
            $error = true;
            $error_message = 'Password minimal 8 karakter!';
        }
    }

    if ($error) {
        // Simpan old value agar tetap ditampilkan di form
        $old_nip = $nip;
        $old_nama = $nama;
        $old_kode_golongan = $kode_golongan;
        $old_kode_jabatan = $kode_jabatan;
        $old_is_validator = $is_validator;
        $old_password = password_hash($password, PASSWORD_DEFAULT);
        $old_role = $role;
        $old_status_pegawai = $status_pegawai;
    } else {
        // Update data
        $stmt = $koneksi->prepare(
            "UPDATE users 
             SET nip = ?, nama = ?, kode_golongan = ?, kode_jabatan = ?, is_validator = ?, password = ?, role = ?, kode_status_pegawai = ?
             WHERE id_user = ?"
        );
        $stmt->bind_param(
            "ssssssssi",
            $nip,
            $nama,
            $kode_golongan,
            $kode_jabatan,
            $is_validator,
            $password,
            $role,
            $status_pegawai,
            $id_user
        );
        $stmt->execute();

        if ($stmt->error) {
            echo "Error updating record: " . $stmt->error;
        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                title: "Proses Perubahan Data",
                timer: 1500,
                didOpen: () => { Swal.showLoading(); }
            }).then(() => {
                Swal.fire({
                    icon: "success",
                    title: "Data berhasil disimpan",
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    window.location.href = "index.php?page=daftar_aktor";
                });
            });
            </script>';
        }
        $stmt->close();
    }
}
?>

<section class="cards">
    <div class="card">Edit Data Aktor ID: <?= $id_user ?></div>
</section>

<section>
    <div class="container">
        <form class="row g-2 needs-validation" method="post">
            <input type="hidden" name="id_user" value="<?= $id_user ?>">

            <div class="col-md-6">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= htmlspecialchars($old_nip) ?>"
                    required>
            </div>

            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= htmlspecialchars($data['username']) ?>" disabled>
            </div>

            <div class="col-md-6">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($old_nama) ?>"
                    required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control disabled" id="password" name="password"
                    value="<?= htmlspecialchars($old_password) ?>" required>
            </div>

            <div class="col-md-6">
                <label for="kode_golongan" class="form-label">Golongan</label>
                <select class="form-select" id="kode_golongan" name="kode_golongan" required>
                    <?php while ($golongan = mysqli_fetch_assoc($getKategoriGolongan)): ?>
                        <option value="<?= $golongan['kode_golongan'] ?>"
                            <?= ($golongan['kode_golongan'] == $old_kode_golongan) ? 'selected' : '' ?>>
                            <?= $golongan['kode_golongan'] ?> (<?= $golongan['nama_golongan'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <?php $role_item = ['kepala_sekolah', 'guru', 'operator']; ?>
                <select class="form-select" id="role" name="role" required>
                    <?php foreach ($role_item as $role): ?>
                        <option value="<?= $role ?>" <?= ($role == $old_role) ? 'selected' : '' ?>>
                            <?= ucfirst($role) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="kode_jabatan" class="form-label">Jabatan</label>
                <select class="form-select" id="kode_jabatan" name="kode_jabatan" required>
                    <?php while ($jabatan = mysqli_fetch_assoc($getKategoriJabatan)): ?>
                        <option value="<?= $jabatan['kode_jabatan'] ?>" <?= ($jabatan['kode_jabatan'] == $old_kode_jabatan) ? 'selected' : '' ?>>
                            <?= $jabatan['nama_jabatan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="is_validator" class="form-label">Validator</label>
                <?php $isValidator = ['Ya', 'Tidak']; ?>
                <select class="form-select" id="is_validator" name="is_validator" required>
                    <?php foreach ($isValidator as $validator): ?>
                        <option value="<?= $validator ?>" <?= ($validator == $old_is_validator) ? 'selected' : '' ?>>
                            <?= $validator ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="status_pegawai" class="form-label">Status Pegawai</label>
                <?php
                $getKategoriStatusPegawai = mysqli_query($koneksi, "SELECT * FROM k_status_pegawai");
                ?>
                <select class="form-select" id="status_pegawai" name="status_pegawai" required>
                    <?php while ($status_pegawai = mysqli_fetch_assoc($getKategoriStatusPegawai)): ?>
                        <option value="<?= $status_pegawai['kode_status_pegawai'] ?>"
                            <?= ($status_pegawai['kode_status_pegawai'] == $old_status_pegawai) ? 'selected' : '' ?>>
                            <?= $status_pegawai['kode_status_pegawai'] ?> (<?= $status_pegawai['nama_status_pegawai'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-12 mt-3">
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</section>

<?php if ($error): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Proses Perubahan Data Kategori Penilaian",
            timer: 1500,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            Swal.fire({
                title: "Error",
                icon: "error",
                text: "<?= $error_message ?>",
                showConfirmButton: true,
                timer: 5000
            });
        });
    </script>
<?php endif; ?>

<?php
require_once __DIR__ . '/../../pages/layouts/footer.php';
?>