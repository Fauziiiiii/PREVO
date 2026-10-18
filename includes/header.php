<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/helpers.php';

$isLoggedIn = isset($_SESSION['user_id']);
$favoriteCount = 0;
if ($isLoggedIn) {
    if (isset($pdo)) {
        try {
            $stmtFavorite = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :uid");
            $stmtFavorite->execute(['uid' => $_SESSION['user_id']]);
            $favoriteCount = (int) $stmtFavorite->fetchColumn();
        } catch (Throwable $e) {}
    } elseif (file_exists(__DIR__ . '/koneksi.php')) {
        try {
            require_once __DIR__ . '/koneksi.php';
            $stmtFavorite = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :uid");
            $stmtFavorite->execute(['uid' => $_SESSION['user_id']]);
            $favoriteCount = (int) $stmtFavorite->fetchColumn();
        } catch (Throwable $e) {}
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= isset($page_title) ? e($page_title) . ' | PREVO' : 'PREVO - Marketplace Preloved Mahasiswa Polinema'; ?></title>

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
  
  <!-- Material Symbols Outlined -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">

  <!-- Prevo Compiled Tailwind CSS v4 -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
</head>

<body class="bg-background text-on-surface antialiased min-h-screen flex flex-col font-body-md text-body-md selection:bg-primary-fixed selection:text-primary">
  <!-- TOP APP BAR (Shared Component: TopNavBar) -->
  <header class="bg-surface-container-lowest dark:bg-surface-container-lowest sticky top-0 z-50 shadow-sm dark:shadow-sm">
    <div class="w-full max-w-7xl mx-auto px-gutter-desktop flex items-center justify-between h-20 gap-space-md">
      <!-- Brand Logo & Main Nav -->
      <div class="flex items-center gap-8">
        <a class="font-headline-lg text-headline-lg font-bold text-primary dark:text-primary tracking-tight flex items-center gap-2" href="<?= BASE_URL; ?>home/index.php">
          <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm shadow-primary/20">
            <span class="material-symbols-outlined text-2xl" data-icon="shopping_bag">shopping_bag</span>
          </span>
          PREVO
        </a>
        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-6">
          <a class="text-primary dark:text-primary font-label-lg text-label-lg border-b-2 border-primary pb-1" href="<?= BASE_URL; ?>home/index.php">Beranda</a>
          <a class="text-on-surface-variant dark:text-on-surface-variant font-label-lg text-label-lg hover:text-primary transition-colors" href="<?= BASE_URL; ?>home/index.php#kategori">Jelajah</a>
          <a class="text-on-surface-variant dark:text-on-surface-variant font-label-lg text-label-lg hover:text-primary transition-colors" href="<?= BASE_URL; ?>home/index.php#bantuan">Bantuan</a>
        </nav>
      </div>

      <!-- Search Bar on Center/Right -->
      <div class="flex-1 max-w-md hidden lg:block">
        <form method="GET" action="<?= BASE_URL; ?>home/index.php" class="relative flex items-center w-full">
          <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]" data-icon="search">search</span>
          <input name="q" class="w-full pl-10 pr-28 py-2 bg-surface-container-low border border-outline-variant rounded-full text-on-surface placeholder:text-outline focus:outline-none focus:border-primary text-body-sm font-body-sm transition-all shadow-inner" placeholder="Cari barang, kategori, atau penjual..." type="text">
          <button type="button" class="absolute right-1.5 px-3 py-1 bg-surface-container-lowest text-secondary text-label-sm font-label-sm rounded-full flex items-center gap-1 border border-outline-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[14px]" data-icon="location_on">location_on</span>
            <span>Kampus</span>
            <span class="material-symbols-outlined text-[14px]" data-icon="arrow_drop_down">arrow_drop_down</span>
          </button>
        </form>
      </div>

      <!-- Trailing Action Cluster -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Interactive Icon Actions -->
        <button class="relative p-2 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors duration-150 flex items-center justify-center" title="Notifikasi">
          <span class="material-symbols-outlined text-[22px]" data-icon="notifications">notifications</span>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-error"></span>
        </button>

        <!-- Barang Disimpan / Favorit (Love Icon) -->
        <a href="<?= BASE_URL; ?>barang/favorit.php" class="relative p-2 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors duration-150 flex items-center justify-center" title="Barang Disimpan (Favorit)">
          <span class="material-symbols-outlined text-[22px]" data-icon="favorite_border">favorite_border</span>
          <?php if (!empty($favoriteCount)): ?>
            <span class="absolute -top-0.5 -right-0.5 min-w-[15px] h-[15px] px-1 bg-error text-white text-[9px] font-bold rounded-full flex items-center justify-center leading-none shadow-xs">
              <?= $favoriteCount > 99 ? '99+' : $favoriteCount; ?>
            </span>
          <?php endif; ?>
        </a>
        
        <div class="h-6 w-[1px] bg-outline-variant mx-0.5 sm:mx-1 hidden sm:block"></div>

        <!-- Student Profile Avatar / Auth Status -->
        <?php if ($isLoggedIn): ?>
          <div class="relative" id="userMenuContainer">
            <!-- Dropdown Trigger -->
            <button type="button" id="userMenuButton" class="flex items-center gap-2 pl-1 py-1 pr-1.5 sm:pr-2 rounded-xl hover:bg-surface-container transition-colors duration-150 focus:outline-none" aria-expanded="false" aria-haspopup="true">
              <div class="relative w-9 h-9 rounded-full ring-2 ring-primary/20 overflow-hidden bg-surface-container-high flex-shrink-0 flex items-center justify-center">
                <?php if (!empty($_SESSION['avatar_url'])): ?>
                  <img alt="Foto profil <?= e($_SESSION['name'] ?? 'Mahasiswa'); ?>" class="w-full h-full object-cover" src="<?= e($_SESSION['avatar_url']); ?>">
                <?php else: ?>
                  <span class="font-bold text-primary text-sm">
                    <?= strtoupper(substr(trim($_SESSION['name'] ?? 'M'), 0, 1)); ?>
                  </span>
                <?php endif; ?>
                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-tertiary-container rounded-full ring-2 ring-white"></div>
              </div>
              <div class="hidden xl:flex flex-col text-left">
                <span class="text-label-md font-label-md text-on-surface line-clamp-1 max-w-[130px] font-semibold"><?= e($_SESSION['name'] ?? 'Mahasiswa'); ?></span>
                <span class="text-label-sm font-label-sm text-tertiary flex items-center gap-0.5">
                  <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                  <?= (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'Admin SIM' : 'Polinema'; ?>
                </span>
              </div>
              <span class="material-symbols-outlined text-outline text-[18px] transition-transform duration-200 hidden sm:inline-block" id="userMenuArrow">keyboard_arrow_down</span>
            </button>

            <!-- Dropdown Menu List -->
            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-64 rounded-2xl bg-surface-container-lowest border border-outline-variant/60 shadow-xl py-2 z-50">
              <!-- Header Info -->
              <div class="px-4 py-3 border-b border-outline-variant/40">
                <p class="text-label-md font-bold text-on-surface line-clamp-1"><?= e($_SESSION['name'] ?? 'Mahasiswa Polinema'); ?></p>
                <?php if (!empty($_SESSION['nim'])): ?>
                  <p class="text-body-sm text-on-surface-variant font-medium text-[11px] mt-0.5">NIM: <?= e($_SESSION['nim']); ?></p>
                <?php endif; ?>
                <?php if (!empty($_SESSION['email'])): ?>
                  <p class="text-body-sm text-outline truncate text-[11px] mt-0.5"><?= e($_SESSION['email']); ?></p>
                <?php endif; ?>
                <div class="mt-2 flex items-center gap-1.5">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold <?= (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'bg-error-container text-on-error-container' : 'bg-primary-fixed text-on-primary-fixed-variant'; ?>">
                    <?= (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'ADMINISTRATOR' : 'MAHASISWA'; ?>
                  </span>
                </div>
              </div>

              <!-- Menu Links -->
              <div class="py-1.5">
                <a href="<?= BASE_URL; ?>barang/kelola.php" class="flex items-center gap-3 px-4 py-2 text-body-sm text-on-surface hover:bg-surface-container transition-colors">
                  <span class="material-symbols-outlined text-[20px] text-primary" data-icon="inventory_2">inventory_2</span>
                  <span class="font-medium">Barang Jualan Saya</span>
                </a>

                <a href="<?= BASE_URL; ?>transaksi/index.php" class="flex items-center gap-3 px-4 py-2 text-body-sm text-on-surface hover:bg-surface-container transition-colors">
                  <span class="material-symbols-outlined text-[20px] text-secondary" data-icon="receipt_long">receipt_long</span>
                  <span class="font-medium">Riwayat Transaksi (COD)</span>
                </a>

                <a href="<?= BASE_URL; ?>barang/favorit.php" class="flex items-center gap-3 px-4 py-2 text-body-sm text-on-surface hover:bg-surface-container transition-colors">
                  <span class="material-symbols-outlined text-[20px] text-error" data-icon="favorite">favorite</span>
                  <span class="font-medium">Barang Disimpan</span>
                </a>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                  <a href="<?= BASE_URL; ?>admin/index.php" class="flex items-center gap-3 px-4 py-2 text-body-sm text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[20px] text-tertiary" data-icon="admin_panel_settings">admin_panel_settings</span>
                    <span class="font-medium">Dashboard Admin SIM</span>
                  </a>
                <?php endif; ?>
              </div>

              <!-- Divider -->
              <div class="border-t border-outline-variant/40 my-1"></div>

              <!-- Logout -->
              <div class="px-1.5 py-1">
                <a href="<?= BASE_URL; ?>auth/logout.php" class="flex items-center gap-3 px-3 py-2 rounded-xl text-body-sm text-error hover:bg-error-container/40 transition-colors font-semibold">
                  <span class="material-symbols-outlined text-[20px]" data-icon="logout">logout</span>
                  <span>Keluar dari Akun</span>
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <!-- Tampilan Saat Belum Login -->
          <div class="flex items-center gap-1.5 sm:gap-2">
            <a href="<?= BASE_URL; ?>auth/login.php" class="px-3 py-1.5 rounded-xl text-label-md font-label-md text-primary hover:bg-surface-container transition-colors font-semibold">
              Masuk
            </a>
            <a href="<?= BASE_URL; ?>auth/register.php" class="px-3.5 py-1.5 rounded-xl text-label-md font-label-md bg-primary-fixed text-on-primary-fixed-variant hover:bg-primary/20 transition-colors font-semibold shadow-xs">
              Daftar
            </a>
          </div>
        <?php endif; ?>

        <!-- Primary CTA: Jual Barang -->
        <a href="<?= BASE_URL; ?>barang/tambah.php" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-xl text-label-md font-label-md flex items-center gap-1.5 active:scale-[0.98] transition-all shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-[18px]" data-icon="add_circle">add_circle</span>
          <span class="hidden sm:inline">Jual Barang</span>
          <span class="sm:hidden">Jual</span>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN CANVAS -->
  <main class="flex-grow w-full max-w-7xl mx-auto px-gutter-desktop py-space-md space-y-space-xl">
    <!-- Flash Notification (jika ada) -->
    <?php display_flash(); ?>
