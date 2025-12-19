<?php
require_once __DIR__ . '/../../pages/layouts/header.php';
require_once __DIR__ . '/../../koneksi.php';




$getKategoriJabatan = mysqli_query($koneksi, " SELECT * FROM k_jabatan "); // mengambil data jabatan dari tabel k_jabatan
$getKategoriGolongan = mysqli_query($koneksi, "SELECT * FROM k_golongan"); // mengambil data golongan dari tabel k_golongan
$getKategoriStatusPegawai = mysqli_query($koneksi, "SELECT * FROM k_status_pegawai"); // mengambil data status pegawai dari tabel k_status_pegawai
$getKategoriValidator = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'is_validator'"); // mengambil data validator dari tabel users

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

    if (
        empty($nip) ||
        empty($nama) ||
        empty($kode_golongan) ||
        empty($kode_jabatan) ||
        empty($kode_status_pegawai) ||
        empty($username) ||
        empty($_POST['password']) ||
        empty($role) ||
        empty($is_validator)
    ) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Form Tidak Lengkap",
            text: "Semua field wajib diisi!",
            showConfirmButton: true
        });
    </script>';
        return; // ⛔ STOP di sini
    }

    $stmt = $koneksi->prepare("INSERT INTO users (nip, nama, kode_golongan, kode_jabatan, kode_status_pegawai, username, password, role, is_validator) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $nip, $nama, $kode_golongan, $kode_jabatan, $kode_status_pegawai, $username, $password, $role, $is_validator);
    $stmt->execute();
    if ($stmt->error) {
        echo "Error updating record: " . $stmt->error;
    } else {
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
    $stmt->close();
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
                <input type="text" class="form-control" id="nip" name="nip" required autofocus>
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control disabled" id="password" name="password" required>
            </div>

            <div class="col-md-6">
                <label for="kode_golongan" class="form-label">Golongan</label>
                <select class="form-select" id="kode_golongan" name="kode_golongan" required>
                    <option selected disabled value="">==Golongan==</option>
                    <?php while ($golongan = mysqli_fetch_assoc($getKategoriGolongan)): ?>
                        <option value="<?= $golongan['kode_golongan'] ?>">
                            (<?= $golongan['kode_golongan'] ?>) <?= $golongan['nama_golongan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option selected disabled value="">==Role==</option>
                    <?php $role_item = ['kepala_sekolah', 'guru', 'operator']; ?>
                    <?php foreach ($role_item as $role): ?>
                        <option value="<?= $role ?>">
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
                        <option value="<?= $jabatan['kode_jabatan'] ?>">
                            <?= $jabatan['nama_jabatan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="is_validator" class="form-label">Validator</label>
                <select class="form-select" id="is_validator" name="is_validator" required>
                    <option selected disabled value="">==Validator==</option>
                    <?php $isValidator = ['Ya', 'Tidak']; ?>
                    <?php foreach ($isValidator as $validator): ?>
                        <option value="<?= $validator ?>">
                            <?= ucfirst($validator) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="kode_status_pegawai" class="form-label">Status Pegawai</label>
                <select class="form-select" id="kode_status_pegawai" name="kode_status_pegawai" required>
                    <option selected disabled value="">==status_pegawai==</option>
                    <?php while ($status_pegawai = mysqli_fetch_assoc($getKategoriStatusPegawai)): ?>
                        <option value="<?= $status_pegawai['kode_status_pegawai'] ?>">
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
</div>
</div>

<?php
require_once __DIR__ . '/../../pages/layouts/footer.php';
?>