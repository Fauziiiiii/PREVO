<?php
// ============================================================
//  PREVO - Handler Booking 1-Klik Barang
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/TransactionService.php';

// Wajib login sebagai mahasiswa dengan WhatsApp terverifikasi
$user = auth_guard('mahasiswa', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/barang/index.php');
}

verify_csrf();

$itemId = (int)($_POST['item_id'] ?? 0);

if ($itemId <= 0) {
    set_flash('error', 'Barang tidak valid.');
    redirect(BASE_URL . '/barang/index.php');
}

$txService = new TransactionService();
$result = $txService->createBooking($itemId, (int)$user['id']);

if ($result['success']) {
    set_flash('success', 'Berhasil membooking barang! Anda memiliki waktu 24 jam untuk bertransaksi COD. Hubungi penjual sekarang melalui WhatsApp.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
} else {
    set_flash('error', $result['error'] ?? 'Gagal membooking barang.');
    redirect(BASE_URL . '/barang/detail.php?id=' . $itemId);
}
