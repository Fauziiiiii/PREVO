<?php
// Root entry point: arahkan langsung ke katalog utama barang
require_once __DIR__ . '/config/app.php';
header("Location: " . BASE_URL . "/barang/index.php");
exit;
