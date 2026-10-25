-- ============================================================
--  PREVO - Data Uji (Dummy Data) untuk Testing
--  Target Engine: PostgreSQL 15+
--  Jalankan SETELAH prevo_schema.sql
-- ============================================================

-- Bersihkan semua tabel dan reset ID sequence ke 1
TRUNCATE TABLE 
    reports, 
    reviews, 
    transactions, 
    favorites, 
    items, 
    categories, 
    users 
RESTART IDENTITY CASCADE;

-- ============================================================
--  1. USERS
--  Password untuk SEMUA akun di bawah ini: Test@12345
--  Hash valid bcrypt ($2b$12$):
--  $2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq
-- ============================================================

INSERT INTO users (nim, email, name, password, jurusan, prodi, phone, whatsapp, instagram, role, is_active, email_verified_at, whatsapp_verified_at) VALUES
    -- Admin (ID: 1) - Jurusan & Prodi NULL
    ('ADMIN001', 'admin@prevo.polinema.ac.id', 'Administrator PREVO',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', NULL, NULL,
     '081234000000', '6281234000000', 'prevo_admin', 'admin', TRUE, NOW(), NOW()),

    -- Mahasiswa (ID: 2 - 7)
    ('254107020219', 'reyhandhika@student.polinema.ac.id', 'Reyhandhika Zikri Prijadi',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Teknologi Informasi', 'D4 Teknik Informatika',
     '081234000001', '6281234000001', 'reyhandhika_z', 'mahasiswa', TRUE, NOW(), NOW()),

    ('254107020085', 'fauzi@student.polinema.ac.id', 'Muhammad Fauzi Fadillah',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Teknologi Informasi', 'D4 Teknik Informatika',
     '081234000002', '6281234000002', 'fauzi_fade', 'mahasiswa', TRUE, NOW(), NOW()),

    ('254107020047', 'vito@student.polinema.ac.id', 'L.G Adama Vito',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Teknologi Informasi', 'D4 Teknik Informatika',
     '081234000003', '6281234000003', 'adama_vito', 'mahasiswa', TRUE, NOW(), NOW()),

    ('254107020130', 'adit@student.polinema.ac.id', 'Achmad Pradita Dwi Firmansyah',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Teknologi Informasi', 'D4 Teknik Informatika',
     '081234000004', '6281234000004', 'adit_pradita', 'mahasiswa', TRUE, NOW(), NOW()),

    ('244107020001', 'buyer1@student.polinema.ac.id', 'Budi Santoso',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Teknik Mesin', 'D4 Teknik Mesin Produksi dan Perawatan',
     '081234000005', '6281234000005', 'budi_santoso', 'mahasiswa', TRUE, NOW(), NOW()),

    ('244107020002', 'buyer2@student.polinema.ac.id', 'Siti Rahmawati',
     '$2y$10$eIIqt4blDWvW99kMUkGV5uCeHgZeAwqpoKlkjyJHV6jJEHfpyLuOq', 'Akuntansi', 'D4 Akuntansi Manajemen',
     '081234000006', '6281234000006', 'siti_rahma', 'mahasiswa', TRUE, NOW(), NOW());

-- ============================================================
--  2. CATEGORIES (ID: 1 - 7)
-- ============================================================

INSERT INTO categories (name, description, icon) VALUES
    ('Elektronik',          'Laptop, HP, aksesoris, charger, dll.',         'devices'),
    ('Buku & Alat Tulis',   'Buku kuliah, modul, ATK, kalkulator',          'menu_book'),
    ('Perabot Kos',         'Kipas angin, meja belajar, kursi, rak',        'single_bed'),
    ('Fashion',             'Pakaian, sepatu, tas, aksesoris fashion',       'checkroom'),
    ('Alat Masak & Dapur',  'Rice cooker, kompor, peralatan dapur',         'soup_kitchen'),
    ('Olahraga & Hobi',     'Peralatan olahraga, alat musik, dll.',         'sports_soccer'),
    ('Lain-lain',           'Barang yang tidak masuk kategori di atas',     'more_horiz');

