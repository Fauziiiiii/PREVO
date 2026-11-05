<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect(is_admin() ? '/admin/dashboard.php' : '/barang/index.php');
}

$pageTitle = 'Masuk Akun';
$errors = [];
$identifier = '';
$redirectUrl = $_GET['redirect'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $identifier = sanitize_input($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirectUrl = $_POST['redirect'] ?? '';

    $authService = new AuthService();
    $result = $authService->login($identifier, $password);

    if ($result['success']) {
        set_flash('success', 'Selamat datang kembali di PREVO!');
        if ($result['role'] === 'admin') {
            redirect('/admin/dashboard.php');
        } elseif (!empty($_SESSION['user']) && empty($_SESSION['user']['whatsapp_verified_at'])) {
            redirect('/auth/verifikasi_wa.php');
        } elseif (!empty($redirectUrl)) {
            redirect($redirectUrl);
        } else {
            redirect('/barang/index.php');
        }
    } else {
        $errors[] = $result['error'];
    }
}

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-md mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke PREVO</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Gunakan akun civitas akademika Polinema Anda.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/login.php" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="redirect" value="<?= e($redirectUrl) ?>">

            <!-- Identifier (NIM / Email) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIM atau Email Kampus</label>
                <input type="text" name="identifier" required value="<?= e($identifier) ?>" 
                       placeholder="2541... atau nama@student.polinema.ac.id"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                </div>
                <div class="relative">
                    <input type="password" id="loginPassword" name="password" required placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <button type="button" onclick="togglePassword('loginPassword', this)" 
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
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all mt-2">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Belum punya akun? 
            <a href="<?= BASE_URL ?>/auth/register.php" class="font-bold text-emerald-700 hover:text-emerald-800">Daftar mahasiswa</a>
        </div>

        <!-- Quick Demo Accounts Helper -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-xs">
            <div class="font-bold text-slate-700 mb-2 flex items-center justify-between">
                <span>⚡ Akun Uji Cepat (Demo):</span>
                <span class="text-[11px] font-normal text-slate-400">Pass: Test@12345</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <button type="button" onclick="fillLogin('admin@prevo.polinema.ac.id', 'Test@12345')"
                        class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left font-medium text-slate-700">
                    👑 <strong>Admin</strong><br><span class="text-slate-400">admin@prevo...</span>
                </button>
                <button type="button" onclick="fillLogin('fauzi@student.polinema.ac.id', 'Test@12345')"
                        class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left font-medium text-slate-700">
                    👨‍🎓 <strong>Fauzi (Penjual)</strong><br><span class="text-slate-400">fauzi@student...</span>
                </button>
                <button type="button" onclick="fillLogin('buyer1@student.polinema.ac.id', 'Test@12345')"
                        class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left font-medium text-slate-700">
                    🛒 <strong>Budi (Pembeli)</strong><br><span class="text-slate-400">buyer1@student...</span>
                </button>
                <button type="button" onclick="fillLogin('reyhandhika@student.polinema.ac.id', 'Test@12345')"
                        class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left font-medium text-slate-700">
                    💻 <strong>Reyhan (Penjual)</strong><br><span class="text-slate-400">reyhan@student...</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
function fillLogin(id, pw) {
    document.querySelector('input[name="identifier"]').value = id;
    document.querySelector('input[name="password"]').value = pw;
}

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const showIcon = btn.querySelector('.eye-icon-show');
    const hideIcon = btn.querySelector('.eye-icon-hide');
    if (input.type === 'password') {
        input.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}
</script>

<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>
