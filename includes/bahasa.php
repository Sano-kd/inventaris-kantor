<?php
if (session_status() === PHP_SESSION_NONE) {
    // Cookie session tidak bisa dibaca JavaScript & tidak ikut dikirim dari situs lain
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

// Bahasa default: Indonesia (dan pastikan nilainya hanya id / en)
$bahasa_boleh = ['id', 'en'];
if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $bahasa_boleh, true)) {
    $_SESSION['lang'] = 'id';
}

$lang = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';