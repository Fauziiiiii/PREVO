<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kosongkan seluruh variabel sesi
$_SESSION = [];

// Hapus cookie sesi dari browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Mulai sesi baru
session_start();
$_SESSION['flash'] = ['type' => 'info', 'pesan' => 'Anda telah berhasil keluar dari akun PREVO.'];

header('Location: login.php');
exit;