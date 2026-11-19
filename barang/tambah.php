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
$codLocations = $itemService->getActiveLocations();

$errors = [];
$formData = [
    'title'         => '',
    'category_id'   => '',
    'condition'     => 'bekas_baik',
    'price'         => '',
    'cod_location'  => '',
    'cod_note'      => '',
    'location_info' => '',
    'description'   => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $codLoc = sanitize_input($_POST['cod_location'] ?? '');
    $codNote = sanitize_input($_POST['cod_note'] ?? '');
    $formattedLoc = format_lokasi_cod($codLoc, $codNote);

    $formData = [
        'title'         => sanitize_input($_POST['title'] ?? ''),
        'category_id'   => sanitize_input($_POST['category_id'] ?? ''),
        'condition'     => sanitize_input($_POST['condition'] ?? ''),
        'price'         => sanitize_input($_POST['price'] ?? ''),
        'cod_location'  => $codLoc,
        'cod_note'      => $codNote,
        'location_info' => $formattedLoc,
        'description'   => sanitize_input($_POST['description'] ?? '')
    ];

    if (empty($codLoc)) {
        $errors[] = 'Silakan pilih titik lokasi COD di area kampus Polinema.';
    } else {
        $photoFile = $_FILES['photo'] ?? null;
        $res = $itemService->createItem($user['id'], $formData, $photoFile);

        if ($res['success']) {
            set_flash('success', 'Barang berhasil dipasang! Listing Anda kini masuk ke antrean kurasi Admin sebelum tayang di katalog.');
            redirect('/barang/kelola.php');
        } else {
            $errors[] = $res['error'];
        }
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

        <form id="formTambahBarang" action="<?= BASE_URL ?>/barang/tambah.php" method="POST" enctype="multipart/form-data" class="space-y-4">
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

            <!-- Harga & Titik Temu COD Kampus Polinema -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga Penawaran Awal (Rp)</label>
                    <input type="number" name="price" required min="0" step="500" value="<?= e($formData['price']) ?>" placeholder="Contoh: 120000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Titik COD (Area Kampus)</label>
                    <select name="cod_location" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600">
                        <option value="">Pilih Titik COD Kampus</option>
                        <?php foreach ($codLocations as $loc): ?>
                            <option value="<?= e($loc['name']) ?>" <?= $formData['cod_location'] === $loc['name'] ? 'selected' : '' ?>>
                                📍 <?= e($loc['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Patokan Spesifik Lokasi COD (Opsional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Patokan COD <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                <input type="text" name="cod_note" value="<?= e($formData['cod_note']) ?>" placeholder="Contoh: Depan pintu masuk utama / Meja gazebo dekat taman"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600">
                <p class="text-[11px] text-slate-400 mt-1">Titik temu dibatasi di lingkungan kampus Polinema demi kenyamanan dan keamanan transaksi.</p>
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
                <button type="button" onclick="openConfirmModal()"
                        class="flex-2 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                    Pasang Iklan Sekarang
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Konfirmasi Pasang Iklan -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header Modal -->
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-lg">📦</span>
                <h3 class="font-bold text-sm">Konfirmasi Pasang Iklan Barang</h3>
            </div>
            <button type="button" onclick="closeConfirmModal()" class="text-slate-400 hover:text-white text-xl leading-none">&times;</button>
        </div>

        <!-- Isi Modal / Ringkasan Data -->
        <div class="p-6 space-y-4">
            <p class="text-xs text-slate-500 leading-relaxed">
                Mohon tinjau kembali ringkasan informasi barang sebelum dikirimkan ke tim kurasi PREVO Polinema.
            </p>

            <!-- Kartu Ringkasan -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-start gap-3.5">
                    <div id="modalPhotoThumb" class="w-16 h-16 rounded-xl bg-slate-200 border border-slate-300 overflow-hidden shrink-0 flex items-center justify-center text-slate-400 text-xl font-bold">
                        📦
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 id="modalTitle" class="font-bold text-slate-900 text-sm truncate"></h4>
                        <div id="modalPrice" class="font-extrabold text-emerald-700 text-base mt-0.5"></div>
                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5 text-[11px]">
                            <span id="modalCategory" class="px-2 py-0.5 rounded-md bg-slate-200 text-slate-700 font-medium"></span>
                            <span id="modalCondition" class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 font-medium"></span>
                        </div>
                    </div>
                </div>

                <div class="pt-2.5 border-t border-slate-200 text-xs space-y-1">
                    <div class="flex items-start gap-1.5 text-slate-600">
                        <span class="text-slate-400 shrink-0">📍 Titik COD:</span>
                        <span id="modalLocation" class="font-semibold text-slate-800"></span>
                    </div>
                </div>
            </div>

            <!-- Notice Moderasi Kampus -->
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-950 text-xs leading-relaxed flex items-start gap-2.5">
                <span class="text-base shrink-0 mt-0.5">🛡️</span>
                <div>
                    <strong class="font-bold text-amber-950 block mb-0.5">Ketentuan Kurasi & Keamanan Kampus:</strong>
                    <p class="text-[11px] text-amber-900/90 leading-relaxed">
                        Listing baru akan berstatus <strong>Menunggu Persetujuan</strong> dan ditinjau oleh Admin untuk memastikan kepatuhan tata tertib kampus sebelum tampil di katalog publik.
                    </p>
                </div>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeConfirmModal()" 
                        class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">
                    Periksa Kembali
                </button>
                <button type="button" id="btnSubmitItem" onclick="submitItemForm()" 
                        class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-1.5">
                    <span>✓ Ya, Pasang Iklan Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let uploadedImageDataUrl = null;

function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedImageDataUrl = e.target.result;
            document.getElementById('imagePreview').src = uploadedImageDataUrl;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        uploadedImageDataUrl = null;
        document.getElementById('imagePreviewContainer').classList.add('hidden');
    }
}

function openConfirmModal() {
    const form = document.getElementById('formTambahBarang');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Ambil nilai dari input formulir
    const title = form.elements['title'].value.trim();
    const catSelect = form.elements['category_id'];
    const category = catSelect.options[catSelect.selectedIndex]?.text.trim() || '-';
    
    const condSelect = form.elements['condition'];
    const condition = condSelect.options[condSelect.selectedIndex]?.text.trim() || '-';

    const priceVal = parseFloat(form.elements['price'].value) || 0;
    const formattedPrice = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(priceVal);

    const codLocSelect = form.elements['cod_location'];
    const codLoc = codLocSelect.options[codLocSelect.selectedIndex]?.text.replace('📍', '').trim() || '-';
    const codNote = form.elements['cod_note'] ? form.elements['cod_note'].value.trim() : '';
    const fullLoc = codNote ? `${codLoc} — ${codNote}` : codLoc;

    // Masukkan ke dalam elemen modal
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalPrice').textContent = formattedPrice;
    document.getElementById('modalCategory').textContent = category;
    document.getElementById('modalCondition').textContent = condition;
    document.getElementById('modalLocation').textContent = fullLoc;

    // Foto thumbnail di modal
    const thumbContainer = document.getElementById('modalPhotoThumb');
    if (uploadedImageDataUrl) {
        thumbContainer.innerHTML = `<img src="${uploadedImageDataUrl}" alt="Thumbnail" class="w-full h-full object-cover">`;
    } else {
        thumbContainer.innerHTML = `<span class="text-2xl">📦</span>`;
    }

    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function submitItemForm() {
    const btn = document.getElementById('btnSubmitItem');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
    document.getElementById('formTambahBarang').submit();
}

// Tutup modal jika klik area luar backdrop atau tekan tombol Escape
document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
