<?php
$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']}",
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database Connection Error [PREVO]: " . $e->getMessage());
    http_response_code(500);
    die(
        '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; max-width: 580px; margin: 48px auto; padding: 24px; border: 1px solid #ffdad6; background: #fff5f5; border-radius: 16px; color: #ba1a1a; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">' .
        '<h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 700;">Gagal Terhubung ke Database PostgreSQL</h3>' .
        '<p style="margin: 0; font-size: 14px; line-height: 1.5; color: #434655;">Sistem PREVO tidak dapat tersambung ke database. Pastikan service PostgreSQL Anda aktif dan pengaturan pada <code>includes/config.php</code> sudah sesuai.</p>' .
        '</div>'
    );
}