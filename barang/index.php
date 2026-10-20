<?php
$page_title = "Jelajah Preloved Laptop & Barang Mahasiswa";
$active_nav = "jelajah";

// Tangkap filter query string dari URL (Sisi Klien / Backend-Ready Multiple-Filter)
$q = trim($_GET['q'] ?? '');

// Kategori Multiple (Checkbox Array)
$rawCategories = $_GET['kategori'] ?? [];
$selectedCategories = is_array($rawCategories) ? $rawCategories : ($rawCategories !== '' ? [$rawCategories] : []);
if (empty($selectedCategories) && !isset($_GET['kategori']) && empty($q)) {
    // Default aktif saat pertama kali dibuka tanpa parameter
    $selectedCategories = ['elektronik'];
}

// Kondisi Multiple (Checkbox Array)
$rawConditions = $_GET['kondisi'] ?? [];
$selectedConditions = is_array($rawConditions) ? $rawConditions : ($rawConditions !== '' && $rawConditions !== 'semua' ? [$rawConditions] : []);

$selectedRadius = $_GET['radius'] ?? '3km';
$minPrice       = $_GET['min_price'] ?? '1000000';
$maxPrice       = $_GET['max_price'] ?? '10000000';
$selectedSort   = $_GET['sort'] ?? 'terbaru';
$filterNego     = !empty($_GET['is_nego']);
$filterVerified = !empty($_GET['is_verified']);

// Data Dummy Kategori untuk Sidebar Filter
$filterCategories = [
    ['slug' => 'buku', 'name' => 'Buku Kuliah', 'count' => 32],
    ['slug' => 'elektronik', 'name' => 'Elektronik & Gadget', 'count' => 18],
    ['slug' => 'fashion', 'name' => 'Fashion & Seragam', 'count' => 45],
    ['slug' => 'kos', 'name' => 'Perlengkapan Kos', 'count' => 29],
    ['slug' => 'alat-kuliah', 'name' => 'Alat Tulis & Gambar', 'count' => 14],
    ['slug' => 'olahraga', 'name' => 'Olahraga', 'count' => 8],
    ['slug' => 'kesehatan', 'name' => 'Kesehatan & Skincare', 'count' => 6],
];

