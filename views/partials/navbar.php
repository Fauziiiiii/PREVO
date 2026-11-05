<?php
    $user = current_user();
    $searchQuery = $_GET['q'] ?? '';
?>
<header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-40 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            
            <!-- Logo & Campus Badge -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="<?= BASE_URL ?>/barang/index.php" class="flex items-center gap-0.5 text-xl font-extrabold tracking-tight group">
                    <span class="text-white">PRE</span><span class="text-emerald-400 group-hover:text-emerald-300 transition-colors">VO</span>
                </a>
                <!-- <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-950/80 text-emerald-300 border border-emerald-800/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Polinema Verified
                </span> -->
            </div>

            <!-- Search Bar (Desktop) -->
            <form action="<?= BASE_URL ?>/barang/index.php" method="GET" class="hidden md:flex flex-1 max-w-lg mx-2">
                <div class="relative w-full">
                    <input type="text" name="q" value="<?= e($searchQuery) ?>" 
                           placeholder="Cari meja kos, kipas, buku kuliah, kalkulator..." 
                           class="w-full bg-slate-800/90 text-sm text-white placeholder-slate-400 pl-9 pr-4 py-2 rounded-lg border border-slate-700 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </form>

            <!-- Navigation Actions -->
            <div class="flex items-center gap-3">
                
                <?php if (is_logged_in()): ?>
                    
                    <?php if (is_admin()): ?>
                        <!-- Admin Navigation (Desktop) -->
                        <div class="hidden lg:flex items-center gap-2 text-xs font-semibold">
                            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Dashboard SIM</a>
                            <a href="<?= BASE_URL ?>/admin/moderasi.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Moderasi Barang</a>
                            <a href="<?= BASE_URL ?>/admin/kategori.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Kategori</a>
                            <a href="<?= BASE_URL ?>/admin/lokasi.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Titik COD</a>
                            <a href="<?= BASE_URL ?>/admin/laporan.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Laporan</a>
                        </div>
                    <?php else: ?>
                        <!-- Mahasiswa Navigation (Desktop) -->
                        <a href="<?= BASE_URL ?>/barang/tambah.php" class="hidden sm:inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold px-3.5 py-2 rounded-lg shadow-sm transition-all">
                            <span>+</span> Jual Barang
                        </a>

                        <div class="hidden md:flex items-center gap-3 text-xs font-medium text-slate-300">
                            <a href="<?= BASE_URL ?>/barang/kelola.php" class="hover:text-white transition-colors">Barang Saya</a>
                            <a href="<?= BASE_URL ?>/transaksi/riwayat.php" class="hover:text-white transition-colors">Transaksi</a>
                            <a href="<?= BASE_URL ?>/barang/favorit.php" class="hover:text-white transition-colors">Favorit</a>
                        </div>
                    <?php endif; ?>

                    <!-- User Profile Icon / Name -->
                    <div class="relative flex items-center pl-2 border-l border-slate-700">
                        <a href="<?= BASE_URL ?>/auth/profil.php" class="flex items-center gap-2 hover:opacity-90">
                            <div class="w-8 h-8 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center text-xs ring-2 ring-slate-800">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="hidden xl:block text-left text-xs leading-tight">
                                <div class="font-bold text-white truncate max-w-[120px]"><?= e(explode(' ', $user['name'])[0]) ?></div>
                                <div class="text-slate-400 font-normal"><?= e($user['role'] === 'admin' ? 'Administrator' : ($user['nim'] ?? 'Mahasiswa')) ?></div>
                            </div>
                        </a>
                        <a href="<?= BASE_URL ?>/auth/logout.php" title="Keluar Akun" class="hidden sm:block ml-3 text-slate-400 hover:text-rose-400 text-xs font-semibold">
                            Keluar
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Guest Navigation (Desktop) -->
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="<?= BASE_URL ?>/auth/login.php" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                            Masuk
                        </a>
                        <a href="<?= BASE_URL ?>/auth/register.php" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold px-3.5 py-1.5 rounded-lg transition-colors">
                            Daftar Mahasiswa
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Mobile Hamburger Button -->
                <button type="button" onclick="toggleMobileNav()" 
                        class="<?= (is_logged_in() && is_admin()) ? 'lg:hidden' : 'md:hidden' ?> p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500" 
                        aria-label="Toggle navigasi mobile">
                    <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>

        </div>

        <!-- Search Bar (Mobile Only) -->
        <div class="pb-3 md:hidden">
            <form action="<?= BASE_URL ?>/barang/index.php" method="GET">
                <input type="text" name="q" value="<?= e($searchQuery) ?>" 
                       placeholder="Cari perlengkapan kos, buku..." 
                       class="w-full bg-slate-800 text-sm text-white placeholder-slate-400 px-3.5 py-2 rounded-lg border border-slate-700 focus:outline-none focus:border-emerald-500">
            </form>
        </div>

        <!-- Mobile Drawer Menu (Collapsible) -->
        <div id="mobileNavMenu" class="hidden <?= (is_logged_in() && is_admin()) ? 'lg:hidden' : 'md:hidden' ?> pb-4 pt-2 border-t border-slate-800">
            <div class="flex flex-col space-y-1 text-sm font-medium text-slate-300">
                <?php if (is_logged_in()): ?>
                    <?php if (is_admin()): ?>
                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menu Administrator</div>
                        <a href="<?= BASE_URL ?>/admin/dashboard.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>📊</span> Dashboard SIM
                        </a>
                        <a href="<?= BASE_URL ?>/admin/moderasi.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>🛡️</span> Moderasi Barang
                        </a>
                        <a href="<?= BASE_URL ?>/admin/kategori.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>🏷️</span> Manajemen Kategori
                        </a>
                        <a href="<?= BASE_URL ?>/admin/lokasi.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>📍</span> Titik COD Kampus
                        </a>
                        <a href="<?= BASE_URL ?>/admin/laporan.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>🚨</span> Laporan Pelanggaran
                        </a>
                    <?php else: ?>
                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas Mahasiswa</div>
                        <a href="<?= BASE_URL ?>/barang/tambah.php" class="px-3 py-2 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-500 flex items-center gap-2">
                            <span>➕</span> Pasang Iklan Barang
                        </a>
                        <a href="<?= BASE_URL ?>/barang/kelola.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>📦</span> Barang Saya
                        </a>
                        <a href="<?= BASE_URL ?>/transaksi/riwayat.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>🤝</span> Riwayat Transaksi COD
                        </a>
                        <a href="<?= BASE_URL ?>/barang/favorit.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>❤️</span> Barang Favorit
                        </a>
                        <a href="<?= BASE_URL ?>/auth/profil.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                            <span>👤</span> Profil Saya
                        </a>
                    <?php endif; ?>

                    <div class="pt-2 border-t border-slate-800 mt-2">
                        <a href="<?= BASE_URL ?>/auth/logout.php" class="px-3 py-2 rounded-lg text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 flex items-center gap-2 font-semibold">
                            <span>🚪</span> Keluar Akun
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/barang/index.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                        <span>🛍️</span> Jelajahi Katalog
                    </a>
                    <a href="<?= BASE_URL ?>/auth/login.php" class="px-3 py-2 rounded-lg hover:bg-slate-800 hover:text-white flex items-center gap-2">
                        <span>🔑</span> Masuk Akun
                    </a>
                    <a href="<?= BASE_URL ?>/auth/register.php" class="px-3 py-2 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-500 flex items-center gap-2">
                        <span>🎓</span> Daftar Akun Mahasiswa
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
function toggleMobileNav() {
    const menu = document.getElementById('mobileNavMenu');
    const burger = document.getElementById('hamburgerIcon');
    const close = document.getElementById('closeIcon');
    if (!menu) return;

    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        if (burger) burger.classList.add('hidden');
        if (close) close.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
        if (burger) burger.classList.remove('hidden');
        if (close) close.classList.add('hidden');
    }
}
</script>
