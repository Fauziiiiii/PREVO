<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

auth_guard();

$currentUser = current_user();
$targetId = isset($_GET['id']) ? (int)$_GET['id'] : $currentUser['id'];
$isOwnProfile = ($targetId === $currentUser['id']);

$authService = new AuthService();
$userProfile = $authService->getUserById($targetId);

if (!$userProfile) {
    set_flash('error', 'Pengguna tidak ditemukan.');
    redirect('/barang/index.php');
}

$pageTitle = 'Profil · ' . $userProfile['name'];
$errors = [];

// Handle update profil jika profil sendiri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isOwnProfile) {
    verify_csrf();
    $updateData = [
        'name'      => sanitize_input($_POST['name'] ?? ''),
        'whatsapp'  => sanitize_input($_POST['whatsapp'] ?? ''),
        'instagram' => sanitize_input($_POST['instagram'] ?? ''),
        'telegram'  => sanitize_input($_POST['telegram'] ?? ''),
    ];

    $res = $authService->updateProfile($currentUser['id'], $updateData);
    if ($res['success']) {
        set_flash('success', 'Profil dan informasi kontak berhasil diperbarui!');
        redirect('/auth/profil.php');
    } else {
        $errors[] = $res['error'];
    }
}

// Ambil listing barang aktif milik user ini
$db = get_db();
$stmt = $db->prepare("SELECT i.*, c.name as category_name 
                      FROM items i 
                      JOIN categories c ON c.id = i.category_id 
                      WHERE i.seller_id = :seller_id AND i.status IN ('tersedia', 'booking')
                      ORDER BY i.created_at DESC");
$stmt->execute(['seller_id' => $targetId]);
$userItems = $stmt->fetchAll();

// Ambil ulasan yang diterima user ini
$stmtReview = $db->prepare("SELECT r.*, u.name as reviewer_name, t.completed_at 
                            FROM reviews r 
                            JOIN users u ON u.id = r.reviewer_id 
                            JOIN transactions t ON t.id = r.transaction_id
                            WHERE r.seller_id = :seller_id 
                            ORDER BY r.created_at DESC LIMIT 5");
$stmtReview->execute(['seller_id' => $targetId]);
$userReviews = $stmtReview->fetchAll();

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Kartu Profil & Identitas Kampus -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-900 text-white font-extrabold text-2xl flex items-center justify-center mx-auto shadow-md ring-4 ring-slate-100">
                    <?= strtoupper(substr($userProfile['name'], 0, 1)) ?>
                </div>

                <h1 class="text-lg font-bold text-slate-950 mt-4 leading-tight"><?= e($userProfile['name']) ?></h1>
                <p class="text-xs text-slate-500 mt-0.5">NIM: <?= e($userProfile['nim']) ?></p>

                <div class="mt-3 flex flex-wrap items-center justify-center gap-1.5">
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                        <span>✓</span> Mahasiswa Terverifikasi
                    </span>
                    <?php if (!empty($userProfile['whatsapp_verified_at'])): ?>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full">
                            <span>📱</span> WA Terverifikasi
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Akademik Info -->
                <div class="mt-5 pt-4 border-t border-slate-100 text-left text-xs space-y-2 text-slate-600">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Jurusan</span>
                        <span class="font-semibold text-slate-800"><?= e($userProfile['jurusan'] ?? 'Politeknik Negeri Malang') ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Program Studi</span>
                        <span class="font-semibold text-slate-800"><?= e($userProfile['prodi'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Reputasi Penjual</span>
                        <span class="font-bold text-amber-600">
                            ★ <?= !empty($userProfile['avg_rating']) ? number_format((float)$userProfile['avg_rating'], 1) : '5.0' ?>
                        </span>
                        <span class="text-slate-500">(<?= (int)($userProfile['total_reviews'] ?? 0) ?> Ulasan Sukses COD)</span>
                    </div>
                </div>

                <!-- Kontak Pribadi (Hanya Tampil Jika Profil Sendiri) -->
                <?php if ($isOwnProfile): ?>
                    <div class="mt-5 pt-4 border-t border-slate-100 text-left text-xs space-y-2 text-slate-600">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Kontak Transaksi (Privat)</span>
                        <div><strong>Email Kampus:</strong> <?= e($userProfile['email']) ?></div>
                        <div><strong>WhatsApp:</strong> +<?= e($userProfile['whatsapp']) ?></div>
                        <?php if (!empty($userProfile['instagram'])): ?>
                            <div><strong>Instagram:</strong> @<?= e($userProfile['instagram']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Form Edit Profil Sendiri -->
            <?php if ($isOwnProfile): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-1.5">
                        <span>⚙️</span> Perbarui Kontak & Profil
                    </h3>

                    <?php if (!empty($errors)): ?>
                        <div class="mb-3 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs font-semibold text-rose-800">
                            <?php foreach ($errors as $err): ?>
                                <p>⚠️ <?= e($err) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/auth/profil.php" method="POST" class="space-y-3 text-xs">
                        <?= csrf_field() ?>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Tampilan</label>
                            <input type="text" name="name" required value="<?= e($userProfile['name']) ?>"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="whatsapp" required value="<?= e($userProfile['whatsapp']) ?>"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Akun Instagram (Opsional)</label>
                            <input type="text" name="instagram" value="<?= e($userProfile['instagram'] ?? '') ?>" placeholder="username_tanpa_@"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Telegram (Opsional)</label>
                            <input type="text" name="telegram" value="<?= e($userProfile['telegram'] ?? '') ?>" placeholder="username_telegram"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow-sm transition-all mt-2">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Kolom Kanan: Barang yang Dijual & Ulasan Pembeli -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Daftar Barang Dijual -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">
                        Barang Dijual Oleh <?= e(explode(' ', $userProfile['name'])[0]) ?>
                        <span class="text-xs font-normal text-slate-500">(<?= count($userItems) ?> aktif)</span>
                    </h2>
                    <?php if ($isOwnProfile): ?>
                        <a href="<?= BASE_URL ?>/barang/kelola.php" class="text-xs font-semibold text-emerald-700 hover:underline">
                            Kelola Semua Barang →
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (empty($userItems)): ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-xs text-slate-500">
                        Belum ada barang aktif yang dijual saat ini.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($userItems as $item): 
                            $item['seller_name'] = $userProfile['name'];
                            $item['avg_rating'] = $userProfile['avg_rating'];
                            require __DIR__ . '/../views/partials/card-item.php';
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Riwayat Ulasan & Rating -->
            <div>
                <h2 class="text-base font-bold text-slate-900 mb-4">Ulasan Pembeli Pasca-COD</h2>

                <?php if (empty($userReviews)): ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6 text-center text-xs text-slate-500">
                        Belum ada ulasan dari pembeli. Ulasan akan muncul setelah transaksi COD diselesaikan.
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 overflow-hidden shadow-xs">
                        <?php foreach ($userReviews as $rev): ?>
                            <div class="p-4 text-xs">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                        <span><?= e($rev['reviewer_name']) ?></span>
                                        <span class="text-amber-500 font-bold">★ <?= (int)$rev['rating'] ?></span>
                                    </div>
                                    <span class="text-[11px] text-slate-400"><?= format_tanggal($rev['created_at']) ?></span>
                                </div>
                                <p class="text-slate-600 leading-relaxed"><?= e($rev['comment'] ?: 'Penjual ramah dan COD tepat waktu.') ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
