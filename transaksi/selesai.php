<?php
// ============================================================
//  PREVO - Handler Selesaikan Transaksi COD (Buyer / Seller)
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
$fromTab = in_array($_POST['tab'] ?? '', ['buying', 'selling']) ? $_POST['tab'] : 'buying';

if ($txId <= 0) {
    set_flash('error', 'ID Transaksi tidak valid.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
}

$txService = new TransactionService();
$result = $txService->completeTransaction($txId, (int)$user['id']);

if ($result['success']) {
    set_flash('success', 'Transaksi COD berhasil diselesaikan! Terima kasih telah bertransaksi secara aman di PREVO.');
    // Jika aktor adalah pembeli, arahkan untuk memberi ulasan
    $tx = $txService->getTransactionById($txId);
    if ($tx && (int)$tx['buyer_id'] === (int)$user['id']) {
        redirect(BASE_URL . '/transaksi/beri_ulasan.php?tx_id=' . $txId);
    }
} else {
    set_flash('error', $result['error'] ?? 'Gagal menyelesaikan transaksi.');
}

redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