// Data Dummy Katalog Barang
$allItems = [
    [
        'id'          => 1,
        'title'       => 'Laptop Asus Vivobook 14',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan Elektro',
        'price'       => 4000000,
        'description' => 'Intel Core i3, RAM 8GB SSD 512GB, Mulus 95% pemakaian skripsi.',
        'distance'    => '1.2 km · Sudimoro',
        'is_nego'     => true,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB0w_gF42xkLra5L3RzgdKsE4unDqojJGaBLGJoSeKOBFAE0GaP71fhdACWTTSl01yBgN_FivTvkZFkdXOo6nG7iRv-xRq36rcyJ3_voAFsYwzDwAThbKGBz1hDxClI6j_cJ659Xm69xjbUSXJFUjTRJMhAgcpNJkyQs3mQdLVWuvl_IWzYAZ9YpWp9_B1fe8MQSuW6QbRQs4nGCnqdL1S_VhotLZxCaEhkHSMCuYyr45K-dPHRzL5NdQ'
    ],
    [
        'id'          => 2,
        'title'       => 'Macbook Air M1 256GB',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan TI',
        'price'       => 7500000,
        'description' => 'Battery Health 89%, Fullset Box & Charger, siap pakai coding.',
        'distance'    => '2.3 km · Suhat',
        'is_nego'     => true,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDGkPbdOUli3fZ-34i7VKc8gpc8dSf9WHvN5uz5kpoXJv--aUyfzVZZV2NxVXSL_z9NmGwP7AxuAxxv4wWocDw8J4ysvJvIjdh79HBIa-Uxf4zKf4d55nYZDVZlIq8F10oEW76ozqu8l5PTjToZPasI38M-BfxjCL1EZKuO4UY5xffW1eIg7qYRqkeWFd5P21ALrycj8aLppKn4kn9M2qoqIJkUz8m5NXnF0-UQA9X3-Nf_tVADjBa4Lg'
    ],
    [
        'id'          => 3,
        'title'       => 'Lenovo Ideapad Slim 3',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan Akuntansi',
        'price'       => 3800000,
        'description' => 'Ryzen 3 5300U, SSD 256GB, Baterai awet 5 jam cocok kuliah reguler.',
        'distance'    => '1.8 km · Kalipat',
        'is_nego'     => false,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAvhquD4_e4LerlbskzBEZjVTktVy8g-EynMyCtWx4CLYK27Z4_JkqIC-nKnCiy9L9pii_KAmH2YZeTVzZofJ-tbJJQJ1Jrs51NbAer2J6iLdxBMkJphveVGPUTiAM737LjREsyQRiIShgeqo78TE0otEI5jHp4tP3eFJxCSzmJNIx0Q-AH8KCJaxC_Vx7xERF9ZV9-6ZEN30skMqSXHj-Ml0OxrxwZdua1QdB2UsuHKRF7ecg4N0wf-g'
    ],
    [
        'id'          => 4,
        'title'       => 'Laptop Acer Aspire 5 Slim',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan Mesin',
        'price'       => 3000000,
        'description' => 'Core i3 Gen 11, layar FHD 14 inci, bonus tas original & mouse.',
        'distance'    => '2.6 km · Jatimulyo',
        'is_nego'     => true,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBAyga4OU4yfAcBIJ5Gv-Zl-5oRytch9dznJj-hcMgiWLQSBnemqWYgZmGCxQXUm1lus4RFC4W-5wdqSF7qUNyzmwS79KdzQLRzJHMQ7S8RYZNq1zYec9OHJ1GiAgsHqbV1lN4APIurKtVVtBrjNDfP5PvAa_g754kzrLnIGvi4Qoj4ppD0Mwi8LY3Wzs5PENezXXDZ1gmfckltCRvRACdBzWYBVGfUf6-72bl9DeeXvX-alpyLuO6h3w'
    ],
    [
        'id'          => 5,
        'title'       => 'Dell Inspiron 15 3511',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan Sipil',
        'price'       => 4200000,
        'description' => 'Intel Core i5, Layar luas 15.6 Inch cocok render AutoCAD & Excel.',
        'distance'    => '1.1 km · Belakang Graha',
        'is_nego'     => false,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDzOVWtvisllD9wtFh-ShsVqR9fQ-xduvK8TGJXNHbzaWdXXkX-V2ahrbOsYeDAw8ukkTEASPtpuTVAJ7RHTlVDAgcoehy5CjjnBppqzY7jyxMaBntzPvVCw95BcENIICLnGTrXcAy9jYZiE0oQbTNnBfFtFiMHcb8DPUT3Ju5LpDx2gNBkddALBoDz8QNd_O5nn6KyknPJcaKo4sY2PVXNuRB75rCBX_iLkGgmbUY2fJwxYF99_BNYqA'
    ],
    [
        'id'          => 6,
        'title'       => 'ASUS TUF Gaming F15',
        'category'    => 'Elektronik',
        'category_slug' => 'elektronik',
        'department'  => 'Jurusan TI',
        'price'       => 8900000,
        'description' => 'Core i5 Gen 11, GTX 1650 4GB, Refresh Rate 144Hz, Pemakaian apik.',
        'distance'    => '0.5 km · Gedung Mesin',
        'is_nego'     => true,
        'is_verified' => true,
        'condition'   => 'bekas_baik',
        'image_url'   => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBiZUlwNULIeJBJRI51_TYr_0pVYV0YyZf-FTB00-aLgLupcAW4xscwd6neVS0duJZbHcoUUFdLGhE0jICvmAxP73saC1U-O-I8b9p7CX_KJRYdcHka6uEF2U5geCIzlCnMqSf8gI8JwXhTzxN0lU64UWgK82gv-xo1jrZnnNyGw-wVYFvHOIVns1EYYx_W3nFxz1nAfIzl1SipLBKxaOuvGELMP0zL5iZLehasRd-zmnoW6LuINA5rrg'
    ]
];

