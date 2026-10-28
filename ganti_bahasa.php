<?php
require_once 'includes/bahasa.php';

// Whitelist: hanya 'id' dan 'en' yang diterima
if (isset($_GET['lang']) && in_array($_GET['lang'], $bahasa_boleh, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Kembali ke halaman sebelumnya, tetapi hanya jika berasal dari situs ini
$kembali = 'index.php';
$ref = parse_url($_SERVER['HTTP_REFERER'] ?? '');
$host_sendiri = parse_url('//' . ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST);

if (!empty($ref['host']) && $ref['host'] === $host_sendiri && !empty($ref['path'])) {
    $file = basename($ref['path']);
    if (preg_match('/^[a-z_]+\.php$/', $file)) {
        $kembali = $file . (!empty($ref['query']) ? '?' . $ref['query'] : '');
    }
}

header("Location: $kembali");
exit;