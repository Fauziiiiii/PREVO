<?php
$page_title = "Daftar Akun Mahasiswa - PREVO Polinema";
require_once __DIR__ . '/../includes/auth/header.php';
?>

    <!-- Main Content Container: Desktop Two-Column Split Screen -->
    <main class="w-full flex-1 flex flex-col lg:flex-row relative">
        <section class="lg:w-5/12 xl:w-1/2 bg-gradient-to-br from-surface-container-low via-surface-container to-surface-container-high p-6 md:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden border-r border-outline-variant/40">
            <!-- Subtle Campus Background Motif -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-fixed/40 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary-fixed/30 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <!-- Header Afiliasi Kampus -->
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-surface-container-lowest shadow-sm p-1.5 flex items-center justify-center border border-outline-variant/50">
                            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm tracking-wide">
                                    AFILIASI RESMI KAMPUS
                                </span>
                            </div>
                            <p class="font-label-lg text-label-lg text-on-surface font-bold tracking-tight">POLITEKNIK NEGERI MALANG</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 bg-surface-container-lowest/80 backdrop-blur-sm rounded-full border border-outline-variant/60 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        <span class="font-label-sm text-label-sm text-secondary font-medium">Jaringan Kampus Aktif</span>
                    </div>
                </div>

                <!-- Kartu Gedung Kampus Polinema -->
                <div class="relative rounded-2xl overflow-hidden shadow-sm border border-outline-variant/60 group bg-surface-container-lowest">
                    <div class="relative h-64 sm:h-80 w-full overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            data-alt="Gedung Kampus Politeknik Negeri Malang"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCbcuLlgBgzHeCVSjHTboO-_pO83gBPWwt5XMDUqprE_FJz700dWJZu9weyXRnAh45yT7jSvxrs4boenDeDWg9r85HUpQwYA2Nzgu2CIfCdf62WlWLNaaBg1VVWrQhIXuq-WnBKgwnDFhueKTRfkLPkyzjXckIlUtoxDUYEkqgkCi-0lBxQaaqAgSp0XZe8aDyUC2JtQW--XdKPZo3gBnfRnMuYFD6oD3-1tag8G6FO9vJVVzrUqy0LoQ" />
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent"></div>
                        
                        <!-- Floating Badge: Kawasan Terpadu -->
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-primary font-label-md text-label-md font-semibold shadow-sm border border-outline-variant/40">
                                <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">verified</span>
                                Kawasan Terpadu Jual-Beli Terpercaya Mahasiswa Polinema
                            </span>
                        </div>

                        <!-- Overlay Lokasi Kampus -->
                        <div class="absolute bottom-4 left-4 right-4 text-surface-container-lowest">
                            <div class="flex items-start gap-2 bg-inverse-surface/80 backdrop-blur-md p-3.5 rounded-xl border border-white/10">
                                <span class="material-symbols-outlined text-secondary-container text-xl mt-0.5" style="font-variation-settings: 'FILL' 1;">location_on</span>
                                <div>
                                    <p class="font-label-md text-label-md font-bold text-white leading-snug">Kampus Utama Polinema</p>
                                    <p class="font-body-sm text-body-sm text-inverse-on-surface/90">Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Malang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Badge Kutipan: Dari Mahasiswa, Untuk Mahasiswa -->
                <div class="bg-surface-container-lowest/90 backdrop-blur-md p-5 rounded-2xl border border-outline-variant/60 shadow-sm relative">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">handshake</span>
                        </div>
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-primary tracking-tight">“Dari Mahasiswa, Untuk Mahasiswa”</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                                100% Khusus Civitas Academica Polinema aktif. Transaksi hemat, aman, dan dekat langsung di dalam lingkungan kampus tanpa was-was penipuan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3 Chip Fitur Unggulan -->
            <div class="relative z-10 pt-6 mt-6 border-t border-outline-variant/50">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-container-lowest/80 border border-outline-variant/60">
                        <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">badge</span>
                        <div>
                            <p class="font-label-md text-label-md font-bold text-on-surface">Wajib NIM Aktif</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Validasi kampus</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-container-lowest/80 border border-outline-variant/60">
                        <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings: 'FILL' 1;">pin_drop</span>
                        <div>
                            <p class="font-label-md text-label-md font-bold text-on-surface">COD Titik Kampus</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Titik temu aman</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-container-lowest/80 border border-outline-variant/60">
                        <span class="material-symbols-outlined text-primary-container text-xl" style="font-variation-settings: 'FILL' 1;">price_check</span>
                        <div>
                            <p class="font-label-md text-label-md font-bold text-on-surface">Bisa Nego Santai</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Ramah kantong</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="lg:w-7/12 xl:w-1/2 bg-surface-container-lowest p-6 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-center items-center">
            <div class="w-full max-w-xl mx-auto space-y-6">
                <!-- Header Brand PREVO -->
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <a class="font-headline-lg text-headline-lg font-bold text-primary tracking-tight flex items-center gap-2" href="<?= BASE_URL; ?>home/index.php">
                            <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-on-primary shadow-sm shadow-primary/20">
                                <span class="material-symbols-outlined text-2xl" data-icon="shopping_bag">shopping_bag</span>
                            </span>
                            PREVO
                        </a>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/30 border border-secondary text-secondary">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">hub</span>
                        <span class="font-label-md text-label-md font-semibold">Polinema Hub</span>
                    </div>
                </div>

                <!-- Judul & Subjudul -->
                <div>
                    <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface font-extrabold tracking-tight">
                        Daftar Akun PREVO
                    </h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1.5 leading-relaxed">
                        Bergabung dengan komunitas preloved mahasiswa Polinema. Jual beli buku kuliah, elektronik, dan kebutuhan kost aman sesama mahasiswa.
                    </p>
                </div>

                <!-- Tab Switcher: Masuk vs Daftar (Daftar Aktif) -->
                <div class="p-1 rounded-xl bg-surface-container-low border border-outline-variant/40 grid grid-cols-2 gap-1">
                    <a class="py-2.5 px-4 text-center rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center gap-2"
                        href="login.php">
                        <span class="material-symbols-outlined text-base">login</span>
                        Masuk
                    </a>
                    <button class="py-2.5 px-4 text-center rounded-lg bg-surface-container-lowest font-label-md text-label-md text-primary font-bold shadow-xs border border-outline-variant/30 flex items-center justify-center gap-2"
                        type="button">
                        <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">how_to_reg</span>
                        Daftar
                    </button>
                </div>

                <!-- Flash Message Notification -->
                <?php tampil_flash(); ?>

                <!-- Box Informasi Keamanan Akun -->
                <div class="p-3.5 rounded-xl bg-surface-container-low border border-primary/20 flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-xl flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">shield_with_heart</span>
                    <div class="text-on-surface font-body-sm text-body-sm">
                        <span class="font-bold text-primary">Verifikasi Otomatis Mahasiswa:</span>
                        Akun baru otomatis diverifikasi jika mendaftar menggunakan email kampus aktif (@student.polinema.ac.id).
                    </div>
                </div>

                <!-- Form Registrasi Mahasiswa -->
                <form action="proses_register.php" method="POST" class="space-y-4 text-left">
                    <?= csrf_field(); ?>

                    <!-- 1. Nama Lengkap -->
                    <div class="space-y-1.5">
                        <label class="block font-label-md text-label-md text-on-surface font-semibold" for="fullName">
                            Nama Lengkap Sesuai KTM
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-xl">person</span>
                            </span>
                            <input class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface placeholder:text-outline font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed outline-none transition"
                                id="fullName" name="name" placeholder="cth: Muhammad Rizky Pratama" required type="text" />
                        </div>
                    </div>

                    <!-- 2. NIM & Email Kampus Otomatis (Smart Input) -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="nimInput">
                                Nomor Induk Mahasiswa (NIM)
                            </label>
                            <span class="font-label-sm text-label-sm text-secondary font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                Validasi Otomatis
                            </span>
                        </div>
                        <div class="flex rounded-xl shadow-xs overflow-hidden border border-outline-variant focus-within:border-primary focus-within:ring-2 focus-within:ring-primary-fixed bg-surface-container-lowest transition">
                            <span class="inline-flex items-center pl-3.5 text-outline pointer-events-none">
                                <span class="material-symbols-outlined text-xl">badge</span>
                            </span>
                            <input class="w-full pl-2.5 pr-2 py-2.5 border-0 bg-transparent text-on-surface placeholder:text-outline font-body-md text-body-md focus:ring-0 outline-none"
                                id="nimInput" name="nim" maxlength="14" pattern="[0-9]*" placeholder="254107020099" required type="text" />
                            <span class="inline-flex items-center px-3 bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold border-l border-outline-variant/60 select-none">
                                @student.polinema.ac.id
                            </span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 pt-0.5">
                            <span class="material-symbols-outlined text-xs text-primary">info</span>
                            NIM otomatis terhubung dengan email resmi @student.polinema.ac.id untuk validasi akun.
                        </p>
                    </div>

                    <!-- 3 & 4. Dropdown Jurusan & Program Studi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Jurusan -->
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="departmentSelect">
                                Jurusan
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-xl">apartment</span>
                                </span>
                                <select class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed outline-none transition appearance-none"
                                    id="departmentSelect" name="department">
                                    <option disabled selected value="">Pilih Jurusan...</option>
                                    <option value="jti">Teknologi Informasi</option>
                                    <option value="te">Teknik Elektro</option>
                                    <option value="tm">Teknik Mesin</option>
                                    <option value="ts">Teknik Sipil</option>
                                    <option value="tk">Teknik Kimia</option>
                                    <option value="ak">Akuntansi</option>
                                    <option value="an">Administrasi Niaga</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-lg">arrow_drop_down</span>
                                </span>
                            </div>
                        </div>

                        <!-- Program Studi -->
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="prodiSelect">
                                Program Studi
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-xl">category</span>
                                </span>
                                <select class="w-full pl-10 pr-9 py-2.5 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed outline-none transition appearance-none"
                                    id="prodiSelect" name="study_program">
                                    <option disabled selected value="">Pilih Program Studi...</option>
                                    <option value="ti">D4 Teknik Informatika</option>
                                    <option value="sib">D4 Sistem Informasi Bisnis</option>
                                    <option value="mi">D3 Manajemen Informatika</option>
                                    <option value="te">D4 Teknik Elektronika</option>
                                    <option value="tt">D3 Teknik Telekomunikasi</option>
                                    <option value="an">D4 Manajemen Pemasaran</option>
                                    <option value="ak">D4 Akuntansi Manajemen</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-lg">arrow_drop_down</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Nomor WhatsApp Aktif -->
                    <div class="space-y-1.5">
                        <label class="block font-label-md text-label-md text-on-surface font-semibold" for="waNumber">
                            Nomor WhatsApp Aktif
                        </label>
                        <div class="flex rounded-xl shadow-xs overflow-hidden border border-outline-variant focus-within:border-primary focus-within:ring-2 focus-within:ring-primary-fixed bg-surface-container-lowest transition">
                            <span class="inline-flex items-center px-3.5 bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold border-r border-outline-variant/60 gap-1.5 select-none">
                                <span>🇮🇩</span>
                                <span>+62</span>
                            </span>
                            <input class="w-full pl-3 pr-4 py-2.5 border-0 bg-transparent text-on-surface placeholder:text-outline font-body-md text-body-md focus:ring-0 outline-none"
                                id="waNumber" name="whatsapp" placeholder="81234567890" required type="tel" />
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 pt-0.5">
                            <span class="material-symbols-outlined text-xs text-secondary">forum</span>
                            Digunakan untuk konfirmasi COD &amp; notifikasi nego pembeli.
                        </p>
                    </div>

                    <!-- 6 & 7. Password & Konfirmasi Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Password -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="passInput">
                                    Password
                                </label>
                                <span class="font-label-sm text-label-sm text-secondary font-semibold">Min 6 Karakter</span>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-xl">lock</span>
                                </span>
                                <input class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed outline-none transition"
                                    id="passInput" name="password" placeholder="Minimal 6 karakter" required type="password" />
                                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-primary transition-colors toggle-password-btn"
                                    data-target="passInput" type="button">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="space-y-1.5">
                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="confirmPass">
                                Ulangi Password
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                    <span class="material-symbols-outlined text-xl">lock_reset</span>
                                </span>
                                <input class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-outline-variant bg-surface-container-lowest text-on-surface font-body-md text-body-md focus:border-primary focus:ring-2 focus:ring-primary-fixed outline-none transition"
                                    id="confirmPass" name="password_confirmation" placeholder="Ulangi kata sandi" required type="password" />
                                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-primary transition-colors toggle-password-btn"
                                    data-target="confirmPass" type="button">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox Persetujuan Syarat -->
                    <div class="pt-1">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input checked class="mt-1 w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant cursor-pointer"
                                required type="checkbox" name="agree_terms" />
                            <span class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                Saya menyetujui <a class="text-primary font-semibold hover:underline" href="#">Syarat &amp; Ketentuan PREVO</a> serta bersedia mematuhi <a class="text-primary font-semibold hover:underline" href="#">Etika Transaksi Kampus Polinema</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Tombol CTA Utama: Daftar Sekarang -->
                    <div class="pt-2">
                        <button class="w-full py-3 px-6 rounded-xl bg-primary hover:bg-primary-container active:scale-[0.99] text-on-primary font-label-lg text-label-lg font-bold flex items-center justify-center gap-2 shadow-sm transition-all duration-150"
                            type="submit">
                            <span>Daftar Sekarang</span>
                            <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </button>
                    </div>

                    <!-- Navigasi Bawah: Sudah punya akun? -->
                    <p class="text-center font-body-md text-body-md text-on-surface-variant pt-2">
                        Sudah punya akun PREVO?
                        <a class="text-primary font-bold hover:underline ml-1" href="login.php">Masuk</a>
                    </p>
                </form>
            </div>
        </section>
    </main>

    <!-- ================= FOOTER PERSISTEN (Shared Blueprint) ================= -->
    <footer class="bg-surface-container-low border-t border-outline-variant">
        <div class="w-full max-w-7xl mx-auto px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <!-- Brand & Hak Cipta -->
            <div class="space-y-1">
                <p class="font-body-sm text-body-sm text-on-surface-variant">
                    © <?= date('Y'); ?> PREVO Polinema. Marketplace Preloved Mahasiswa Terverifikasi Politeknik Negeri Malang.
                </p>
            </div>

            <!-- Links Navigasi Footer -->
            <nav class="flex flex-wrap items-center justify-center gap-y-2 gap-x-6">
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">
                    Panduan COD Kampus
                </a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">
                    Syarat &amp; Ketentuan
                </a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">
                    Kebijakan Privasi
                </a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">
                    Pusat Bantuan Mahasiswa
                </a>
            </nav>
        </div>
    </footer>

<?php
require_once __DIR__ . '/../includes/auth/footer.php';
?>
