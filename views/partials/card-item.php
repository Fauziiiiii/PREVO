<?php
/**
 * Komponen Card Item Standar PREVO
 * Variabel yang dibutuhkan: $item (array)
 */
$statusLabels = [
    'tersedia'             => ['label' => 'Tersedia', 'class' => 'bg-emerald-600 text-white'],
    'booking'              => ['label' => 'Sedang Booking', 'class' => 'bg-amber-600 text-white'],
    'terjual'              => ['label' => 'Terjual', 'class' => 'bg-slate-600 text-white'],
    'menunggu_persetujuan' => ['label' => 'Menunggu Review', 'class' => 'bg-indigo-600 text-white'],
    'ditolak'              => ['label' => 'Ditolak', 'class' => 'bg-rose-600 text-white'],
    'dihapus'              => ['label' => 'Dihapus', 'class' => 'bg-slate-400 text-white']
];

$conditionLabels = [
    'baru'        => 'Baru',
    'bekas_baik'  => 'Bekas Baik',
    'bekas_rusak' => 'Bekas / Minus'
];

$itemStatus = $statusLabels[$item['status']] ?? ['label' => $item['status'], 'class' => 'bg-slate-600 text-white'];
$condition = $conditionLabels[$item['condition']] ?? $item['condition'];

// Penanganan gambar default jika belum ada
$hasPhoto = !empty($item['image_url']) && file_exists(ROOT_PATH . '/' . $item['image_url']);
$photoSrc = $hasPhoto ? BASE_URL . '/' . e($item['image_url']) : null;
?>
<div class="group bg-white rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
    
    <!-- Top Image Container -->
    <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= (int)$item['id'] ?>" class="block relative aspect-4/3 bg-slate-100 overflow-hidden">
        <?php if ($photoSrc): ?>
            <img src="<?= $photoSrc ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <?php else: ?>
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                <svg class="w-10 h-10 mb-1 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-medium text-slate-400"><?= e($item['category_name'] ?? 'Preloved') ?></span>
            </div>
        <?php endif; ?>

        <!-- Badges on Photo -->
        <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded shadow-xs <?= $itemStatus['class'] ?>">
                <?= $itemStatus['label'] ?>
            </span>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-900/75 text-white backdrop-blur-xs">
                <?= $condition ?>
            </span>
        </div>
    </a>

    <!-- Card Body -->
    <div class="p-3.5 flex-1 flex flex-col justify-between">
        <div>
            <!-- Category & Location -->
            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                <span class="font-medium text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded"><?= e($item['category_name'] ?? 'Umum') ?></span>
                <span class="truncate max-w-[130px]" title="<?= e($item['location_info'] ?? 'Area Kampus') ?>">
                    📍 <?= e($item['location_info'] ?? 'Area Kampus') ?>
                </span>
            </div>

            <!-- Title -->
            <a href="<?= BASE_URL ?>/barang/detail.php?id=<?= (int)$item['id'] ?>" class="block font-bold text-slate-900 text-sm leading-snug line-clamp-2 hover:text-emerald-700 transition-colors mt-1">
                <?= e($item['title']) ?>
            </a>
        </div>

        <!-- Price & Seller Footer -->
        <div class="mt-3 pt-2.5 border-t border-slate-100">
            <div class="text-base font-extrabold text-slate-900 tracking-tight">
                <?= format_rupiah($item['price']) ?>
            </div>

            <div class="flex items-center justify-between mt-1 text-[11px] text-slate-500">
                <span class="truncate max-w-[120px]">Oleh <strong><?= e(explode(' ', $item['seller_name'] ?? 'Mahasiswa')[0]) ?></strong></span>
                <?php if (!empty($item['avg_rating']) && (float)$item['avg_rating'] > 0): ?>
                    <span class="font-bold text-amber-600 flex items-center gap-0.5">
                        ★ <?= number_format((float)$item['avg_rating'], 1) ?>
                    </span>
                <?php else: ?>
                    <span class="text-slate-400">Baru</span>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