// Simulasi filter sisi front-end agar responsif
$items = array_filter($allItems, function ($item) use ($q, $selectedCategories, $selectedConditions, $filterNego, $filterVerified) {
    if ($q !== '' && stripos($item['title'], $q) === false && stripos($item['description'], $q) === false) {
        return false;
    }
    if (!empty($selectedCategories) && !in_array($item['category_slug'], $selectedCategories, true)) {
        return false;
    }
    if (!empty($selectedConditions) && !in_array($item['condition'], $selectedConditions, true)) {
        return false;
    }
    if ($filterNego && empty($item['is_nego'])) {
        return false;
    }
    if ($filterVerified && empty($item['is_verified'])) {
        return false;
    }
    return true;
});

// Layout Master Header PREVO
require_once __DIR__ . '/../includes/header.php';
?>

<!-- BREADCRUMBS & TOP BAR -->
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <!-- Breadcrumb Nav -->
        <nav class="flex items-center gap-2 text-body-sm font-body-sm text-on-surface-variant mb-1">
            <a class="hover:text-primary transition-colors" href="<?= BASE_URL; ?>home/index.php">Beranda</a>
            <span class="text-outline-variant">/</span>
            <a class="hover:text-primary transition-colors" href="<?= BASE_URL; ?>barang/index.php">Jelajah</a>
            <span class="text-outline-variant">/</span>
            <span class="text-on-surface font-label-md text-label-md">
                <?= $q !== '' ? 'Pencarian: "' . e($q) . '"' : 'Semua Barang Preloved'; ?>
            </span>
        </nav>
        <h1 class="font-headline-lg text-headline-lg text-on-surface">
            <?= $q !== '' ? 'Hasil Pencarian: ' . e($q) : 'Katalog Preloved Mahasiswa Polinema'; ?>
        </h1>
        <p class="font-body-sm text-body-sm text-on-surface-variant">
            Menampilkan <span class="font-bold text-on-surface"><?= count($items); ?></span> barang terverifikasi mahasiswa di sekitar kampus Polinema
        </p>
    </div>

    <!-- Sorting Filter -->
    <div class="flex items-center gap-3">
        <label class="font-label-md text-label-md text-on-surface-variant hidden sm:inline-block" for="sort-dropdown">Urutkan:</label>
        <div class="relative inline-block min-w-[190px]">
            <select
                class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface text-body-sm font-label-md rounded-xl py-2 pl-3 pr-9 focus:border-primary focus:outline-none cursor-pointer shadow-sm"
                id="sort-dropdown"
                onchange="document.getElementById('sort-input').value = this.value; document.getElementById('filterForm').submit();">
                <option value="terbaru" <?= $selectedSort === 'terbaru' ? 'selected' : ''; ?>>Terbaru Ditambahkan</option>
                <option value="termurah" <?= $selectedSort === 'termurah' ? 'selected' : ''; ?>>Harga: Termurah</option>
                <option value="tertinggi" <?= $selectedSort === 'tertinggi' ? 'selected' : ''; ?>>Harga: Tertinggi</option>
                <option value="terdekat" <?= $selectedSort === 'terdekat' ? 'selected' : ''; ?>>Jarak: Paling Dekat</option>
            </select>
            <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[20px]" data-icon="expand_more">expand_more</span>
        </div>
    </div>
</section>

