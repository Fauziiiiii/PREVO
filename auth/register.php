<?php
$page_title = "Daftar Akun Baru - PREVO Polinema";
$body_class = "font-sans antialiased text-slate-800 bg-[#f8fafc] min-h-screen";
require_once __DIR__ . '/../includes/auth/header.php';
?>

    <div class="flex min-h-screen w-full">
        <!-- BEGIN: Left Column (Brand Hero & Visual) -->
        <section
            class="hidden lg:flex lg:w-[48%] xl:w-[46%] relative flex-col justify-between p-10 xl:p-14 text-white overflow-hidden"
            data-purpose="hero-banner">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 -z-20 overflow-hidden">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjec3hjGZbk1bZCuvjOVxvbnpJBIpxnBBt7Q6xK2nOfJhm0gBpO074BKZImo2G_uY2VOq_msiDdsf9DaxMm6hQdZoGa2FNXRrCCFjio6_07qjkiHmvTrz5IjOm5N7aBCtCuF4hFRD-6OPQa5YA9P4SnLZUhsxyVehD_XkJkW98O0RyOxk8I9XbPatX4Bye2v2dXS-mNytKhXvbufImvLUnqwqn3PYZTAdMV32GXUi3AWoptOBHoosQ3I5UzFFxx7NdF1Y"
                    alt="Gedung Kampus Polinema"
                    class="w-full h-full object-cover object-center scale-105 transition-transform duration-700">
            </div>
            <!-- Royal Blue Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#1a56db]/80 via-[#1e40af]/75 to-[#0f172a]/85 -z-10 backdrop-blur-[0.5px]"></div>

            <!-- Top Section: Platform Badge -->
            <div class="relative z-10 pt-2">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 border border-white/20 backdrop-blur-md text-xs font-medium text-white shadow-sm">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                    </svg>
                    <span>Platform Resmi Civitas Polinema</span>
                </div>
            </div>

            <!-- Middle Section: Main Hero Title & Subtitle -->
            <div class="relative z-10 mt-6 mb-auto max-w-xl">
                <h1 class="text-4xl xl:text-[42px] leading-[1.2] font-bold tracking-tight text-white mb-5 [text-shadow:_0_2px_12px_rgba(0,0,0,0.5)]">
                    Pusatnya Preloved<br>Mahasiswa Polinema.
                </h1>
                <p class="text-white/95 text-[15px] xl:text-base leading-relaxed max-w-lg font-medium [text-shadow:_0_1px_8px_rgba(0,0,0,0.4)]">
                    Jual beli perlengkapan kuliah, laptop, modul praktikum, dan kebutuhan anak kost langsung sesama mahasiswa terverifikasi.
                </p>
            </div>

            <!-- Bottom Section: Feature Pills & Campus Address Footer -->
            <div class="relative z-10 space-y-8">
                <!-- Feature Chips -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs font-medium text-white/95">
                    <!-- Chip 1 -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full bg-white/15 border border-white/20 backdrop-blur-md">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>Wajib NIM Aktif</span>
                    </div>

                    <!-- Chip 2 -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full bg-white/15 border border-white/20 backdrop-blur-md">
                        <svg class="w-3.5 h-3.5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>COD Titik Kampus</span>
                    </div>

                    <!-- Chip 3 -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full bg-white/15 border border-white/20 backdrop-blur-md">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>Bisa Nego Santai</span>
                    </div>
                </div>

                <!-- Left Column Footer -->
                <div class="flex items-center justify-between text-xs text-white/60 pt-4 border-t border-white/10">
                    <span>Jl. Soekarno Hatta No.9, Malang</span>
                    <span>PREVO Polinema</span>
                </div>
            </div>
        </section>
        <!-- END: Left Column -->

        <!-- BEGIN: Right Column (Form Container) -->
        <main
            class="flex-1 flex flex-col justify-between items-center py-8 px-4 sm:px-6 md:px-10 lg:px-8 xl:px-12 overflow-y-auto custom-scroll"
            data-purpose="registration-screen">
            <!-- Top Bar: Logo & Beranda Navigation -->
            <header class="w-full max-w-[540px] flex items-center justify-between mb-6 pt-2">
                <!-- Logo & Brand Info -->
                <a href="<?= BASE_URL; ?>home/index.php" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 bg-brand-600 group-hover:bg-brand-700 rounded-xl flex items-center justify-center text-white shadow-md shadow-brand-500/20 transition">
                        <!-- Shopping Bag Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight leading-none mb-1">PREVO</h2>
                        <p class="text-[12px] text-slate-500 font-normal">Polinema Preloved Hub</p>
                    </div>
                </a>

                <!-- Home Button Link -->
                <a class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition shadow-sm"
                    href="<?= BASE_URL; ?>home/index.php">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span>Beranda</span>
                </a>
            </header>

            <!-- Center Card: Form Container -->
            <div class="w-full max-w-[540px] bg-white rounded-2xl border border-slate-200/90 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] p-6 sm:p-8"
                data-purpose="form-card">
                <!-- Tab Navigation Switcher -->
                <div class="flex items-center border-b border-slate-100 mb-6">
                    <!-- Masuk Tab (Inactive) -->
                    <a class="flex items-center gap-2 py-3 px-4 text-xs font-semibold text-slate-500 hover:text-slate-800 transition"
                        href="login.php">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>Masuk</span>
                    </a>

                    <!-- Daftar Akun Baru Tab (Active) -->
                    <div class="flex items-center gap-2 py-3 px-4 text-xs font-semibold text-brand-600 border-b-2 border-brand-600 -mb-[1px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>Daftar Akun Baru</span>
                    </div>
                </div>

                <!-- Form Heading & Lead Text -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-1.5">Daftar Akun Baru</h3>
                    <p class="text-xs text-slate-500 leading-normal">
                        Pusatnya Preloved Mahasiswa Polinema. Jual beli praktis sesama mahasiswa.
                    </p>
                </div>

                <!-- Flash Notification (Error / Sukses) -->
                <?php display_flash(); ?>

                <!-- Main Form Elements -->
                <form action="proses_register.php" class="space-y-4" method="POST" id="registerForm">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="redirect_to" value="register.php">

                    <!-- Field 1: Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5" for="fullName">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input class="block w-full pl-10 pr-3.5 py-2.5 bg-[#fbfcfd] border border-slate-200 rounded-lg text-xs placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                id="fullName" name="name" placeholder="Nama lengkap sesuai KTM" required type="text">
                        </div>
                    </div>

                    <!-- Field 2: NIM / Email Kampus -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5" for="identifier">
                            NIM / Email Kampus
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <!-- Identification / ID Card Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input class="block w-full pl-10 pr-3.5 py-2.5 bg-[#fbfcfd] border border-slate-200 rounded-lg text-xs placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                id="identifier" name="nim" placeholder="cth: 2141720000 atau nama@student.polinema.ac.id" required type="text">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Otomatis diverifikasi dengan email resmi @student.polinema.ac.id</p>
                    </div>

                    <!-- Field 3: Nomor WhatsApp -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5" for="phone">
                            Nomor WhatsApp
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <!-- Chat / Message Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input class="block w-full pl-10 pr-3.5 py-2.5 bg-[#fbfcfd] border border-slate-200 rounded-lg text-xs placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                id="phone" name="whatsapp" placeholder="08xxxxxxxxxx (untuk koordinasi COD)" required type="tel">
                        </div>
                    </div>

                    <!-- Field 4: Kata Sandi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5" for="password">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <!-- Lock Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input class="block w-full pl-10 pr-10 py-2.5 bg-[#fbfcfd] border border-slate-200 rounded-lg text-xs placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                id="password" name="password" placeholder="Minimal 6 karakter" required type="password">
                            <button class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none toggle-password-btn"
                                data-target="password" id="togglePasswordBtn" type="button">
                                <!-- Eye Outline Icon -->
                                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start pt-1.5 pb-1">
                        <div class="flex items-center h-4">
                            <input class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded cursor-pointer"
                                id="terms" name="terms" type="checkbox" required checked>
                        </div>
                        <label class="ml-2.5 text-xs text-slate-600 leading-snug cursor-pointer select-none" for="terms">
                            Saya menyetujui <a class="font-bold text-brand-700 hover:underline" href="#">Ketentuan Komunitas</a> &amp; <a class="font-bold text-brand-700 hover:underline" href="#">Panduan COD Aman Polinema.</a>
                        </label>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-2">
                        <button class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-white bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-xs font-semibold shadow-md shadow-brand-600/25 transition duration-150"
                            type="submit">
                            <span>Daftar Akun PREVO</span>
                            <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Divider with Text -->
                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-[11px] leading-none">
                        <span class="bg-white px-3 text-slate-400">atau</span>
                    </div>
                </div>

                <!-- Google Mail Kampus SSO Button -->
                <button class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition duration-150"
                    type="button">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"></path>
                    </svg>
                    <span>Lanjutkan dengan Google Mail Kampus</span>
                </button>

                <!-- Login Switch Prompt Footer -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-600">
                    Sudah punya akun PREVO?
                    <a class="font-bold text-brand-600 hover:text-brand-700 ml-1" href="login.php">Masuk di sini</a>
                </div>
            </div>

            <!-- Bottom System Footer -->
            <footer class="w-full max-w-[540px] text-center text-[11px] text-slate-500 py-6" data-purpose="copyright-info">
                <p>© <?= date('Y'); ?> PREVO Politeknik Negeri Malang · Bantuan · Privasi · Panduan COD</p>
            </footer>
        </main>
        <!-- END: Right Column -->
    </div>

<?php
require_once __DIR__ . '/../includes/auth/footer.php';
?>
