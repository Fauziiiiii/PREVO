<?php
/**
 * PREVO - Modul Katalog & Detail Barang
 * Halaman Detail Spesifikasi, Kontak Penjual, & Booking COD Mahasiswa
 */

$itemId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 1;

// Data Dummy Detail Barang (Sesuai skema tabel items, categories, & users)
$item = [
    'id'               => $itemId,
    'title'            => 'Laptop Asus Zenbook OLED UX3402',
    'price'            => 10000000,
    'original_price'   => 12000000,
    'discount_pct'     => 17,
    'condition'        => 'bekas_baik',
    'condition_label'  => 'Bekas - Kondisi Baik (95%)',
    'status'           => 'tersedia',
    'category_name'    => 'Elektronik',
    'subcategory'      => 'Laptop & Komputer',
    'posted_ago'       => '2 hari yang lalu',
    'location_info'    => 'Belakang Gedung Mesin / Gazebo Sipil · 300m dari lokasimu',
    'is_nego'          => true,
    'specs'            => [
        ['label' => 'Processor', 'value' => 'Intel Core i5 (Gen 12) 1240P', 'icon' => 'memory'],
        ['label' => 'RAM', 'value' => '8 GB LPDDR5 Dual-Channel', 'icon' => 'developer_board'],
        ['label' => 'Penyimpanan', 'value' => '512 GB NVMe PCIe 4.0 SSD', 'icon' => 'hard_drive'],
        ['label' => 'Ukuran Layar', 'value' => '14 inch 2.8K OLED (90Hz, 100% DCI-P3)', 'icon' => 'laptop_chromebook'],
        ['label' => 'Sistem Operasi', 'value' => 'Windows 11 Home Original (Licensed)', 'icon' => 'window', 'span' => 2],
    ],
    'description'      => "Pemakaian pribadi untuk pengerjaan tugas akhir di Jurusan Teknologi Informasi Polinema. Baterai sangat awet (6-7 jam untuk pemakaian office dan coding ringan). Layar OLED sangat tajam dan jernih, no minus, no dead pixel.\n\nKelengkapan:\n- Unit Laptop Asus Zenbook OLED\n- Charger 65W Type-C Fast Charging Original\n- Box + Manual Book lengkap\n- Free Sleeve Pouch original Asus\n\nBisa langsung tes unit dan cek performa sepuasnya saat ketemuan COD di area kampus Polinema!",
    'images'           => [
        'https://lh3.googleusercontent.com/aida-public/AB6AXuAqSBuga10xj_BUwz0sFtR6uyz7XiAllHevbfO6cvlGacIcqLVsfKhxdO4RLMt0gPEbBe-tz_DNz119My9bt0OwG5YDb4DzXD5tAUwrB1MjBRSg0LC8RrRKMa6dXQKymEkWvOVW4AQmw2b0hWZ0gyPmFGQk502vpFxNnp-lu0y83LQ8Zt1XuILnpQnqSinPe_lHgQJk3vTHmufFtpzH7CpRa8mMucPCXwrC2aQZ-QcnEc_gHovm7RTCUg',
        'https://lh3.googleusercontent.com/aida-public/AB6AXuAfcM4NpIGIUqalQLFYEv0XGhYzx5EhU7PvRh9nq9To70bcSBU9oKa8FtOBmaWMUrIhZKUORBSd6x1RzLEHtCqOTw0Sce2lM8p_TAnLCltvF2FNac8f8E_SYTbXh8YqG_yWqGwcbB9TR0wuNDP3P6zp_usD2AwvpSNdmYI4fWiwJc70OkIJt_Z26FhujHSMQx6A9HXWTUZnHx0tREebthS9n6c4sznxsLC3xt5JGRLAiw0-BnartwTXXQ',
        'https://lh3.googleusercontent.com/aida-public/AB6AXuC5cCZLNyh4CwIVbOgAMTUbeGX_ZZz0mDRkS85uqru7crw-4k1wUJ4m4JcF2PePU5QZwfvkzMK42bP3JXrwGrThlCikah0Tte6Ci4xSJRnfn4NPFmUOGn7Gq8nSTpanUH5n2nohR66YJR3gS6i2knS_rGsy1rjcf-SiZxeWeloQ2m9nzJu20vz9GiFU35RprulgiNrcGUSeAzHi5Im9mbEy5aT5w6lfnyajZ-0cq9jrsQgTP_xYAAMXww',
        'https://lh3.googleusercontent.com/aida-public/AB6AXuArmwqmIM-afKvOl-uTZl1jWc3bVEw1nUJnSi5c4Zs4kiUtLJHglPzC0vbDZ2cVXXhri6dH97Y2PMlr-A2w-as69_MHT7z7RIn8m_CZ6cduE-gwoVbODRecnSbouk_SXs9SB1e6TKASZJJKVhjcLFJRiZsfXumCi23EzPDgFJ14ok3EymAIiuDQLH6fFjRDQ6QKoU2WvASI5p4uffxlaW6qJDL8yYTz06KiJXKTvlxEv3CqclsPw2NtEg'
    ],
    'seller'           => [
        'id'            => 10,
        'name'          => 'Andi Pratama',
        'nim'           => '2241720088',
        'department'    => 'Teknik Informatika',
        'prodi'         => 'D4 Teknik Informatika - Kelas TI 3G',
        'rating'        => 4.8,
        'reviews_count' => 24,
        'is_verified'   => true,
        'avatar_url'    => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDfJEqtzX6zvgia9hV99Tz0c8D66x-aBcQaMdg32Y9f6hLuUvYcZWvo7n2mvWIhV6wlCOAE4GTyv7WSkifngtf03GSiIGDiGqunrvV0K4aeqFgoA0R9e3TRUEX4b2p6oCJQ5er97VV1PN2tTsXCpETzbEdL0KshNvhiEbXUDRpH4jPCpFI-X-phzbZEydDXf5tMdFPuwWe-7oaH5IMwM-WHn29FyPrDeA9GIlrAp2PwLlO6v2aRlVP0sQ',
        'whatsapp'      => '6281234567890',
        'instagram'     => 'andipratama_ti',
        'telegram'      => 'andipratama'
    ]
];

