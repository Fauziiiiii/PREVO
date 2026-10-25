<?php
// ============================================================
//  PREVO - Konfigurasi Aplikasi Global
// ============================================================

define('APP_NAME', 'PREVO');
define('APP_TAGLINE', 'Marketplace Barang Bekas Khusus Mahasiswa Polinema');

// Base URL otomatis mendeteksi folder root proyek
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? null) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

// Hitung base path proyek (PBL-2)
$parts = explode('/', trim($scriptDir, '/'));
$baseFolder = '';
if (!empty($parts[0])) {
    // Jika berada di dalam subfolder (misal /PBL-2/...)
    if (in_array('PBL-2', $parts)) {
        $idx = array_search('PBL-2', $parts);
        $baseFolder = '/' . implode('/', array_slice($parts, 0, $idx + 1));
    }
}
define('BASE_URL', rtrim($protocol . $host . $baseFolder, '/'));

// Direktori root absolut proyek
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/items');

// Domain email kampus yang diizinkan mendaftar
define('ALLOWED_EMAIL_DOMAINS', [
    'student.polinema.ac.id',
    'polinema.ac.id'
]);

// Jurusan & Program Studi di Polinema (untuk form registrasi & profil)
define('JURUSAN_POLINEMA', [
    'Teknologi Informasi' => [
        'D4 Teknik Informatika',
        'D4 Sistem Informasi Bisnis',
        'D2 Pengembangan Piranti Lunak Situs'
    ],
    'Teknik Elektro' => [
        'D4 Teknik Elektronika',
        'D4 Sistem Kelistrikan',
        'D4 Teknik Jaringan Telekomunikasi Digital',
        'D3 Teknik Elektronika',
        'D3 Teknik Listrik',
        'D3 Teknik Telekomunikasi'
    ],
    'Teknik Mesin' => [
        'D4 Teknik Mesin Produksi dan Perawatan',
        'D4 Teknik Otomotif Elektronik',
        'D3 Teknik Mesin'
    ],
    'Teknik Sipil' => [
        'D4 Manajemen Rekayasa Konstruksi',
        'D3 Teknik Sipil',
        'D3 Teknologi Konstruksi Jalan Jembatan dan Bangunan Air'
    ],
    'Teknik Kimia' => [
        'D4 Teknologi Kimia Industri',
        'D3 Teknik Kimia'
    ],
    'Akuntansi' => [
        'D4 Akuntansi Manajemen',
        'D4 Keuangan',
        'D3 Akuntansi'
    ],
    'Administrasi Niaga' => [
        'D4 Manajemen Pemasaran',
        'D4 Bahasa Inggris untuk Komunikasi Bisnis dan Profesional',
        'D3 Administrasi Bisnis'
    ]
]);
