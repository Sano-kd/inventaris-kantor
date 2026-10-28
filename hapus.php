<?php
require_once 'includes/auth.php';
require_once 'config/koneksi.php';

// Hanya menerima POST (bukan link biasa)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = mysqli_prepare($koneksi, "DELETE FROM barang WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['pesan'] = $lang['sukses_hapus'];
    }
}

header("Location: index.php");
exit;