<?php
// ============================================================
//  PREVO - Handler Pembatalan Transaksi COD
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/TransactionService.php';

$user = auth_guard('mahasiswa', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/transaksi/riwayat.php');
}

verify_csrf();

$txId = (int)($_POST['tx_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');
$fromTab = in_array($_POST['tab'] ?? '', ['buying', 'selling']) ? $_POST['tab'] : 'buying';

if ($txId <= 0) {
    set_flash('error', 'ID Transaksi tidak valid.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
}

$txService = new TransactionService();
$result = $txService->cancelTransaction($txId, (int)$user['id'], $reason);

if ($result['success']) {
    set_flash('info', 'Transaksi berhasil dibatalkan. Status barang otomatis kembali tersedia di katalog.');
} else {
    set_flash('error', $result['error'] ?? 'Gagal membatalkan transaksi.');
}

redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
