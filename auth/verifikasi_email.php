<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

$pageTitle = 'Verifikasi Email Kampus';
$token = $_GET['token'] ?? '';
$authService = new AuthService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $tokenToVerify = $_POST['token'] ?? '';
    $res = $authService->verifyEmailToken($tokenToVerify);

    if ($res['success']) {
        set_flash('success', 'Email kampus berhasil diverifikasi! Akun Anda kini aktif, silakan login.');
        redirect('/auth/login.php');
    } else {
        set_flash('error', $res['error']);
        redirect('/auth/verifikasi_email.php?token=' . urlencode($tokenToVerify));
    }
}

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 text-center">
        
        <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto text-2xl mb-4">
            ✉️
        </div>

        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Email Kampus</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
            Untuk memastikan keamanan ekosistem civitas akademika Polinema, tautan aktivasi telah dikirimkan ke email kampus Anda.
        </p>

        <!-- Banner Simulasi Demo Lingkungan Lokal -->
        <div class="my-6 p-4 bg-slate-50 border border-slate-200 rounded-xl text-left text-xs text-slate-600">
            <div class="font-bold text-slate-800 mb-1 flex items-center gap-1.5">
                <span>🛠️</span> Mode Pengujian Lokal / Presentasi:
            </div>
            <p class="text-[11px] text-slate-500 mb-3">
                Token aktivasi terdeteksi di lingkungan lokal untuk memudahkan proses demonstrasi 4 mata kuliah:
            </p>
            <code class="block bg-slate-200 p-2 rounded text-[10px] break-all font-mono text-slate-800">
                <?= e($token ?: 'Token tidak ditemukan') ?>
            </code>
        </div>

        <?php if (!empty($token)): ?>
            <form action="<?= BASE_URL ?>/auth/verifikasi_email.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <button type="submit" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                    Aktivasi Akun Sekarang (Simulasi)
                </button>
            </form>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/auth/login.php" class="inline-block py-2.5 px-5 bg-slate-900 text-white text-xs font-bold rounded-xl">
                Kembali ke Login
            </a>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
