<?php
// ============================================================
//  PREVO - Handler Update Kesepakatan Nego / COD (Penjual)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/TransactionService.php';

$user = auth_guard('mahasiswa', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=selling');
}

verify_csrf();

$txId = (int)($_POST['tx_id'] ?? 0);
$agreedPrice = (float)($_POST['agreed_price'] ?? 0);

if (isset($_POST['cod_location'])) {
    $codLoc = sanitize_input($_POST['cod_location'] ?? '');
    $codNote = sanitize_input($_POST['cod_note'] ?? '');
    $location = format_lokasi_cod($codLoc, $codNote);
} else {
    $location = trim($_POST['meetup_location'] ?? $_POST['meeting_location'] ?? '');
}

$meetupTime = trim($_POST['meetup_time'] ?? $_POST['meeting_time'] ?? '');

if ($txId <= 0 || $agreedPrice <= 0) {
    set_flash('error', 'Data kesepakatan tidak valid. Harga harus lebih dari 0.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=selling');
}

$txService = new TransactionService();
$result = $txService->updateBookingDeal($txId, (int)$user['id'], $agreedPrice, $location, $meetupTime);

if ($result['success']) {
    set_flash('success', 'Kesepakatan harga & lokasi COD berhasil diperbarui.');
} else {
    set_flash('error', $result['error'] ?? 'Gagal memperbarui kesepakatan.');
}

redirect(BASE_URL . '/transaksi/riwayat.php?tab=selling');