<!-- ACTIVE FILTER CHIPS ROW -->
<section class="flex flex-wrap items-center gap-2 pt-1 pb-2">
    <span class="font-label-md text-label-md text-outline">Filter Aktif:</span>

    <?php if ($q !== ''): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container text-primary font-label-md text-label-md rounded-full border border-primary/20">
            <span>Cari: <?= e($q); ?></span>
            <button type="button" onclick="document.querySelector('input[name=q]').value = ''; document.getElementById('filterForm').submit();" class="hover:text-error flex items-center cursor-pointer" title="Hapus filter pencarian">
                <span class="material-symbols-outlined text-[14px]" data-icon="close">close</span>
            </button>
        </span>
    <?php endif; ?>

    <!-- Chips Kategori yang Terpilih -->
    <?php foreach ($selectedCategories as $catSlug): ?>
        <?php
        $catName = ucfirst($catSlug);
        foreach ($filterCategories as $fc) {
            if ($fc['slug'] === $catSlug) {
                $catName = $fc['name'];
                break;
            }
        }
        ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container text-primary font-label-md text-label-md rounded-full border border-primary/20">
            <span>Kategori: <?= e($catName); ?></span>
            <button type="button" onclick="removeCategoryFilter('<?= e($catSlug); ?>')" class="hover:text-error flex items-center cursor-pointer" title="Hapus filter <?= e($catName); ?>">
                <span class="material-symbols-outlined text-[14px]" data-icon="close">close</span>
            </button>
        </span>
    <?php endforeach; ?>

    <?php foreach ($selectedConditions as $condSlug): ?>
        <?php
        $condLabels = [
            'baru'        => 'Baru',
            'bekas_baik'  => 'Bekas Layak Pakai',
            'bekas_rusak' => 'Butuh Servis'
        ];
        $condName = $condLabels[$condSlug] ?? ucfirst($condSlug);
        ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-high text-on-secondary-container font-label-md text-label-md rounded-full border border-secondary-container/40">
            <span>Kondisi: <?= e($condName); ?></span>
            <button type="button" onclick="removeConditionFilter('<?= e($condSlug); ?>')" class="hover:text-error flex items-center cursor-pointer" title="Hapus filter <?= e($condName); ?>">
                <span class="material-symbols-outlined text-[14px]" data-icon="close">close</span>
            </button>
        </span>
    <?php endforeach; ?>

    <!-- Chip Nego jika Aktif -->
    <?php if ($filterNego): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-high text-on-secondary-container font-label-md text-label-md rounded-full border border-secondary-container/40">
            <span class="material-symbols-outlined text-[14px]" data-icon="handshake">handshake</span>
            <span>Bisa Nego</span>
            <button type="button" onclick="document.querySelector('input[name=is_nego]').checked = false; document.getElementById('filterForm').submit();" class="hover:text-error flex items-center cursor-pointer">
                <span class="material-symbols-outlined text-[14px]" data-icon="close">close</span>
            </button>
        </span>
    <?php endif; ?>

    <!-- Chip Verified jika Aktif -->
    <?php if ($filterVerified): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-md text-label-md rounded-full border border-tertiary-fixed-dim/60">
            <span class="material-symbols-outlined text-[14px]" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
            <span>Mahasiswa Verified</span>
            <button type="button" onclick="document.querySelector('input[name=is_verified]').checked = false; document.getElementById('filterForm').submit();" class="hover:text-error flex items-center cursor-pointer">
                <span class="material-symbols-outlined text-[14px]" data-icon="close">close</span>
            </button>
        </span>
    <?php endif; ?>

    <?php if (!empty($selectedCategories) || !empty($selectedConditions) || $q !== '' || $filterNego || $filterVerified): ?>
        <a href="<?= BASE_URL; ?>barang/index.php" class="text-primary font-label-md text-label-md ml-1 hover:underline">
            Reset Semua Filter
        </a>
    <?php endif; ?>
</section>

