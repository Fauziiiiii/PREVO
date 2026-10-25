<?php
// ============================================================
//  PREVO - Moderasi Listing Barang Mahasiswa (Admin)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AdminService.php';

$user = auth_guard('admin');
$adminService = new AdminService();

// Handle Form Action (Approve / Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $itemId = (int)($_POST['item_id'] ?? 0);

    if ($action === 'approve' && $itemId > 0) {
        $res = $adminService->approveItem($itemId, (int)$user['id']);
        if ($res['success']) {
            set_flash('success', 'Barang berhasil disetujui dan kini tayang di katalog publik.');
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menyetujui barang.');
        }
    } elseif ($action === 'reject' && $itemId > 0) {
        $reason = trim($_POST['rejection_reason'] ?? '');
        $res = $adminService->rejectItem($itemId, (int)$user['id'], $reason);
        if ($res['success']) {
            set_flash('info', 'Barang telah ditolak dengan catatan edukasi untuk penjual.');
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menolak barang.');
        }
    }

    redirect(BASE_URL . '/admin/moderasi.php');
}

$pendingList = $adminService->getPendingItems();

$pageTitle = 'Moderasi Listing Barang - PREVO';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-xs text-blue-900 font-semibold hover:underline">&larr; Kembali ke Dashboard</a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Antrean Moderasi Barang</h1>
            <p class="text-sm text-slate-500 mt-1">Review barang yang baru diunggah mahasiswa sebelum dipublikasikan ke katalog umum Polinema.</p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <?= count($pendingList) ?> Barang Menunggu Review
            </span>
        </div>
    </div>

    <?php if (empty($pendingList)): ?>
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Antrean Moderasi Bersih</h3>
            <p class="text-sm text-slate-500 mt-1 mb-6">Tidak ada barang baru yang menunggu persetujuan saat ini.</p>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-950 text-white text-sm font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                Ke Dashboard SIM
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($pendingList as $item): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all hover:border-slate-300">
                    <div class="p-5 sm:p-6 flex flex-col lg:flex-row gap-6">
                        <!-- Foto Barang -->
                        <div class="w-full lg:w-56 h-56 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                            <?php 
                                $imgFile = $item['image_url'] ?: 'default.jpg';
                                $hasImg = file_exists(ROOT_PATH . '/uploads/items/' . $imgFile);
                                $imgUrl = $hasImg ? asset_url('uploads/items/' . $imgFile) : asset_url('uploads/items/default.jpg');
                            ?>
                            <img src="<?= $imgUrl ?>" 
                                 alt="<?= e($item['title']) ?>" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Info & Metadata -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                            <?= e($item['category_name']) ?>
                                        </span>
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800">
                                            Kondisi: <?= e(ucfirst($item['condition'])) ?>
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            Diunggah: <?= format_tanggal($item['created_at']) ?>
                                        </span>
                                    </div>
                                    <div class="text-lg font-black text-emerald-700">
                                        <?= format_rupiah((float)$item['price']) ?>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 mb-2">
                                    <?= e($item['title']) ?>
                                </h3>

                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-4">
                                    <?= nl2br(e($item['description'])) ?>
                                </p>

                                <!-- Info Penjual (Mahasiswa) -->
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 flex flex-wrap items-center gap-y-1 gap-x-6">
                                    <div>
                                        <span class="text-slate-400">Penjual:</span> 
                                        <span class="font-bold text-slate-800"><?= e($item['seller_name']) ?></span> (NIM <?= e($item['seller_nim']) ?>)
                                    </div>
                                    <div>
                                        <span class="text-slate-400">Jurusan:</span> <?= e($item['seller_jurusan'] ?? '-') ?> &bull; <?= e($item['seller_prodi'] ?? '-') ?>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">WhatsApp:</span> <?= e($item['seller_whatsapp'] ?? '-') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <!-- Tombol Tolak (Buka Modal) -->
                                <button type="button" onclick="document.getElementById('modal-reject-<?= $item['id'] ?>').classList.remove('hidden')" 
                                        class="px-4 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-bold hover:bg-red-50 transition-colors">
                                    Tolak Listing
                                </button>

                                <!-- Form Setujui -->
                                <form action="<?= BASE_URL ?>/admin/moderasi.php" method="POST" onsubmit="return confirm('Setujui barang ini agar tayang di katalog umum?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-500 shadow-xs transition-colors">
                                        ✓ Setujui & Tayangkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL ALASAN PENOLAKAN -->
                    <div id="modal-reject-<?= $item['id'] ?>" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                                <h4 class="font-bold text-sm">Alasan Penolakan Listing</h4>
                                <button type="button" onclick="document.getElementById('modal-reject-<?= $item['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
                            </div>
                            <form action="<?= BASE_URL ?>/admin/moderasi.php" method="POST" class="p-6 space-y-4">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                                        Catatan / Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="rejection_reason" rows="3" required
                                              class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500"
                                              placeholder="Misal: Foto barang tidak jelas/buram, barang melanggar ketentuan kampus, dll..."></textarea>
                                    <p class="text-[11px] text-slate-400 mt-1">Alasan ini akan ditampilkan kepada mahasiswa di menu 'Kelola Barang' untuk diperbaiki.</p>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2">
                                    <button type="button" onclick="document.getElementById('modal-reject-<?= $item['id'] ?>').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-500">
                                        Kirim Penolakan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