$page_title = $item['title'];
$active_nav = "jelajah";

// Layout Master Header PREVO
require_once __DIR__ . '/../includes/header.php';
?>

<!-- BREADCRUMBS NAV -->
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm mb-4 flex-wrap">
    <a class="hover:text-primary transition-colors" href="<?= BASE_URL; ?>home/index.php">Beranda</a>
    <span class="material-symbols-outlined text-xs text-outline" data-icon="chevron_right">chevron_right</span>
    <a class="hover:text-primary transition-colors" href="<?= BASE_URL; ?>barang/index.php">Jelajah</a>
    <span class="material-symbols-outlined text-xs text-outline" data-icon="chevron_right">chevron_right</span>
    <a class="hover:text-primary transition-colors" href="<?= BASE_URL; ?>barang/index.php?kategori=elektronik"><?= e($item['category_name']); ?></a>
    <span class="material-symbols-outlined text-xs text-outline" data-icon="chevron_right">chevron_right</span>
    <span class="text-on-surface font-label-md text-label-md truncate max-w-xs sm:max-w-md"><?= e($item['title']); ?></span>
</nav>

<!-- PRODUCT DETAIL GRID (2 COLUMNS) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- LEFT COLUMN: Image Gallery & Detailed Tabs (7 Cols) -->
    <div class="lg:col-span-7 flex flex-col gap-6">
        <!-- Gallery Container -->
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant shadow-sm">
            <div class="flex flex-col-reverse md:flex-row gap-4">
                <!-- Thumbnails Column -->
                <div class="flex md:flex-col gap-3 overflow-x-auto overflow-y-hidden md:overflow-visible [scrollbar-width:none] [&::-webkit-scrollbar]:hidden shrink-0 pb-1 md:pb-0">
                    <?php foreach ($item['images'] as $index => $imgUrl): ?>
                        <button
                            type="button"
                            onclick="changeGalleryImage('<?= e($imgUrl); ?>', this)"
                            class="thumb-btn w-16 h-16 rounded-xl overflow-hidden border-2 <?= $index === 0 ? 'border-primary ring-2 ring-primary/20' : 'border-outline-variant hover:border-primary/60 opacity-80 hover:opacity-100'; ?> p-0.5 focus:outline-none shrink-0 transition-all cursor-pointer">
                            <img class="w-full h-full object-cover rounded-lg" src="<?= e($imgUrl); ?>" alt="Thumbnail <?= $index + 1; ?>">
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Hero Main Photo -->
                <div class="relative flex-1 aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-outline-variant group">
                    <img
                        id="mainProductImg"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                        src="<?= e($item['images'][0]); ?>"
                        alt="<?= e($item['title']); ?>">

                    <!-- Floating 'Nego' Badge -->
                    <?php if (!empty($item['is_nego'])): ?>
                        <div class="absolute top-3.5 right-3.5 bg-primary-container text-on-primary font-label-md text-label-md px-3 py-1 rounded-full shadow-sm flex items-center gap-1 backdrop-blur-sm">
                            <span class="material-symbols-outlined text-sm" data-icon="handshake">handshake</span>
                            <span>Bisa Nego</span>
                        </div>
                    <?php endif; ?>

                    <!-- Zoom / Expand Action -->
                    <button
                        type="button"
                        onclick="window.open(document.getElementById('mainProductImg').src, '_blank')"
                        class="absolute bottom-3.5 right-3.5 w-9 h-9 rounded-full bg-surface-container-lowest/90 hover:bg-surface-container-lowest text-on-surface flex items-center justify-center shadow-md transition-all hover:scale-105"
                        title="Lihat Foto Ukuran Penuh">
                        <span class="material-symbols-outlined text-lg" data-icon="zoom_in">zoom_in</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabbed Information Panel (Spesifikasi, Deskripsi, Lokasi COD) -->
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 shadow-sm">
            <!-- Tab Navigation Bar -->
            <div class="flex border-b border-outline-variant gap-6 sm:gap-8 mb-6 overflow-x-auto overflow-y-hidden [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <button
                    type="button"
                    onclick="switchTab('specs', this)"
                    class="tab-btn text-primary border-primary font-label-lg text-label-lg border-b-2 pb-3 flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-base" data-icon="tune">tune</span>
                    <span>Spesifikasi</span>
                </button>
                <button
                    type="button"
                    onclick="switchTab('desc', this)"
                    class="tab-btn text-on-surface-variant hover:text-primary border-transparent font-label-lg text-label-lg border-b-2 pb-3 transition-colors flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-base" data-icon="description">description</span>
                    <span>Deskripsi Lengkap</span>
                </button>
                <button
                    type="button"
                    onclick="switchTab('cod', this)"
                    class="tab-btn text-on-surface-variant hover:text-primary border-transparent font-label-lg text-label-lg border-b-2 pb-3 transition-colors flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <span class="material-symbols-outlined text-base" data-icon="location_on">location_on</span>
                    <span>Lokasi COD Kampus</span>
                </button>
            </div>

            <!-- Tab 1: Specifications Content -->
            <div id="tab-specs" class="tab-content">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3.5 gap-x-5">
                    <?php foreach ($item['specs'] as $spec): ?>
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/60 <?= (!empty($spec['span']) && $spec['span'] === 2) ? 'sm:col-span-2' : ''; ?>">
                            <span class="text-on-surface-variant font-body-sm text-body-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-primary" data-icon="<?= e($spec['icon']); ?>"><?= e($spec['icon']); ?></span>
                                <?= e($spec['label']); ?>
                            </span>
                            <span class="text-on-surface font-label-md text-label-md font-semibold"><?= e($spec['value']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Quick Seller Notes Preview -->
                <div class="mt-6 pt-5 border-t border-outline-variant/60 text-on-surface-variant font-body-md text-body-md">
                    <h4 class="text-on-surface font-label-lg text-label-lg mb-2">Ringkasan Pemakaian:</h4>
                    <p class="leading-relaxed text-on-surface-variant text-body-md">
                        Kondisi fisik mulus terawat 95%, tidak pernah terjatuh atau terkena tumpahan cairan. Baterai masih prima dan layar jernih. Silakan cek langsung saat COD kampus.
                    </p>
                </div>
            </div>

            <!-- Tab 2: Full Description Content -->
            <div id="tab-desc" class="tab-content hidden">
                <div class="prose max-w-none text-on-surface font-body-md text-body-md leading-relaxed whitespace-pre-line">
                    <?= e($item['description']); ?>
                </div>
            </div>

            <!-- Tab 3: COD Campus Meeting Points -->
            <div id="tab-cod" class="tab-content hidden space-y-4">
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Untuk menjamin keamanan dan kenyamanan sesama civitas akademika Polinema, berikut titik temu COD yang sangat disarankan:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/70 flex gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl" data-icon="local_cafe">local_cafe</span>
                        <div>
                            <h5 class="font-label-lg text-label-lg text-on-surface">Kantin Gedung Teknik & Mesin</h5>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Spot terang dan ramai, cocok untuk tes fisik unit laptop & santai.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/70 flex gap-3">
                        <span class="material-symbols-outlined text-tertiary text-2xl" data-icon="menu_book">menu_book</span>
                        <div>
                            <h5 class="font-label-lg text-label-lg text-on-surface">Lobi Perpustakaan Pusat Polinema</h5>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Tersedia banyak stop kontak meja untuk tes charger & baterai.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/70 flex gap-3">
                        <span class="material-symbols-outlined text-secondary text-2xl" data-icon="deck">deck</span>
                        <div>
                            <h5 class="font-label-lg text-label-lg text-on-surface">Gazebo Jurusan Sipil / Elektro</h5>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Area teduh dan mudah dijangkau dari arah parkiran motor.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/70 flex gap-3">
                        <span class="material-symbols-outlined text-outline text-2xl" data-icon="theater_comedy">theater_comedy</span>
                        <div>
                            <h5 class="font-label-lg text-label-lg text-on-surface">Graha Teater Polinema</h5>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Spot publik terbuka di samping lapangan utama Polinema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Purchase, Nego & Seller Interaction (5 Cols) -->
    <div class="lg:col-span-5 flex flex-col gap-6">
        <!-- Main Price & Info Card -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm flex flex-col gap-5">
            <!-- Top Row: Verified Tag & Share -->
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-sm text-label-sm border border-tertiary-fixed-dim">
                    <span class="material-symbols-outlined text-sm font-bold text-tertiary" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <span>Verified Student Seller</span>
                </span>
                <button
                    type="button"
                    onclick="shareProduct()"
                    class="text-on-surface-variant hover:text-primary p-2 rounded-full hover:bg-surface-container transition-colors cursor-pointer"
                    title="Salin Link Produk">
                    <span class="material-symbols-outlined text-xl" data-icon="share">share</span>
                </button>
            </div>

            <!-- Product Title -->
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface leading-tight tracking-tight">
                    <?= e($item['title']); ?>
                </h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <span class="font-label-md text-label-md px-2.5 py-0.5 rounded-lg bg-surface-container text-on-surface-variant">
                        Kondisi: <?= e($item['condition_label']); ?>
                    </span>
                    <span class="text-outline text-xs">&bull;</span>
                    <span class="text-on-surface-variant font-body-sm text-body-sm"><?= e($item['posted_ago']); ?></span>
                    <span class="text-outline text-xs">&bull;</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-tertiary-container/20 text-tertiary uppercase">
                        <?= e($item['status']); ?>
                    </span>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="pt-2 border-t border-outline-variant/50">
                <div class="flex items-baseline gap-3">
                    <span class="font-headline-xl text-headline-xl text-primary font-bold">
                        <?= format_rupiah($item['price']); ?>
                    </span>
                    <?php if (!empty($item['original_price'])): ?>
                        <span class="line-through text-outline font-body-md text-body-md">
                            <?= format_rupiah($item['original_price']); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($item['discount_pct'])): ?>
                        <span class="px-2 py-0.5 rounded-md bg-error-container text-on-error-container font-label-sm text-label-sm font-bold">
                            -<?= e($item['discount_pct']); ?>%
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Campus Distance Tag -->
            <div class="flex items-center gap-2.5 p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/70 text-on-surface">
                <span class="material-symbols-outlined text-primary text-xl shrink-0" data-icon="location_on">location_on</span>
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface">Lokasi COD Kampus Polinema</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant"><?= e($item['location_info']); ?></span>
                </div>
            </div>

            <!-- NEGO ACCELERATOR BOX (Ajukan Penawaran) -->
            <?php if (!empty($item['is_nego'])): ?>
                <div class="p-4 rounded-xl bg-surface-container border border-primary/20 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary font-bold text-lg" data-icon="price_change">price_change</span>
                            <span class="font-label-lg text-label-lg text-on-surface">Harga Nego</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm font-bold">
                            Bisa Nego Santai
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-snug">
                        Penjual menerima penawaran harga. Masukkan harga penawaranmu yang wajar untuk mulai negosiasi.
                    </p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline font-label-md text-label-md">Rp</span>
                            <input
                                id="quickOfferInput"
                                class="w-full pl-9 pr-3 py-2 bg-surface-container-lowest rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary text-on-surface font-label-lg text-label-lg"
                                type="text"
                                value="9.500.000">
                        </div>
                        <button
                            type="button"
                            onclick="openNegoModal(document.getElementById('quickOfferInput').value)"
                            class="px-4 py-2 bg-primary hover:bg-primary-container text-on-primary rounded-xl font-label-md text-label-md transition-all active:scale-[0.98] shadow-sm flex items-center gap-1 shrink-0 cursor-pointer">
                            <span>Tawar Harga</span>
                            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SELLER PROFILE CARD -->
            <div class="p-4 rounded-xl border border-outline-variant bg-surface-container-lowest flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="relative shrink-0">
                        <img
                            class="w-12 h-12 rounded-full object-cover border border-outline-variant"
                            src="<?= e($item['seller']['avatar_url']); ?>"
                            alt="<?= e($item['seller']['name']); ?>">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-tertiary-container border-2 border-surface-container-lowest rounded-full"></span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-label-lg text-label-lg text-on-surface"><?= e($item['seller']['name']); ?></span>
                            <span class="material-symbols-outlined text-primary text-sm" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface-variant"><?= e($item['seller']['department']); ?></span>
                        <div class="flex items-center gap-1 mt-0.5 text-xs text-on-surface-variant font-label-sm text-label-sm">
                            <span class="material-symbols-outlined text-amber-500 text-sm" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="font-bold text-on-surface"><?= e($item['seller']['rating']); ?></span>
                            <span class="text-outline">(<?= e($item['seller']['reviews_count']); ?> ulasan)</span>
                            <span class="text-outline mx-1">&bull;</span>
                            <span>Mahasiswa Polinema</span>
                        </div>
                    </div>
                </div>
                <a class="font-label-md text-label-md text-primary hover:underline shrink-0" href="#">Lihat Profil</a>
            </div>

            <!-- PRIMARY ACTION CTA BUTTONS -->
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="button"
                    onclick="toggleWishlist(this)"
                    class="p-3 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant hover:text-error transition-colors flex items-center justify-center shrink-0 cursor-pointer"
                    title="Simpan ke Wishlist">
                    <span class="material-symbols-outlined text-2xl" data-icon="favorite_border">favorite_border</span>
                </button>
                <button
                    type="button"
                    onclick="document.getElementById('contact-modal').classList.remove('hidden')"
                    class="flex-1 py-3 px-5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg transition-all active:scale-[0.98] shadow-md flex items-center justify-center gap-2 cursor-pointer font-semibold">
                    <span class="material-symbols-outlined text-xl" data-icon="chat">chat</span>
                    <span>Hubungi Penjual / Beli</span>
                </button>
            </div>

            <!-- Trust & Safe Transaction Micro-copy -->
            <div class="flex items-center justify-center gap-2 text-outline font-body-sm text-body-sm pt-1">
                <span class="material-symbols-outlined text-base text-tertiary" data-icon="security">security</span>
                <span>Jaminan Transaksi Aman & COD Langsung di Area Kampus</span>
            </div>
        </div>

        <!-- Campus Safety Assurance Pill Bento -->
        <div class="grid grid-cols-2 gap-3">
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/70 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-primary text-xl shrink-0" data-icon="shield_person">shield_person</span>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">NIM Terdaftar</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-0.5">Penjual terverifikasi lewat email kampus @polinema.ac.id</p>
                </div>
            </div>
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/70 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-tertiary text-xl shrink-0" data-icon="storefront">storefront</span>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">Titik Temu Resmi</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-0.5">Rekomendasi COD di Gazebo, Kantin Teknik, atau Perpustakaan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: HUBUNGI PENJUAL (Direct Channels)                     -->
<!-- ============================================================== -->
<div class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden" id="contact-modal">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-2xl border border-outline-variant shadow-2xl p-6 relative flex flex-col gap-5">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-outline-variant/60 pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl" data-icon="contact_support">contact_support</span>
                <h3 class="font-headline-md text-headline-md text-on-surface">Hubungi Penjual</h3>
            </div>
            <button
                type="button"
                class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors cursor-pointer"
                onclick="document.getElementById('contact-modal').classList.add('hidden')">
                <span class="material-symbols-outlined text-xl" data-icon="close">close</span>
            </button>
        </div>

        <!-- Compact Seller Brief -->
        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container border border-outline-variant/60">
            <img class="w-10 h-10 rounded-full object-cover" src="<?= e($item['seller']['avatar_url']); ?>" alt="<?= e($item['seller']['name']); ?>">
            <div>
                <h4 class="font-label-lg text-label-lg text-on-surface"><?= e($item['seller']['name']); ?></h4>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Mahasiswa Terverifikasi &middot; <?= e($item['seller']['department']); ?></p>
            </div>
        </div>

        <p class="font-body-sm text-body-sm text-on-surface-variant">
            Pilih jalur komunikasi langsung untuk diskusi kondisi barang atau janjian ketemuan COD di kampus:
        </p>

        <!-- Channels List -->
        <div class="flex flex-col gap-2.5">
            <?php
            $waMessage = "Halo " . $item['seller']['name'] . ", saya sesama mahasiswa Polinema tertarik dengan barang \"" . $item['title'] . "\" di PREVO seharga " . format_rupiah($item['price']) . ". Apakah masih tersedia?";
            $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $item['seller']['whatsapp']) . "?text=" . urlencode($waMessage);
            ?>
            <!-- WhatsApp -->
            <a
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant hover:border-tertiary-container hover:bg-tertiary-fixed/20 transition-all group"
                href="<?= e($waUrl); ?>">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-tertiary-container/10 flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined text-xl" data-icon="chat_bubble">chat_bubble</span>
                    </div>
                    <div>
                        <span class="font-label-lg text-label-lg text-on-surface group-hover:text-tertiary transition-colors">Chat via WhatsApp</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant text-xs">Respon cepat untuk janjian ketemuan COD kampus</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline group-hover:text-tertiary transition-colors text-lg" data-icon="arrow_forward">arrow_forward</span>
            </a>

            <!-- Instagram -->
            <?php if (!empty($item['seller']['instagram'])): ?>
                <a
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant hover:border-primary hover:bg-surface-container transition-all group"
                    href="https://instagram.com/<?= urlencode($item['seller']['instagram']); ?>">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-xl" data-icon="photo_camera">photo_camera</span>
                        </div>
                        <div>
                            <span class="font-label-lg text-label-lg text-on-surface group-hover:text-primary transition-colors">Instagram DM</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant text-xs">@<?= e($item['seller']['instagram']); ?> (Cek profil mutual)</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-lg" data-icon="arrow_forward">arrow_forward</span>
                </a>
            <?php endif; ?>

            <!-- Telegram -->
            <?php if (!empty($item['seller']['telegram'])): ?>
                <a
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center justify-between p-3.5 rounded-xl border border-outline-variant hover:border-secondary hover:bg-secondary-fixed/20 transition-all group"
                    href="https://t.me/<?= urlencode($item['seller']['telegram']); ?>">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-xl" data-icon="send">send</span>
                        </div>
                        <div>
                            <span class="font-label-lg text-label-lg text-on-surface group-hover:text-secondary transition-colors">Telegram</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant text-xs">Chat aman tanpa membagikan nomor HP</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-secondary transition-colors text-lg" data-icon="arrow_forward">arrow_forward</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Safety Callout -->
        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant flex items-center gap-2.5">
            <span class="material-symbols-outlined text-primary text-lg shrink-0" data-icon="tips_and_updates">tips_and_updates</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">
                Tetap sopan dan selalu lakukan transaksi pembayaran langsung (COD) di area kampus Polinema.
            </span>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: AJUKAN PENAWARAN (Nego Flow & Booking Transaksi)     -->
