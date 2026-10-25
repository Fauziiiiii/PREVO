<?php
// ============================================================
//  PREVO - Modul Manajemen Sesi & Auth Guard
// ============================================================

require_once __DIR__ . '/../config/app.php';

function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_samesite', 'Lax');
        session_start();
    }
}

// Inisialisasi otomatis
start_session();

function is_logged_in(): bool {
    return !empty($_SESSION['user']['id']);
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function user_id(): ?int {
    return $_SESSION['user']['id'] ?? null;
}

function is_admin(): bool {
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function is_whatsapp_verified(): bool {
    return is_logged_in() && !empty($_SESSION['user']['whatsapp_verified_at']);
}

/**
 * Penjaga otorisasi halaman (Auth Guard)
 */
function auth_guard(?string $requiredRole = null, bool $requireVerifiedWa = false): void {
    if (!is_logged_in()) {
        set_flash('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        header("Location: " . BASE_URL . "/auth/login.php?redirect=" . urlencode($currentUrl));
        exit;
    }

    if ($requiredRole !== null && ($_SESSION['user']['role'] ?? '') !== $requiredRole) {
        http_response_code(403);
        set_flash('error', 'Akses ditolak: Anda tidak memiliki izin untuk halaman ini.');
        header("Location: " . BASE_URL . "/barang/index.php");
        exit;
    }

    // Jika membutuhkan verifikasi nomor WhatsApp (misal untuk pasang iklan atau booking)
    if ($requireVerifiedWa && !is_admin() && !is_whatsapp_verified()) {
        set_flash('warning', 'Nomor WhatsApp Anda belum diverifikasi. Silakan masukkan kode OTP untuk mengaktifkan fitur ini.');
        header("Location: " . BASE_URL . "/auth/verifikasi_wa.php");
        exit;
    }
}

/**
 * Menyimpan pesan flash (success, error, warning, info)
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash'][$type][] = $message;
}

/**
 * Mengambil dan membersihkan pesan flash
 */
function get_flashes(): array {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}
