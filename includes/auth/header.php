<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../csrf.php';

// Definisi BASE_URL dinamis agar link aset dan halaman selalu valid
if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/../..'));
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        $basePath = substr($projectRoot, strlen($docRoot));
    } else {
        $basePath = '';
    }
    define('BASE_URL', rtrim($basePath, '/') . '/');
}

// Jika pengguna sudah login, redirect langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'home/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= isset($page_title) ? e($page_title) . ' - PREVO' : 'PREVO - Autentikasi Mahasiswa Polinema'; ?></title>

    <!-- Google Fonts: Plus Jakarta Sans, Inter, & Caveat -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Prevo Compiled Tailwind CSS v4 -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css" />
</head>

<body class="<?= $body_class ?? 'bg-background text-on-surface antialiased min-h-screen flex flex-col justify-between selection:bg-primary selection:text-on-primary'; ?>">
