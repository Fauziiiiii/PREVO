<?php
// ============================================================
//  PREVO - Riwayat Transaksi COD Mahasiswa (Beli & Jual)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/TransactionService.php';
require_once __DIR__ . '/../services/ItemService.php';

$user = auth_guard('mahasiswa', true);
$txService = new TransactionService();
$itemService = new ItemService();
$codLocations = $itemService->getActiveLocations();

$tab = in_array($_GET['tab'] ?? '', ['buying', 'selling']) ? $_GET['tab'] : 'buying';

$transactions = $txService->getUserTransactions((int)$user['id']);
$buyingList = $transactions['buying'];
$sellingList = $transactions['selling'];

$pageTitle = 'Riwayat Transaksi COD - PREVO';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Transaksi COD</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau status booking 24-jam, komunikasi WhatsApp penjual/pembeli, dan konfirmasi transaksi COD.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/barang/index.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors shadow-xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Jelajahi Katalog
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b border-slate-200 mb-6">
        <a href="<?= BASE_URL ?>/transaksi/riwayat.php?tab=buying" 
           class="py-3 px-5 text-sm font-semibold border-b-2 flex items-center gap-2 transition-colors <?= $tab === 'buying' ? 'border-blue-950 text-blue-950' : 'border-transparent text-slate-500 hover:text-slate-700' ?>">
            <span>Pembelian Saya (Booking)</span>
            <span class="px-2 py-0.5 text-xs rounded-full <?= $tab === 'buying' ? 'bg-blue-100 text-blue-950' : 'bg-slate-100 text-slate-600' ?>">
                <?= count($buyingList) ?>
            </span>
        </a>
        <a href="<?= BASE_URL ?>/transaksi/riwayat.php?tab=selling" 
           class="py-3 px-5 text-sm font-semibold border-b-2 flex items-center gap-2 transition-colors <?= $tab === 'selling' ? 'border-blue-950 text-blue-950' : 'border-transparent text-slate-500 hover:text-slate-700' ?>">
            <span>Penjualan Saya (Order Masuk)</span>
            <span class="px-2 py-0.5 text-xs rounded-full <?= $tab === 'selling' ? 'bg-blue-100 text-blue-950' : 'bg-slate-100 text-slate-600' ?>">
                <?= count($sellingList) ?>
            </span>
        </a>
    </div>

    <!-- TAB 1: PEMBELIAN SAYA -->
    <?php if ($tab === 'buying'): ?>
        <?php if (empty($buyingList)): ?>
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Transaksi Pembelian</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">Temukan perlengkapan kuliah bekas berkualitas dari sesama mahasiswa Polinema.</p>
                <a href="<?= BASE_URL ?>/barang/index.php" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-950 text-white text-sm font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($buyingList as $tx): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all hover:border-slate-300">
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-5">
                            <!-- Item Image -->
                            <div class="w-full md:w-36 h-36 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                <?php 
                                    $imgFile = $tx['item_image'] ?: 'default.jpg';
                                    $hasImg = file_exists(ROOT_PATH . '/uploads/items/' . $imgFile);
                                    $imgUrl = $hasImg ? asset_url('uploads/items/' . $imgFile) : asset_url('uploads/items/default.jpg');
                                ?>
                                <img src="<?= $imgUrl ?>" 
                                     alt="<?= e($tx['item_title']) ?>" 
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Content Info -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                                <?= e($tx['category_name']) ?>
                                            </span>
                                            <span class="text-xs text-slate-400">
                                                ID: #TX-<?= $tx['id'] ?> &bull; <?= format_tanggal($tx['created_at']) ?>
                                            </span>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            <?php if ($tx['status'] === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Booking Aktif (COD Pending)
                                                </span>
                                            <?php elseif ($tx['status'] === 'selesai'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    COD Berhasil
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                    Dibatalkan
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-900 hover:text-blue-900 transition-colors">
                                        <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= $tx['item_id'] ?>">
                                            <?= e($tx['item_title']) ?>
                                        </a>
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-y-1 gap-x-6 text-xs text-slate-600">
                                        <div>
                                            <span class="text-slate-400">Penjual:</span> 
                                            <span class="font-semibold text-slate-800"><?= e($tx['seller_name']) ?></span> (NIM <?= e($tx['seller_nim']) ?>)
                                        </div>
                                        <div>
                                            <span class="text-slate-400">Harga Kesepakatan:</span> 
                                            <span class="font-bold text-emerald-600 text-sm">
                                                <?= format_rupiah((float)($tx['agreed_price'] ?? $tx['original_price'] ?? 0)) ?>
                                            </span>
                                            <?php if (!empty($tx['agreed_price']) && (float)$tx['agreed_price'] != (float)$tx['original_price']): ?>
                                                <span class="line-through text-slate-400 ml-1"><?= format_rupiah((float)$tx['original_price']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($tx['meetup_location']) || !empty($tx['meetup_time'])): ?>
                                        <div class="mt-2 p-2.5 rounded-lg bg-blue-50/60 border border-blue-100 text-xs text-blue-900 flex flex-wrap gap-4">
                                            <?php if (!empty($tx['meetup_location'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    <span>Lokasi COD: <strong><?= e($tx['meetup_location']) ?></strong></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($tx['meetup_time'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>Waktu COD: <strong><?= format_tanggal($tx['meetup_time']) ?></strong></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($tx['status'] === 'pending' && !empty($tx['expires_at'])): ?>
                                        <p class="text-xs text-amber-700 mt-2 font-medium">
                                            ⏳ Batas Waktu Booking: <?= format_tanggal($tx['expires_at']) ?> (Otomatis batal jika tidak diselesaikan)
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($tx['status'] === 'pending'): ?>
                                            <?php 
                                                $waMsg = "Halo {$tx['seller_name']}, saya {$user['name']} (NIM {$user['nim']}) yang baru saja membooking barang '{$tx['item_title']}' di PREVO seharga " . format_rupiah((float)($tx['agreed_price'] ?? $tx['original_price'])) . ". Boleh janjian COD di area kampus Polinema?";
                                            ?>
                                            <a href="<?= wa_link($tx['seller_whatsapp'], $waMsg) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                                Chat WhatsApp Penjual
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($tx['status'] === 'pending'): ?>
                                            <!-- Konfirmasi COD Selesai -->
                                            <form action="<?= BASE_URL ?>/transaksi/selesai.php" method="POST" onsubmit="return confirm('Apakah Anda sudah bertemu COD, memeriksa barang, dan menyerahkan pembayaran kepada penjual?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                                                <input type="hidden" name="tab" value="buying">
                                                <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                                                    Konfirmasi COD Selesai
                                                </button>
                                            </form>

                                            <!-- Batalkan Booking -->
                                            <form action="<?= BASE_URL ?>/transaksi/batal.php" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini? Barang akan kembali tersedia untuk mahasiswa lain.')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                                                <input type="hidden" name="tab" value="buying">
                                                <button type="submit" class="px-3 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition-colors">
                                                    Batalkan
                                                </button>
                                            </form>
                                        <?php elseif ($tx['status'] === 'selesai'): ?>
                                            <?php if (empty($tx['review_id'])): ?>
                                                <a href="<?= BASE_URL ?>/transaksi/beri_ulasan.php?tx_id=<?= $tx['id'] ?>" 
                                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-400 text-slate-900 text-xs font-bold hover:bg-amber-500 transition-colors shadow-xs">
                                                    <span>★</span> Beri Ulasan & Rating
                                                </a>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                                                    <span>★</span> Diulas: <?= $tx['review_rating'] ?> / 5 Bintang
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- TAB 2: PENJUALAN SAYA -->
    <?php else: ?>
        <?php if (empty($sellingList)): ?>
            <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Order Booking Masuk</h3>
                <p class="text-sm text-slate-500 mt-1 mb-6">Barang jualan Anda yang dibooking oleh calon pembeli akan muncul di sini.</p>
                <a href="<?= BASE_URL ?>/barang/kelola.php" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-950 text-white text-sm font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                    Kelola Barang Saya
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($sellingList as $tx): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all hover:border-slate-300">
                        <div class="p-5 sm:p-6 flex flex-col md:flex-row gap-5">
                            <!-- Item Image -->
                            <div class="w-full md:w-36 h-36 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                <?php 
                                    $imgFile = $tx['item_image'] ?: 'default.jpg';
                                    $hasImg = file_exists(ROOT_PATH . '/uploads/items/' . $imgFile);
                                    $imgUrl = $hasImg ? asset_url('uploads/items/' . $imgFile) : asset_url('uploads/items/default.jpg');
                                ?>
                                <img src="<?= $imgUrl ?>" 
                                     alt="<?= e($tx['item_title']) ?>" 
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Content Info -->
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                                <?= e($tx['category_name']) ?>
                                            </span>
                                            <span class="text-xs text-slate-400">
                                                ID: #TX-<?= $tx['id'] ?> &bull; <?= format_tanggal($tx['created_at']) ?>
                                            </span>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            <?php if ($tx['status'] === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Booking Aktif (Menunggu COD)
                                                </span>
                                            <?php elseif ($tx['status'] === 'selesai'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Terjual & Selesai
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                    Dibatalkan
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-900">
                                        <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= $tx['item_id'] ?>" class="hover:text-blue-900 transition-colors">
                                            <?= e($tx['item_title']) ?>
                                        </a>
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-y-1 gap-x-6 text-xs text-slate-600">
                                        <div>
                                            <span class="text-slate-400">Calon Pembeli:</span> 
                                            <span class="font-semibold text-slate-800"><?= e($tx['buyer_name']) ?></span> (NIM <?= e($tx['buyer_nim']) ?>)
                                        </div>
                                        <div>
                                            <span class="text-slate-400">Harga Kesepakatan:</span> 
                                            <span class="font-bold text-emerald-600 text-sm">
                                                <?= format_rupiah((float)($tx['agreed_price'] ?? $tx['original_price'] ?? 0)) ?>
                                            </span>
                                            <?php if (!empty($tx['agreed_price']) && (float)$tx['agreed_price'] != (float)$tx['original_price']): ?>
                                                <span class="line-through text-slate-400 ml-1"><?= format_rupiah((float)$tx['original_price']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($tx['meetup_location']) || !empty($tx['meetup_time'])): ?>
                                        <div class="mt-2 p-2.5 rounded-lg bg-blue-50/60 border border-blue-100 text-xs text-blue-900 flex flex-wrap gap-4">
                                            <?php if (!empty($tx['meetup_location'])): ?>
                                                <div>Lokasi COD: <strong><?= e($tx['meetup_location']) ?></strong></div>
                                            <?php endif; ?>
                                            <?php if (!empty($tx['meetup_time'])): ?>
                                                <div>Waktu COD: <strong><?= format_tanggal($tx['meetup_time']) ?></strong></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($tx['status'] === 'pending' && !empty($tx['expires_at'])): ?>
                                        <p class="text-xs text-amber-700 mt-2 font-medium">
                                            ⏳ Batas Waktu Booking: <?= format_tanggal($tx['expires_at']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($tx['status'] === 'selesai' && !empty($tx['review_id'])): ?>
                                        <div class="mt-2 text-xs text-amber-600 font-semibold flex items-center gap-1">
                                            <span>★</span> Pembeli memberi rating: <?= $tx['review_rating'] ?> / 5 Bintang
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons Penjual -->
                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($tx['status'] === 'pending'): ?>
                                            <?php 
                                                $waMsg = "Halo {$tx['buyer_name']}, saya {$user['name']} penjual '{$tx['item_title']}' di PREVO. Terima kasih sudah membooking. Kapan dan di mana kita bisa ketemuan COD di kampus?";
                                            ?>
                                            <a href="<?= wa_link($tx['buyer_whatsapp'], $waMsg) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-xs">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                                </svg>
                                                Chat WhatsApp Pembeli
                                            </a>

                                            <!-- Tombol Update Kesepakatan (Toggle Modal) -->
                                            <button type="button" onclick="document.getElementById('modal-deal-<?= $tx['id'] ?>').classList.remove('hidden')" 
                                                    class="px-3.5 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
                                                Atur Nego / Lokasi COD
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($tx['status'] === 'pending'): ?>
                                            <!-- Konfirmasi COD Selesai Penjual -->
                                            <form action="<?= BASE_URL ?>/transaksi/selesai.php" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang telah diserahkan dan uang pembayaran telah Anda terima?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                                                <input type="hidden" name="tab" value="selling">
                                                <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900 transition-colors shadow-xs">
                                                    Konfirmasi COD Selesai
                                                </button>
                                            </form>

                                            <!-- Batalkan Transaksi Penjual -->
                                            <form action="<?= BASE_URL ?>/transaksi/batal.php" method="POST" onsubmit="return confirm('Batalkan transaksi ini? Barang Anda akan kembali berstatus tersedia untuk mahasiswa lain.')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">
                                                <input type="hidden" name="tab" value="selling">
                                                <button type="submit" class="px-3 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition-colors">
                                                    Batalkan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL UPDATE DEAL PENJUAL -->
                        <?php if ($tx['status'] === 'pending'): ?>
                            <div id="modal-deal-<?= $tx['id'] ?>" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                                        <h4 class="font-bold text-sm">Atur Kesepakatan Nego & Lokasi COD</h4>
                                        <button type="button" onclick="document.getElementById('modal-deal-<?= $tx['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
                                    </div>
                                    <form action="<?= BASE_URL ?>/transaksi/update_deal.php" method="POST" class="p-6 space-y-4">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="tx_id" value="<?= $tx['id'] ?>">

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Kesepakatan Final (Rp) <span class="text-red-500">*</span></label>
                                            <input type="number" name="agreed_price" value="<?= e($tx['agreed_price'] ?? $tx['original_price']) ?>" min="0" step="1000" required
                                                   class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                            <p class="text-[11px] text-slate-400 mt-1">Ubah jika pembeli menawar via WhatsApp.</p>
                                        </div>

                                        <?php $parsedDealLoc = parse_lokasi_cod($tx['meetup_location'] ?? ''); ?>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Titik Lokasi COD (Polinema) <span class="text-red-500">*</span></label>
                                            <select name="cod_location" required
                                                    class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-900">
                                                <option value="">Pilih Titik COD Kampus</option>
                                                <?php 
                                                $locMatched = false;
                                                foreach ($codLocations as $loc): 
                                                    $isSelected = ($parsedDealLoc['location'] === $loc['name']);
                                                    if ($isSelected) $locMatched = true;
                                                ?>
                                                    <option value="<?= e($loc['name']) ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                        📍 <?= e($loc['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                <?php if (!empty($parsedDealLoc['location']) && !$locMatched): ?>
                                                    <option value="<?= e($parsedDealLoc['location']) ?>" selected>
                                                        📍 <?= e($parsedDealLoc['location']) ?> (Sebelumnya)
                                                    </option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Patokan COD <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                                            <input type="text" name="cod_note" value="<?= e($parsedDealLoc['note']) ?>" placeholder="Misal: Gazebo barat, meja kantin dekat kasir..."
                                                   class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Rencana Waktu Janjian</label>
                                            <input type="datetime-local" name="meetup_time" value="<?= !empty($tx['meetup_time']) ? date('Y-m-d\TH:i', strtotime($tx['meetup_time'])) : '' ?>"
                                                   class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-900">
                                        </div>

                                        <div class="flex items-center justify-end gap-2 pt-2">
                                            <button type="button" onclick="document.getElementById('modal-deal-<?= $tx['id'] ?>').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-950 text-white text-xs font-semibold hover:bg-blue-900">
                                                Simpan Kesepakatan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
