<?php
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Format nominal angka ke format mata uang Rupiah
function format_rupiah($nominal)
{
    return 'Rp ' . number_format((float) $nominal, 0, ',', '.');
}

// Set notifikasi flash session
function set_flash($tipe, $pesan)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $tipe, 'pesan' => $pesan];
}

// Tampilkan dan bersihkan notifikasi flash
function tampil_flash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        $type = $flash['type'] ?? 'info';
        $pesan = $flash['pesan'] ?? '';

        $colorClass = 'bg-surface-container-high text-on-surface border-outline-variant';
        if ($type === 'error' || $type === 'danger') {
            $colorClass = 'bg-error-container text-on-error-container border-error/30';
        } elseif ($type === 'success') {
            $colorClass = 'bg-tertiary-container/30 text-tertiary border-tertiary/30';
        }

        echo '<div class="p-4 mb-4 rounded-xl border text-body-md font-medium flex items-center justify-between ' . $colorClass . '">';
        echo '<span>' . e($pesan) . '</span>';
        echo '<button type="button" onclick="this.parentElement.remove()" class="text-lg leading-none px-2">&times;</button>';
        echo '</div>';
    }
}