<?php
require_once __DIR__ . '/bahasa.php';

// Belum login? Arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Halaman terproteksi tidak disimpan cache browser
// (setelah logout, tombol Back tidak menampilkan halaman lama)
header("Cache-Control: no-store, no-cache, must-revalidate");