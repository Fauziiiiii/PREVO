<?php
$page_title = "Masuk - Marketplace Preloved Mahasiswa Polinema";
require_once __DIR__ . '/../includes/auth/header.php';
?>

    <!-- Main Split Layout Container -->
    <main class="flex-1 w-full min-h-screen flex flex-col lg:flex-row">
        <!-- LEFT SIDE: Campus Branding & Visual Identity (Politeknik Negeri Malang) -->
        <div class="relative w-full lg:w-5/12 xl:w-1/2 bg-gradient-to-br from-primary-fixed via-surface-container to-surface-container-high p-8 lg:p-12 flex flex-col justify-between overflow-hidden">
            <!-- Decorative Backdrop Circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-secondary-container/20 rounded-full blur-2xl pointer-events-none"></div>
            
            <!-- Top Header / Polinema Logo Lockup -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-surface-container-lowest p-1.5 shadow-sm border border-outline-variant/40 flex items-center justify-center">
                    <!-- Polinema Emblem Representation -->
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBds_2OamHbRCvzDGeJG0jMTn5-l4HQID2qJYVDNSDYQNmZhJAMQa0Kh4tjMI-Y4r6dOa7zL1xH5jzQRj0hdR4HGz2gXDUgQAmf25FgBTmrh8OwZ8F5d5EmE5Rp8ZTc8eXWZ47ZsOssCRVaLQVJN2t_tghNEzMt0H6hP4L6aGk5cYfAQrkgwbPl26q6e_B9nzts4rYCCZeho-oQDYGkgyRg0_qQ303pwcpv6fwSExYlV_S9L_s_KdFquoX8G-pt0aH_FRU"
                        alt="Logo Politeknik Negeri Malang" class="w-full h-full object-contain" />
                </div>
                <div>
                    <span class="inline-block px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-sm text-label-sm tracking-wide mb-0.5">
                        AFILIASI RESMI KAMPUS
                    </span>
                    <h2 class="font-headline-md text-headline-md text-on-surface tracking-tight">
                        POLITEKNIK NEGERI MALANG
                    </h2>
                </div>
            </div>

            <!-- Campus Building Visual Card with Floating Elements -->
            <div class="relative z-10 my-8 lg:my-0 flex flex-col items-center">
                <div class="relative w-full max-w-md rounded-2xl overflow-hidden shadow-lg border border-outline-variant/40 bg-surface-container-lowest">
                    <!-- Real Polinema Campus Landmark Photo Placeholder -->
                    <img class="w-full h-72 lg:h-80 object-cover transform hover:scale-105 transition-transform duration-700"
                        data-alt="The modern grand multi-story main administration building of Politeknik Negeri Malang campus under a bright, sunny blue Indonesian sky."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjec3hjGZbk1bZCuvjOVxvbnpJBIpxnBBt7Q6xK2nOfJhm0gBpO074BKZImo2G_uY2VOq_msiDdsf9DaxMm6hQdZoGa2FNXRrCCFjio6_07qjkiHmvTrz5IjOm5N7aBCtCuF4hFRD-6OPQa5YA9P4SnLZUhsxyVehD_XkJkW98O0RyOxk8I9XbPatX4Bye2v2dXS-mNytKhXvbufImvLUnqwqn3PYZTAdMV32GXUi3AWoptOBHoosQ3I5UzFFxx7NdF1Y" />
                    <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-transparent to-transparent flex flex-col justify-end p-5 text-on-primary">
                        <span class="font-label-sm text-label-sm text-secondary-fixed font-bold flex items-center gap-1.5 mb-1">
                            <span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
                            Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Malang
                        </span>
                        <p class="font-body-md text-body-md text-surface-bright/90 font-medium">
                            Kawasan Terpadu Jual-Beli Terpercaya Mahasiswa Polinema
                        </p>
                    </div>
                </div>

                <!-- Handwritten Doodle Badge -->
                <div class="relative mt-6 lg:mt-8 text-center">
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-surface-container-lowest/90 backdrop-blur-md shadow-sm border border-primary/20 transform -rotate-1 hover:rotate-0 transition-transform">
                        <span class="material-symbols-outlined text-primary text-xl" data-icon="stars">stars</span>
                        <span class="font-handwriting text-2xl lg:text-3xl font-bold text-primary tracking-wide">
                            "Dari Mahasiswa, Untuk Mahasiswa"
                        </span>
                    </div>
                    <p class="mt-2 font-body-sm text-body-sm text-on-surface-variant max-w-xs mx-auto">
                        100% Khusus Civitas Academica Polinema aktif. Transaksi hemat, aman, dan dekat.
                    </p>
                </div>
            </div>

            <!-- Bottom Trust Badges Cluster -->
            <div class="relative z-10 grid grid-cols-3 gap-2 pt-4 border-t border-outline-variant/40">
                <div class="flex items-center gap-2 bg-surface-container-lowest/70 backdrop-blur-sm p-2.5 rounded-xl border border-outline-variant/30">
                    <span class="material-symbols-outlined text-tertiary text-lg" data-icon="verified_user">verified_user</span>
                    <span class="font-label-sm text-label-sm text-on-surface leading-tight">Wajib NIM Aktif</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-lowest/70 backdrop-blur-sm p-2.5 rounded-xl border border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-lg" data-icon="handshake">handshake</span>
                    <span class="font-label-sm text-label-sm text-on-surface leading-tight">COD Titik Kampus</span>
                </div>
                <div class="flex items-center gap-2 bg-surface-container-lowest/70 backdrop-blur-sm p-2.5 rounded-xl border border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-lg" data-icon="price_check">price_check</span>
                    <span class="font-label-sm text-label-sm text-on-surface leading-tight">Bisa Nego Santai</span>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Authentication Form Canvas -->
        <div class="w-full lg:w-7/12 xl:w-1/2 bg-surface-container-lowest px-6 py-10 lg:px-16 lg:py-14 flex flex-col justify-between items-center relative">
            <!-- Top Navigation & Brand Header -->
            <div class="w-full max-w-md flex items-center justify-between">
                <a class="font-headline-lg text-headline-lg font-bold text-primary tracking-tight flex items-center gap-2"
                    href="<?= BASE_URL; ?>home/index.php">
                    <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm shadow-primary/20">
                        <span class="material-symbols-outlined text-2xl" data-icon="shopping_bag">shopping_bag</span>
                    </span>
                    PREVO
                </a>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-surface-container font-label-sm text-label-sm text-primary font-semibold flex items-center gap-1 border border-outline-variant/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim animate-pulse"></span>
                        Polinema Hub
                    </span>
                </div>
            </div>

            <!-- Center Authentication Box -->
            <div class="w-full max-w-md my-auto py-8">
                <div class="mb-8 text-center sm:text-left">
                    <h1 class="font-headline-xl text-headline-xl text-on-surface mb-2 tracking-tight">
                        Selamat Datang!
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Marketplace Preloved Mahasiswa Polinema. Temukan buku, alat praktikum, gadget, dan kosan di sekitarmu.
                    </p>
                </div>

                <!-- Segmented Tab: Masuk vs Daftar -->
                <div class="w-full bg-surface-container-low p-1.5 rounded-xl flex items-center mb-6 border border-outline-variant/40">
                    <a href="login.php"
                        class="flex-1 py-2 rounded-lg font-label-md text-label-md text-center transition-all duration-150 bg-surface-container-lowest text-primary shadow-sm font-bold"
                        id="tab-masuk">
                        Masuk
                    </a>
                    <a href="register.php"
                        class="flex-1 py-2 rounded-lg font-label-md text-label-md text-center text-on-surface-variant hover:text-primary transition-all duration-150"
                        id="tab-daftar">
                        Daftar
                    </a>
                </div>

                <!-- Flash Message Notification -->
                <?php display_flash(); ?>

                <!-- LOGIN FORM -->
                <form action="proses_login.php" method="POST" class="space-y-4" id="auth-form">
                    <?= csrf_field(); ?>

                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-1.5" for="identifier">
                            NIM / Email Kampus / Username
                        </label>
                        <div class="relative rounded-xl border border-outline-variant hover:border-outline focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-colors bg-surface-container-lowest">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-xl" data-icon="person">person</span>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 rounded-xl bg-transparent border-0 font-body-md text-body-md text-on-surface placeholder:text-outline focus:ring-0 focus:outline-none"
                                id="identifier" name="identifier"
                                placeholder="cth: 2141720000 atau nama@student.polinema.ac.id" required
                                type="text" />
                        </div>
                        <div class="flex justify-between items-center mt-1 px-1">
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-[11px]">
                                Gunakan NIM atau email @student.polinema.ac.id
                            </span>
                        </div>
                    </div>

                    <!-- Input 2: Password with Show/Hide Toggle -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block font-label-md text-label-md text-on-surface" for="password">
                                Kata Sandi
                            </label>
                            <a class="font-label-sm text-label-sm text-primary hover:underline" href="#">
                                Lupa sandi?
                            </a>
                        </div>
                        <div class="relative rounded-xl border border-outline-variant hover:border-outline focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-colors bg-surface-container-lowest">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-xl" data-icon="lock">lock</span>
                            </div>
                            <input class="w-full pl-11 pr-11 py-3 rounded-xl bg-transparent border-0 font-body-md text-body-md text-on-surface placeholder:text-outline focus:ring-0 focus:outline-none"
                                id="password" name="password" placeholder="Masukkan kata sandi akun PREVO" required
                                type="password" />
                            <button aria-label="Tampilkan atau sembunyikan kata sandi"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-outline hover:text-on-surface transition-colors focus:outline-none toggle-password-btn"
                                data-target="password" id="toggle-password" type="button">
                                <span class="material-symbols-outlined text-xl" data-icon="visibility" id="eye-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me & Security Check -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input checked
                                class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 transition duration-150"
                                type="checkbox" name="remember" />
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Ingat saya di perangkat ini</span>
                        </label>
                        <span class="font-label-sm text-label-sm text-tertiary font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm" data-icon="encrypted">encrypted</span>
                            SSL Terproteksi
                        </span>
                    </div>

                    <!-- Primary Submit Button -->
                    <button class="w-full py-3.5 px-6 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-bold shadow-sm hover:shadow-md active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2"
                        type="submit">
                        <span>Masuk ke PREVO</span>
                        <span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
                    </button>
                </form>

                <!-- Switch to Registration Footer Note -->
                <p class="text-center font-body-md text-body-md text-on-surface-variant mt-6">
                    Belum punya akun PREVO?
                    <a class="font-label-md text-label-md text-primary font-bold hover:underline ml-1" href="register.php">
                        Daftar Sekarang
                    </a>
                </p>

                <!-- Verification Banner (KTM Notice) -->
                <div class="mt-6 p-3.5 rounded-xl bg-surface-container-low border border-secondary-container/40 flex items-start gap-3">
                    <span class="material-symbols-outlined text-secondary text-xl mt-0.5" data-icon="badge">badge</span>
                    <div>
                        <p class="font-label-md text-label-md text-on-surface">
                            Syarat Akun Terverifikasi Polinema
                        </p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Siapkan foto KTM fisik atau tangkapan layar Siakad aktif untuk membuka fitur pasang iklan &amp; transaksi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Mini Footer -->
            <footer class="w-full max-w-md pt-4 border-t border-outline-variant/40 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
                <span class="font-body-sm text-body-sm text-outline">
                    © <?= date('Y'); ?> PREVO Politeknik Negeri Malang
                </span>
                <div class="flex items-center gap-4">
                    <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Bantuan</a>
                    <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Privasi</a>
                    <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Panduan COD</a>
                </div>
            </footer>
        </div>
    </main>

<?php
require_once __DIR__ . '/../includes/auth/footer.php';
?>
