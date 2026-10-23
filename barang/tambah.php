<?php
$page_title = "Jual Barang";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';

$categories = $pdo->query(
    "SELECT id, name 
    FROM categories
    ORDER BY name ASC
    "
)->fetchAll();
?>