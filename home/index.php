<?php
$page_title = "Marketplace Preloved Mahasiswa Polinema";

// Data Dummy Kategori Populer
$popularCategories = [
    ['name' => 'Buku', 'icon' => 'menu_book', 'slug' => 'buku'],
    ['name' => 'Elektronik', 'icon' => 'devices', 'slug' => 'elektronik'],
    ['name' => 'Fashion', 'icon' => 'checkroom', 'slug' => 'fashion'],
    ['name' => 'Kos', 'icon' => 'single_bed', 'slug' => 'kos'],
    ['name' => 'Alat Kuliah', 'icon' => 'draw', 'slug' => 'alat-kuliah'],
    ['name' => 'Olahraga', 'icon' => 'sports_soccer', 'slug' => 'olahraga'],
    ['name' => 'Kesehatan', 'icon' => 'sanitizer', 'slug' => 'kesehatan'],
    ['name' => 'Lainnya', 'icon' => 'more_horiz', 'slug' => 'lainnya'],
];

// Data Dummy Rekomendasi Barang
$recommendedItems = [
    [
        'id' => 1,
        'title' => 'Laptop Asus Vivobook 14',
        'category' => 'Elektronik',
        'price' => 4000000,
        'distance' => '1.2 km',
        'is_nego' => true,
        'is_verified' => true,
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBeyTq61Zw96EbWiBIH9V8iGM5AACtw2lrBlxZvIajrmZxXb2TJhwoN6HuiKfF3K--YzFADbt70Ju4BCH2_0-luMN2rHLTnZLP0-UaUFNfFDp860GaKMHv7sejK5bR_V6G_mCLwNjVftxKBj6kEC9_DqPt3rhpEyIWrUkJTPW0kP-NAVG0tjFIWFYpdG5XAw336JLKxU7ORkA0xEaCb1CYCLdh5MvFVHdMcD-H5Ax0A9dsQwAHRg3maOA'
    ],
    [
        'id' => 2,
        'title' => 'Sepatu Sneakers Casual',
        'category' => 'Fashion',
        'price' => 280000,
        'distance' => '0.8 km',
        'is_nego' => true,
        'is_verified' => true,
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDc4g-vNPH2YGXbgK647p_i36sMbzNoKZ_dFl8gD2JtAB1_KYAHb_PsnU-uAbI6yhdA3io8RPxmGcWew3amX3oyi-qmiAdTa_2q09XeHhwgTt1u60jhmPEYwLtukcrP2HdGfu8y1vdaB8B7uKYwtCZmiOp7b-XzgWxH0vfoXygaGUfOAoC0FFrVHq3kt3GoxChnHryq91j9Gbu470FR4LlH3CLxJCPy1kUowuII-3osJXPxfhKYn9Zwcw'
    ],
    [
        'id' => 3,
        'title' => 'Hoodie Oversize Hitam',
        'category' => 'Fashion',
        'price' => 120000,
        'distance' => '1.5 km',
        'is_nego' => false,
        'is_verified' => true,
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCbu5KuanTKCMyX5vgnzVqkjbkNqJLRI6e4Z1AhvzNcBkUSFm61fM-Zp2eRMKZ8eJFc2sSp-J34iLNcgY2-kb9QBbbRMI4R6wVJ9ljG9mS9WWc-8x067sE-gUat1Ds1DXN1S0iR1YhspbZvcV9nrBqOM33ZfP4K7gvnYx8xax-bmMJ775E5yDciI9-5Q6xYVg8qpJDQGJfs3SUWplTCchV5Dz1wr9znTbgzEPK7Q3qfFmfISohAJZs8WQ'
    ],
    [
        'id' => 4,
        'title' => 'iPad Gen 9 64GB WiFi',
        'category' => 'Elektronik',
        'price' => 3500000,
        'distance' => '2.1 km',
        'is_nego' => true,
        'is_verified' => true,
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB5c-lWxa3HcfI8-Vnz-SBLWo55z89AWu7kFYS1FxO3rwXAkX5AwNYCDtLVklXLYjJxkHNwGNLKRVr4jKbZUQLo5XFta_V_CYL5bOMAMYBWCbs9L7ZLZpdoAhgZNbyyTc_pWw1NS8oz2xGY6xemcTp_ooVZ0bVrReJ09LKO9FkaxGKDzaGSVaoXwQwlsVU8rrJgbPyD87bwsL1Pf-7hIfVr-QMI-iJKEEyb3uUuaYQJHtaRmAF5dNbC4g'
    ],
    [
        'id' => 5,
        'title' => 'Meja Belajar Lipat Kayu',
        'category' => 'Perlengkapan Kos',
        'price' => 350000,
        'distance' => '0.4 km',
        'is_nego' => true,
        'is_verified' => true,
        'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-FdDPnk-RCoDDKPcTrusK4BXzW-OJ5pvZJ-gr7mXA1VhpSd5R44ZO_WAUmyl4dWl_4X5t5w7kdyU29PU4ZwVL4N8UoJ0wibn2IXPSF1apFKHGmaf6tDPGL3g4-E4E9dIL7qGvSn4iRzrzhubfcUa8u-rr_lL-_1npEfTtkAEp45fCiPYVDlyjZRnY49vOFFqHy3u22J034DSfzu7fzzwAVyzo4gDzWJRGjU1b982y6yoauMIzd5K_UA'
    ]
];

