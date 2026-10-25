<?php
// ============================================================
//  PREVO - Beri Ulasan & Rating Bintang (Pembeli)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/TransactionService.php';
require_once __DIR__ . '/../services/ReviewService.php';

$user = auth_guard('mahasiswa', true);
$txService = new TransactionService();
$reviewService = new ReviewService();

$txId = (int)($_GET['tx_id'] ?? $_POST['tx_id'] ?? 0);

if ($txId <= 0) {
    set_flash('error', 'Transaksi tidak ditemukan.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
}

$tx = $txService->getTransactionById($txId);

if (!$tx || (int)$tx['buyer_id'] !== (int)$user['id']) {
    set_flash('error', 'Anda tidak memiliki akses untuk mengulas transaksi ini.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
}

if ($tx['status'] !== 'selesai') {
    set_flash('error', 'Ulasan hanya dapat diberikan jika transaksi COD telah selesai.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
}

if (!empty($tx['review_id'])) {
    set_flash('info', 'Anda sudah memberikan ulasan untuk transaksi ini.');
    redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = 'Silakan pilih rating antara 1 sampai 5 bintang.';
    } else {
        $res = $reviewService->addReview($txId, (int)$user['id'], $rating, $comment);
        if ($res['success']) {
            set_flash('success', 'Terima kasih! Ulasan dan rating Anda untuk penjual berhasil disimpan.');
            redirect(BASE_URL . '/transaksi/riwayat.php?tab=buying');
        } else {
            $error = $res['error'] ?? 'Gagal menyimpan ulasan.';
        }
    }
}

$pageTitle = 'Beri Ulasan Penjual - PREVO';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-slate-500 mb-6">
        <a href="<?= BASE_URL ?>/transaksi/riwayat.php?tab=buying" class="hover:text-blue-900 transition-colors">Riwayat Pembelian</a>
        <span class="mx-2">/</span>
        <span class="text-slate-800 font-medium">Beri Ulasan</span>
    </nav>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="bg-gradient-to-r from-blue-950 to-slate-900 px-6 py-6 text-white">
            <h1 class="text-xl font-bold">Beri Ulasan & Rating</h1>
            <p class="text-xs text-blue-200 mt-1">Ulasan Anda membantu membangun reputasi ekosistem jual beli kampus yang aman dan terpercaya di Polinema.</p>
        </div>

        <div class="p-6">
            <!-- Info Transaksi -->
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 mb-6">
                <?php 
                    $imgFile = $tx['item_image'] ?: 'default.jpg';
                    $hasImg = file_exists(ROOT_PATH . '/uploads/items/' . $imgFile);
                    $imgUrl = $hasImg ? asset_url('uploads/items/' . $imgFile) : asset_url('uploads/items/default.jpg');
                ?>
                <img src="<?= $imgUrl ?>" 
                     alt="<?= e($tx['item_title']) ?>" 
                     class="w-16 h-16 object-cover rounded-lg bg-slate-200 shrink-0">
                <div class="min-w-0 flex-1">
                    <h3 class="font-bold text-slate-900 text-sm truncate"><?= e($tx['item_title']) ?></h3>
                    <p class="text-xs text-slate-500 mt-0.5">Penjual: <span class="font-medium text-slate-700"><?= e($tx['seller_name']) ?></span></p>
                    <p class="text-xs text-emerald-600 font-semibold mt-1">Harga Kesepakatan: <?= format_rupiah((float)($tx['agreed_price'] ?? $tx['original_price'] ?? 0)) ?></p>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/transaksi/beri_ulasan.php?tx_id=<?= $txId ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="tx_id" value="<?= $txId ?>">

                <!-- Star Rating Radio Group -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Penilaian Bintang <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-4">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="radio" name="rating" value="<?= $i ?>" required class="sr-only peer" <?= (isset($_POST['rating']) && (int)$_POST['rating'] === $i) ? 'checked' : ($i === 5 ? 'checked' : '') ?>>
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center border-2 border-slate-200 peer-checked:border-amber-400 peer-checked:bg-amber-50 group-hover:border-amber-300 transition-all">
                                    <span class="text-amber-400 text-xl font-bold">★</span>
                                </div>
                                <span class="text-xs text-slate-600 mt-1 font-medium peer-checked:text-amber-700 peer-checked:font-bold"><?= $i ?> Bintang</span>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Beri nilai 1 (sangat buruk) sampai 5 (sangat memuaskan).</p>
                </div>

                <!-- Comment Input -->
                <div>
                    <label for="comment" class="block text-sm font-semibold text-slate-800 mb-1">Ulasan / Testimoni <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <textarea id="comment" name="comment" rows="4" 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent transition-all"
                              placeholder="Ceritakan pengalaman Anda: ketepatan waktu COD, keramahan penjual, kondisi barang sesuai deskripsi, dll..."><?= e($_POST['comment'] ?? '') ?></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= BASE_URL ?>/transaksi/riwayat.php?tab=buying" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-950 text-white text-sm font-semibold hover:bg-blue-900 shadow-sm transition-all">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