-- ============================================================
--  3. ITEMS (ID: 1 - 12)
--  Mencakup variasi status: tersedia, booking, terjual,
--  menunggu_persetujuan (antrean admin), dan ditolak.
-- ============================================================

INSERT INTO items (
    seller_id, category_id, title, description, price, condition, 
    status, rejection_reason, approved_by, approved_at, location_info
) VALUES
    -- Seller: Reyhandhika (id=2) — Status: tersedia (disetujui admin)
    (2, 1, 'Laptop ASUS VivoBook 14 Core i5',
     'Laptop kuliah kondisi mulus, baterai masih bagus 4 jam, sudah diupgrade RAM 16GB. Jual karena upgrade laptop baru.',
     3500000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '3 days', 'Kos area Soekarno Hatta'),

    (2, 2, 'Buku Algoritma & Pemrograman edisi 4',
     'Buku kuliah algoritma masih lengkap, ada beberapa coretan pensil. Edisi terbaru 2023.',
     55000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '2 days', 'Kampus Polinema Blimbing'),

    -- Seller: Fauzi (id=3) — Status: tersedia
    (3, 3, 'Kipas Angin Cosmos 16 inch',
     'Kipas angin masih berfungsi normal, sedikit kotor di kisi-kisi tapi mudah dibersihkan. Jual karena pindah kos ber-AC.',
     120000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '4 days', 'Kos area Veteran'),

    (3, 4, 'Jaket Hoodie Polinema size L',
     'Jaket hoodie Polinema official, kondisi 90% masih bagus, hanya dipakai 3x. Warna navy.',
     85000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '3 days', 'Kos area Soekarno Hatta'),

    -- Seller: Vito (id=4) — Status: tersedia
    (4, 1, 'Earphone JBL T110 Original',
     'Earphone JBL original beli di iBox, masih ada dus dan garansi. Jual karena beralih ke TWS.',
     130000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '1 day', 'Kampus Polinema Blimbing'),

    (4, 3, 'Meja Belajar Lipat Kayu',
     'Meja belajar lipat portable, cocok untuk kos sempit. Kayu solid, permukaan bersih. Ukuran 60x40cm.',
     90000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '2 days', 'Kos area Veteran'),

    -- Seller: Adit (id=5) — Status: tersedia
    (5, 1, 'Keyboard Mechanical Rexus MX5.0',
     'Keyboard mechanical switch blue, kondisi baik. Ada beberapa keycap yang agak pudar tapi semua fungsi normal.',
     280000, 'bekas_baik', 'tersedia', NULL, 1, NOW() - INTERVAL '5 days', 'Kos area Sigura-gura'),

    -- Seller: Adit (id=5) — Status: terjual (sukses COD)
    (5, 5, 'Rice Cooker Miyako 1.8L',
     'Rice cooker masih berfungsi normal, inner pot masih bagus. Jual karena pindah ke kos dengan fasilitas masak.',
     150000, 'bekas_baik', 'terjual', NULL, 1, NOW() - INTERVAL '6 days', 'Kos area Sigura-gura'),

    -- Seller: Reyhandhika (id=2) — Status: booking (sedang dalam proses transaksi COD)
    (2, 2, 'Kalkulator Scientific Casio FX-991EX',
     'Kalkulator Casio Classwiz original, kondisi 95%, semua tombol berfungsi. Masih ada dus.',
     180000, 'bekas_baik', 'booking', NULL, 1, NOW() - INTERVAL '3 days', 'Kampus Polinema Blimbing'),

    -- Seller: Fauzi (id=3) — Status: menunggu_persetujuan (Data uji untuk Approval Queue Admin)
    (3, 3, 'Lampu Belajar LED Philips Fleksibel',
     'Lampu belajar meja dengan leher fleksibel, port USB charger. Baru dipakai 2 bulan.',
     45000, 'bekas_baik', 'menunggu_persetujuan', NULL, NULL, NULL, 'Kos area Veteran'),

    -- Seller: Vito (id=4) — Status: menunggu_persetujuan (Data uji untuk Approval Queue Admin)
    (4, 4, 'Sepatu Kets Ventela White Original Size 42',
     'Sepatu Ventela original masih baru dalam dus, beli online ternyata kebesaran. Size 42.',
     320000, 'baru', 'menunggu_persetujuan', NULL, NULL, NULL, 'Kampus Polinema Blimbing'),

    -- Seller: Adit (id=5) — Status: ditolak (Data uji barang yang ditolak admin dengan alasan)
    (5, 6, 'Raket Badminton Yonex Nanoray 20',
     'Raket badminton minus grip agak terkelupas. Dijual murah apa adanya.',
     200000, 'bekas_baik', 'ditolak', 
     'Foto buram dan tidak memperlihatkan kondisi fisik frame raket dengan jelas. Mohon unggah ulang dengan pencahayaan yang cukup.',
     1, NOW() - INTERVAL '1 day', 'Kos area Sigura-gura');

