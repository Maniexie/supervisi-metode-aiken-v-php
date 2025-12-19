<?php
require_once __DIR__ . '/../pages/layouts/header.php';
require_once __DIR__ . '/../koneksi.php';

$id_user = $_SESSION['id_user'];

$getProfil = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = '$id_user'");
$data = mysqli_fetch_assoc($getProfil);

$getKategoriJabatan = mysqli_query($koneksi, " SELECT * FROM k_jabatan "); // mengambil data jabatan dari tabel k_jabatan
$getKategoriGolongan = mysqli_query($koneksi, "SELECT * FROM k_golongan"); // mengambil data golongan dari tabel k_golongan


?>


<!-- Content -->
<section>
    <div class="container border p-4 mt-4">
        <h2 class="text-center">Profil (<span class="text-uppercase"><?= $_SESSION['role'] ?></span>)</h2>
        <!-- Form -->
        <form class="row g-2 needs-validation">
            <div class="col-md-6">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= $data['nip'] ?>" disabled>
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $data['username'] ?> "
                    disabled>
            </div>
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama'] ?>" disabled>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control disabled" id="password" name="password"
                    value="hackdongsayang❤️❤️❤️" disabled>
            </div>

            <div class="col-md-6">
                <label for="kode_golongan" class="form-label">Golongan</label>
                <select class="form-control" id="kode_golongan" name="kode_golongan" disabled>
                    <?php while ($golongan = mysqli_fetch_assoc($getKategoriGolongan)): ?>
                        <option value="<?= $golongan['kode_golongan'] ?>"
                            <?= ($golongan['kode_golongan'] == $data['kode_golongan']) ? 'selected' : '' ?>>
                            <?= $golongan['kode_golongan'] ?> (<?= $golongan['nama_golongan'] ?>)
                        </option>
                    <?php endwhile; ?>

                </select>
            </div>
            <div class="col-md-6">
                <label for="is_validator" class="form-label">Validator</label>
                <input type="text" class="form-control" id="is_validator" name="is_validator"
                    value="<?= $data['is_validator'] ?> " disabled>
            </div>
            <div class="col-md-6">
                <label for="kode_jabatan" class="form-label">Jabatan</label>
                <select class="form-control" id="kode_jabatan" name="kode_jabatan" disabled>
                    <?php while ($jabatan = mysqli_fetch_assoc($getKategoriJabatan)): ?>
                        <option value="<?= $jabatan['kode_jabatan'] ?>" <?= ($jabatan['kode_jabatan'] == $data['kode_jabatan']) ? 'selected' : '' ?>>
                            <?= $jabatan['nama_jabatan'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>


        </form>
    </div>
</section>


<?php
require_once __DIR__ . '/../pages/layouts/footer.php';
?>