<!-- ============================================================== -->
<div class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden" id="nego-modal">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-2xl border border-outline-variant shadow-2xl p-6 relative flex flex-col gap-5">
        <form method="POST" action="<?= BASE_URL; ?>transaksi/booking.php" id="formNego">
            <?php if (function_exists('csrf_field')) echo csrf_field(); ?>
            <input type="hidden" name="item_id" value="<?= (int) $item['id']; ?>">

            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl" data-icon="handshake">handshake</span>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Penawaran Harga COD</h3>
                </div>
                <button
                    type="button"
                    class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors cursor-pointer"
                    onclick="document.getElementById('nego-modal').classList.add('hidden')">
                    <span class="material-symbols-outlined text-xl" data-icon="close">close</span>
                </button>
            </div>

            <!-- Summary Product -->
            <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-container border border-outline-variant/60 my-4">
                <img class="w-12 h-12 rounded-lg object-cover" src="<?= e($item['images'][0]); ?>" alt="<?= e($item['title']); ?>">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface line-clamp-1"><?= e($item['title']); ?></span>
                    <span class="font-label-md text-label-md text-primary font-bold">Harga Asli: <?= format_rupiah($item['price']); ?></span>
                </div>
            </div>

            <!-- Form Inputs -->
            <div class="flex flex-col gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5" for="modalOfferPrice">
                        Harga Penawaran yang Diajukan (Rp) <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline font-label-md text-label-md">Rp</span>
                        <input
                            id="modalOfferPrice"
                            name="offer_price"
                            class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant focus:border-primary text-on-surface font-label-lg text-label-lg focus:outline-none"
                            type="number"
                            required
                            min="1000"
                            step="1000"
                            value="9500000">
                    </div>
                    <span class="text-xs text-on-surface-variant mt-1 block">Penawaran harga akan dikonfirmasi oleh penjual untuk janji COD.</span>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5" for="negoMessage">
                        Pesan Singkat & Janji Temu (Opsional)
                    </label>
                    <textarea
                        id="negoMessage"
                        name="message"
                        class="w-full p-3 bg-surface-container-low rounded-xl border border-outline-variant focus:border-primary text-on-surface font-body-sm text-body-sm resize-none focus:outline-none"
                        placeholder="Halo Andi! Boleh nego tipis? Siap COD di Gazebo Perpustakaan siang ini sehabis mata kuliah."
                        rows="3"></textarea>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end gap-2 pt-4 mt-2 border-t border-outline-variant/60">
                <button
                    type="button"
                    class="px-4 py-2 rounded-xl text-on-surface-variant hover:bg-surface-container font-label-md text-label-md transition-colors cursor-pointer"
                    onclick="document.getElementById('nego-modal').classList.add('hidden')">
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md transition-all active:scale-[0.98] shadow-sm cursor-pointer font-semibold">
                    Kirim Penawaran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JAVASCRIPT LOGIC UNTUK DETAIL PRODUK -->