-- ============================================================
--  4. FAVORITES
-- ============================================================

INSERT INTO favorites (user_id, item_id) VALUES
    (6, 1),  -- Budi simpan Laptop ASUS
    (6, 5),  -- Budi simpan Earphone JBL
    (7, 3),  -- Siti simpan Kipas Angin
    (7, 6),  -- Siti simpan Meja Belajar
    (3, 7),  -- Fauzi simpan Keyboard Rexus
    (4, 2);  -- Vito simpan Buku Algoritma

-- ============================================================
--  5. TRANSACTIONS
-- ============================================================

-- Transaksi 1: Rice Cooker (item_id=8), status selesai
INSERT INTO transactions (item_id, buyer_id, seller_id, status, agreed_price, meetup_location, meetup_time, completed_at)
VALUES (
    8,
    6,  -- buyer: Budi
    5,  -- seller: Adit
    'selesai',
    140000,
    'Kantin Graha Polinema',
    NOW() - INTERVAL '5 days',
    NOW() - INTERVAL '5 days'
);

-- Transaksi 2: Kalkulator Casio (item_id=9), status pending (booking aktif)
INSERT INTO transactions (item_id, buyer_id, seller_id, status, agreed_price, meetup_location, meetup_time, expires_at)
VALUES (
    9,
    7,  -- buyer: Siti
    2,  -- seller: Reyhandhika
    'pending',
    170000,
    'Perpustakaan Pusat Polinema',
    NOW() + INTERVAL '1 day',
    NOW() + INTERVAL '1 day'
);

-- ============================================================
--  6. REVIEWS
-- ============================================================

INSERT INTO reviews (transaction_id, reviewer_id, seller_id, rating, comment)
VALUES (
    1,
    6,  -- reviewer: Budi
    5,  -- seller: Adit
    5,
    'Barang sesuai deskripsi, penjual ramah dan fast response. COD lancar di kantin. Sangat recommended!'
);

-- ============================================================
--  7. REPORTS
-- ============================================================

INSERT INTO reports (reporter_id, item_id, reason, description, status)
VALUES (
    6,  -- reporter: Budi
    4,  -- item: Jaket Hoodie
    'Foto tidak sesuai kondisi asli',
    'Kondisi barang di foto terlihat sangat bagus tapi di deskripsi diakui hanya 90%. Perlu konfirmasi penjual.',
    'pending'
);

-- ============================================================
--  VERIFIKASI DATA SEED
-- ============================================================

SELECT '=== RINGKASAN DATA SEED PREVO ===' AS status;

SELECT 'Users'        AS tabel, COUNT(*) AS total FROM users
UNION ALL
SELECT 'Categories',   COUNT(*) FROM categories
UNION ALL
SELECT 'Items',        COUNT(*) FROM items
UNION ALL
SELECT 'Favorites',    COUNT(*) FROM favorites
UNION ALL
SELECT 'Transactions', COUNT(*) FROM transactions
UNION ALL
SELECT 'Reviews',      COUNT(*) FROM reviews
UNION ALL
SELECT 'Reports',      COUNT(*) FROM reports;
