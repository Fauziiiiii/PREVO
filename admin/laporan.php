<?php
// ============================================================
//  PREVO - Moderasi Laporan Pelanggaran Komunitas (Admin)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AdminService.php';

$user = auth_guard('admin');
$adminService = new AdminService();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $reportId = (int)($_POST['report_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $adminNote = trim($_POST['admin_note'] ?? '');

    if ($reportId > 0) {
        if ($action === 'takedown') {
            // Takedown item and mark report selesai
            $res = $adminService->handleReport($reportId, (int)$user['id'], 'selesai', $adminNote, true);
            if ($res['success']) {
                set_flash('success', 'Laporan berhasil ditindaklanjuti dan barang telah di-takedown dari katalog.');
            } else {
                set_flash('error', $res['error'] ?? 'Gagal memproses laporan.');
            }
        } elseif ($action === 'dismiss') {
            // Reject report as invalid
            $res = $adminService->handleReport($reportId, (int)$user['id'], 'ditolak', $adminNote, false);
            if ($res['success']) {
                set_flash('info', 'Laporan ditolak / diabaikan karena tidak terbukti melanggar ketentuan.');
            } else {
                set_flash('error', $res['error'] ?? 'Gagal memproses laporan.');
            }
        } elseif ($action === 'review') {
            $res = $adminService->handleReport($reportId, (int)$user['id'], 'ditinjau', $adminNote, false);
            if ($res['success']) {
                set_flash('info', 'Status laporan diubah menjadi Dalam Peninjauan.');
            } else {
                set_flash('error', $res['error'] ?? 'Gagal memproses laporan.');
            }
        }
    }

    redirect(BASE_URL . '/admin/laporan.php');
}

$statusFilter = in_array($_GET['status'] ?? '', ['menunggu', 'ditinjau', 'selesai', 'ditolak']) ? $_GET['status'] : null;
$reports = $adminService->getReports($statusFilter);

$pageTitle = 'Laporan Pelanggaran Komunitas - PREVO';
include __DIR__ . '/../views/partials/header.php';
include __DIR__ . '/../views/partials/navbar.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-xs text-blue-900 font-semibold hover:underline">&larr; Kembali ke Dashboard</a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Pelanggaran & Keamanan Komunitas</h1>
            <p class="text-sm text-slate-500 mt-1">Tindak lanjuti aduan mahasiswa terhadap postingan barang yang terindikasi melanggar aturan atau scam.</p>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="<?= BASE_URL ?>/admin/laporan.php" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors <?= !$statusFilter ? 'bg-blue-950 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            Semua Laporan
        </a>
        <a href="<?= BASE_URL ?>/admin/laporan.php?status=menunggu" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors <?= $statusFilter === 'menunggu' ? 'bg-amber-500 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            Menunggu Tindakan
        </a>
        <a href="<?= BASE_URL ?>/admin/laporan.php?status=ditinjau" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors <?= $statusFilter === 'ditinjau' ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            Sedang Ditinjau
        </a>
        <a href="<?= BASE_URL ?>/admin/laporan.php?status=selesai" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors <?= $statusFilter === 'selesai' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            Selesai (Ditindak)
        </a>
        <a href="<?= BASE_URL ?>/admin/laporan.php?status=ditolak" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors <?= $statusFilter === 'ditolak' ? 'bg-slate-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            Ditolak / Diabaikan
        </a>
    </div>

    <?php if (empty($reports)): ?>
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Tidak Ada Laporan Pelanggaran</h3>
            <p class="text-sm text-slate-500 mt-1 mb-6">Lingkungan jual beli kampus Polinema aman dan tertib.</p>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-950 text-white text-sm font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                Ke Dashboard SIM
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($reports as $rep): ?>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all hover:border-slate-300">
                    <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-5">
                        <!-- Foto Barang yang Dilaporkan -->
                        <div class="w-full md:w-32 h-32 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                            <img src="<?= asset_url('uploads/items/' . ($rep['item_image'] ?: 'default.jpg')) ?>" 
                                 alt="<?= e($rep['item_title']) ?>" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Info Laporan -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <?= e($rep['reason']) ?>
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            Dilaporkan: <?= format_tanggal($rep['created_at']) ?>
                                        </span>
                                    </div>

                                    <!-- Status Laporan -->
                                    <div>
                                        <?php if ($rep['status'] === 'menunggu'): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                Menunggu Tindakan
                                            </span>
                                        <?php elseif ($rep['status'] === 'ditinjau'): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                                Sedang Ditinjau
                                            </span>
                                        <?php elseif ($rep['status'] === 'selesai'): ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                Ditindaklanjuti (Selesai)
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                Ditolak / Diabaikan
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 mb-1">
                                    <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= $rep['item_id'] ?>" target="_blank" class="hover:text-blue-900 hover:underline">
                                        <?= e($rep['item_title']) ?> &nearr;
                                    </a>
                                    <?php if ($rep['item_status'] === 'dihapus'): ?>
                                        <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded bg-rose-100 text-rose-700">Telah Di-takedown</span>
                                    <?php endif; ?>
                                </h3>

                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 mb-3">
                                    <div class="font-semibold text-slate-500 mb-1">Detail Aduan Pelapor:</div>
                                    <p class="italic">"<?= e($rep['description']) ?>"</p>
                                </div>

                                <div class="flex flex-wrap items-center gap-y-1 gap-x-6 text-xs text-slate-500">
                                    <div>
                                        <span class="text-slate-400">Pelapor:</span> 
                                        <span class="font-semibold text-slate-800"><?= e($rep['reporter_name']) ?></span> (NIM <?= e($rep['reporter_nim']) ?>)
                                    </div>
                                    <div>
                                        <span class="text-slate-400">Penjual Terlapor:</span> 
                                        <span class="font-semibold text-slate-800"><?= e($rep['seller_name']) ?></span> (NIM <?= e($rep['seller_nim']) ?>)
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Admin -->
                            <?php if ($rep['status'] === 'menunggu' || $rep['status'] === 'ditinjau'): ?>
                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-end gap-2">
                                    <!-- Abaikan Laporan -->
                                    <form action="<?= BASE_URL ?>/admin/laporan.php" method="POST" onsubmit="return confirm('Tolak/abaikan laporan ini karena tidak melanggar ketentuan?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="report_id" value="<?= $rep['id'] ?>">
                                        <input type="hidden" name="action" value="dismiss">
                                        <button type="submit" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition-colors">
                                            Tolak Laporan (Abaikan)
                                        </button>
                                    </form>

                                    <!-- Takedown Barang & Selesaikan -->
                                    <form action="<?= BASE_URL ?>/admin/laporan.php" method="POST" onsubmit="return confirm('Takedown barang ini dari katalog dan tandai laporan selesai?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="report_id" value="<?= $rep['id'] ?>">
                                        <input type="hidden" name="action" value="takedown">
                                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-500 transition-colors shadow-xs">
                                            🚨 Takedown Barang & Selesaikan
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
