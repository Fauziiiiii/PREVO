<?php
$page_title = "Produk Saya";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';

$sellerId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT i.*, c.name AS category_name
    FROM items i
    JOIN categories c ON i.category_id = c.id
    WHERE i.seller_id = :seller_id AND i.status != 'dihapus'
    ORDER BY created_at DESC
    "
);
$stmt->execute([':seller_id' => $sellerId]);
$items = $stmt->fetchAll();
?>