<!-- CONTENT GRID: SIDEBAR & PRODUCT CATALOG -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- LEFT SIDEBAR FILTER FORM -->
    <aside class="lg:col-span-3 bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/50 shadow-sm flex flex-col gap-6 sticky top-28">
        <form id="filterForm" method="GET" action="<?= BASE_URL; ?>barang/index.php" class="space-y-6">
            <!-- Hidden inputs untuk state query & sorting -->
            <input type="hidden" name="q" value="<?= e($q); ?>">
            <input type="hidden" name="sort" id="sort-input" value="<?= e($selectedSort); ?>">

            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3">
                <h2 class="font-headline-md text-headline-md text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]" data-icon="tune">tune</span>
                    Filter
                </h2>
                <a href="<?= BASE_URL; ?>barang/index.php" class="font-label-md text-label-md text-primary hover:underline">Hapus Semua</a>
            </div>

            <!-- 1. Kategori Filter (Multiple-Filter Checkbox) -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <label class="font-label-lg text-label-lg text-on-surface">Kategori</label>
                    <span class="text-outline text-xs"><?= count($selectedCategories); ?> dipilih</span>
                </div>
                <div class="flex flex-col gap-2 font-body-md text-body-md text-on-surface-variant max-h-60 overflow-y-auto pr-1">
                    <?php foreach ($filterCategories as $cat): ?>
                        <?php $isChecked = in_array($cat['slug'], $selectedCategories, true); ?>
                        <label class="flex items-center gap-2.5 cursor-pointer hover:text-on-surface group py-0.5">
                            <input
                                class="w-4 h-4 rounded text-primary border-outline focus:ring-primary/20 cursor-pointer accent-primary"
                                type="checkbox"
                                name="kategori[]"
                                value="<?= e($cat['slug']); ?>"
                                <?= $isChecked ? 'checked' : ''; ?>>
                            <span class="flex-1 group-hover:text-primary transition-colors <?= $isChecked ? 'text-primary font-semibold' : ''; ?>">
                                <?= e($cat['name']); ?>
                            </span>
                            <span class="font-label-sm text-label-sm <?= $isChecked ? 'bg-primary/10 text-primary font-bold px-1.5 py-0.5 rounded-full' : 'text-outline'; ?>">
                                <?= e($cat['count']); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- <hr class="border-outline-variant/30"> -->

            <!-- 2. Radius Jarak COD Kampus -->
            <!-- <div class="flex flex-col gap-2.5">
                <label class="font-label-lg text-label-lg text-on-surface flex items-center justify-between">
                    <span>Radius COD Polinema</span>
                    <span class="font-label-sm text-label-sm text-primary">&lt; 3.0 km</span>
                </label>
                <select
                    name="radius"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-xl py-2 px-3 text-body-md text-on-surface focus:outline-none focus:border-primary cursor-pointer">
                    <option value="semua" <?= $selectedRadius === 'semua' ? 'selected' : ''; ?>>Semua Radius Kampus</option>
                    <option value="1km" <?= $selectedRadius === '1km' ? 'selected' : ''; ?>>&lt; 1 km (Area Dalam Kampus)</option>
                    <option value="3km" <?= $selectedRadius === '3km' ? 'selected' : ''; ?>>&lt; 3 km (Sudimoro, Soekarno-Hatta)</option>
                    <option value="5km" <?= $selectedRadius === '5km' ? 'selected' : ''; ?>>&lt; 5 km (Kota Malang)</option>
                </select>
                <p class="font-body-sm text-body-sm text-outline">Lokasi mudah dijangkau untuk COD di Gedung Sipil, Kantin, atau Perpustakaan.</p>
            </div> -->

            <hr class="border-outline-variant/30">

            <!-- 3. Kondisi Barang (Multiple-Filter Checkbox) -->
            <div class="flex flex-col gap-2.5">
                <div class="flex items-center justify-between">
                    <label class="font-label-lg text-label-lg text-on-surface">Kondisi Barang</label>
                    <span class="text-outline text-xs"><?= count($selectedConditions); ?> dipilih</span>
                </div>
                <div class="flex flex-col gap-2 font-body-md text-body-md text-on-surface-variant">
                    <label class="flex items-center gap-2.5 cursor-pointer hover:text-on-surface group py-0.5">
                        <input
                            class="w-4 h-4 rounded text-primary border-outline focus:ring-primary/20 cursor-pointer accent-primary"
                            type="checkbox"
                            name="kondisi[]"
                            value="baru"
                            <?= in_array('baru', $selectedConditions, true) ? 'checked' : ''; ?>>
                        <span class="flex-1 group-hover:text-primary transition-colors <?= in_array('baru', $selectedConditions, true) ? 'text-primary font-semibold' : ''; ?>">
                            Baru (Brand New / Segel)
                        </span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer hover:text-on-surface group py-0.5">
                        <input
                            class="w-4 h-4 rounded text-primary border-outline focus:ring-primary/20 cursor-pointer accent-primary"
                            type="checkbox"
                            name="kondisi[]"
                            value="bekas_baik"
                            <?= in_array('bekas_baik', $selectedConditions, true) ? 'checked' : ''; ?>>
                        <span class="flex-1 group-hover:text-primary transition-colors <?= in_array('bekas_baik', $selectedConditions, true) ? 'text-primary font-semibold' : ''; ?>">
                            Bekas - Layak Pakai (Mulus)
                        </span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer hover:text-on-surface group py-0.5">
                        <input
                            class="w-4 h-4 rounded text-primary border-outline focus:ring-primary/20 cursor-pointer accent-primary"
                            type="checkbox"
                            name="kondisi[]"
                            value="bekas_rusak"
                            <?= in_array('bekas_rusak', $selectedConditions, true) ? 'checked' : ''; ?>>
                        <span class="flex-1 group-hover:text-primary transition-colors <?= in_array('bekas_rusak', $selectedConditions, true) ? 'text-primary font-semibold' : ''; ?>">
                            Bekas - Butuh Servis / Minus
                        </span>
                    </label>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- 4. Rentang Harga -->
            <div class="flex flex-col gap-3">
                <label class="font-label-lg text-label-lg text-on-surface">Rentang Harga (Rp)</label>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-surface-container-low border border-outline-variant rounded-xl px-2.5 py-1.5 focus-within:border-primary">
                        <span class="font-label-sm text-label-sm text-outline block">Min</span>
                        <input
                            class="w-full bg-transparent p-0 text-body-md font-label-md text-on-surface border-0 focus:ring-0 focus:outline-none"
                            type="number"
                            name="min_price"
                            value="<?= e($minPrice); ?>"
                            placeholder="0">
                    </div>
                    <span class="text-outline-variant">-</span>
                    <div class="flex-1 bg-surface-container-low border border-outline-variant rounded-xl px-2.5 py-1.5 focus-within:border-primary">
                        <span class="font-label-sm text-label-sm text-outline block">Max</span>
                        <input
                            class="w-full bg-transparent p-0 text-body-md font-label-md text-on-surface border-0 focus:ring-0 focus:outline-none"
                            type="number"
                            name="max_price"
                            value="<?= e($maxPrice); ?>"
                            placeholder="10000000">
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- 5. Opsi Tambahan Multiple-Filter (Nego & Verified) -->
            <div class="flex flex-col gap-2.5">
                <label class="font-label-lg text-label-lg text-on-surface">Opsi Transaksi</label>
                <div class="flex flex-col gap-2 font-body-md text-body-md text-on-surface-variant">
                    <label class="flex items-center gap-2.5 cursor-pointer hover:text-on-surface group py-0.5">
                        <input
                            class="w-4 h-4 rounded text-primary border-outline focus:ring-primary/20 cursor-pointer accent-primary"
                            type="checkbox"
                            name="is_nego"
                            value="1"
                            <?= $filterNego ? 'checked' : ''; ?>>
                        <span class="flex-1 group-hover:text-primary transition-colors <?= $filterNego ? 'text-primary font-semibold' : ''; ?>">
                            Hanya yang Bisa Nego
                        </span>
                    </label>
                </div>
            </div>

            <!-- Submit Button Form Filter -->
            <button
                type="submit"
                class="w-full py-2.5 bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md rounded-xl transition-all duration-150 active:scale-[0.98] shadow-sm flex items-center justify-center gap-2 cursor-pointer font-semibold">
                <span class="material-symbols-outlined text-[18px]" data-icon="filter_alt">filter_alt</span>
                <span>Terapkan Filter</span>
            </button>
        </form>

        <!-- Campus Safety Guarantee Banner -->
        <div class="mt-2 p-3.5 rounded-xl bg-tertiary-fixed/30 border border-tertiary-fixed-dim/40 flex items-start gap-3">
            <span class="material-symbols-outlined text-tertiary text-[22px]" data-icon="shield" style="font-variation-settings: 'FILL' 1;">shield</span>
            <div>
                <h4 class="font-label-md text-label-md text-tertiary font-bold">100% Mahasiswa Polinema</h4>
                <p class="font-body-sm text-body-sm text-on-tertiary-fixed-variant mt-0.5">Penjual terverifikasi menggunakan email resmi @polinema.ac.id untuk keamanan COD.</p>
            </div>
        </div>
    </aside>

    <!-- RIGHT PRODUCT CATALOG -->
    <section class="lg:col-span-9 flex flex-col gap-6">
        <!-- Campus Safety Tip Banner -->
        <div class="bg-gradient-to-r from-surface-container-low via-surface-bright to-surface-container rounded-2xl p-4 sm:p-5 border border-outline-variant/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]" data-icon="tips_and_updates">tips_and_updates</span>
                </div>
                <div>
                    <p class="font-headline-md text-headline-md text-on-surface">Tips Belanja Aman di Area Kampus</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Lakukan pemeriksaan fisik dan tes fungsi unit di spot publik ramai seperti Graha Polinema atau Perpustakaan.</p>
                </div>
            </div>
            <a href="<?= BASE_URL; ?>home/index.php#bantuan" class="shrink-0 text-label-md font-label-md text-primary hover:text-on-primary-fixed-variant flex items-center gap-1 font-semibold">
                <span>Panduan COD</span>
                <span class="material-symbols-outlined text-[16px]" data-icon="arrow_forward">arrow_forward</span>
            </a>
        </div>

        <!-- PRODUCT CARDS GRID -->
        <?php if (!empty($items)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach ($items as $item): ?>
                    <article class="group bg-surface-container-lowest rounded-2xl border border-outline-variant/60 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 flex flex-col">
                        <!-- Product Image Container -->
                        <div class="relative w-full aspect-[4/3] bg-surface-container overflow-hidden">
                            <a href="<?= BASE_URL; ?>barang/detail.php?id=<?= (int) $item['id']; ?>" class="block w-full h-full">
                                <img
                                    alt="<?= e($item['title']); ?>"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    src="<?= e($item['image_url']); ?>"
                                    loading="lazy">
                            </a>

                            <!-- Nego Pill Floating Badge -->
                            <?php if (!empty($item['is_nego'])): ?>
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-bold shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]" data-icon="handshake">handshake</span>
                                    <span>Bisa Nego</span>
                                </span>
                            <?php endif; ?>

                            <!-- Favorite Bookmark Button -->
                            <button
                                type="button"
                                onclick="toggleFavorite(this, <?= (int) $item['id']; ?>)"
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-outline hover:text-error flex items-center justify-center shadow-sm transition-colors cursor-pointer"
                                title="Simpan ke favorit">
                                <span class="material-symbols-outlined text-[18px]" data-icon="favorite_border">favorite_border</span>
                            </button>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                            <div>
                                <span class="font-label-sm text-label-sm text-outline block mb-1">
                                    <?= e($item['category']); ?> &middot; <?= e($item['department']); ?>
                                </span>
                                <h3 class="font-headline-md text-headline-md text-on-surface group-hover:text-primary transition-colors line-clamp-1">
                                    <a href="<?= BASE_URL; ?>barang/detail.php?id=<?= (int) $item['id']; ?>">
                                        <?= e($item['title']); ?>
                                    </a>
                                </h3>
                                <p class="font-headline-lg text-headline-lg font-bold text-primary mt-1">
                                    <?= format_rupiah($item['price']); ?>
                                </p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-1 mt-1">
                                    <?= e($item['description']); ?>
                                </p>
                            </div>

                            <!-- Meta Info & Distance -->
                            <div class="pt-2 border-t border-outline-variant/40 flex items-center justify-between text-body-sm text-on-surface-variant">
                                <div class="flex items-center gap-1 text-outline">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="pin_drop">pin_drop</span>
                                    <span><?= e($item['distance']); ?></span>
                                </div>
                                <?php if (!empty($item['is_verified'])): ?>
                                    <div class="flex items-center gap-1 text-tertiary font-label-md">
                                        <span class="material-symbols-outlined text-[16px]" data-icon="verified" style="font-variation-settings: 'FILL' 1;">verified</span>
                                        <span>Verified</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Quick Action Button -->
                            <a
                                href="<?= BASE_URL; ?>barang/detail.php?id=<?= (int) $item['id']; ?>"
                                class="w-full py-2 bg-surface-container-low hover:bg-primary hover:text-on-primary text-primary font-label-md text-label-md rounded-xl transition-all duration-150 flex items-center justify-center gap-1.5 font-semibold">
                                <span class="material-symbols-outlined text-[16px]" data-icon="visibility">visibility</span>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- PAGINATION SECTION -->
            <nav aria-label="Pagination" class="flex items-center justify-between border-t border-outline-variant/50 pt-6 mt-4">
                <div class="hidden sm:block text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-bold text-on-surface">1 - <?= count($items); ?></span> dari <span class="font-bold text-on-surface"><?= count($items); ?></span> produk preloved
                </div>
                <div class="flex items-center justify-center w-full sm:w-auto gap-2">
                    <button class="px-3 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container flex items-center gap-1 text-label-md transition-colors disabled:opacity-40" disabled>
                        <span class="material-symbols-outlined text-[18px]" data-icon="chevron_left">chevron_left</span>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-primary text-on-primary font-label-md flex items-center justify-center shadow-sm">
                        1
                    </button>
                    <button class="px-3 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container flex items-center gap-1 text-label-md transition-colors">
                        <span class="hidden sm:inline">Berikutnya</span>
                        <span class="material-symbols-outlined text-[18px]" data-icon="chevron_right">chevron_right</span>
                    </button>
                </div>
            </nav>
        <?php else: ?>
            <!-- EMPTY STATE -->
            <div class="p-12 text-center rounded-2xl bg-surface-container-low border border-outline-variant/60 flex flex-col items-center justify-center gap-4">
                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-outline">
                    <span class="material-symbols-outlined text-4xl" data-icon="search_off">search_off</span>
                </div>
                <div class="space-y-1">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Barang Tidak Ditemukan</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
                        Tidak ada barang preloved yang cocok dengan kombinasi filter yang dipilih. Coba kurangi centang filter atau reset filter.
                    </p>
                </div>
                <a href="<?= BASE_URL; ?>barang/index.php" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors font-semibold">
                    Reset Semua Filter
                </a>
            </div>
        <?php endif; ?>

        <!-- STUDENT REQUEST / SAFETY CALLOUT BANNER -->
        <div class="mt-4 p-6 rounded-2xl bg-surface-container-low border border-outline-variant/60 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-tertiary flex items-center justify-center text-on-tertiary shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]" data-icon="verified_user">verified_user</span>
                </div>
                <div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Belum menemukan tipe barang yang dicari?</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Kamu bisa posting barang yang sedang kamu cari agar teman kampus se-Polinema dapat menawarkan langsung.</p>
                </div>
            </div>
            <a href="<?= BASE_URL; ?>barang/tambah.php" class="w-full md:w-auto px-5 py-2.5 bg-primary text-on-primary rounded-xl font-label-md text-label-md shadow-sm hover:opacity-90 active:scale-[0.98] transition-all whitespace-nowrap text-center font-semibold">
                Pasang Iklan / Jual Barang
            </a>
        </div>
    </section>
