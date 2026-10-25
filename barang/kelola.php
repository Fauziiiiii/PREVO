<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

auth_guard('mahasiswa');
$user = current_user();

$pageTitle = 'Kelola Barang Jualan Saya';
$itemService = new ItemService();
$sellerItems = $itemService->getSellerItems($user['id']);

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight flex items-center gap-2">
                <span>📦 Kelola Barang Jualan Saya</span>
                <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full">
                    <?= count($sellerItems) ?> total
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Pantau status kurasi moderasi admin, ubah informasi, dan kelola ketersediaan listing.</p>
        </div>

        <a href="<?= BASE_URL ?>/barang/tambah.php" 
           class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
            <span>+</span> Pasang Iklan Baru
        </a>
    </div>

    <?php if (empty($sellerItems)): ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-md mx-auto my-8">
            <span class="text-4xl block mb-2">📦</span>
            <h3 class="text-base font-bold text-slate-900">Belum ada barang jualan</h3>
            <p class="text-xs text-slate-500 mt-1 mb-5">Miliki barang kos atau buku kuliah yang tidak terpakai? Pasang iklan sekarang.</p>
            <a href="<?= BASE_URL ?>/barang/tambah.php" class="inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-500">
                + Pasang Iklan Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($sellerItems as $item): 
                $hasPhoto = !empty($item['image_url']) && file_exists(ROOT_PATH . '/' . $item['image_url']);
                $photoSrc = $hasPhoto ? BASE_URL . '/' . e($item['image_url']) : null;
            ?>
                <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    
                    <!-- Info Kiri: Foto & Judul -->
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center text-xs text-slate-400">
                            <?php if ($photoSrc): ?>
                                <img src="<?= $photoSrc ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span>No Foto</span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <!-- Badge Status -->
                                <?php if ($item['status'] === 'tersedia'): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        ✓ Tayang di Katalog
                                    </span>
                                <?php elseif ($item['status'] === 'booking'): ?>
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        ⏳ Sedang Dibooking
                                    </span>
                                <?php elseif ($item['status'] === 'menunggu_persetujuan'): ?>
                                    <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        ⏱️ Menunggu Review Admin
                                    </span>
                                <?php elseif ($item['status'] === 'ditolak'): ?>
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        ✕ Ditolak Admin
                                    </span>
                                <?php elseif ($item['status'] === 'terjual'): ?>
                                    <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        🎉 Terjual
                                    </span>
                                <?php endif; ?>

                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-500"><?= e($item['category_name']) ?></span>
                            </div>

                            <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">
                                <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= (int)$item['id'] ?>" class="hover:text-emerald-700 hover:underline">
                                    <?= e($item['title']) ?>
                                </a>
                            </h3>

                            <div class="mt-1 flex items-center gap-3 text-xs">
                                <span class="font-extrabold text-slate-900 text-sm"><?= format_rupiah($item['price']) ?></span>
                                <span class="text-slate-400">|</span>
                                <span class="text-slate-500">📍 <?= e($item['location_info'] ?: 'Kampus Polinema') ?></span>
                                <span class="text-slate-400">|</span>
                                <span class="text-slate-400 text-[11px]"><?= format_tanggal($item['created_at']) ?></span>
                            </div>

                            <!-- Catatan Penolakan Admin (Jika Ditolak) -->
                            <?php if ($item['status'] === 'ditolak' && !empty($item['rejection_reason'])): ?>
                                <div class="mt-2.5 p-2.5 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-900 leading-relaxed max-w-xl">
                                    <strong class="font-bold block mb-0.5">Alasan Penolakan oleh Admin:</strong>
                                    <?= e($item['rejection_reason']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Aksi Kanan -->
                    <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                        <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= (int)$item['id'] ?>" 
                           class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                            Lihat
                        </a>

                        <?php if ($item['status'] !== 'terjual'): ?>
                            <a href="<?= BASE_URL ?>/barang/edit.php?id=<?= (int)$item['id'] ?>" 
                               class="px-3 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800">
                                <?= $item['status'] === 'ditolak' ? 'Edit & Ajukan Ulang' : 'Edit' ?>
                            </a>

                            <?php if ($item['status'] !== 'booking'): ?>
                                <button type="button" 
                                        onclick="confirmDelete(<?= (int)$item['id'] ?>, '<?= e(addslashes($item['title'])) ?>')"
                                        class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 text-xs font-semibold hover:bg-rose-50">
                                    Hapus
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center">
        <span class="text-3xl block mb-2">🗑️</span>
        <h3 class="font-bold text-slate-900 text-base mb-1">Hapus Listing Barang?</h3>
        <p class="text-xs text-slate-500 mb-5">Barang <strong id="deleteItemTitle" class="text-slate-800"></strong> akan ditarik dari katalog secara permanen.</p>

        <form id="deleteForm" action="<?= BASE_URL ?>/barang/hapus.php" method="POST" class="flex gap-2">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="deleteItemId" value="">
            <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 py-2 px-3 border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </button>
            <button type="submit" 
                    class="flex-1 py-2 px-3 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
function confirmDelete(id, title) {
    document.getElementById('deleteItemId').value = id;
    document.getElementById('deleteItemTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
