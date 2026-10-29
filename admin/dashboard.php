<?php
// ============================================================
//  PREVO - Dashboard Eksekutif Sistem Informasi Manajemen (SIM)
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AdminService.php';

$user = auth_guard('admin');
$adminService = new AdminService();

$summary = $adminService->getDashboardSummary();

$pageTitle = 'Dashboard Eksekutif SIM - PREVO Polinema';
include __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Admin Dashboard -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    Sistem Informasi Manajemen (SIM)
                </span>
                <!-- <span class="text-xs text-slate-400">Semester 3 PBL-TI Polinema</span> -->
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Eksekutif & Monitoring Operasional</h1>
            <p class="text-sm text-slate-500 mt-1">Laporan analitik sirkulasi barang bekas, kesehatan transaksi COD kampus, dan reputasi mahasiswa.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= BASE_URL ?>/admin/moderasi.php" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-950 text-white text-xs font-bold hover:bg-blue-900 transition-colors shadow-xs">
                <span>🛡️</span> Moderasi Barang (<?= $summary['pending_items'] ?>)
            </a>
            <a href="<?= BASE_URL ?>/admin/laporan.php" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors shadow-xs">
                <span>⚠️</span> Laporan (<?= $summary['pending_reports'] ?>)
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <!-- Metric 1: Total Mahasiswa -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">Mahasiswa</span>
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($summary['total_students']) ?></div>
            <div class="text-[11px] text-slate-400 mt-1">Civitas Polinema</div>
        </div>

        <!-- Metric 2: Listing Aktif -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">Katalog Aktif</span>
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($summary['active_items']) ?></div>
            <div class="text-[11px] text-emerald-600 font-medium mt-1">Tersedia Dijual</div>
        </div>

        <!-- Metric 3: Pending Moderasi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs <?= $summary['pending_items'] > 0 ? 'bg-amber-50/50 border-amber-200' : '' ?>">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">Antrean Moderasi</span>
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="text-2xl font-black <?= $summary['pending_items'] > 0 ? 'text-amber-700' : 'text-slate-900' ?>">
                <?= number_format($summary['pending_items']) ?>
            </div>
            <div class="text-[11px] <?= $summary['pending_items'] > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' ?> mt-1">
                <?= $summary['pending_items'] > 0 ? 'Butuh review segera' : 'Semua beres' ?>
            </div>
        </div>

        <!-- Metric 4: Transaksi COD Sukses -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">COD Sukses</span>
                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-black text-slate-900"><?= number_format($summary['completed_tx']) ?></div>
            <div class="text-[11px] text-blue-600 font-medium mt-1">Transaksi Berhasil</div>
        </div>

        <!-- Metric 5: Total Volume Transaksi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs col-span-2 sm:col-span-1 lg:col-span-1">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">Volume COD</span>
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-lg font-black text-emerald-700 truncate" title="<?= format_rupiah($summary['total_volume']) ?>">
                <?= format_rupiah($summary['total_volume']) ?>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Nilai Sirkulasi</div>
        </div>

        <!-- Metric 6: Laporan Pelanggaran -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs <?= $summary['pending_reports'] > 0 ? 'bg-rose-50/50 border-rose-200' : '' ?>">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold text-slate-500">Laporan</span>
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="text-2xl font-black <?= $summary['pending_reports'] > 0 ? 'text-rose-700' : 'text-slate-900' ?>">
                <?= number_format($summary['pending_reports']) ?>
            </div>
            <div class="text-[11px] <?= $summary['pending_reports'] > 0 ? 'text-rose-600 font-semibold' : 'text-slate-400' ?> mt-1">
                <?= $summary['pending_reports'] > 0 ? 'Perlu tindakan' : 'Aman kondusif' ?>
            </div>
        </div>
    </div>

    <!-- Section: Laporan SIM 1 - Ringkasan Aktivitas Bulanan (View: monthly_summary) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-900">Ringkasan Aktivitas Bulanan (SIM Aggregation)</h2>
                <p class="text-xs text-slate-500">Agregasi SQL View <code>monthly_summary</code> untuk evaluasi pertumbuhan transaksi antar bulan.</p>
            </div>
            <span class="text-xs font-mono text-slate-400 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                <!-- PostgreSQL View: monthly_summary -->
                monthly_summary
            </span>
        </div>

        <?php if (empty($summary['monthly_summary'])): ?>
            <div class="p-8 text-center text-sm text-slate-400">
                Belum ada data transaksi bulanan yang terekam.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Periode Bulan</th>
                            <th class="px-6 py-3 text-center">Total Transaksi</th>
                            <th class="px-6 py-3 text-center">COD Sukses</th>
                            <th class="px-6 py-3 text-center">Batal / Expired</th>
                            <th class="px-6 py-3 text-right">Rata-rata Nilai</th>
                            <th class="px-6 py-3 text-center">Pembeli Unik</th>
                            <th class="px-6 py-3 text-center">Penjual Unik</th>
                            <th class="px-6 py-3 text-center">Tingkat Keberhasilan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($summary['monthly_summary'] as $m): ?>
                            <?php 
                                $successRate = (int)$m['total_transactions'] > 0 ? round(((int)$m['completed'] / (int)$m['total_transactions']) * 100, 1) : 0;
                            ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <?= date('F Y', strtotime($m['month'])) ?>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold">
                                    <?= number_format($m['total_transactions']) ?>
                                </td>
                                <td class="px-6 py-4 text-center text-emerald-600 font-bold">
                                    <?= number_format($m['completed']) ?>
                                </td>
                                <td class="px-6 py-4 text-center text-rose-500 font-medium">
                                    <?= number_format($m['cancelled']) ?>
                                </td>
                                <td class="px-6 py-4 text-right font-medium">
                                    <?= format_rupiah((float)($m['avg_price'] ?? 0)) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-800 font-medium"><?= $m['unique_buyers'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 font-medium"><?= $m['unique_sellers'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $successRate >= 70 ? 'bg-emerald-100 text-emerald-800' : ($successRate >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') ?>">
                                        <?= $successRate ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- 2 Kolom: Kategori & Top Seller -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Section: Kinerja per Kategori (View: category_stats) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Statistik per Kategori Barang</h2>
                    <p class="text-xs text-slate-500">View <code>category_stats</code></p>
                </div>
                <a href="<?= BASE_URL ?>/admin/kategori.php" class="text-xs font-semibold text-blue-900 hover:underline">
                    Kelola Kategori &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3 text-center">Total Listing</th>
                            <th class="px-5 py-3 text-center">COD Sukses</th>
                            <th class="px-5 py-3 text-right">Rata-rata Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($summary['category_stats'] as $cat): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900">
                                    <?= e($cat['category_name']) ?>
                                </td>
                                <td class="px-5 py-3.5 text-center font-medium">
                                    <?= number_format($cat['total_listings']) ?>
                                </td>
                                <td class="px-5 py-3.5 text-center text-emerald-600 font-bold">
                                    <?= number_format($cat['completed_transactions']) ?>
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium text-slate-600">
                                    <?= $cat['avg_transaction_price'] ? format_rupiah((float)$cat['avg_transaction_price']) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Reputasi Penjual Terpercaya (View: seller_ratings) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Penjual Terpercaya (Rating Teratas)</h2>
                    <p class="text-xs text-slate-500">View <code>seller_ratings</code></p>
                </div>
                <span class="text-xs font-medium text-slate-400">Top 5 Mahasiswa</span>
            </div>

            <?php if (empty($summary['top_sellers'])): ?>
                <div class="p-8 text-center text-sm text-slate-400">
                    Belum ada ulasan bintang untuk penjual.
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php foreach ($summary['top_sellers'] as $seller): ?>
                        <div class="px-6 py-3.5 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-xs">
                                    <?= strtoupper(substr($seller['seller_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm"><?= e($seller['seller_name']) ?></h4>
                                    <p class="text-[11px] text-slate-500">NIM: <?= e($seller['seller_nim']) ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center justify-end gap-1 text-amber-500 font-bold text-xs">
                                    <span>★</span>
                                    <span><?= number_format((float)$seller['avg_rating'], 2) ?></span>
                                    <span class="text-slate-400 font-normal">/ 5.0</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <?= $seller['total_reviews'] ?> Ulasan (<?= $seller['five_star'] ?> bintang 5)
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
