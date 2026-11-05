<?php
// ============================================================
//  PREVO - Handler Konfirmasi Transaksi COD 2-Tahap
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
$tx = $txService->getTransactionById($txId);

if (!$tx) {
    set_flash('error', 'Transaksi tidak ditemukan.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
}

$userId = (int)$user['id'];

if ((int)$tx['seller_id'] === $userId) {
    // Penjual mengonfirmasi penyerahan barang (Tahap 1)
    $result = $txService->confirmSellerHandover($txId, $userId);
    if ($result['success']) {
        set_flash('success', 'Penyerahan barang berhasil dikonfirmasi! Sekarang menunggu pembeli memeriksa barang dan menyelesaikan transaksi.');
    } else {
        set_flash('error', $result['error'] ?? 'Gagal mengonfirmasi penyerahan barang.');
    }
} elseif ((int)$tx['buyer_id'] === $userId) {
    // Pembeli mengonfirmasi penerimaan barang & menyelesaikan transaksi (Tahap 2)
    $result = $txService->completeTransaction($txId, $userId);
    if ($result['success']) {
        set_flash('success', 'Transaksi COD berhasil diselesaikan! Terima kasih telah bertransaksi aman di PREVO.');
        redirect(BASE_URL . '/transaksi/beri_ulasan.php?tx_id=' . $txId);
    } else {
        set_flash('error', $result['error'] ?? 'Gagal menyelesaikan transaksi.');
    }
} else {
    set_flash('error', 'Anda tidak memiliki hak akses pada transaksi ini.');
}

redirect(BASE_URL . '/transaksi/riwayat.php?tab=' . $fromTab);
