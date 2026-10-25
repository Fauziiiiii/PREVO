<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

$pageTitle = 'Katalog Barang Preloved Kampus Polinema';

$itemService = new ItemService();
$categories = $itemService->getCategories();

$activeCategoryId = isset($_GET['kategori']) ? (int)$_GET['kategori'] : null;
$activeCondition = $_GET['kondisi'] ?? '';
$searchQuery = $_GET['q'] ?? '';
$activeSort = $_GET['sort'] ?? 'terbaru';

$filters = [
    'category_id' => $activeCategoryId,
    'condition'   => $activeCondition,
    'q'           => $searchQuery,
    'sort'        => $activeSort
];

$items = $itemService->getCatalogItems($filters);

require_once __DIR__ . '/../views/partials/header.php';
?>

<!-- Category Quick Filter Subheader (Horizontal Scroll) -->
<div class="bg-white border-b border-slate-200 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Kategori:</span>
            
            <!-- Tombol Semua Kategori -->
            <a href="<?= BASE_URL ?>/barang/index.php<?= !empty($searchQuery) ? '?q=' . urlencode($searchQuery) : '' ?>" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold shrink-0 transition-all <?= empty($activeCategoryId) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>">
                Semua Barang
            </a>

            <?php foreach ($categories as $cat): 
                $isActive = ($activeCategoryId === (int)$cat['id']);
                $urlParams = array_filter(['kategori' => $cat['id'], 'q' => $searchQuery, 'kondisi' => $activeCondition, 'sort' => $activeSort]);
                $catUrl = BASE_URL . '/barang/index.php?' . http_build_query($urlParams);
            ?>
                <a href="<?= $catUrl ?>" 
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold shrink-0 transition-all <?= $isActive ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>">
                    <?= e($cat['name']) ?>
                    <?php if ((int)($cat['active_count'] ?? 0) > 0): ?>
                        <span class="ml-1 text-[10px] <?= $isActive ? 'text-emerald-300' : 'text-slate-400' ?>">(<?= (int)$cat['active_count'] ?>)</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Main Catalog Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    
    <!-- Filter Bar (Kondisi, Sort, & Info Hasil) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-5 border-b border-slate-200 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-950 tracking-tight flex items-center gap-2">
                <span>Katalog Barang Tersedia</span>
                <span class="text-xs font-semibold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                    <?= count($items) ?> barang
                </span>
            </h1>
            <?php if (!empty($searchQuery)): ?>
                <p class="text-xs text-slate-500 mt-1">
                    Hasil pencarian untuk kata kunci: <strong class="text-slate-800">"<?= e($searchQuery) ?>"</strong>
                    <a href="<?= BASE_URL ?>/barang/index.php" class="text-rose-600 hover:underline ml-2">Reset Pencarian &times;</a>
                </p>
            <?php else: ?>
                <p class="text-xs text-slate-500 mt-0.5">Barang preloved terkurasi siap COD langsung di lingkungan kampus Polinema.</p>
            <?php endif; ?>
        </div>

        <!-- Filter Controls -->
        <form id="filterForm" action="<?= BASE_URL ?>/barang/index.php" method="GET" class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
            <?php if (!empty($activeCategoryId)): ?>
                <input type="hidden" name="kategori" value="<?= (int)$activeCategoryId ?>">
            <?php endif; ?>
            <?php if (!empty($searchQuery)): ?>
                <input type="hidden" name="q" value="<?= e($searchQuery) ?>">
            <?php endif; ?>

            <!-- Filter Kondisi -->
            <select name="kondisi" onchange="document.getElementById('filterForm').submit()" 
                    class="bg-white border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-600">
                <option value="">Semua Kondisi</option>
                <option value="baru" <?= $activeCondition === 'baru' ? 'selected' : '' ?>>Baru</option>
                <option value="bekas_baik" <?= $activeCondition === 'bekas_baik' ? 'selected' : '' ?>>Bekas Baik</option>
                <option value="bekas_rusak" <?= $activeCondition === 'bekas_rusak' ? 'selected' : '' ?>>Bekas / Minus</option>
            </select>

            <!-- Pengurutan -->
            <select name="sort" onchange="document.getElementById('filterForm').submit()" 
                    class="bg-white border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-600">
                <option value="terbaru" <?= $activeSort === 'terbaru' ? 'selected' : '' ?>>Urutkan: Terbaru</option>
                <option value="termurah" <?= $activeSort === 'termurah' ? 'selected' : '' ?>>Harga: Termurah</option>
                <option value="termahal" <?= $activeSort === 'termahal' ? 'selected' : '' ?>>Harga: Tertinggi</option>
            </select>
        </form>
    </div>

    <!-- Product Grid -->
    <?php if (empty($items)): ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto my-8">
            <span class="text-4xl block mb-3">🔍</span>
            <h3 class="text-base font-bold text-slate-900">Tidak ada barang yang cocok</h3>
            <p class="text-xs text-slate-500 mt-1 mb-5">Coba ubah kata kunci pencarian atau ganti filter kategori/kondisi barang.</p>
            <a href="<?= BASE_URL ?>/barang/index.php" class="inline-block px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-slate-800">
                Lihat Semua Barang
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($items as $item): ?>
                <?php require __DIR__ . '/../views/partials/card-item.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
