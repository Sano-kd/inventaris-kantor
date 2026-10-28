<?php
require_once 'includes/bahasa.php';

$bahasa_terakhir = $_SESSION['lang'];

// Hapus seluruh data login
$_SESSION = [];
session_destroy();

// Mulai session baru (ID baru) 
session_start();
session_regenerate_id(true);
$_SESSION['lang'] = $bahasa_terakhir;

header("Location: login.php");
exit;