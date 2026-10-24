<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';

$sellerId = (int) $_SESSION['user_id'];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare(
    "SELECT * FROM items
    WHERE id = :id AND seller_id = :seller_id AND status != 'dihapus'
    "
);
$stmt->execute([
    ':id' => $id,
    ':seller_id' => $sellerId
]);
$item = $stmt->fetch();

if (!$item) {
    set_flash('error', 'Barang tidak ditemukan atau bukan milik Anda.');
    header('Location: kelola.php');
    exit;
}

$categories = $pdo->query(
    "SELECT id, name
    FROM categories
    ORDER BY name ASC
    "
)->fetchAll();
?>