// Master Layout Header
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- HERO PROMO SECTION -->
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#D9E6FF] via-[#EBF2FF] to-[#D9F5E8] p-6 md:p-10 border border-outline-variant/40 shadow-sm">
      <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
        <!-- Left Hero Content -->
        <div class="md:col-span-7 space-y-4">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-lowest text-primary text-label-sm font-label-sm shadow-sm border border-outline-variant/30">
            <span class="material-symbols-outlined text-[16px] text-tertiary" data-icon="school">school</span>
            <span>Khusus Civitas Akademika Polinema</span>
          </div>
          <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
            Barang bagus, <br class="hidden sm:inline">
            <span class="text-primary">harga mahasiswa.</span>
          </h1>
          <p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg">
            Temukan barang preloved dari mahasiswa di sekitarmu. Transaksi COD aman di dalam area kampus Politeknik Negeri Malang.
          </p>
          <!-- Quick Search Pill in Hero -->
          <div class="pt-2 max-w-md">
            <form method="GET" action="<?= BASE_URL; ?>home/index.php" class="flex items-center bg-surface-container-lowest rounded-full p-1.5 shadow-md border border-outline-variant/50 focus-within:ring-2 focus-within:ring-primary">
              <span class="material-symbols-outlined ml-3 text-outline text-[20px]" data-icon="search">search</span>
              <input name="q" class="w-full bg-transparent border-0 px-3 py-1.5 text-on-surface text-body-md font-body-md placeholder:text-outline focus:ring-0 focus:outline-none" placeholder="Cari buku, elektronik, fashion, dll..." type="text">
              <button type="submit" aria-label="Cari" class="w-10 h-10 rounded-full bg-primary hover:bg-primary-container text-on-primary flex items-center justify-center shrink-0 active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-[18px]" data-icon="arrow_forward">arrow_forward</span>
              </button>
            </form>
          </div>
        </div>
        <!-- Right Hero Visual / Illustration -->
        <div class="md:col-span-5 relative flex justify-center items-center">
          <!-- Floating Badge -->
          <div class="absolute -top-3 -right-2 md:top-2 md:right-2 z-20 bg-surface-container-lowest/95 backdrop-blur-md px-3.5 py-1.5 rounded-full shadow-lg border border-primary/20 rotate-3 flex items-center gap-1.5 text-primary text-label-md font-label-md animate-bounce duration-1000">
            <span class="material-symbols-outlined text-tertiary text-[18px]" data-icon="local_fire_department" style="font-variation-settings: 'FILL' 1;">local_fire_department</span>
            <span>Good Stuff, Better Price!</span>
          </div>
          <!-- Hero Image Showcase -->
          <div class="relative w-full max-w-sm rounded-xl overflow-hidden shadow-lg border border-white/60 bg-surface-container-lowest">
            <img alt="Peralatan Kuliah Mahasiswa Polinema" class="w-full h-56 object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuABM57go_UdZhD_p2pTzOXRHH3sDQWOoOkgKX6xx1G4QYgZay0zYslV6FwftHhZ4Iewg4oiVm1M6akvcIAmPAmulwtuuegQivyKEJ-OD43RredEKX0xFtQiyXu-s-MrB5kLkHTPwB2RUGFbPCNcX2Y2wIMU_DTZkBNDAIFnUOnupgKf30umW76PCevFDsKgWCun6m-bxJO8Hst-XJe-tAn4YD0WC4wWp0-mD2XgTAM4qcXP0HbAyE3lHA">
            <div class="absolute bottom-2 left-3 bg-surface-container-lowest/90 backdrop-blur-sm px-2.5 py-1 rounded-lg text-label-sm font-label-sm text-on-surface flex items-center gap-1.5 shadow-sm">
              <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
              <span>120+ Mahasiswa Online di Kampus</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CAMPUS TRUST & VERIFICATION BANNER -->
    <section class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
        <!-- Left: Verified Campus Seal -->
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-primary/20 flex items-center justify-center shrink-0 shadow-inner">
            <span class="material-symbols-outlined text-primary text-[32px]" style="font-variation-settings: 'FILL' 1;">verified_user</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h2 class="font-headline-md text-headline-md text-on-surface">Mahasiswa Terverifikasi</h2>
              <span class="px-2 py-0.5 rounded-full bg-tertiary-container/15 text-tertiary text-label-sm font-label-sm font-bold">100% Valid Polinema</span>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant">Semua akun penjual dan pembeli terhubung dengan email resmi NIM mahasiswa aktif.</p>
          </div>
        </div>
        <!-- Right: 3 Key Pillars -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
          <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-surface-container-low/80 border border-outline-variant/40">
            <span class="material-symbols-outlined text-primary text-[20px]" data-icon="shield">shield</span>
            <span class="font-label-md text-label-md text-on-surface">Transaksi Lebih Aman</span>
          </div>
          <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-surface-container-low/80 border border-outline-variant/40">
            <span class="material-symbols-outlined text-primary text-[20px]" data-icon="pin_drop">pin_drop</span>
            <span class="font-label-md text-label-md text-on-surface">Jarak Penjual Terlihat</span>
          </div>
          <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl bg-surface-container-low/80 border border-outline-variant/40">
            <span class="material-symbols-outlined text-primary text-[20px]" data-icon="handshake">handshake</span>
            <span class="font-label-md text-label-md text-on-surface">Bisa Nego Harga</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ROUNDED CATEGORY BAR -->
    <section id="kategori" class="space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="font-headline-md text-headline-md text-on-surface">Kategori Populer</h2>
        <a class="text-primary text-label-md font-label-md flex items-center gap-0.5 hover:underline" href="<?= BASE_URL; ?>barang/index.php">
          Lihat Semua
          <span class="material-symbols-outlined text-[16px]" data-icon="chevron_right">chevron_right</span>
        </a>
      </div>
      <!-- Category Pills Grid (Generated via Loop) -->
      <div class="grid grid-cols-4 sm:grid-cols-4 md:grid-cols-8 gap-3">
        <?php foreach ($popularCategories as $category): ?>
          <a class="flex flex-col items-center p-3 rounded-2xl bg-surface-container-lowest border border-outline-variant hover:border-primary hover:shadow-md transition-all group text-center" href="<?= BASE_URL; ?>barang/index.php?kategori=<?= urlencode($category['slug']); ?>">
            <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors mb-2">
              <span class="material-symbols-outlined text-[24px]"><?= e($category['icon']); ?></span>
            </div>
            <span class="font-label-md text-label-md text-on-surface group-hover:text-primary transition-colors"><?= e($category['name']); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- REKOMENDASI UNTUK KAMU SECTION -->
    <section class="space-y-5">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="font-headline-lg text-headline-lg text-on-surface">Rekomendasi Untuk Kamu</h2>
          <p class="font-body-sm text-body-sm text-on-surface-variant">Barang preloved terdekat dari gedung kuliah dan asrama Polinema</p>
        </div>
        <a class="text-primary text-label-md font-label-md flex items-center gap-1 hover:underline" href="<?= BASE_URL; ?>barang/index.php">
          Lihat Semua (<?= count($recommendedItems); ?>)
          <span class="material-symbols-outlined text-[16px]" data-icon="arrow_forward">arrow_forward</span>
        </a>
      </div>

      <!-- 5-Item Product Card Grid (Generated via Clean Reusable Card Pattern) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <?php foreach ($recommendedItems as $item): ?>
          <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden hover:shadow-md transition-all flex flex-col group">
            <!-- Image Container -->
            <div class="relative w-full aspect-square overflow-hidden bg-surface-container-low">
              <a href="<?= BASE_URL; ?>barang/detail.php?id=<?= $item['id']; ?>" class="block w-full h-full">
                <img alt="<?= e($item['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="<?= e($item['image_url']); ?>">
              </a>
              
              <!-- Nego Pill Badge (if applicable) -->
              <?php if (!empty($item['is_nego'])): ?>
                <div class="absolute top-2.5 left-2.5 bg-tertiary-container text-on-tertiary px-2 py-0.5 rounded-full text-label-sm font-label-sm flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-outlined text-[12px]" data-icon="sell">sell</span>
                  <span>Bisa Nego</span>
                </div>
              <?php endif; ?>

              <!-- Favorite Action -->
              <button type="button" aria-label="Favorit" class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-outline hover:text-error transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]" data-icon="favorite">favorite</span>
              </button>
            </div>

            <!-- Body Details -->
            <div class="p-3.5 flex flex-col flex-1 justify-between gap-2.5">
              <div>
                <span class="text-label-sm font-label-sm text-outline uppercase tracking-wider"><?= e($item['category']); ?></span>
                <h3 class="font-headline-md text-headline-md text-on-surface line-clamp-1 group-hover:text-primary transition-colors">
                  <a href="<?= BASE_URL; ?>barang/detail.php?id=<?= $item['id']; ?>">
                    <?= e($item['title']); ?>
                  </a>
                </h3>
                <p class="font-headline-md text-headline-md text-primary mt-1">
                  <?= format_rupiah($item['price']); ?>
                </p>
              </div>
              
              <div class="pt-2 border-t border-outline-variant/40 flex items-center justify-between text-body-sm font-body-sm">
                <div class="flex items-center gap-1 text-on-surface-variant">
                  <span class="material-symbols-outlined text-[15px] text-outline" data-icon="pin_drop">pin_drop</span>
                  <span><?= e($item['distance']); ?></span>
                </div>
                <?php if (!empty($item['is_verified'])): ?>
                  <div class="flex items-center gap-1 text-tertiary font-label-sm font-label-sm">
                    <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <span>Verified</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- COMMUNITY BENTO PROMO / SAFE COD TIPS -->
    <section id="bantuan" class="grid grid-cols-1 md:grid-cols-12 gap-5 pt-2">
      <!-- Left Bento Card: COD Safety -->
      <div class="md:col-span-8 bg-surface-container-low rounded-2xl p-6 border border-outline-variant/50 relative overflow-hidden flex flex-col justify-between">
        <div class="relative z-10 max-w-lg space-y-2">
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-secondary text-label-sm font-label-sm shadow-sm border border-outline-variant">
            <span class="material-symbols-outlined text-[16px]" data-icon="lock">lock</span>
            <span>Panduan Aman Kampus Polinema</span>
          </div>
          <h3 class="font-headline-lg text-headline-lg text-on-surface">Titik Temu COD Paling Direkomendasikan</h3>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Untuk keamanan maksimal, selalu lakukan transaksi serah terima barang di area ramai seperti Gazebo Gedung Sipil, Perpustakaan Pusat Polinema, Kantin Graha Polinema, atau Pos Satpam Utama.
          </p>
        </div>
        <div class="relative z-10 pt-4 flex flex-wrap gap-2.5">
          <span class="px-3 py-1.5 rounded-xl bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-primary text-[16px]" data-icon="local_library">local_library</span>
            Perpustakaan Pusat
          </span>
          <span class="px-3 py-1.5 rounded-xl bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-primary text-[16px]" data-icon="storefront">storefront</span>
            Kantin Utama
          </span>
          <span class="px-3 py-1.5 rounded-xl bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-outline-variant flex items-center gap-1.5 shadow-sm">
            <span class="material-symbols-outlined text-primary text-[16px]" data-icon="flag">flag</span>
            Gazebo Mesin
          </span>
        </div>
      </div>
      <!-- Right Bento Card: Student Support -->
      <div class="md:col-span-4 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-2xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden">
        <div class="space-y-3 relative z-10">
          <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
            <span class="material-symbols-outlined text-[22px]" data-icon="volunteer_activism">volunteer_activism</span>
          </div>
          <h3 class="font-headline-md text-headline-md text-white">Beli Cerdas, Support Sesama Mahasiswa!</h3>
          <p class="font-body-sm text-body-sm text-white/90">
            Dukung sirkulasi ekonomi kampus yang hemat, berkelanjutan, dan saling membantu antar angkatan.
          </p>
        </div>
        <div class="pt-4 relative z-10">
          <a class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-lowest text-primary rounded-xl font-label-md text-label-md shadow hover:bg-surface-bright transition-colors" href="#">
            <span>Pelajari Tips Jual Cepat</span>
            <span class="material-symbols-outlined text-[16px]" data-icon="arrow_forward">arrow_forward</span>
          </a>
        </div>
      </div>
    </section>

<?php

// Master Layout Footer
require_once __DIR__ . '/../includes/footer.php';
?>