<script>
// Ganti Foto Utama Galeri
function changeGalleryImage(imgSrc, btnElement) {
    const mainImg = document.getElementById('mainProductImg');
    if (mainImg) {
        mainImg.src = imgSrc;
    }
    // Update active ring pada thumbnail
    document.querySelectorAll('.thumb-btn').forEach(btn => {
        btn.classList.remove('border-primary', 'ring-2', 'ring-primary/20');
        btn.classList.add('border-outline-variant', 'opacity-80');
    });
    btnElement.classList.add('border-primary', 'ring-2', 'ring-primary/20');
    btnElement.classList.remove('border-outline-variant', 'opacity-80');
}

// Buka Modal Nego dengan Value Terkini
function openNegoModal(val) {
    const modalInput = document.getElementById('modalOfferPrice');
    if (modalInput && val) {
        // Hilangkan titik jika format lokal
        const cleanNum = val.replace(/\./g, '').replace(/[^0-9]/g, '');
        if (cleanNum) modalInput.value = cleanNum;
    }
    document.getElementById('nego-modal').classList.remove('hidden');
}

// Switch Tabs Informasi (Spesifikasi, Deskripsi, COD)
function switchTab(tabKey, btnElement) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-primary', 'border-primary');
        btn.classList.add('text-on-surface-variant', 'border-transparent');
    });

    const targetContent = document.getElementById('tab-' + tabKey);
    if (targetContent) targetContent.classList.remove('hidden');

    btnElement.classList.remove('text-on-surface-variant', 'border-transparent');
    btnElement.classList.add('text-primary', 'border-primary');
}

// Toggle Wishlist / Favorit
function toggleWishlist(btn) {
    const icon = btn.querySelector('.material-symbols-outlined');
    if (icon.innerText === 'favorite_border') {
        icon.innerText = 'favorite';
        btn.classList.add('text-error', 'border-error/30', 'bg-error-container/20');
        btn.classList.remove('text-on-surface-variant');
    } else {
        icon.innerText = 'favorite_border';
        btn.classList.remove('text-error', 'border-error/30', 'bg-error-container/20');
        btn.classList.add('text-on-surface-variant');
    }
}

// Fitur Share Link Produk
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: <?= json_encode($item['title'] . ' | PREVO'); ?>,
            url: window.location.href
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan barang berhasil disalin ke clipboard!');
    }
}

// Tutup modal dengan tombol ESC atau klik di luar modal
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.getElementById('contact-modal')?.classList.add('hidden');
        document.getElementById('nego-modal')?.classList.add('hidden');
    }
});

[document.getElementById('contact-modal'), document.getElementById('nego-modal')].forEach(modal => {
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }
});
</script>

<?php
// Layout Master Footer PREVO
require_once __DIR__ . '/../includes/footer.php';
?>
