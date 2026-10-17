<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/helpers.php';

// Menentukan BASE_URL secara dinamis agar link aset dan halaman selalu valid dari folder manapun
if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        $basePath = substr($projectRoot, strlen($docRoot));
    } else {
        $basePath = '';
    }
    define('BASE_URL', rtrim($basePath, '/') . '/');
}

$sudahLogin = isset($_SESSION['user_id']);
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

  <!-- Tailwind CSS CDN with Custom Theme Config -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          "colors": {
            "tertiary-fixed-dim": "#4edea3",
            "tertiary": "#006141",
            "surface-tint": "#0051df",
            "primary-fixed-dim": "#b5c4ff",
            "on-secondary": "#ffffff",
            "surface-bright": "#f8f9ff",
            "secondary-fixed-dim": "#7bd0ff",
            "error-container": "#ffdad6",
            "on-error": "#ffffff",
            "on-secondary-fixed-variant": "#004c69",
            "inverse-primary": "#b5c4ff",
            "surface": "#f8f9ff",
            "error": "#ba1a1a",
            "primary": "#0048c9",
            "inverse-on-surface": "#eaf1ff",
            "on-primary-container": "#edeeff",
            "on-surface-variant": "#434655",
            "on-tertiary-fixed-variant": "#005236",
            "on-surface": "#0b1c30",
            "secondary-container": "#40c2fd",
            "surface-dim": "#cbdbf5",
            "background": "#f8f9ff",
            "on-primary-fixed-variant": "#003cab",
            "outline-variant": "#c3c5d8",
            "on-primary": "#ffffff",
            "on-tertiary": "#ffffff",
            "surface-container-low": "#eff4ff",
            "on-error-container": "#93000a",
            "on-secondary-fixed": "#001e2c",
            "secondary": "#00668a",
            "tertiary-container": "#007c55",
            "on-tertiary-container": "#b5ffd7",
            "primary-container": "#1e60f3",
            "on-background": "#0b1c30",
            "on-tertiary-fixed": "#002113",
            "primary-fixed": "#dbe1ff",
            "inverse-surface": "#213145",
            "outline": "#737687",
            "surface-container-lowest": "#ffffff",
            "on-secondary-container": "#004d6a",
            "surface-container-high": "#dce9ff",
            "on-primary-fixed": "#00174d",
            "surface-container-highest": "#d3e4fe",
            "secondary-fixed": "#c4e7ff",
            "surface-variant": "#d3e4fe",
            "tertiary-fixed": "#6ffbbe",
            "surface-container": "#e5eeff"
          },
          "borderRadius": {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
          "spacing": {
            "gutter": "1rem",
            "space-xs": "0.25rem",
            "space-lg": "1.5rem",
            "space-xl": "2.5rem",
            "space-sm": "0.5rem",
            "gutter-desktop": "1.5rem",
            "margin-desktop": "2.5rem",
            "space-md": "1rem",
            "margin": "1rem"
          },
          "fontFamily": {
            "headline-md": ["Plus Jakarta Sans"],
            "body-md": ["Plus Jakarta Sans"],
            "label-md": ["Plus Jakarta Sans"],
            "headline-xl": ["Plus Jakarta Sans"],
            "headline-lg-mobile": ["Plus Jakarta Sans"],
            "label-sm": ["Plus Jakarta Sans"],
            "headline-lg": ["Plus Jakarta Sans"],
            "body-sm": ["Plus Jakarta Sans"],
            "headline-xl-mobile": ["Plus Jakarta Sans"],
            "body-lg": ["Plus Jakarta Sans"],
            "label-lg": ["Plus Jakarta Sans"]
          },
          "fontSize": {
            "headline-md": ["18px", { "lineHeight": "26px", "fontWeight": "700" }],
            "body-md": ["14px", { "lineHeight": "22px", "fontWeight": "400" }],
            "label-md": ["12px", { "lineHeight": "16px", "fontWeight": "600" }],
            "headline-xl": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "800" }],
            "headline-lg-mobile": ["20px", { "lineHeight": "28px", "fontWeight": "700" }],
            "label-sm": ["10px", { "lineHeight": "14px", "letterSpacing": "0.02em", "fontWeight": "700" }],
            "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
            "body-sm": ["12px", { "lineHeight": "18px", "fontWeight": "400" }],
            "headline-xl-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.015em", "fontWeight": "800" }],
            "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "500" }],
            "label-lg": ["14px", { "lineHeight": "20px", "fontWeight": "600" }]
          }
        }
      }
    }
  </script>
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
      <div class="flex items-center gap-3">
        <!-- Interactive Icon Actions -->
        <button class="relative p-2 rounded-full text-on-surface-variant hover:bg-surface-container dark:hover:bg-surface-container transition-colors duration-150" title="Notifikasi">
          <span class="material-symbols-outlined text-[22px]" data-icon="notifications">notifications</span>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-error"></span>
        </button>
        <button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container dark:hover:bg-surface-container transition-colors duration-150" title="Pesan Chat">
          <span class="material-symbols-outlined text-[22px]" data-icon="chat">chat</span>
        </button>
        <button class="relative p-2 rounded-full text-on-surface-variant hover:bg-surface-container dark:hover:bg-surface-container transition-colors duration-150" title="Keranjang">
          <span class="material-symbols-outlined text-[22px]" data-icon="shopping_bag">shopping_bag</span>
          <span class="absolute top-1 right-1 px-1.5 py-0.2 bg-primary text-on-primary text-[10px] font-bold rounded-full">2</span>
        </button>
        
        <div class="h-6 w-[1px] bg-outline-variant mx-1 hidden sm:block"></div>

        <!-- Student Profile Avatar / Auth Status -->
        <?php if ($sudahLogin): ?>
          <div class="flex items-center gap-2 pl-1 cursor-pointer">
            <div class="relative w-9 h-9 rounded-full ring-2 ring-primary/20 overflow-hidden bg-surface-container-high">
              <img alt="Foto profil mahasiswa" class="w-full h-full object-cover" src="<?= e($_SESSION['avatar_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkqDVer_4vkeFdhY63ufrHCj24k7iMNAZaKOd3SpQ6L4vfhY8WpRHa7lIoiPNf2YmIasuc83FHp9MjMF7TveRkz7riCO1QHVN_vGOitmosfJP9pNA9G4ATh2E3WLcGLEi6tojjwodBpadVnOETXEtBSG70GvFPVA_8ciTegyFOkQFiCrJ0UO29kjm9oOoB8NCohfxcKlZPPxOhtL5SgJhXs1xdx2TYt9Q-ph94-ZxNIYxiklPkvZzvuA'); ?>">
              <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-tertiary-container rounded-full ring-2 ring-white"></div>
            </div>
            <div class="hidden xl:flex flex-col text-left">
              <span class="text-label-md font-label-md text-on-surface line-clamp-1"><?= e($_SESSION['name'] ?? 'Mahasiswa'); ?></span>
              <span class="text-label-sm font-label-sm text-tertiary flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                Polinema
              </span>
            </div>
          </div>
        <?php else: ?>
          <!-- Tampilan saat belum login (atau mode preview mahasiswa terverifikasi) -->
          <div class="flex items-center gap-2 pl-1 cursor-pointer">
            <div class="relative w-9 h-9 rounded-full ring-2 ring-primary/20 overflow-hidden bg-surface-container-high">
              <img alt="Foto profil mahasiswa terverifikasi Politeknik Negeri Malang" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkqDVer_4vkeFdhY63ufrHCj24k7iMNAZaKOd3SpQ6L4vfhY8WpRHa7lIoiPNf2YmIasuc83FHp9MjMF7TveRkz7riCO1QHVN_vGOitmosfJP9pNA9G4ATh2E3WLcGLEi6tojjwodBpadVnOETXEtBSG70GvFPVA_8ciTegyFOkQFiCrJ0UO29kjm9oOoB8NCohfxcKlZPPxOhtL5SgJhXs1xdx2TYt9Q-ph94-ZxNIYxiklPkvZzvuA">
              <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-tertiary-container rounded-full ring-2 ring-white"></div>
            </div>
            <div class="hidden xl:flex flex-col text-left">
              <span class="text-label-md font-label-md text-on-surface line-clamp-1">Andi Pratama</span>
              <span class="text-label-sm font-label-sm text-tertiary flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                Polinema
              </span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Primary CTA: Jual Barang -->
        <a href="<?= BASE_URL; ?>barang/tambah.php" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-xl text-label-md font-label-md flex items-center gap-1.5 active:scale-[0.98] transition-all shadow-sm">
          <span class="material-symbols-outlined text-[18px]" data-icon="add_circle">add_circle</span>
          <span>Jual Barang</span>
        </a>
      </div>
    </div>
  </header>

  <!-- MAIN CANVAS -->
  <main class="flex-grow w-full max-w-7xl mx-auto px-gutter-desktop py-space-md space-y-space-xl">
    <!-- Flash Notification (jika ada) -->
    <?php tampil_flash(); ?>
