<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

if (!isset($_SESSION['user_id'])) {
    set_flash('error', 'Silakan masuk terlebih dahulu untuk mengakses menu administrator.');
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    set_flash('error', 'Akses ditolak: Anda tidak memiliki izin untuk membuka halaman administrator.');
    header('Location: ' . BASE_URL . 'home/index.php');
    exit;
}
