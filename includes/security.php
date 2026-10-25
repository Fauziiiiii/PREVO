<?php
// ============================================================
//  PREVO - Modul Proteksi Keamanan (CSRF & XSS)
// ============================================================

/**
 * Escaping output untuk mencegah celah Cross-Site Scripting (XSS)
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Mendapatkan token anti-CSRF saat ini atau menghasilkan token baru
 */
function csrf_token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Menghasilkan input hidden HTML token CSRF untuk formulir POST
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Memvalidasi token CSRF dari request POST
 */
function verify_csrf(): bool {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die("403 Forbidden: Validasi keamanan (Token CSRF) tidak valid atau sesi Anda telah kedaluwarsa. Silakan refresh halaman dan coba lagi.");
    }
    return true;
}

/**
 * Membersihkan input string dasar
 */
function sanitize_input(?string $input): string {
    return trim($input ?? '');
}
