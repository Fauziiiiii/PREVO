<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../csrf.php';

// Definisi BASE_URL dinamis agar link aset dan halaman selalu valid
if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/../..'));
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        $basePath = substr($projectRoot, strlen($docRoot));
    } else {
        $basePath = '';
    }
    define('BASE_URL', rtrim($basePath, '/') . '/');
}

// Jika pengguna sudah login, redirect langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'home/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= isset($page_title) ? e($page_title) . ' - PREVO' : 'PREVO - Autentikasi Mahasiswa Polinema'; ?></title>

    <!-- Google Fonts: Plus Jakarta Sans, Inter, & Caveat -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS with forms & container-queries -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef4ff',
                            100: '#d9e6ff',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1a56db',
                            800: '#1e40af',
                            900: '#172554',
                        },
                        "tertiary-fixed-dim": "#4edea3",
                        "tertiary": "#006141",
                        "surface-tint": "#0051df",
                        "primary-fixed-dim": "#b5c4ff",
                        "on-secondary": "#ffffff",
                        "surface-bright": "#f8f9ff",
                        "secondary-fixed-dim": "#7bd0ff",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-secondary-fixed-variant": "#004c69",
                        "inverse-primary": "#b5c4ff",
                        "surface": "#f8f9ff",
                        "error": "#ba1a1a",
                        "primary": "#0048c9",
                        "inverse-on-surface": "#eaf1ff",
                        "on-primary-container": "#edeeff",
                        "on-surface-variant": "#434655",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-surface": "#0b1c30",
                        "secondary-container": "#40c2fd",
                        "surface-dim": "#cbdbf5",
                        "background": "#f8f9ff",
                        "on-primary-fixed-variant": "#003cab",
                        "outline-variant": "#c3c5d8",
                        "on-primary": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "on-error-container": "#93000a",
                        "on-secondary-fixed": "#001e2c",
                        "secondary": "#00668a",
                        "tertiary-container": "#007c55",
                        "on-tertiary-container": "#b5ffd7",
                        "primary-container": "#1e60f3",
                        "on-background": "#0b1c30",
                        "on-tertiary-fixed": "#002113",
                        "primary-fixed": "#dbe1ff",
                        "inverse-surface": "#213145",
                        "outline": "#737687",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-container": "#004d6a",
                        "surface-container-high": "#dce9ff",
                        "on-primary-fixed": "#00174d",
                        "surface-container-highest": "#d3e4fe",
                        "secondary-fixed": "#c4e7ff",
                        "surface-variant": "#d3e4fe",
                        "tertiary-fixed": "#6ffbbe",
                        "surface-container": "#e5eeff",
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px",
                    },
                    spacing: {
                        gutter: "1rem",
                        "space-xs": "0.25rem",
                        "space-lg": "1.5rem",
                        "space-xl": "2.5rem",
                        "space-sm": "0.5rem",
                        "gutter-desktop": "1.5rem",
                        "margin-desktop": "2.5rem",
                        "space-md": "1rem",
                        margin: "1rem",
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        "jakarta-sans": ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        jakarta: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        "headline-xl": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "display-hero": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "body-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "label-lg": ["Inter", "Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Inter", "Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Inter", "Plus Jakarta Sans", "sans-serif"],
                        "handwriting": ["Caveat", "cursive"],
                    },
                    fontSize: {
                        "headline-xl": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "headline-md": ["18px", { lineHeight: "26px", fontWeight: "700" }],
                        "headline-sm": ["16px", { lineHeight: "24px", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "500" }],
                        "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }],
                        "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }],
                        "label-lg": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "label-md": ["12px", { lineHeight: "16px", fontWeight: "600" }],
                        "label-sm": ["10px", { lineHeight: "14px", letterSpacing: "0.02em", fontWeight: "700" }],
                        "display-hero": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "700" }],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        .bg-campus-overlay {
            background: linear-gradient(180deg, rgba(29, 78, 216, 0.88) 0%, rgba(30, 58, 138, 0.94) 100%);
        }

        .custom-curved-bg {
            background: radial-gradient(135.2% 102.8% at 9.8% 8.2%, rgba(229, 238, 255, 0.9) 0%, rgba(248, 249, 255, 0.3) 100%);
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f0f3ff;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c3c5d8;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="<?= $body_class ?? 'bg-background text-on-surface antialiased min-h-screen flex flex-col justify-between selection:bg-primary selection:text-on-primary'; ?>">
