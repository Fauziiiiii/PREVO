<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect('/barang/index.php');
}

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$errors = [];
$isSuccess = false;
$authService = new AuthService();
$tokenValid = !empty($token) && $authService->isResetTokenValid($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$authService->isResetTokenValid($token)) {
        $errors[] = 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan ajukan permintaan reset baru.';
        $tokenValid = false;
    } elseif (strlen($newPassword) < 6) {
        $errors[] = 'Kata sandi baru minimal 6 karakter.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = 'Konfirmasi kata sandi tidak cocok.';
    } else {
        $result = $authService->resetPassword($token, $newPassword);
        if ($result['success']) {
            $isSuccess = true;
            $tokenValid = false; // Token telah dikonsumsi
        } else {
            $errors[] = $result['error'] ?? 'Gagal memperbarui kata sandi.';
        }
    }
}

$pageTitle = 'Atur Ulang Kata Sandi';
require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-md mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <?php if ($isSuccess): ?>
            <!-- Tampilan Berhasil Reset -->
            <div class="text-center py-4 space-y-4">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-xs">
                    ✓
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kata Sandi Diperbarui!</h1>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Kata sandi akun Anda telah berhasil diubah. Sekarang Anda dapat masuk ke PREVO menggunakan kata sandi baru.
                </p>
                <div class="pt-3">
                    <a href="<?= BASE_URL ?>/auth/login.php" 
                       class="block w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm text-center rounded-xl shadow-md transition-all">
                        Masuk ke Akun Sekarang
                    </a>
                </div>
            </div>

        <?php elseif ($tokenValid): ?>
            <!-- Form Atur Ulang Kata Sandi -->
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto text-xl mb-3 shadow-xs">
                    🔒
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Atur Ulang Kata Sandi</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Buat kata sandi baru yang aman dan mudah Anda ingat (minimal 6 karakter).
                </p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800 space-y-1">
                    <?php foreach ($errors as $err): ?>
                        <p>⚠️ <?= e($err) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/reset_password.php" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">

                <!-- Password Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                    <div class="relative">
                        <input type="password" id="resetPassword" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        <button type="button" onclick="togglePassword('resetPassword', this)" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                aria-label="Tampilkan atau sembunyikan kata sandi">
                            <svg class="w-5 h-5 eye-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-5 h-5 eye-icon-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirm_password" required minlength="6" placeholder="Ulangi kata sandi baru"
                               class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        <button type="button" onclick="togglePassword('confirmPassword', this)" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                aria-label="Tampilkan atau sembunyikan kata sandi">
                            <svg class="w-5 h-5 eye-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-5 h-5 eye-icon-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                    Simpan Kata Sandi Baru
                </button>
            </form>

        <?php else: ?>
            <!-- Tampilan Token Tidak Valid atau Kedaluwarsa -->
            <div class="text-center py-4 space-y-4">
                <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-xs">
                    ⚠️
                </div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Tautan Kedaluwarsa</h1>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Tautan reset kata sandi ini tidak valid atau telah melewati batas waktu berlaku (30 menit). Demi keamanan, silakan ajukan permintaan baru.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="<?= BASE_URL ?>/auth/forgot_password.php" 
                       class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-xs transition-all text-center">
                        Ajukan Tautan Reset Baru
                    </a>
                    <a href="<?= BASE_URL ?>/auth/login.php" 
                       class="text-xs font-semibold text-slate-500 hover:text-slate-700 py-1">
                        Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const showIcon = btn.querySelector('.eye-icon-show');
    const hideIcon = btn.querySelector('.eye-icon-hide');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        if (showIcon) showIcon.classList.add('hidden');
        if (hideIcon) hideIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        if (showIcon) showIcon.classList.remove('hidden');
        if (hideIcon) hideIcon.classList.add('hidden');
    }
}
</script>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
