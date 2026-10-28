<?php
require_once 'includes/auth.php';
require_once 'config/koneksi.php';

$daftar_kategori = require 'includes/kategori.php';

$errors = [];
$kode = $nama = $kategori = $jumlah = $harga = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. SANITASI: trim di sini, htmlspecialchars() saat data ditampilkan
    $kode     = trim($_POST['kode_barang'] ?? '');
    $nama     = trim($_POST['nama_barang'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');        
    $jumlah   = trim($_POST['jumlah'] ?? '');
    $harga    = trim($_POST['harga'] ?? '');               // tampilan, mis. "230.000"
    $harga_bersih = str_replace('.', '', $harga);          // angka murni, "230000"

    // 2. VALIDASI
    if ($kode === '') {
        $errors['kode'] = $lang['kode'] . ' ' . $lang['wajib_isi'];
    } elseif (mb_strlen($kode) > 20) {
        $errors['kode'] = $lang['kode'] . ' ' . sprintf($lang['maks_karakter'], 20);
    }

    if ($nama === '') {
        $errors['nama'] = $lang['nama'] . ' ' . $lang['wajib_isi'];
    } elseif (mb_strlen($nama) > 100) {
        $errors['nama'] = $lang['nama'] . ' ' . sprintf($lang['maks_karakter'], 100);
    }

    if ($kategori === '') {
        $errors['kategori'] = $lang['kategori'] . ' ' . $lang['wajib_isi'];
    } elseif (!array_key_exists($kategori, $daftar_kategori)) {
        $errors['kategori'] = $lang['kategori_valid'];
    }

    if ($jumlah === '') {
        $errors['jumlah'] = $lang['jumlah'] . ' ' . $lang['wajib_isi'];
    } elseif (filter_var($jumlah, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 2147483647]]) === false) {
        $errors['jumlah'] = $lang['jumlah'] . ' ' . $lang['angka_valid'];
    }

    if ($harga === '') {
        $errors['harga'] = $lang['harga'] . ' ' . $lang['wajib_isi'];
    } elseif (!ctype_digit($harga_bersih)) {
        $errors['harga'] = $lang['harga'] . ' ' . $lang['angka_valid'];
    } elseif (strlen($harga_bersih) > 10) {
        $errors['harga'] = $lang['harga'] . ' ' . $lang['terlalu_besar'];
    }

    // 3. Cek kode duplikat
    if (empty($errors)) {
        $cek = mysqli_prepare($koneksi, "SELECT id FROM barang WHERE kode_barang = ?");
        mysqli_stmt_bind_param($cek, "s", $kode);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) {
            $errors['kode'] = $lang['kode_ada'];
        }
    }

    // 4. SIMPAN jika tidak ada error
    if (empty($errors)) {
        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga) VALUES (?, ?, ?, ?, ?)");
        $j = (int)$jumlah;
        $h = (float)$harga_bersih;
        mysqli_stmt_bind_param($stmt, "sssid", $kode, $nama, $kategori, $j, $h);
        mysqli_stmt_execute($stmt);

        $_SESSION['pesan'] = $lang['sukses_tambah'];
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $lang['tambah'] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="center-wrap">
    <div class="card form-card">
        <div class="form-head">
            <h1><?= $lang['tambah'] ?></h1>
            <div class="lang-switch">
                <a href="ganti_bahasa.php?lang=id" class="<?= $_SESSION['lang'] === 'id' ? 'aktif' : '' ?>">ID</a>
                <a href="ganti_bahasa.php?lang=en" class="<?= $_SESSION['lang'] === 'en' ? 'aktif' : '' ?>">EN</a>
            </div>
        </div>

        <form method="POST">
            <div class="field">
                <label for="kode_barang"><?= $lang['kode'] ?></label>
                <input type="text" id="kode_barang" name="kode_barang"
                       class="<?= isset($errors['kode']) ? 'invalid' : '' ?>"
                       value="<?= htmlspecialchars($kode) ?>">
                <span class="err"><?= $errors['kode'] ?? '' ?></span>
            </div>

            <div class="field">
                <label for="nama_barang"><?= $lang['nama'] ?></label>
                <input type="text" id="nama_barang" name="nama_barang"
                       class="<?= isset($errors['nama']) ? 'invalid' : '' ?>"
                       value="<?= htmlspecialchars($nama) ?>">
                <span class="err"><?= $errors['nama'] ?? '' ?></span>
            </div>

            <div class="field">
                <label for="kategori"><?= $lang['kategori'] ?></label>
                <select id="kategori" name="kategori" class="<?= isset($errors['kategori']) ? 'invalid' : '' ?>">
                    <option value=""><?= $lang['pilih_kategori'] ?></option>
                    <?php foreach ($daftar_kategori as $kunci => $label): ?>
                        <option value="<?= $kunci ?>" <?= $kategori === $kunci ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label[$_SESSION['lang']] . ' (' . $label['contoh_' . $_SESSION['lang']] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="err"><?= $errors['kategori'] ?? '' ?></span>
            </div>

            <div class="row">
                <div class="field">
                    <label for="jumlah"><?= $lang['jumlah'] ?></label>
                    <input type="text" id="jumlah" name="jumlah" inputmode="numeric"
                           class="<?= isset($errors['jumlah']) ? 'invalid' : '' ?>"
                           value="<?= htmlspecialchars($jumlah) ?>">
                    <span class="err"><?= $errors['jumlah'] ?? '' ?></span>
                </div>

                <div class="field">
                    <label for="harga"><?= $lang['harga'] ?></label>
                    <div class="rupiah <?= isset($errors['harga']) ? 'invalid' : '' ?>">
                        <span>Rp</span>
                        <input type="text" name="harga" id="harga" inputmode="numeric"
                               value="<?= htmlspecialchars($harga) ?>">
                    </div>
                    <span class="err"><?= $errors['harga'] ?? '' ?></span>
                </div>
            </div>

            <div class="form-actions">
                <a class="btn btn-ghost" href="index.php"><?= $lang['batal'] ?></a>
                <button type="submit" class="btn btn-primary"><?= $lang['simpan'] ?></button>
            </div>
        </form>
    </div>
</div>

<script>
const hargaInput = document.getElementById('harga');

function formatRupiah(nilai) {
    const angka = nilai.replace(/\D/g, '');                   // buang semua selain angka
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');       // sisipkan titik tiap 3 digit
}

hargaInput.addEventListener('input', function () {
    this.value = formatRupiah(this.value);
});

if (hargaInput.value) hargaInput.value = formatRupiah(hargaInput.value);
</script>
</body>
</html>