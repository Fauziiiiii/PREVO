<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

auth_guard('mahasiswa');
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $itemId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    $itemService = new ItemService();
    $res = $itemService->deleteItem($itemId, $user['id']);

    if ($res['success']) {
        set_flash('success', 'Listing barang berhasil dihapus dari sistem.');
    } else {
        set_flash('error', $res['error']);
    }
}

redirect('/barang/kelola.php');
