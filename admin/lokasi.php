<?php
// ============================================================
//  PREVO - Manajemen Master Titik Lokasi COD Kampus (Admin)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AdminService.php';

$user = auth_guard('admin');
$adminService = new AdminService();

// Handle Form Submissions (Create / Update / Toggle / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $res = $adminService->addLocation($name);
        if ($res['success']) {
            set_flash('success', "Titik COD '{$name}' berhasil ditambahkan ke sistem.");
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menambah titik COD.');
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $isActive = isset($_POST['is_active']);
        $res = $adminService->updateLocation($id, $name, $isActive);
        if ($res['success']) {
            set_flash('success', "Titik COD '{$name}' berhasil diperbarui.");
        } else {
            set_flash('error', $res['error'] ?? 'Gagal memperbarui titik COD.');
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $res = $adminService->toggleLocationStatus($id);
        if ($res['success']) {
            set_flash('info', 'Status aktifitas titik COD berhasil diperbarui.');
        } else {
            set_flash('error', $res['error'] ?? 'Gagal mengubah status lokasi.');
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $res = $adminService->deleteLocation($id);
        if ($res['success']) {
            set_flash('info', 'Titik COD berhasil dihapus dari sistem.');
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menghapus titik COD.');
        }
    }

    redirect(BASE_URL . '/admin/lokasi.php');
}

$locations = $adminService->getLocations();

$pageTitle = 'Master Titik Lokasi COD Kampus - PREVO';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-xs text-blue-900 font-semibold hover:underline">&larr; Kembali ke Dashboard</a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Titik Lokasi COD Kampus</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar titik temu transaksi COD resmi di area kampus Polinema (berdasarkan denah resmi).</p>
        </div>
        <div>
            <button type="button" onclick="document.getElementById('modal-add-location').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-950 text-white text-xs font-bold hover:bg-blue-900 transition-colors shadow-xs">
                <span>+</span> Tambah Titik COD Baru
            </button>
        </div>
    </div>

    <!-- Table Lokasi COD -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5 w-16">ID</th>
                        <th class="px-6 py-3.5">Nama Titik COD Kampus</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5">Terakhir Diperbarui</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($locations)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada titik COD yang terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($locations as $loc): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono text-slate-400">#<?= $loc['id'] ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                        <span class="text-emerald-700">📍</span>
                                        <span><?= e($loc['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="<?= BASE_URL ?>/admin/lokasi.php" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= $loc['id'] ?>">
                                        <button type="submit" title="Klik untuk mengubah status aktif"
                                                class="px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-transform hover:scale-105 <?= $loc['is_active'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' ?>">
                                            <?= $loc['is_active'] ? '✓ Aktif' : '✕ Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-xs">
                                    <?= format_tanggal($loc['updated_at']) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="document.getElementById('modal-edit-<?= $loc['id'] ?>').classList.remove('hidden')" 
                                                class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                                            Edit
                                        </button>
                                        <form action="<?= BASE_URL ?>/admin/lokasi.php" method="POST" onsubmit="return confirm('Hapus titik COD <?= e(addslashes($loc['name'])) ?> dari master data?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $loc['id'] ?>">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-semibold transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Titik COD -->
                            <div id="modal-edit-<?= $loc['id'] ?>" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                                        <h4 class="font-bold text-sm">Edit Titik Lokasi COD</h4>
                                        <button type="button" onclick="document.getElementById('modal-edit-<?= $loc['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
                                    </div>
                                    <form action="<?= BASE_URL ?>/admin/lokasi.php" method="POST" class="p-6 space-y-4">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?= $loc['id'] ?>">

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Titik COD Kampus <span class="text-red-500">*</span></label>
                                            <input type="text" name="name" value="<?= e($loc['name']) ?>" required
                                                   class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                        </div>

                                        <div class="flex items-center gap-2 pt-1">
                                            <input type="checkbox" name="is_active" id="edit_active_<?= $loc['id'] ?>" value="1" <?= $loc['is_active'] ? 'checked' : '' ?>
                                                   class="w-4 h-4 text-emerald-600 rounded-sm border-slate-300 focus:ring-emerald-500">
                                            <label for="edit_active_<?= $loc['id'] ?>" class="text-xs font-semibold text-slate-700">Tampilkan titik ini di pilihan dropdown mahasiswa</label>
                                        </div>

                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" onclick="document.getElementById('modal-edit-<?= $loc['id'] ?>').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Titik COD Baru -->
<div id="modal-add-location" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="font-bold text-sm">Tambah Titik Lokasi COD Baru</h4>
            <button type="button" onclick="document.getElementById('modal-add-location').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/admin/lokasi.php" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Titik COD Kampus <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Misal: Kantin Graha Polinema"
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                <p class="text-[11px] text-slate-400 mt-1">Pastikan titik berada di area dalam kampus Politeknik Negeri Malang.</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-add-location').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900">
                    Tambah Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
