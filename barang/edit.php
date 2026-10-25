<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

auth_guard('mahasiswa');
$user = current_user();

$itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$itemService = new ItemService();
$item = $itemService->getItemDetail($itemId);

if (!$item || (int)$item['seller_id'] !== (int)$user['id']) {
    set_flash('error', 'Barang tidak ditemukan atau Anda tidak memiliki hak akses.');
    redirect('/barang/kelola.php');
}

$pageTitle = 'Edit Barang · ' . $item['title'];
$categories = $itemService->getCategories();
$errors = [];

$formData = [
    'title'         => $item['title'],
    'category_id'   => $item['category_id'],
    'condition'     => $item['condition'],
    'price'         => (int)$item['price'],
    'location_info' => $item['location_info'],
    'description'   => $item['description']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $formData = [
        'title'         => sanitize_input($_POST['title'] ?? ''),
        'category_id'   => sanitize_input($_POST['category_id'] ?? ''),
        'condition'     => sanitize_input($_POST['condition'] ?? ''),
        'price'         => sanitize_input($_POST['price'] ?? ''),
        'location_info' => sanitize_input($_POST['location_info'] ?? ''),
        'description'   => sanitize_input($_POST['description'] ?? '')
    ];

    $photoFile = $_FILES['photo'] ?? null;
    $res = $itemService->updateItem($itemId, $user['id'], $formData, $photoFile);

    if ($res['success']) {
        if ($res['new_status'] === 'menunggu_persetujuan') {
            set_flash('success', 'Perubahan disimpan! Barang telah diajukan ulang ke antrean review Admin.');
        } else {
            set_flash('success', 'Informasi barang berhasil diperbarui!');
        }
        redirect('/barang/kelola.php');
    } else {
        $errors[] = $res['error'];
    }
}

$hasPhoto = !empty($item['image_url']) && file_exists(ROOT_PATH . '/' . $item['image_url']);
$photoSrc = $hasPhoto ? BASE_URL . '/' . e($item['image_url']) : null;

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Informasi Barang</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui deskripsi, harga penawaran, atau foto produk.</p>
            </div>
            <span class="text-2xl">✏️</span>
        </div>

        <?php if ($item['status'] === 'ditolak'): ?>
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 leading-relaxed">
                <strong class="font-bold block mb-1">Catatan Penolakan Admin:</strong>
                <p class="mb-2 italic">"<?= e($item['rejection_reason'] ?: 'Mohon perbaiki informasi barang.') ?>"</p>
                <span class="text-[11px] text-amber-800 font-semibold block">
                    💡 Menyimpan perubahan formulir ini otomatis mereset status menjadi <strong>Menunggu Persetujuan</strong> untuk ditinjau ulang oleh Admin.
                </span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/barang/edit.php?id=<?= (int)$itemId ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <!-- Judul Barang -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul / Nama Barang</label>
                <input type="text" name="title" required value="<?= e($formData['title']) ?>" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
            </div>

            <!-- Kategori & Kondisi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Barang</label>
                    <select name="category_id" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= (string)$formData['category_id'] === (string)$cat['id'] ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kondisi Fisik</label>
                    <select name="condition" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600">
                        <option value="baru" <?= $formData['condition'] === 'baru' ? 'selected' : '' ?>>Baru</option>
                        <option value="bekas_baik" <?= $formData['condition'] === 'bekas_baik' ? 'selected' : '' ?>>Bekas Baik</option>
                        <option value="bekas_rusak" <?= $formData['condition'] === 'bekas_rusak' ? 'selected' : '' ?>>Bekas / Minus</option>
                    </select>
                </div>
            </div>

            <!-- Harga & Titik Temu COD -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga Penawaran Awal (Rp)</label>
                    <input type="number" name="price" required min="0" step="500" value="<?= e($formData['price']) ?>"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi COD / Area Kos</label>
                    <input type="text" name="location_info" required value="<?= e($formData['location_info']) ?>"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                </div>
            </div>

            <!-- Foto Produk -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Produk (Biarkan kosong jika tidak diganti)</label>
                
                <?php if ($photoSrc): ?>
                    <div class="mb-3 flex items-center gap-3">
                        <div class="w-20 h-16 rounded-lg overflow-hidden border border-slate-200 shrink-0">
                            <img src="<?= $photoSrc ?>" alt="Current" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-slate-500">Foto saat ini. Pilih file baru di bawah untuk mengganti.</span>
                    </div>
                <?php endif; ?>

                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
            </div>

            <!-- Deskripsi Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi & Kelengkapan Barang</label>
                <textarea name="description" rows="4" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600"><?= e($formData['description']) ?></textarea>
            </div>

            <div class="pt-3 flex gap-3">
                <a href="<?= BASE_URL ?>/barang/kelola.php" 
                   class="flex-1 py-3 border border-slate-300 text-slate-700 font-bold text-center text-sm rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-2 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
