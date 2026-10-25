<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

auth_guard('mahasiswa');
$user = current_user();
$itemService = new ItemService();

// Handle Toggle Bookmark via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $redirectUrl = $_POST['redirect'] ?? '/barang/favorit.php';

    if ($itemId > 0) {
        $added = $itemService->toggleFavorite($user['id'], $itemId);
        if ($added) {
            set_flash('success', 'Barang berhasil disimpan ke daftar Favorit.');
        } else {
            set_flash('info', 'Barang dihapus dari daftar Favorit.');
        }
    }
    redirect($redirectUrl);
}

// Display List of Favorites
$pageTitle = 'Barang Favorit Saya';
$favorites = $itemService->getUserFavorites($user['id']);

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight flex items-center gap-2">
            <span>❤️ Barang Favorit Saya</span>
            <span class="text-xs font-semibold text-rose-700 bg-rose-100 px-2.5 py-0.5 rounded-full">
                <?= count($favorites) ?> disimpan
            </span>
        </h1>
        <p class="text-xs text-slate-500 mt-1">Daftar barang preloved yang Anda simpan untuk dipantau ketersediaannya.</p>
    </div>

    <?php if (empty($favorites)): ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-md mx-auto my-8">
            <span class="text-4xl block mb-2">🤍</span>
            <h3 class="text-base font-bold text-slate-900">Belum ada barang favorit</h3>
            <p class="text-xs text-slate-500 mt-1 mb-5">Temukan barang yang Anda minati di katalog dan klik tombol simpan.</p>
            <a href="<?= BASE_URL ?>/barang/index.php" class="inline-block px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-slate-800">
                Jelajahi Katalog
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($favorites as $item): ?>
                <?php require __DIR__ . '/../views/partials/card-item.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