</div>

<!-- Mobile Floating Action Button (FAB) "+ Jual Barang" -->
<div class="fixed bottom-6 right-6 md:hidden z-40">
    <a href="<?= BASE_URL; ?>barang/tambah.php" class="p-4 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center hover:scale-105 active:scale-95 transition-all" title="Jual Barang">
        <span class="material-symbols-outlined text-[24px]" data-icon="add">add</span>
    </a>
</div>

<script>
// Interaktivitas Bookmark / Favorit Instan
function toggleFavorite(btn, itemId) {
    const icon = btn.querySelector('.material-symbols-outlined');
    if (icon.innerText === 'favorite_border') {
        icon.innerText = 'favorite';
        btn.classList.add('text-error');
        btn.classList.remove('text-outline');
    } else {
        icon.innerText = 'favorite_border';
        btn.classList.remove('text-error');
        btn.classList.add('text-outline');
    }
}

// Hapus Chip Filter Kategori secara Spesifik
function removeCategoryFilter(slug) {
    const checkbox = document.querySelector(`input[name="kategori[]"][value="${slug}"]`);
    if (checkbox) {
        checkbox.checked = false;
    }
    document.getElementById('filterForm').submit();
}

// Hapus Chip Filter Kondisi secara Spesifik
function removeConditionFilter(cond) {
    const checkbox = document.querySelector(`input[name="kondisi[]"][value="${cond}"]`);
    if (checkbox) {
        checkbox.checked = false;
    }
    document.getElementById('filterForm').submit();
}
</script>

<?php
// Layout Master Footer PREVO
require_once __DIR__ . '/../includes/footer.php';
?>
