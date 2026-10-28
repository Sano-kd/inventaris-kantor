<?php
require_once 'includes/auth.php';
require_once 'config/koneksi.php';

$daftar_kategori = require 'includes/kategori.php';

// Pencarian: berdasarkan kode atau nama barang
$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = mysqli_prepare($koneksi,
        "SELECT * FROM barang WHERE kode_barang LIKE ? OR nama_barang LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
} else {
    $hasil = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id DESC");
}

$total = mysqli_num_rows($hasil);

$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $lang['judul'] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="page">

    <div class="topbar">
        <h1><?= $lang['judul'] ?></h1>
        <div class="topbar-right">
            <div class="lang-switch">
                <a href="ganti_bahasa.php?lang=id" class="<?= $_SESSION['lang'] === 'id' ? 'aktif' : '' ?>">ID</a>
                <a href="ganti_bahasa.php?lang=en" class="<?= $_SESSION['lang'] === 'en' ? 'aktif' : '' ?>">EN</a>
            </div>
            <span class="user"><?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php"><?= $lang['logout'] ?></a>
        </div>
    </div>

    <?php if ($pesan): ?>
        <div class="alert-ok"><?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="toolbar">
            <form method="GET" class="search">
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>"
                       placeholder="<?= htmlspecialchars($lang['cari_placeholder']) ?>">
                <button type="submit" class="btn btn-ghost btn-sm"><?= $lang['cari'] ?></button>
                <?php if ($q !== ''): ?>
                    <a class="btn btn-ghost btn-sm" href="index.php"><?= $lang['reset'] ?></a>
                <?php endif; ?>
            </form>
            <span class="total"><?= $lang['total_barang'] ?>: <?= $total ?></span>
        </div>

        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><?= $lang['kode'] ?></th>
                        <th><?= $lang['nama'] ?></th>
                        <th><?= $lang['kategori'] ?></th>
                        <th class="num"><?= $lang['jumlah'] ?></th>
                        <th class="num"><?= $lang['harga'] ?></th>
                        <th class="aksi"><?= $lang['aksi'] ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($total > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($hasil)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($daftar_kategori[$row['kategori']][$_SESSION['lang']] ?? $row['kategori']) ?></td>
                        <td class="num"><?= (int)$row['jumlah'] ?></td>
                        <td class="num">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td class="aksi">
                            <a class="btn btn-sm btn-edit" href="edit.php?id=<?= (int)$row['id'] ?>"><?= $lang['edit'] ?></a>
                            <form method="POST" action="hapus.php" class="inline"
                                  onsubmit='return confirm(<?= json_encode($lang['konfirmasi'], JSON_HEX_APOS) ?>)'>
                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><?= $lang['hapus'] ?></button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="kosong"><?= $q !== '' ? $lang['tidak_ditemukan'] : $lang['kosong'] ?></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <a class="btn btn-primary" href="tambah.php">+ <?= $lang['tambah'] ?></a>
        </div>
    </div>

</div>
</body>
</html>