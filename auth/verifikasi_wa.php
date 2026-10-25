<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

auth_guard('mahasiswa');

$user = current_user();
if (!empty($user['whatsapp_verified_at'])) {
    redirect('/barang/index.php');
}

$pageTitle = 'Verifikasi Nomor WhatsApp';
$authService = new AuthService();
$userData = $authService->getUserById($user['id']);
$currentOtp = $userData['whatsapp_otp'] ?? '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $otp = sanitize_input($_POST['otp'] ?? '');
    $res = $authService->verifyWhatsAppOtp($user['id'], $otp);

    if ($res['success']) {
        set_flash('success', 'Nomor WhatsApp Anda berhasil diverifikasi! Sekarang Anda dapat menjual dan mem-booking barang.');
        redirect('/barang/index.php');
    } else {
        $errors[] = $res['error'];
    }
}

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 text-center">
        
        <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto text-2xl mb-4">
            📱
        </div>

        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Nomor WhatsApp</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
            Nomor WhatsApp Anda (<strong class="text-slate-900">+<?= e($userData['whatsapp'] ?? '') ?></strong>) digunakan untuk menghubungkan penjual dan pembeli saat COD. Silakan masukkan kode OTP untuk mengaktifkan akun.
        </p>

        <!-- Banner Simulasi OTP -->
        <div class="my-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-left text-xs text-emerald-950">
            <div class="font-bold mb-1 flex items-center gap-1.5">
                <span>🔐</span> Kode OTP Pengujian Lokal / Sidang:
            </div>
            <div class="flex items-center justify-between mt-2">
                <span class="text-lg font-mono font-extrabold tracking-widest text-emerald-800 bg-white px-3 py-1 rounded border border-emerald-300">
                    <?= e($currentOtp ?: 'Belum dibuat') ?>
                </span>
                <button type="button" onclick="document.getElementById('inputOtp').value = '<?= e($currentOtp) ?>'"
                        class="text-xs bg-emerald-700 text-white font-semibold px-2.5 py-1.5 rounded-lg hover:bg-emerald-800">
                    Salin Otomatis
                </button>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800 text-left">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/verifikasi_wa.php" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <input type="text" name="otp" id="inputOtp" required maxlength="6" placeholder="Masukkan 6 Digit OTP"
                       class="w-full text-center tracking-widest font-mono text-lg font-bold py-2.5 px-4 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                Verifikasi WhatsApp
            </button>
        </form>

        <div class="mt-4 text-xs text-slate-400">
            Ingin mengganti nomor? <a href="<?= BASE_URL ?>/auth/profil.php" class="text-emerald-700 font-bold hover:underline">Edit nomor di Profil</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
