<?php
// ============================================================
//  PREVO - Manajemen Master Kategori Barang (Admin)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AdminService.php';

$user = auth_guard('admin');
$adminService = new AdminService();

// Handle Form Submissions (Create / Update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $res = $adminService->addCategory($name, $description, $icon);
        if ($res['success']) {
            set_flash('success', "Kategori '{$name}' berhasil ditambahkan.");
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menambah kategori.');
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '');
        $res = $adminService->updateCategory($id, $name, $description, $icon);
        if ($res['success']) {
            set_flash('success', "Kategori '{$name}' berhasil diperbarui.");
        } else {
            set_flash('error', $res['error'] ?? 'Gagal memperbarui kategori.');
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $res = $adminService->deleteCategory($id);
        if ($res['success']) {
            set_flash('info', 'Kategori berhasil dihapus.');
        } else {
            set_flash('error', $res['error'] ?? 'Gagal menghapus kategori.');
        }
    }

    redirect(BASE_URL . '/admin/kategori.php');
}

$categories = $adminService->getCategories();

$pageTitle = 'Master Kategori Barang - PREVO';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-xs text-blue-900 font-semibold hover:underline">&larr; Kembali ke Dashboard</a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Kategori Barang</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola klasifikasi produk mahasiswa untuk mempermudah pencarian dan filter di katalog.</p>
        </div>
        <div>
            <button type="button" onclick="document.getElementById('modal-add-category').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-950 text-white text-xs font-bold hover:bg-blue-900 transition-colors shadow-xs">
                <span>+</span> Tambah Kategori Baru
            </button>
        </div>
    </div>

    <!-- Table Kategori -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Icon</th>
                        <th class="px-6 py-3.5">Nama Kategori</th>
                        <th class="px-6 py-3.5">Deskripsi</th>
                        <th class="px-6 py-3.5 text-center">Jumlah Barang Terdaftar</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono text-slate-400">#<?= $cat['id'] ?></td>
                            <td class="px-6 py-4 text-base"><?= e($cat['icon'] ?: '📦') ?></td>
                            <td class="px-6 py-4 font-bold text-slate-900"><?= e($cat['name']) ?></td>
                            <td class="px-6 py-4 text-slate-500"><?= e($cat['description'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= (int)$cat['total_items'] > 0 ? 'bg-blue-50 text-blue-900' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= (int)$cat['total_items'] ?> item
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('modal-edit-<?= $cat['id'] ?>').classList.remove('hidden')" 
                                            class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                                        Edit
                                    </button>

                                    <?php if ((int)$cat['total_items'] === 0): ?>
                                        <form action="<?= BASE_URL ?>/admin/kategori.php" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-semibold transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Kategori -->
                        <div id="modal-edit-<?= $cat['id'] ?>" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                                    <h4 class="font-bold text-sm">Edit Kategori</h4>
                                    <button type="button" onclick="document.getElementById('modal-edit-<?= $cat['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
                                </div>
                                <form action="<?= BASE_URL ?>/admin/kategori.php" method="POST" class="p-6 space-y-4">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" value="<?= e($cat['name']) ?>" required
                                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                                        <input type="text" name="description" value="<?= e($cat['description'] ?? '') ?>"
                                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Icon Emoji</label>
                                        <input type="text" name="icon" value="<?= e($cat['icon'] ?? '') ?>"
                                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('modal-edit-<?= $cat['id'] ?>').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="modal-add-category" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="font-bold text-sm">Tambah Kategori Baru</h4>
            <button type="button" onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/admin/kategori.php" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Misal: Perlengkapan Praktikum"
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat (Opsional)</label>
                <input type="text" name="description" placeholder="Alat, komponen, dan jas lab praktikum"
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Icon Emoji</label>
                <input type="text" name="icon" placeholder="🔬"
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900">
                    Tambah Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
