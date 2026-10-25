<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/security.php';

$title = isset($pageTitle) ? e($pageTitle) . ' · ' . APP_NAME : APP_NAME . ' · ' . APP_TAGLINE;
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --color-polinema: #0f172a;
            --color-emerald-action: #059669;
        }
    </style>
</head>
<body class="flex flex-col min-h-full font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <!-- Flash Messages Container -->
    <?php
    $flashes = get_flashes();
    if (!empty($flashes)):
    ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php foreach ($flashes as $type => $messages): ?>
            <?php foreach ($messages as $msg): 
                $bg = 'bg-blue-50 border-blue-200 text-blue-800';
                $icon = 'ℹ️';
                if ($type === 'success') {
                    $bg = 'bg-emerald-50 border-emerald-200 text-emerald-800';
                    $icon = '✅';
                } elseif ($type === 'error') {
                    $bg = 'bg-rose-50 border-rose-200 text-rose-800';
                    $icon = '⚠️';
                } elseif ($type === 'warning') {
                    $bg = 'bg-amber-50 border-amber-200 text-amber-800';
                    $icon = '🔔';
                }
            ?>
            <div class="flex items-center justify-between p-3.5 mb-2.5 rounded-lg border text-sm font-medium shadow-xs <?= $bg ?>" role="alert">
                <div class="flex items-center gap-2">
                    <span><?= $icon ?></span>
                    <span><?= e($msg) ?></span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 font-bold ml-4 text-base leading-none">&times;</button>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main class="flex-1">
