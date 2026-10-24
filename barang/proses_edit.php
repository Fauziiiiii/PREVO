<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';

csrf_verify();

$sellerId = (int) $_SESSION['user_id'];
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$title = trim($_POST['title'] ?? '');
$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$description = trim($_POST['description'] ?? '');
$price = preg_replace('/[^0-9]/', '', $_POST['price'] ?? '');
$condition = trim($_POST['condition'] ?? '');
$image_url = trim($_POST['image_url'] ?? '');
$location_info = trim($_POST['location_info'] ?? '');

if (!$id || $title === '' || !$categoryId || $description === '' || $price === '' || $price <= 0) {
    set_flash('error', 'Data tidak valid atau kurang lengkap.');
    header("Location: edit.php?id=$id");
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE items 
        SET title = :title, category_id = :category_id, description = :description, 
            price = :price, condition = :condition, image_url = :image_url, location_info = :location_info,
            updated_at = NOW()
        WHERE id = :id AND seller_id = :seller_id AND status != 'dihapus'
    ");
    
    $stmt->execute([
        ':title' => $title,
        ':category_id' => $categoryId,
        ':description' => $description,
        ':price' => $price,
        ':condition' => $condition,
        ':image_url' => $image_url,
        ':location_info' => $location_info,
        ':id' => $id,
        ':seller_id' => $sellerId
    ]);

    set_flash('success', 'Barang berhasil diperbarui.');
    header('Location: kelola.php');
    exit;
} catch (PDOException $e) {
    set_flash('error', 'Gagal memperbarui barang.');
    header("Location: edit.php?id=$id");
    exit;
}