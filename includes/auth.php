<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

if (!isset($_SESSION['user_id'])) {
    set_flash('error', 'Silakan masuk ke akun Anda terlebih dahulu.');
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}