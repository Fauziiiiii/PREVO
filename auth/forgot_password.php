<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect('/barang/index.php');
}

$resetLink = null;
$resetToken = null;
$email = '';
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Masukkan alamat email kampus yang valid.';
    } else {
        $emailParts = explode('@', $email);
        $domain = $emailParts[1] ?? '';
        if (!in_array($domain, ALLOWED_EMAIL_DOMAINS)) {
            $errors[] = 'Email harus menggunakan domain resmi Polinema (@student.polinema.ac.id atau @polinema.ac.id).';
        } else {
            $authService = new AuthService();
            $result = $authService->requestPasswordReset($email);

            if ($result['success'] && !empty($result['token'])) {
                $resetToken = $result['token'];
                $resetLink = BASE_URL . '/auth/reset_password.php?token=' . urlencode($resetToken);
                $successMessage = 'Tautan reset kata sandi telah berhasil dibuat. Silakan gunakan tautan di bawah ini untuk mengatur ulang kata sandi Anda.';
            } else {
                $errors[] = 'Alamat email tidak terdaftar atau belum diaktivasi sebagai mahasiswa di PREVO.';
            }
        }
    }
}

$pageTitle = 'Lupa Kata Sandi';
require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-md mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center mx-auto text-xl mb-3 shadow-xs">
                🔑
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Masukkan email kampus Polinema yang terdaftar untuk menerima tautan pemulihan akun.
            </p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800 space-y-1">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage) && !empty($resetLink)): ?>
            <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 space-y-3">
                <div class="font-bold flex items-center gap-1.5 text-emerald-950">
                    <span>✓</span> Permintaan Reset Terkirim!
                </div>
                <p class="text-[11px] text-emerald-800 leading-relaxed">
                    <?= e($successMessage) ?>
                </p>

                <!-- Banner Simulasi Pengujian Lokal / Presentasi Dosen -->
                <div class="p-3 bg-white/80 rounded-lg border border-emerald-200 text-slate-700 space-y-2">
                    <div class="font-bold text-[11px] text-slate-800 flex items-center gap-1">
                        <span>🛠️</span> Mode Pengujian Lokal / Presentasi:
                    </div>
                    <code class="block bg-slate-100 p-2 rounded text-[10px] break-all font-mono text-slate-800 select-all">
                        <?= e($resetLink) ?>
                    </code>
                    <a href="<?= $resetLink ?>" 
                       class="block w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-center text-xs rounded-lg shadow-xs transition-all">
                        Buka Halaman Reset Kata Sandi (Simulasi) →
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/forgot_password.php" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <!-- Input Email Kampus -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Kampus Polinema</label>
                <input type="email" name="email" required value="<?= e($email) ?>" 
                       placeholder="nim@student.polinema.ac.id"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                <span class="text-[11px] text-slate-400 mt-1 block">Khusus domain @student.polinema.ac.id atau @polinema.ac.id</span>
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                Kirim Tautan Reset
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Ingat kata sandi akun Anda? 
            <a href="<?= BASE_URL ?>/auth/login.php" class="font-bold text-emerald-700 hover:text-emerald-800">Masuk di sini</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
