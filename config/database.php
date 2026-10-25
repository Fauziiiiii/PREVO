<?php
// ============================================================
//  PREVO - Konfigurasi & Koneksi Basis Data PostgreSQL
// ============================================================

function get_db(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'prevo';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: 'postgres16';

        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};options='--client_encoding=UTF8'";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            // Panggil rilis booking kedaluwarsa secara berkala (maksimal 1x per 2 menit)
            if (session_status() === PHP_SESSION_ACTIVE) {
                $lastCheck = $_SESSION['__last_expired_check'] ?? 0;
                if (time() - $lastCheck > 120) {
                    try {
                        $pdo->exec("CALL release_expired_bookings()");
                        $_SESSION['__last_expired_check'] = time();
                    } catch (Exception $e) {
                        // Abaikan jika SP belum siap
                    }
                }
            }

        } catch (PDOException $e) {
            die("Koneksi Basis Data Gagal: " . htmlspecialchars($e->getMessage()));
        }
    }

    return $pdo;
}
