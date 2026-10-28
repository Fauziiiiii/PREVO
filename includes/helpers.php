<?php
// ============================================================
//  PREVO - Fungsi Pembantu (Helpers)
// ============================================================

require_once __DIR__ . '/../config/app.php';

/**
 * Format angka ke format mata uang Rupiah (contoh: Rp120.000)
 */
function format_rupiah($amount): string {
    $num = (float)$amount;
    return 'Rp' . number_format($num, 0, ',', '.');
}

/**
 * Format timestamp ISO/PostgreSQL ke format tanggal Indonesia
 */
function format_tanggal(?string $timestamp, bool $includeTime = false): string {
    if (empty($timestamp)) {
        return '-';
    }
    $time = strtotime($timestamp);
    if (!$time) {
        return '-';
    }

    $bulanIndo = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    $d = date('j', $time);
    $m = $bulanIndo[(int)date('n', $time)];
    $y = date('Y', $time);

    $dateStr = "$d $m $y";
    if ($includeTime) {
        $dateStr .= ' ' . date('H:i', $time) . ' WIB';
    }
    return $dateStr;
}

/**
 * Menghasilkan tautan langsung WhatsApp dengan format nomor Indonesia (628...)
 */
function wa_link(?string $phone, string $message = ''): string {
    if (empty($phone)) {
        return '#';
    }
    // Bersihkan karakter non-digit
    $clean = preg_replace('/[^0-9]/', '', $phone);
    // Konversi format lokal 08... menjadi 628...
    if (str_starts_with($clean, '0')) {
        $clean = '62' . substr($clean, 1);
    }
    $url = "https://wa.me/" . $clean;
    if (!empty($message)) {
        $url .= "?text=" . urlencode($message);
    }
    return $url;
}

/**
 * Redirect aman ke URL internal
 */
function redirect(string $path): void {
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        header("Location: " . $path);
    } else {
        header("Location: " . BASE_URL . '/' . ltrim($path, '/'));
    }
    exit;
}

/**
 * Helper URL Aset
 */
function asset_url(string $path): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Penanganan unggahan foto barang (Validasi MIME & Ukuran maks 2MB)
 */
function upload_item_photo(array $file): array {
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Tidak ada file yang diunggah atau terjadi kesalahan upload.'];
    }

    // Maksimal 2MB
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Ukuran foto maksimal 2MB.'];
    }

    // Validasi tipe MIME
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'image/jpeg' => '.jpg',
        'image/png'  => '.png',
        'image/webp' => '.webp',
    ];

    if (!array_key_exists($mime, $allowedMimes)) {
        return ['success' => false, 'error' => 'Format file harus berupa gambar (JPG, PNG, atau WEBP).'];
    }

    $ext = $allowedMimes[$mime];
    $filename = 'item_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . $ext;
    
    $destDir = UPLOAD_PATH;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $destPath = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return ['success' => false, 'error' => 'Gagal memindahkan file ke direktori uploads.'];
    }

    // Simpan path relatif terhadap web root
    return ['success' => true, 'path' => 'uploads/items/' . $filename];
}

/**
 * Format teks lokasi COD terpadu (Nama Lokasi + Catatan Patokan)
 */
function format_lokasi_cod(string $location, ?string $note = null): string {
    $loc = trim($location);
    $nt = trim($note ?? '');
    if (!empty($nt)) {
        return $loc . ' — ' . $nt;
    }
    return $loc;
}

/**
 * Parsing teks lokasi COD terpadu menjadi komponen nama lokasi & catatan patokan
 */
function parse_lokasi_cod(?string $rawLocation): array {
    $raw = trim($rawLocation ?? '');
    if (empty($raw)) {
        return ['location' => '', 'note' => ''];
    }

    if (str_contains($raw, ' — ')) {
        $parts = explode(' — ', $raw, 2);
        return ['location' => trim($parts[0]), 'note' => trim($parts[1])];
    }

    if (str_contains($raw, ' - ')) {
        $parts = explode(' - ', $raw, 2);
        return ['location' => trim($parts[0]), 'note' => trim($parts[1])];
    }

    return ['location' => $raw, 'note' => ''];
}
