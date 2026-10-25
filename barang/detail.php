<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../services/ItemService.php';

$itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$itemService = new ItemService();
$item = $itemService->getItemDetail($itemId);

if (!$item) {
    set_flash('error', 'Barang tidak ditemukan atau telah dihapus.');
    redirect('/barang/index.php');
}

$pageTitle = $item['title'];
$currentUser = current_user();
$isLoggedIn = is_logged_in();
$isSeller = $isLoggedIn && ((int)$currentUser['id'] === (int)$item['seller_id']);
$isFavorited = $isLoggedIn ? $itemService->isFavorited($currentUser['id'], $item['id']) : false;

// Cek apakah pembeli saat ini yang sedang mem-booking barang ini
$db = get_db();
$activeBooking = null;
if ($isLoggedIn && $item['status'] === 'booking') {
    $stmtB = $db->prepare("SELECT id, status, agreed_price FROM transactions 
                           WHERE item_id = :item_id AND buyer_id = :buyer_id AND status = 'pending'");
    $stmtB->execute(['item_id' => $item['id'], 'buyer_id' => $currentUser['id']]);
    $activeBooking = $stmtB->fetch();
}

// Handle Form Laporan Pelanggaran
$reportMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'report') {
    auth_guard();
    verify_csrf();
    $reason = sanitize_input($_POST['reason'] ?? '');
    $description = sanitize_input($_POST['description'] ?? '');
    $resReport = $itemService->reportItem($currentUser['id'], $item['id'], $reason, $description);
    if ($resReport['success']) {
        set_flash('success', 'Laporan Anda telah dikirimkan ke Admin untuk ditinjau. Terima kasih telah menjaga keamanan kampus!');
        redirect('/barang/detail.php?id=' . $item['id']);
    } else {
        $reportMessage = $resReport['error'];
    }
}

// Penanganan status dan foto
$conditionLabels = [
    'baru'        => 'Baru',
    'bekas_baik'  => 'Bekas Baik',
    'bekas_rusak' => 'Bekas / Minus'
];
$condition = $conditionLabels[$item['condition']] ?? $item['condition'];

