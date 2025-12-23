<?php

require_once __DIR__ . '/../../../pages/layouts/header.php';
require_once __DIR__ . '/../../../koneksi.php';

$id_guru = $_GET['id_guru'];
$id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];

$getItemPenilaian = mysqli_query($koneksi, "SELECT ip.*, kp.nama_kategori_penilaian
FROM item_penilaian ip
JOIN (
    SELECT kode_item_penilaian, MAX(versi) AS versi_terakhir
    FROM item_penilaian
    GROUP BY kode_item_penilaian
) v ON ip.kode_item_penilaian = v.kode_item_penilaian
   AND ip.versi = v.versi_terakhir
JOIN k_penilaian kp
  ON ip.kode_kategori_penilaian = kp.kode_kategori_penilaian
WHERE ip.status_item = 'aktif'
ORDER BY ip.kode_item_penilaian ASC  ;
"); // mengambil data item penilaian dari tabel item_penilaian

$currentKategori = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jawabanKosong = [];

    foreach ($getItemPenilaian as $item) {
        $key = 'jawaban' . $item['id_item_penilaian'];
        if (!isset($_POST[$key]) || $_POST[$key] == '') {
            $jawabanKosong[] = $item['id_item_penilaian'];
        }
    }

    // Jika ada yang kosong → hentikan proses
    if (!empty($jawabanKosong)) {
        echo "<script>
            alert('Masih ada kuesioner yang belum diisi!');
            window.history.back();
        </script>";
        exit;
    }

    // Jika semua terisi → simpan
    foreach ($getItemPenilaian as $item) {

        $id_jadwal_supervisi = $_GET['id_jadwal_supervisi'];
        $id_item_penilaian = $item['id_item_penilaian'];
        $id_kepala_sekolah = $_SESSION['id_user'];
        $id_guru = $_GET['id_guru'];
        $tanggal_pengisian = date('Y-m-d');

        $jawaban = $_POST['jawaban' . $id_item_penilaian];

        $mapping = [
            'sts' => 1,
            'ts' => 2,
            'n' => 3,
            's' => 4,
            'ss' => 5
        ];

        $jawaban = $mapping[$jawaban];

        mysqli_query(
            $koneksi,
            "INSERT INTO jawaban_supervisi 
            (id_jadwal_supervisi, id_item_penilaian, id_kepala_sekolah, id_guru, jawaban, tanggal_pengisian)
            VALUES 
            ('$id_jadwal_supervisi','$id_item_penilaian','$id_kepala_sekolah','$id_guru','$jawaban','$tanggal_pengisian')"
        );
    }

    echo "<script>
        alert('Kuesioner berhasil disubmit.');
        window.location.href = 'index.php?page=daftar_mulai_supervisi';
    </script>";
}




?>

<!-- Content -->
<section>
    <div class="container border mt-4">
        <div class="row g-0">
            <h2 class=" text-center">Mulai Supervisi</h2>
            <div class="col-md-6 ">
                <?php
                $getGuru = mysqli_query($koneksi, "SELECT *,nama_jabatan FROM users JOIN k_jabatan ON users.kode_jabatan = k_jabatan.kode_jabatan WHERE id_user = '$id_guru'");
                $guru = mysqli_fetch_array($getGuru);
                ?>
                <h5 class="">Nama: <?= $guru['nama'] ?></h5>
                <h5 class="">Jabatan : <?= $guru['nama_jabatan'] ?></h5>
                </h5>
            </div>
            <div class=" p-4 scrollable" style="max-height: 500px; overflow-y: auto;">
                <form action="" method="post" id="formSupervisi">
                    <?php $no = 1;
                    foreach ($getItemPenilaian as $item): ?>
                        <div class="row">
                            <?php if ($currentKategori != $item['kode_kategori_penilaian']): ?>
                                <div class="col-md-9 text-center border-top border-bottom border-end p-2">
                                    <span><b>ASPEK : <?= $item['nama_kategori_penilaian']; ?></b></span>
                                </div>
                                <div class="col-md-3 text-center border-top border-bottom border-start p-2">
                                    <b>BOBOT</b>
                                </div>
                                <?php $currentKategori = $item['kode_kategori_penilaian']; ?>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-9  border-end border-bottom p-2">
                                <span class="font-semibold"><?= $no++; ?>. <?= $item['pernyataan'] ?></span>
                            </div>
                            <div class="col-lg-3 border-start border-bottom px-2 py-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <input type="radio" id="sts" name="jawaban<?= $item['id_item_penilaian'] ?>"
                                            value="sts">
                                        <label for="sts">1</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="ts" name="jawaban<?= $item['id_item_penilaian'] ?>"
                                            value="ts">
                                        <label for="ts">2</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="n" name="jawaban<?= $item['id_item_penilaian'] ?>" value="n"
                                            required>
                                        <label for="n">3</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="s" name="jawaban<?= $item['id_item_penilaian'] ?>"
                                            value="s">
                                        <label for="s">4</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="ss" name="jawaban<?= $item['id_item_penilaian'] ?>"
                                            value="ss">
                                        <label for="ss">5</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
</section>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("formSupervisi");
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
