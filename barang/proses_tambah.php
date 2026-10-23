<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helpers.php';

csrf_verify();

$sellerId = (int) $_SESSION['user_id'];

$title = trim($_POST['title'] ?? '');
$categoryId = filter_var($_POST['category_id'] ?? '', FILTER_VALIDATE_INT);
$description = trim($_POST['description'] ?? '');
$priceRaw = trim($_POST['price'] ?? '');
$condition = trim($_POST['condition'] ?? '');
$image_url = trim($_POST['image_url'] ?? '');
$location_info = trim($_POST['location_info'] ?? '');

$price = preg_replace('/[^0-9]/', '', $priceRaw);

$errors = [];
if ($title === '') {
    $errors[] = 'Nama produk wajib diisi.';
} elseif (mb_strlen($title) > 70) {
    $errors[] = 'Nama produk maksimal 70 karakter.';
}
if ($categoryId === false || $categoryId <= 0) {
    $errors[] = 'Kategori wajib dipilih.';
}
if ($description === '') {
    $errors[] = 'Deskripsi wajib diisi.';
} elseif (mb_strlen($description) > 450) {
    $errors[] = 'Deskripsi maksimal 450 karakter.';
}
if ($price === '' || (int) $price <= 0) {
    $errors[] = 'Harga wajib diisi dengan angka lebih dari 0.';
} elseif (strlen($price) > 9) {
    $errors[] = 'Harga terlalu besar.';
}
if (!in_array($condition, ['baru', 'bekas_baik', 'bekas_rusak'], true)) {
    $errors[] = 'Kondisi barang tidak valid.';
}
if ($image_url !== '') {
    $scheme = strtolower((string) parse_url($image_url, PHP_URL_SCHEME));
    if (mb_strlen($image_url) > 255 || !filter_var($image_url, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true)) {
        $errors[] = 'URL gambar harus http/https yang valid (maks. 255 karakter).';
    }
}
if (mb_strlen($location_info) > 100) {
    $errors[] = 'Titik temu COD maksimal 100 karakter.';
}
if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: tambah.php');
    exit;
}

try {
    $stmtCheckCategoriesId = $pdo->prepare("SELECT id FROM categories WHERE id= :id LIMIT 1");
    $stmtCheckCategoriesId->execute([':id' => $categoryId]);
    if (!$stmtCheckCategoriesId->fetchColumn()) {
        set_flash('error', 'Kategori yang dipilih tidak ditemukan.');
        header('Location: tambah.php');
        exit;
    }

    $stmtItem = $pdo->prepare(
        "INSERT INTO items (seller_id, category_id, title, description, price, condition, image_url, location_info)
         VALUES (:seller_id, :category_id, :title, :description, :price, :condition, :image_url, :location_info)"
    );

    $stmtItem->execute([
        ':seller_id'     => $sellerId,
        ':category_id'   => $categoryId,
        ':title'         => $title,
        ':description'   => $description,
        ':price'         => $price,
        ':condition'     => $condition,
        ':image_url'     => $image_url !== '' ? $image_url : null,
        ':location_info' => $location_info !== '' ? $location_info : null,
    ]);
 
    set_flash('success', 'Barang berhasil ditambahkan.');
    header('Location: kelola.php');
    exit;
} catch (PDOException $e) {
    error_log('[proses_tambah] ' . $e->getMessage());
    set_flash('error', 'Gagal menyimpan data. Silakan coba lagi.');
    header('Location: tambah.php');
    exit;
}