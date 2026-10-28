<?php
    $user = current_user();
    $searchQuery = $_GET['q'] ?? '';
?>
<header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-40 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            
            <!-- Logo & Campus Badge -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="<?= BASE_URL ?>/barang/index.php" class="flex items-center gap-1.5 text-xl font-extrabold tracking-tight group">
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
                        <!-- Admin Navigation -->
                        <div class="hidden lg:flex items-center gap-2 text-xs font-semibold">
                            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Dashboard SIM</a>
                            <a href="<?= BASE_URL ?>/admin/moderasi.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Moderasi Barang</a>
                            <a href="<?= BASE_URL ?>/admin/kategori.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Kategori</a>
                            <a href="<?= BASE_URL ?>/admin/lokasi.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Titik COD</a>
                            <a href="<?= BASE_URL ?>/admin/laporan.php" class="px-3 py-1.5 rounded-md hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">Laporan</a>
                        </div>
                    <?php else: ?>
                        <!-- Mahasiswa Navigation -->
                        <a href="<?= BASE_URL ?>/barang/tambah.php" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold px-3.5 py-2 rounded-lg shadow-sm transition-all">
                            <span>+</span> Jual Barang
                        </a>

                        <div class="hidden md:flex items-center gap-3 text-xs font-medium text-slate-300">
                            <a href="<?= BASE_URL ?>/barang/kelola.php" class="hover:text-white transition-colors">Barang Saya</a>
                            <a href="<?= BASE_URL ?>/transaksi/riwayat.php" class="hover:text-white transition-colors">Transaksi</a>
                            <a href="<?= BASE_URL ?>/barang/favorit.php" class="hover:text-white transition-colors">Favorit</a>
                        </div>
                    <?php endif; ?>

                    <!-- User Dropdown Menu -->
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
                        <a href="<?= BASE_URL ?>/auth/logout.php" title="Keluar Akun" class="ml-3 text-slate-400 hover:text-rose-400 text-xs font-semibold">
                            Keluar
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Guest Navigation -->
                    <div class="flex items-center gap-2">
                        <a href="<?= BASE_URL ?>/auth/login.php" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                            Masuk
                        </a>
                        <a href="<?= BASE_URL ?>/auth/register.php" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold px-3.5 py-1.5 rounded-lg transition-colors">
                            Daftar Mahasiswa
                        </a>
                    </div>
                <?php endif; ?>

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
    </div>
</header>
