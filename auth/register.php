<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect('/barang/index.php');
}

$pageTitle = 'Pendaftaran Akun Mahasiswa';
$errors = [];
$formData = [
    'nim' => '',
    'name' => '',
    'email' => '',
    'whatsapp' => '',
    'jurusan' => '',
    'prodi' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $formData = [
        'nim'      => sanitize_input($_POST['nim'] ?? ''),
        'name'     => sanitize_input($_POST['name'] ?? ''),
        'email'    => sanitize_input($_POST['email'] ?? ''),
        'whatsapp' => sanitize_input($_POST['whatsapp'] ?? ''),
        'jurusan'  => sanitize_input($_POST['jurusan'] ?? ''),
        'prodi'    => sanitize_input($_POST['prodi'] ?? ''),
        'password' => $_POST['password'] ?? ''
    ];

    $authService = new AuthService();
    $result = $authService->register($formData);

    if ($result['success']) {
        set_flash('success', 'Pendaftaran berhasil! Silakan verifikasi email kampus Anda untuk mengaktifkan akun.');
        redirect('/auth/verifikasi_email.php?token=' . urlencode($result['token']));
    } else {
        $errors[] = $result['error'];
    }
}

require_once __DIR__ . '/../views/partials/header.php';
?>

<div class="max-w-xl mx-auto px-4 py-8 sm:py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="text-center mb-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                🎓 Civitas Akademika Polinema
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun PREVO</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Gunakan identitas kampus resmi untuk ekosistem jual-beli yang aman.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800">
                <?php foreach ($errors as $err): ?>
                    <p>⚠️ <?= e($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/register.php" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <!-- NIM -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Induk Mahasiswa (NIM)</label>
                <input type="text" name="nim" required value="<?= e($formData['nim']) ?>" placeholder="Contoh: 254107020085"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" required value="<?= e($formData['name']) ?>" placeholder="Nama sesuai KTM"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
            </div>

            <!-- Email Kampus -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Kampus Polinema</label>
                <input type="email" name="email" required value="<?= e($formData['email']) ?>" placeholder="nim@student.polinema.ac.id"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                <span class="text-[11px] text-slate-400 mt-0.5 block">Wajib menggunakan domain @student.polinema.ac.id</span>
            </div>

            <!-- Jurusan & Prodi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jurusan</label>
                    <select name="jurusan" id="selectJurusan" required onchange="updateProdiOptions()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        <option value="">Pilih Jurusan</option>
                        <?php foreach (array_keys(JURUSAN_POLINEMA) as $j): ?>
                            <option value="<?= e($j) ?>" <?= $formData['jurusan'] === $j ? 'selected' : '' ?>><?= e($j) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program Studi</label>
                    <select name="prodi" id="selectProdi" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        <option value="">Pilih Prodi</option>
                    </select>
                </div>
            </div>

            <!-- Nomor WhatsApp -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp Aktif</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-semibold text-slate-500 pointer-events-none">+62</span>
                    <input type="text" name="whatsapp" required value="<?= e($formData['whatsapp']) ?>" placeholder="81234567890"
                           class="w-full pl-12 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                </div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Digunakan pembeli untuk koordinasi COD setelah barang dibooking.</span>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                <div class="relative">
                    <input type="password" id="registerPassword" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <button type="button" onclick="togglePassword('registerPassword', this)" 
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
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md transition-all mt-4">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Sudah memiliki akun? 
            <a href="<?= BASE_URL ?>/auth/login.php" class="font-bold text-emerald-700 hover:text-emerald-800">Masuk di sini</a>
        </div>

    </div>
</div>

<script>
const prodiMap = <?= json_encode(JURUSAN_POLINEMA) ?>;
const selectedProdiInitial = <?= json_encode($formData['prodi']) ?>;

function updateProdiOptions() {
    const jurusan = document.getElementById('selectJurusan').value;
    const prodiSelect = document.getElementById('selectProdi');
    prodiSelect.innerHTML = '<option value="">Pilih Prodi</option>';

    if (jurusan && prodiMap[jurusan]) {
        prodiMap[jurusan].forEach(p => {
            const opt = document.createElement('option');
            opt.value = p;
            opt.textContent = p;
            if (p === selectedProdiInitial) {
                opt.selected = true;
            }
            prodiSelect.appendChild(opt);
        });
    }
}

// Inisialisasi saat load jika ada nilai lama
document.addEventListener('DOMContentLoaded', updateProdiOptions);

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
