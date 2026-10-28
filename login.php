<?php
require_once 'includes/bahasa.php';
require_once 'config/koneksi.php';

// Kalau sudah login, langsung ke index
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = $lang['login_gagal'];
    } else {
        // Prepared statement: mencegah SQL Injection
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $user  = mysqli_fetch_assoc($hasil);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = $lang['login_gagal'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $lang['login'] ?> - <?= $lang['judul'] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="center-wrap">
    <div class="card form-card login-card">
        <div class="form-head">
            <div>
                <p class="app-name"><?= $lang['judul'] ?></p>
                <h1><?= $lang['login'] ?></h1>
            </div>
            <div class="lang-switch">
                <a href="ganti_bahasa.php?lang=id" class="<?= $_SESSION['lang'] === 'id' ? 'aktif' : '' ?>">ID</a>
                <a href="ganti_bahasa.php?lang=en" class="<?= $_SESSION['lang'] === 'en' ? 'aktif' : '' ?>">EN</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert-err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label for="username"><?= $lang['username'] ?></label>
                <input type="text" id="username" name="username" autocomplete="username" autofocus
                       value="<?= htmlspecialchars($username) ?>">
            </div>
            <div class="field">
                <label for="password"><?= $lang['password'] ?></label>
                <input type="password" id="password" name="password" autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block"><?= $lang['login'] ?></button>
        </form>
    </div>
</div>
</body>
</html>