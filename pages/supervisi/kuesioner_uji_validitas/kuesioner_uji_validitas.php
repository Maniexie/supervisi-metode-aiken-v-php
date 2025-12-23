<?php
require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_validator = $_SESSION['id_user'];
$versi = $_GET['versi'];

$queryItemPenilaian = ("SELECT * , nama_kategori_penilaian FROM item_penilaian 
JOIN k_penilaian ON item_penilaian.kode_kategori_penilaian= k_penilaian.kode_kategori_penilaian WHERE versi = '$versi' AND status_item = 'aktif' ORDER BY  kode_item_penilaian ASC");

$getItemPenilaian = mysqli_query($koneksi, $queryItemPenilaian);
$currentKategori = '';

// Query untuk memeriksa apakah ada status_item == 'tidak_aktif' pada versi yang didapatkan dari URL $_GET['versi'];
$getStatusItemPenilaian = mysqli_query($koneksi, "
    SELECT status_item 
    FROM item_penilaian
    WHERE versi = '$versi' AND status_item = 'tidak_aktif'
");

// Cek apakah ada hasil dari query
if (mysqli_num_rows($getStatusItemPenilaian) > 0) {
    // Jika ada status_item yang 'tidak_aktif'
    echo "<script>
        alert('Kuesioner Uji Validitas Belum Bisa Dimulai atau Ada item yang Tidak Aktif.');
        window.location.href = 'index.php?page=daftar_versi_kuesioner_uji_validitas';
    </script>";
    exit(); // Stop eksekusi lebih lanjut
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($getItemPenilaian as $item) {
        $id_item_penilaian = $item['id_item_penilaian'];
        $tanggal_pengisian = date('d-m-Y');

        if (!isset($_POST['jawaban' . $id_item_penilaian])) {
            continue; // belum dijawab
        }

        $jawaban = $_POST['jawaban' . $id_item_penilaian];

        if ($jawaban == 'sts') {
            $jawaban = 1;
        } elseif ($jawaban == 'ts') {
            $jawaban = 2;
        } elseif ($jawaban == 'n') {
            $jawaban = 3;
        } elseif ($jawaban == 's') {
            $jawaban = 4;
        } elseif ($jawaban == 'ss') {
            $jawaban = 5;
        }
        mysqli_query($koneksi, "INSERT INTO jawaban_validator (  id_item_penilaian, id_validator, jawaban, versi , tanggal_pengisian) VALUES (  '$id_item_penilaian','$id_validator', '$jawaban', '$versi', '$tanggal_pengisian')");


    }

    echo "<script>
            alert('Kuesioner berhasil disubmit.');
            window.location.href = 'index.php?page=daftar_versi_kuesioner_uji_validitas';
          </script>";
}


?>

<!-- Content -->
<section>
    <h2 class="mt-4 text-center">Kuesioner Uji Validitas</h2>
    <div class="container border p-4 mt-4 mb-4">
        <div class="border border-primary p-3 scrollable" style="max-height: 500px; overflow-y: auto;">
            <form action="" method="post" id="formUjiValiditas">
                <?php $no = 1;
                foreach ($getItemPenilaian as $item): ?>
                    <div class="row">
                        <?php if ($currentKategori != $item['kode_kategori_penilaian']): ?>
                            <div class="col-md-9  text-center border-top  border-bottom border-end p-2">
                                <span class=" font-semibold">Pernyataan</span>
                            </div>
                            <div class="col-md-3 text-center border-top border-bottom border-start p-2">
                                <?= $item['nama_kategori_penilaian']; ?>
                            </div>
                            <?php $currentKategori = $item['kode_kategori_penilaian']; ?>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <div class="">
                            <span class="font-semibold"> <?php echo $no++; ?>. <?= $item['pernyataan'] ?></span>
                            <br>
                            <input type="radio" id="sts" name="jawaban<?= $item['id_item_penilaian'] ?>" value="sts">
                            <label for="sts">Sangat Tidak Setuju</label><br>
                            <input type="radio" id="ts" name="jawaban<?= $item['id_item_penilaian'] ?>" value="ts">
                            <label for="ts">Tidak Setuju</label><br>
                            <input type="radio" id="n" name="jawaban<?= $item['id_item_penilaian'] ?>" value="n" required>
                            <label for="n">Netral</label><br>
                            <input type="radio" id="s" name="jawaban<?= $item['id_item_penilaian'] ?>" value="s">
                            <label for="s">Setuju</label><br>
                            <input type="radio" id="ss" name="jawaban<?= $item['id_item_penilaian'] ?>" value="ss">
                            <label for="ss">Sangat Setuju</label><br>
                        </div>
                    </div>
                <?php endforeach ?>
        </div>
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
        </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("formUjiValiditas");
        const submitBtn = document.getElementById("btnSubmit");
        const radioGroups = {};

        // Kumpulkan radio berdasarkan name
        document.querySelectorAll("input[type=radio]").forEach(radio => {
            if (!radioGroups[radio.name]) {
                radioGroups[radio.name] = [];
            }
            radioGroups[radio.name].push(radio);

            radio.addEventListener("change", checkForm);
        });

        function checkForm() {
            let semuaTerisi = true;

            for (let group in radioGroups) {
                const terisi = radioGroups[group].some(r => r.checked);
                if (!terisi) {
                    semuaTerisi = false;
                    break;
                }
            }

            submitBtn.disabled = !semuaTerisi;
        }

        form.addEventListener("submit", function (e) {
            let semuaTerisi = true;

            for (let group in radioGroups) {
                const terisi = radioGroups[group].some(r => r.checked);
                if (!terisi) {
                    semuaTerisi = false;
                    break;
                }
            }

            if (!semuaTerisi) {
                e.preventDefault();
                alert("⚠️ Semua pertanyaan wajib diisi sebelum submit!");
            }
        });
    });
</script>

<?php
require_once __DIR__ . '/../../../pages/layouts/footer.php';
?>