<?php
// ============================================================
//  PREVO - Database Migration & Seeding CLI Helper
// ============================================================

require_once __DIR__ . '/../config/app.php';

echo "====================================================\n";
echo "       PREVO DATABASE MIGRATION & SEEDING           \n";
echo "====================================================\n\n";

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'prevo';
$user = getenv('DB_USER') ?: 'postgres';
$pass = getenv('DB_PASS') ?: 'postgres16';

// 1. Cek / Buat Database 'prevo' jika belum ada
try {
    $dsnDefault = "pgsql:host={$host};port={$port};dbname=postgres;options='--client_encoding=UTF8'";
    $pdoInit = new PDO($dsnDefault, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $checkDb = $pdoInit->query("SELECT 1 FROM pg_database WHERE datname = '{$dbname}'")->fetchColumn();
    if (!$checkDb) {
        echo "[1/3] Database '{$dbname}' belum ada. Membuat database baru...\n";
        $pdoInit->exec("CREATE DATABASE \"{$dbname}\" ENCODING 'UTF8'");
        echo "      ✓ Database '{$dbname}' berhasil dibuat.\n";
    } else {
        echo "[1/3] Database '{$dbname}' sudah tersedia.\n";
    }
} catch (PDOException $e) {
    echo "⚠️ Peringatan saat cek database default: " . $e->getMessage() . "\n";
    echo "   Mencoba langsung koneksi ke '{$dbname}'...\n";
}

// 2. Hubungkan ke database 'prevo'
$dsnTarget = "pgsql:host={$host};port={$port};dbname={$dbname};options='--client_encoding=UTF8'";
try {
    $db = new PDO($dsnTarget, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "[2/3] Terhubung ke database '{$dbname}'.\n";
} catch (PDOException $e) {
    die("❌ Gagal terhubung ke database '{$dbname}': " . $e->getMessage() . "\n");
}

// 3. Eksekusi Schema
$schemaFile = __DIR__ . '/prevo_schema.sql';
if (!file_exists($schemaFile)) {
    die("❌ File skema tidak ditemukan di: {$schemaFile}\n");
}

echo "      Mengimpor skema tabel, stored procedures, triggers, dan views...\n";
$schemaSql = file_get_contents($schemaFile);

try {
    $db->exec($schemaSql);
    echo "      ✓ Skema basis data berhasil diimpor.\n";
} catch (PDOException $e) {
    die("❌ Gagal mengimpor skema: " . $e->getMessage() . "\n");
}

// 4. Eksekusi Seed Data
$seedFile = __DIR__ . '/prevo_seed_data.sql';
if (!file_exists($seedFile)) {
    die("❌ File seed tidak ditemukan di: {$seedFile}\n");
}

echo "[3/3] Mengimpor data awal (seed data)...\n";
$seedSql = file_get_contents($seedFile);

try {
    $db->exec($seedSql);
    echo "      ✓ Data awal berhasil diimpor.\n";
} catch (PDOException $e) {
    die("❌ Gagal mengimpor data awal: " . $e->getMessage() . "\n");
}

// 5. Verifikasi Data Hasil Impor
echo "\n====================================================\n";
echo "            RINGKASAN DATA TERIMPOR                 \n";
echo "====================================================\n";

$tables = ['users', 'categories', 'items', 'transactions', 'reviews', 'reports', 'favorites'];
foreach ($tables as $t) {
    try {
        $cnt = $db->query("SELECT COUNT(*) FROM {$t}")->fetchColumn();
        echo sprintf(" - Tabel %-15s : %d baris data\n", $t, $cnt);
    } catch (Exception $e) {
        echo sprintf(" - Tabel %-15s : Gagal query\n", $t);
    }
}

echo "\nAkun Demo yang Siap Digunakan:\n";
echo " 1. Admin      : admin@prevo.polinema.ac.id     | Sandi: Test@12345\n";
echo " 2. Mahasiswa  : fauzi@student.polinema.ac.id     | Sandi: Test@12345\n";
echo " 3. Mahasiswa  : reyhandhika@student.polinema.ac.id| Sandi: Test@12345\n";
echo "====================================================\n";
echo "🎉 Setup Basis Data PREVO Berhasil 100%!\n";