$hasPhoto = !empty($item['image_url']) && file_exists(ROOT_PATH . '/' . $item['image_url']);
$photoSrc = $hasPhoto ? BASE_URL . '/' . e($item['image_url']) : null;

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    
    <!-- Breadcrumb Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 mb-6 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-1.5 flex-wrap">
            <a href="<?= BASE_URL ?>/barang/index.php" class="text-slate-800 font-semibold hover:text-emerald-700">← Katalog</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/barang/index.php?kategori=<?= (int)$item['category_id'] ?>" class="hover:text-emerald-700">
                <?= e($item['category_name']) ?>
            </a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate max-w-xs"><?= e($item['title']) ?></span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Bookmark Favorit -->
            <?php if ($isLoggedIn && !$isSeller): ?>
                <form action="<?= BASE_URL ?>/barang/favorit.php" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="item_id" value="<?= (int)$item['id'] ?>">
                    <input type="hidden" name="redirect" value="<?= e($_SERVER['REQUEST_URI']) ?>">
                    <button type="submit" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors <?= $isFavorited ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50' ?>">
                        <span><?= $isFavorited ? '❤️' : '🤍' ?></span>
                        <span><?= $isFavorited ? 'Tersimpan' : 'Favorit' ?></span>
                    </button>
                </form>
            <?php endif; ?>

            <!-- Tombol Laporkan -->
            <?php if (!$isSeller): ?>
                <button type="button" onclick="document.getElementById('reportModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-200 text-xs font-semibold bg-white transition-colors">
                    <span>🚩</span> Laporkan
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main 2-Column Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Kolom Kiri: Galeri Foto & Deskripsi Lengkap (7 Kolom) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Foto Utama Produk -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs relative aspect-4/3 flex items-center justify-center">
                <?php if ($photoSrc): ?>
                    <img src="<?= $photoSrc ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="text-center p-8 text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-2 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs font-medium text-slate-500">Foto barang belum diunggah</p>
                    </div>
                <?php endif; ?>

                <!-- Badges Status Floating -->
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    <?php if ($item['status'] === 'tersedia'): ?>
                        <span class="bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            Tersedia
                        </span>
                    <?php elseif ($item['status'] === 'booking'): ?>
                        <span class="bg-amber-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            Sedang Dibooking
                        </span>
                    <?php else: ?>
                        <span class="bg-slate-700 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            <?= e($item['status']) ?>
                        </span>
                    <?php endif; ?>

                    <span class="bg-slate-900/80 text-white backdrop-blur-xs text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                        <?= $condition ?>
                    </span>
                </div>
            </div>

            <!-- Detail & Lokasi COD -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-2">Deskripsi Barang</h2>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        <?= e($item['description']) ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Titik Temu COD / Lokasi Penjual</h3>
                    <div class="flex items-center gap-2 text-sm text-slate-800 font-semibold">
                        <span class="text-emerald-700 text-base">📍</span>
                        <span><?= e($item['location_info'] ?: 'Lingkungan Kampus Polinema') ?></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Disarankan bertransaksi di titik ramai kampus (Kantin Graha, Gedung Sipil, atau Perpustakaan).</p>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Card Aksi & Seller Profile (5 Kolom) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Card Harga & Booking Action -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Harga Penawaran</div>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight mb-4">
                    <?= format_rupiah($item['price']) ?>
                </div>

                <?php if ($isSeller): ?>
                    <!-- Kasus: Pemilik Barang Sendiri -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-600 mb-3">
                        <span class="font-bold text-slate-800 block mb-1">📦 Ini adalah barang jualan Anda</span>
                        Status saat ini: <strong class="text-emerald-700 uppercase"><?= e($item['status']) ?></strong>
                    </div>
                    <a href="<?= BASE_URL ?>/barang/kelola.php" 
                       class="block w-full text-center py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition-all">
                        Kelola di Daftar Barang Saya
                    </a>

                <?php elseif ($activeBooking): ?>
                    <!-- Kasus: Pembeli sedang mem-booking barang ini (WhatsApp UNLOCKED) -->
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 mb-4">
                        <div class="font-bold text-sm flex items-center gap-1.5 mb-1">
                            <span>🎉</span> Anda Berhasil Mem-Booking Barang Ini!
                        </div>
                        <p class="leading-relaxed">
                            Barang diamankan selama 24 jam. Segera hubungi penjual via WhatsApp untuk koordinasi waktu dan titik temu COD.
                        </p>
                    </div>

                    <?php 
                    $waMsg = "Halo " . $item['seller_name'] . ", saya sudah mem-booking *" . $item['title'] . "* di PREVO seharga " . format_rupiah($item['price']) . ". Kapan dan di mana bisa janjian COD di kampus?";
                    $waUrl = wa_link($item['seller_whatsapp'], $waMsg);
                    ?>
                    <a href="<?= $waUrl ?>" target="_blank" rel="noopener noreferrer"
                       class="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                        <span>💬</span> Chat Penjual di WhatsApp
                    </a>

                    <a href="<?= BASE_URL ?>/transaksi/riwayat.php" 
                       class="mt-2 block w-full text-center py-2 text-xs font-semibold text-slate-600 hover:underline">
                        Lihat Status Transaksi Saya →
                    </a>

                <?php elseif ($item['status'] === 'booking'): ?>
                    <!-- Kasus: Sedang dibooking oleh mahasiswa lain -->
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 text-center">
                        <span class="font-bold block mb-1">⏳ Barang Sedang Dibooking</span>
                        Barang ini sedang dalam proses janji temu COD oleh mahasiswa lain. Jika transaksi dibatalkan atau lewat 24 jam, barang akan otomatis tersedia kembali.
                    </div>

                <?php elseif ($item['status'] === 'tersedia'): ?>
                    <!-- Kasus: Barang Tersedia Siap Booking 1-Klik -->
                    <button type="button" 
                            onclick="openBookingModal(<?= (int)$item['id'] ?>, '<?= e(addslashes($item['title'])) ?>', '<?= format_rupiah($item['price']) ?>')"
                            class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <span>🔒</span> Booking 1-Klik (Kunci Barang 24 Jam)
                    </button>

                    <div class="mt-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 space-y-1.5 leading-relaxed">
                        <div class="font-bold text-slate-800 flex items-center gap-1">
                            <span>🛡️</span> Kebijakan Privasi Kontak (Opsi B):
                        </div>
                        <p>
                            Nomor WhatsApp penjual dilindungi dan <strong>akan langsung terbuka otomatis</strong> begitu Anda menekan tombol booking di atas untuk memastikan transaksi aman tanpa spam.
                        </p>
                    </div>

                <?php else: ?>
                    <div class="p-4 bg-slate-100 rounded-xl text-center text-xs text-slate-600">
                        Barang ini tidak lagi tersedia (Status: <?= e($item['status']) ?>).
                    </div>
                <?php endif; ?>

            </div>

            <!-- Seller Card (Identitas & Rating) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Informasi Penjual</h3>
                
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-base shrink-0 shadow-xs">
                        <?= strtoupper(substr($item['seller_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/auth/profil.php?id=<?= (int)$item['seller_id'] ?>" class="font-bold text-slate-900 text-sm hover:text-emerald-700 hover:underline">
                            <?= e($item['seller_name']) ?>
                        </a>
                        <div class="text-xs text-slate-500">NIM: <?= e($item['seller_nim']) ?></div>
                        <div class="text-xs text-slate-500 font-medium"><?= e($item['seller_prodi'] ?: 'Teknik Informatika') ?></div>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-amber-600 text-sm">★ <?= number_format((float)$item['avg_rating'], 1) ?></span>
                        <span class="text-slate-500 ml-1">(<?= (int)$item['total_reviews'] ?> Ulasan COD)</span>
                    </div>
                    <span class="font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                        Mahasiswa Aktif
                    </span>
                </div>
            </div>

            <!-- Petunjuk Keamanan COD -->
            <div class="p-4 bg-slate-900 text-white rounded-2xl text-xs space-y-1.5 shadow-xs">
                <div class="font-bold text-emerald-400 flex items-center gap-1.5">
                    <span>💡</span> Tips Transaksi Aman PREVO:
                </div>
                <ul class="list-disc list-inside text-slate-300 space-y-1 text-[11px] leading-relaxed">
                    <li>Selalu bertemu di area publik kampus Polinema.</li>
                    <li>Periksa fisik dan kelayakan barang sebelum membayar.</li>
                    <li>Selesaikan transaksi di web setelah menerima barang agar reputasi penjual tercatat.</li>
                </ul>
            </div>

        </div>

    </div>
</div>

<!-- Sertakan Modal Booking 1-Klik -->
<?php require_once __DIR__ . '/../views/partials/modal-booking.php'; ?>

<!-- Modal Laporkan Barang -->
<div id="reportModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                <span>🚩</span> Laporkan Listing Barang Ini
            </h3>
            <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
        </div>

        <form action="<?= BASE_URL ?>/barang/detail.php?id=<?= (int)$item['id'] ?>" method="POST" class="mt-4 space-y-3 text-xs">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="report">

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alasan Pelaporan</label>
                <select name="reason" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600">
                    <option value="">Pilih Alasan Pelaporan</option>
                    <option value="Barang Palsu / Menyesatkan">Barang Palsu / Menyesatkan</option>
                    <option value="Foto Tidak Sesuai Kondisi">Foto Tidak Sesuai Kondisi</option>
                    <option value="Harga Tidak Wajar">Harga Tidak Wajar</option>
                    <option value="Barang Terlarang / Melanggar Kebijakan Kampus">Barang Terlarang / Melanggar Kebijakan Kampus</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Keterangan Tambahan</label>
                <textarea name="description" rows="3" placeholder="Jelaskan temuan Anda agar dapat ditinjau admin..."
                          class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-emerald-600"></textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')"
                        class="flex-1 py-2 px-3 rounded-lg border border-slate-300 text-slate-700 font-semibold">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 py-2 px-3 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
