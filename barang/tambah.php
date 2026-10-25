<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

// Membutuhkan mahasiswa login dengan nomor WhatsApp terverifikasi
auth_guard('mahasiswa', true);
$user = current_user();

$pageTitle = 'Pasang Iklan Barang Preloved';
$itemService = new ItemService();
$categories = $itemService->getCategories();

$errors = [];
$formData = [
    'title'         => '',
    'category_id'   => '',
    'condition'     => 'bekas_baik',
    'price'         => '',
    'location_info' => 'Area Kampus Polinema',
    'description'   => ''
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
    $res = $itemService->createItem($user['id'], $formData, $photoFile);

    if ($res['success']) {
        set_flash('success', 'Barang berhasil dipasang! Listing Anda kini masuk ke antrean kurasi Admin sebelum tayang di katalog.');
        redirect('/barang/kelola.php');
    } else {
        $errors[] = $res['error'];
    }
}

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Pasang Iklan Barang</h1>
                <p class="text-xs text-slate-500 mt-0.5">Jual perlengkapan kos, buku, atau elektronik kepada sesama mahasiswa.</p>
            </div>
            <span class="text-2xl">📦</span>
        </div>

        <!-- Banner Info Kebijakan Moderasi Admin -->
        <div class="mb-6 p-3.5 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-950 flex items-start gap-2.5">
            <span class="text-base mt-0.5">🛡️</span>
            <div>
                <strong class="font-bold">Kurasi Keamanan Platform:</strong>
                <p class="text-[11px] text-indigo-900/80 mt-0.5 leading-relaxed">
                    Setiap barang baru akan ditinjau oleh Admin untuk memastikan kesesuaian dengan ketentuan kampus sebelum ditampilkan ke katalog publik.
                </p>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/barang/tambah.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>

            <!-- Judul Barang -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul / Nama Barang</label>
                <input type="text" name="title" required value="<?= e($formData['title']) ?>" 
                       placeholder="Contoh: Kipas Angin Cosmos 16 inch / Meja Belajar Lipat"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
            </div>

            <!-- Kategori & Kondisi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Barang</label>
                    <select name="category_id" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600">
                        <option value="">Pilih Kategori</option>
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
                        <option value="baru" <?= $formData['condition'] === 'baru' ? 'selected' : '' ?>>Baru (Belum Pernah Dipakai)</option>
                        <option value="bekas_baik" <?= $formData['condition'] === 'bekas_baik' ? 'selected' : '' ?>>Bekas Baik (Fungsi Normal)</option>
                        <option value="bekas_rusak" <?= $formData['condition'] === 'bekas_rusak' ? 'selected' : '' ?>>Bekas / Minus (Perlu Servis)</option>
                    </select>
                </div>
            </div>

            <!-- Harga & Titik Temu COD -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga Penawaran Awal (Rp)</label>
                    <input type="number" name="price" required min="0" step="500" value="<?= e($formData['price']) ?>" placeholder="Contoh: 120000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi COD / Area Kos</label>
                    <input type="text" name="location_info" required value="<?= e($formData['location_info']) ?>" placeholder="Contoh: Kos Suhat / Kantin Graha"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                </div>
            </div>

            <!-- Unggah Foto Produk -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Asli Produk (Maks 2MB)</label>
                <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp" onchange="previewImage(event)"
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                
                <!-- Preview Image Container -->
                <div id="imagePreviewContainer" class="hidden mt-3 w-40 h-32 rounded-xl overflow-hidden border border-slate-200">
                    <img id="imagePreview" src="#" alt="Preview" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Deskripsi Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi & Kelengkapan Barang</label>
                <textarea name="description" rows="4" required placeholder="Jelaskan kondisi fisik, kelengkapan aksesoris, minus (jika ada), dan alasan dijual..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600"><?= e($formData['description']) ?></textarea>
            </div>

            <div class="pt-3 flex gap-3">
                <a href="<?= BASE_URL ?>/barang/kelola.php" 
                   class="flex-1 py-3 border border-slate-300 text-slate-700 font-bold text-center text-sm rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-2 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                    Pasang Iklan Sekarang
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
