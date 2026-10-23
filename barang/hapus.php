<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';
csrf_verify();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$sellerId = (int) $_SESSION['user_id'];

if ($id) {
    try {
        $stmt = $pdo->prepare(
            "UPDATE items
            SET status = 'dihapus',
            updated_at = NOW()
            WHERE id = :id AND seller_id = :seller_id
            "
        );
        $stmt->execute([
            ':id' => $id,
            ':seller_id' => $sellerId
        ]);
        set_flash('success', 'Barang berhasil dihapus dari katalog.');
    } catch (PDOException $e) {
        set_flash('error', 'Gagal menghapus barang.');
    }
}
header('Location: kelola.php');
exit;
