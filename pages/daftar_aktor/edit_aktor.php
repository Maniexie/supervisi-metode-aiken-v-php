<?php
require_once __DIR__ . '/../../pages/layouts/header.php';
require_once __DIR__ . '/../../koneksi.php';

$id_user = $_GET['id_user']; // mengambil id_user dari parameter URL dari halaman daftar_aktor.php

$getDataAktor = mysqli_query($koneksi, " SELECT * FROM users WHERE id_user = " . $id_user); // mengambil data aktor berdasarkan id_user di tabel users

// halaman menampilkan pesan jika data tidak ditemukan
if ($getDataAktor->num_rows == 0) {
    echo "<h3 class='text-center mt-5'>Data tidak ditemukan</h3>";
    exit;
}

$getKategoriJabatan = mysqli_query($koneksi, " SELECT * FROM k_jabatan "); // mengambil data jabatan dari tabel k_jabatan
$getKategoriGolongan = mysqli_query($koneksi,"SELECT * FROM k_golongan"); // mengambil data golongan dari tabel k_golongan
$getKategoriValidator = mysqli_query($koneksi,"SHOW COLUMNS FROM users LIKE 'is_validator'"); // mengambil data validator dari tabel users

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_user'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $kode_golongan = $_POST['kode_golongan'];
    $kode_jabatan = $_POST['kode_jabatan'];
    $is_validator = $_POST['is_validator'];
    
    $stmt = $koneksi->prepare("UPDATE users SET 
        nip = ?, 
        nama = ?, 
        kode_golongan = ?, 
        kode_jabatan = ?, 
        is_validator = ?
        WHERE id_user = ?");
    $stmt->bind_param("sssssi", $nip, $nama, $kode_golongan, $kode_jabatan, $is_validator, $id);
    $stmt->execute();
 if ($stmt->error) {
    echo "Error updating record: " . $stmt->error;
} else {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "Proses Perubahan Data",
        timer: 1500,
        didOpen: () => {
            Swal.showLoading();
        }
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

?>

<!-- Cards/Content -->
<section class="cards">
    <div class="card">edit data aktor id: <?= $id_user ?></div>
</section>
<section>
    <div class="container">
        <!-- start get data value -->
        <?php if ($getDataAktor->num_rows > 0): ?>
            <?php $data = mysqli_fetch_assoc($getDataAktor); ?>
            <!-- Form -->
            <form class="row g-2 needs-validation" novalidate method="post">
                <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
                <div class="col-md-6">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="<?= $data['nip'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= $data['username'] ?> "
                        disabled>
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control disabled" id="password" name="password"
                        value="<?= $data['password'] ?>" disabled>
                </div>

                <div class="col-md-6">
                    <label for="kode_golongan" class="form-label">Golongan</label>
                    <select class="form-select" id="kode_golongan" name="kode_golongan" required>
                        <?php while($golongan = mysqli_fetch_assoc($getKategoriGolongan)): ?>
                            <option value="<?= $golongan['kode_golongan'] ?>"
                            <?= ($golongan['kode_golongan'] == $data['kode_golongan']) ? 'selected' : '' ?>
                            >
                              <?= $golongan['kode_golongan'] ?> (<?= $golongan['nama_golongan'] ?>)
                            </option>
                        <?php endwhile; ?>
 
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="is_validator" class="form-label">Validator</label>
                    <select class="form-select" id="is_validator" name="is_validator" required>
                 <?php $isValidator = ['Ya' , 'Tidak']; ?>
                        <?php foreach ($isValidator as $validator): ?>
                            <option value="<?= $validator ?>"
                            <?= ($validator == $data['is_validator']) ? 'selected' : '' ?>
                            >
                                <?= ucfirst($validator) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="kode_jabatan" class="form-label">Jabatan</label>
                    <select class="form-select" id="kode_jabatan" name="kode_jabatan" required>
                        <?php while ($jabatan = mysqli_fetch_assoc($getKategoriJabatan)): ?>
                            <option value="<?= $jabatan['kode_jabatan'] ?>" 
                            <?= ($jabatan['kode_jabatan'] == $data['kode_jabatan']) ? 'selected' : '' ?>
                            >
                                <?= $jabatan['nama_jabatan'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit" name="submit">Submit form</button>
                </div>
            </form>
        <?php endif ?>
    </div>
</section>
</div>
</div>

<?php
require_once __DIR__ . '/../../pages/layouts/footer.php';
?>