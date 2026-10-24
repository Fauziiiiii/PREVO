-- ============================================================
--  PREVO - PREloved Value & Opportunity
--  Database Schema untuk PostgreSQL (v2 - Production-Ready)
--  Kelompok 3 - PBL Semester 3 TI 2G
--  Target Engine: PostgreSQL 15+
-- ============================================================

-- Hapus tabel jika sudah ada (urutan terbalik karena ketergantungan FK)
DROP TABLE IF EXISTS reports CASCADE;
DROP TABLE IF EXISTS reviews CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS favorites CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ============================================================
--  ENUM TYPES
-- ============================================================

DO $$ BEGIN
    CREATE TYPE user_role AS ENUM ('mahasiswa', 'admin');
EXCEPTION WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE item_condition AS ENUM ('baru', 'bekas_baik', 'bekas_rusak');
EXCEPTION WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE item_status AS ENUM ('menunggu_persetujuan', 'tersedia', 'booking', 'terjual', 'ditolak', 'dihapus');
EXCEPTION WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE transaction_status AS ENUM ('pending', 'selesai', 'dibatalkan');
EXCEPTION WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE report_status AS ENUM ('pending', 'ditangani', 'ditolak');
EXCEPTION WHEN duplicate_object THEN null;
END $$;

-- ============================================================
--  TABEL 1: USERS
--  Menampung mahasiswa & admin dalam 1 tabel (role-based)
-- ============================================================

CREATE TABLE users (
    id            SERIAL PRIMARY KEY,
    nim           VARCHAR(20)  UNIQUE NOT NULL,
    email         VARCHAR(100) UNIQUE NOT NULL,
    name          VARCHAR(100) NOT NULL,
    password      VARCHAR(255) NOT NULL,
    jurusan       VARCHAR(100),
    prodi         VARCHAR(100),
    phone         VARCHAR(20),
    whatsapp      VARCHAR(20),
    instagram     VARCHAR(50),
    telegram      VARCHAR(50),
    role          user_role    NOT NULL DEFAULT 'mahasiswa',
    is_active     BOOLEAN      NOT NULL DEFAULT TRUE,
    avatar_url    VARCHAR(255),
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

COMMENT ON TABLE  users IS 'Tabel pengguna: mahasiswa dan admin dalam 1 tabel dengan role-based access';
COMMENT ON COLUMN users.nim IS 'NIM kampus sebagai identitas unik mahasiswa';
COMMENT ON COLUMN users.jurusan IS 'Jurusan mahasiswa di Polinema (misal: Teknologi Informasi) — NULL untuk admin';
COMMENT ON COLUMN users.prodi IS 'Program studi mahasiswa di Polinema (misal: D4 Teknik Informatika) — NULL untuk admin';
COMMENT ON COLUMN users.whatsapp IS 'Nomor WA untuk tombol "Hubungi Penjual" → direct link WA';
COMMENT ON COLUMN users.role IS 'mahasiswa = pengguna biasa, admin = moderator platform';

-- ============================================================
--  TABEL 2: CATEGORIES
--  Kategori barang (dikelola admin)
-- ============================================================

CREATE TABLE categories (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(50)  UNIQUE NOT NULL,
    slug        VARCHAR(100) NULL,
    description TEXT,
    icon        VARCHAR(50),
    created_at  TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

COMMENT ON TABLE categories IS 'Kategori barang — hanya admin yang bisa CRUD (enforced di aplikasi)';

-- ============================================================
--  TABEL 3: ITEMS
--  Listing barang yang dijual mahasiswa
-- ============================================================

CREATE TABLE items (
    id            SERIAL PRIMARY KEY,
    seller_id     INT            NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    category_id   INT            NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    title         VARCHAR(150)   NOT NULL,
    description   TEXT           NOT NULL,
    price         DECIMAL(12,2)  NOT NULL CHECK (price >= 0),
    condition     item_condition NOT NULL,
    status           item_status    NOT NULL DEFAULT 'menunggu_persetujuan',
    rejection_reason TEXT,
    approved_by      INT            REFERENCES users(id) ON DELETE SET NULL,
    approved_at      TIMESTAMPTZ,
    image_url     VARCHAR(255),
    location_info VARCHAR(100),
    created_at    TIMESTAMPTZ    NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMPTZ    NOT NULL DEFAULT NOW()
);

COMMENT ON TABLE  items IS 'Listing barang preloved yang diposting oleh mahasiswa (penjual)';
COMMENT ON COLUMN items.seller_id IS 'FK ke users — pemilik listing';
COMMENT ON COLUMN items.status IS 'menunggu_persetujuan=antrean moderasi admin, tersedia=aktif/lolos kurasi, booking=proses transaksi, terjual=sukses COD, ditolak=tidak memenuhi syarat, dihapus=soft delete';
COMMENT ON COLUMN items.rejection_reason IS 'Alasan penolakan listing oleh admin jika status ditolak';
COMMENT ON COLUMN items.approved_by IS 'FK ke users (role admin) yang menyetujui listing barang';
COMMENT ON COLUMN items.price IS 'Harga penawaran awal dalam Rupiah';

-- ============================================================
--  TABEL 4: FAVORITES
--  Barang yang di-bookmark pengguna (N-N antara users & items)
-- ============================================================

CREATE TABLE favorites (
    id          SERIAL PRIMARY KEY,
    user_id     INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    item_id     INT NOT NULL REFERENCES items(id) ON DELETE CASCADE,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_favorites UNIQUE (user_id, item_id)
);

COMMENT ON TABLE favorites IS 'Barang yang disimpan/di-bookmark pengguna';

-- ============================================================
--  TABEL 5: TRANSACTIONS
--  Pencatatan transaksi COD antar mahasiswa
-- ============================================================

CREATE TABLE transactions (
    id               SERIAL PRIMARY KEY,
    item_id          INT NOT NULL REFERENCES items(id) ON DELETE RESTRICT,
    buyer_id         INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    seller_id        INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    status           transaction_status NOT NULL DEFAULT 'pending',
    agreed_price     DECIMAL(12,2) CHECK (agreed_price >= 0),
    meetup_location  VARCHAR(150),
    meetup_time      TIMESTAMPTZ,
    expires_at       TIMESTAMPTZ,
    completed_at     TIMESTAMPTZ,
    created_at       TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at       TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    -- Pembeli tidak boleh sama dengan penjual
    CONSTRAINT chk_buyer_not_seller CHECK (buyer_id <> seller_id)
);

COMMENT ON TABLE  transactions IS 'Pencatatan transaksi COD antar-mahasiswa';
COMMENT ON COLUMN transactions.expires_at IS 'Batas waktu validitas booking sebelum otomatis kedaluwarsa';
COMMENT ON COLUMN transactions.agreed_price IS 'Harga final kesepakatan setelah negosiasi';
COMMENT ON COLUMN transactions.status IS 'pending=proses, selesai=COD berhasil, dibatalkan=batal/expired';

-- Partial Unique Index: Mencegah double active booking / multiple sales untuk satu item,
-- tetapi mengizinkan riwayat transaksi yang 'dibatalkan'.
CREATE UNIQUE INDEX uq_active_item_transaction 
ON transactions(item_id) 
WHERE status IN ('pending', 'selesai');

-- ============================================================
--  TABEL 6: REVIEWS
--  Rating & ulasan dari pembeli ke penjual setelah COD
-- ============================================================

CREATE TABLE reviews (
    id             SERIAL PRIMARY KEY,
    transaction_id INT NOT NULL REFERENCES transactions(id) ON DELETE CASCADE,
    reviewer_id    INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    seller_id      INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    rating         SMALLINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment        TEXT,
    created_at     TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    -- Satu transaksi hanya menghasilkan satu ulasan
    CONSTRAINT uq_review_per_transaction UNIQUE (transaction_id),

    -- Pengulas tidak boleh sama dengan yang diulas
    CONSTRAINT chk_reviewer_not_seller CHECK (reviewer_id <> seller_id)
);

COMMENT ON TABLE  reviews IS 'Ulasan & rating dari pembeli ke penjual setelah transaksi selesai';
COMMENT ON COLUMN reviews.reviewer_id IS 'Pembeli yang memberikan ulasan';
COMMENT ON COLUMN reviews.seller_id IS 'Penjual yang mendapatkan ulasan';
COMMENT ON COLUMN reviews.rating IS 'Skala 1-5 bintang';

-- ============================================================
--  TABEL 7: REPORTS
--  Laporan barang/pengguna yang melanggar aturan
-- ============================================================

CREATE TABLE reports (
    id           SERIAL PRIMARY KEY,
    reporter_id  INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    item_id      INT NOT NULL REFERENCES items(id)  ON DELETE CASCADE,
    reason       VARCHAR(100) NOT NULL,
    description  TEXT,
    status       report_status NOT NULL DEFAULT 'pending',
    handled_by   INT REFERENCES users(id) ON DELETE SET NULL,
    admin_note   TEXT,
    created_at   TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    handled_at   TIMESTAMPTZ,
    updated_at   TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    -- Satu pengguna hanya bisa melaporkan 1 barang 1x
    CONSTRAINT uq_report UNIQUE (reporter_id, item_id)
);

COMMENT ON TABLE  reports IS 'Laporan pelanggaran listing barang';
COMMENT ON COLUMN reports.handled_by IS 'FK ke users (role=admin) yang menangani laporan — NULL jika belum diverifikasi';

-- ============================================================
--  INDEXES (untuk performa query umum)
-- ============================================================

-- Items: pencarian dan filter
CREATE INDEX idx_items_seller_id    ON items(seller_id);
CREATE INDEX idx_items_category_id  ON items(category_id);
CREATE INDEX idx_items_status       ON items(status);
CREATE INDEX idx_items_condition    ON items(condition);
CREATE INDEX idx_items_price        ON items(price);
CREATE INDEX idx_items_created_at   ON items(created_at DESC);

-- Full-text search untuk judul & deskripsi barang (menggunakan konfigurasi 'simple' agar portabel)
CREATE INDEX idx_items_fts ON items
    USING GIN (to_tsvector('simple', title || ' ' || description));

-- Favorites
CREATE INDEX idx_favorites_user_id  ON favorites(user_id);
CREATE INDEX idx_favorites_item_id  ON favorites(item_id);

-- Transactions
CREATE INDEX idx_transactions_buyer_id   ON transactions(buyer_id);
CREATE INDEX idx_transactions_seller_id  ON transactions(seller_id);
CREATE INDEX idx_transactions_status     ON transactions(status);
CREATE INDEX idx_transactions_expires_at ON transactions(expires_at) WHERE status = 'pending';

-- Reviews
CREATE INDEX idx_reviews_seller_id ON reviews(seller_id);

-- Reports
CREATE INDEX idx_reports_status     ON reports(status);
CREATE INDEX idx_reports_handled_by ON reports(handled_by);

-- ============================================================
--  TRIGGERS: Auto-update updated_at & Sinkronisasi Status Item
-- ============================================================

-- Function: update kolom updated_at otomatis
CREATE OR REPLACE FUNCTION trigger_set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER set_updated_at_users
    BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION trigger_set_updated_at();

CREATE TRIGGER set_updated_at_items
    BEFORE UPDATE ON items
    FOR EACH ROW EXECUTE FUNCTION trigger_set_updated_at();

CREATE TRIGGER set_updated_at_transactions
    BEFORE UPDATE ON transactions
    FOR EACH ROW EXECUTE FUNCTION trigger_set_updated_at();

CREATE TRIGGER set_updated_at_reports
    BEFORE UPDATE ON reports
    FOR EACH ROW EXECUTE FUNCTION trigger_set_updated_at();

-- Function: Sinkronisasi status items berdasarkan perubahan transactions
CREATE OR REPLACE FUNCTION trigger_sync_item_status()
RETURNS TRIGGER AS $$
BEGIN
    -- Sinkronisasi hanya berjalan bila status transaksi berubah
    IF (OLD.status IS DISTINCT FROM NEW.status) THEN
        IF NEW.status = 'selesai' THEN
            UPDATE items
            SET status = 'terjual'
            WHERE id = NEW.item_id;
        ELSIF NEW.status = 'dibatalkan' THEN
            UPDATE items
            SET status = 'tersedia'
            WHERE id = NEW.item_id
                AND status = 'booking';   -- hanya kalau sebelumnya memang 'booking'
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER sync_item_status_after_transaction_update
    AFTER UPDATE ON transactions
    FOR EACH ROW
    EXECUTE FUNCTION trigger_sync_item_status();

COMMENT ON FUNCTION trigger_sync_item_status IS
    'Trigger: Otomatis sinkron status item (terjual/tersedia) saat status transaksi berubah (selesai/dibatalkan)';

-- ============================================================
--  STORED PROCEDURES: Operasi Bisnis Transaksional
-- ============================================================

-- 1. Create Booking (Atomic & Bebas Race Condition)
CREATE OR REPLACE PROCEDURE create_booking(
    p_item_id      INT,
    p_buyer_id     INT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_seller_id    INT;
    v_status       item_status;
    v_price        DECIMAL(12,2);
BEGIN
    -- Kunci baris item untuk mencegah race condition (SELECT ... FOR UPDATE)
    SELECT seller_id, status, price
    INTO v_seller_id, v_status, v_price
    FROM items
    WHERE id = p_item_id
    FOR UPDATE;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Barang dengan ID % tidak ditemukan.', p_item_id;
    END IF;

    -- Validasi status ketersediaan barang
    IF v_status <> 'tersedia' THEN
        RAISE EXCEPTION 'Barang tidak tersedia untuk dibooking (status saat ini: %).', v_status;
    END IF;

    -- Validasi pembeli tidak boleh pemilik barang
    IF p_buyer_id = v_seller_id THEN
        RAISE EXCEPTION 'Penjual tidak dapat membooking barang miliknya sendiri.';
    END IF;

    -- Insert transaksi baru (status 'pending', harga awal = harga listing, aktif selama 24 jam)
    INSERT INTO transactions (
        item_id,
        buyer_id,
        seller_id,
        status,
        agreed_price,
        expires_at
    ) VALUES (
        p_item_id,
        p_buyer_id,
        v_seller_id,
        'pending',
        v_price,
        NOW() + INTERVAL '1 day'
    );

    -- Update status barang menjadi 'booking'
    UPDATE items
    SET status = 'booking'
    WHERE id = p_item_id;

    RAISE NOTICE 'Booking berhasil dibuat untuk item ID % oleh pembeli ID % (Harga awal: %).', p_item_id, p_buyer_id, v_price;
END;
$$;

COMMENT ON PROCEDURE create_booking IS
    'Membuat transaksi booking secara atomic dengan row-level lock; harga awal mengambil harga listing barang';

-- 2. Update Booking Deal (Penjual memperbarui kesepakatan harga / lokasi / waktu COD setelah nego WA)
CREATE OR REPLACE PROCEDURE update_booking_deal(
    p_transaction_id  INT,
    p_seller_id       INT,
    p_agreed_price    DECIMAL(12,2),
    p_meetup_location VARCHAR(150) DEFAULT NULL,
    p_meetup_time     TIMESTAMPTZ  DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_seller_id INT;
    v_status    transaction_status;
BEGIN
    -- Validasi nominal harga kesepakatan
    IF p_agreed_price IS NULL OR p_agreed_price < 0 THEN
        RAISE EXCEPTION 'Harga kesepakatan tidak valid.';
    END IF;

    -- Ambil data transaksi
    SELECT seller_id, status
    INTO v_seller_id, v_status
    FROM transactions
    WHERE id = p_transaction_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Transaksi dengan ID % tidak ditemukan.', p_transaction_id;
    END IF;

    -- Validasi otorisasi: hanya penjual yang berhak mengubah kesepakatan transaksi
    IF p_seller_id <> v_seller_id THEN
        RAISE EXCEPTION 'Hanya penjual pemilik barang yang dapat memperbarui kesepakatan transaksi ini.';
    END IF;

    -- Validasi status transaksi
    IF v_status <> 'pending' THEN
        RAISE EXCEPTION 'Hanya transaksi berstatus pending yang dapat diperbarui (status saat ini: %).', v_status;
    END IF;

    -- Update detail transaksi
    UPDATE transactions
    SET agreed_price    = p_agreed_price,
        meetup_location = COALESCE(p_meetup_location, meetup_location),
        meetup_time     = COALESCE(p_meetup_time, meetup_time)
    WHERE id = p_transaction_id;

    RAISE NOTICE 'Transaksi % berhasil diperbarui: harga kesepakatan menjadi %.', p_transaction_id, p_agreed_price;
END;
$$;

COMMENT ON PROCEDURE update_booking_deal IS
    'Penjual memperbarui harga kesepakatan (hasil nego WA) dan informasi meetup COD';

-- 3. Cancel Transaction (Dapat dibatalkan oleh Buyer maupun Seller jika nego gagal/batal)
CREATE OR REPLACE PROCEDURE cancel_transaction(
    p_transaction_id INT,
    p_actor_id       INT,
    p_reason         TEXT DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_buyer_id   INT;
    v_seller_id  INT;
    v_status     transaction_status;
BEGIN
    -- Ambil data transaksi
    SELECT buyer_id, seller_id, status
    INTO v_buyer_id, v_seller_id, v_status
    FROM transactions
    WHERE id = p_transaction_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Transaksi dengan ID % tidak ditemukan.', p_transaction_id;
    END IF;

    -- Validasi otorisasi pemanggil
    IF p_actor_id <> v_buyer_id AND p_actor_id <> v_seller_id THEN
        RAISE EXCEPTION 'Anda tidak memiliki hak akses untuk membatalkan transaksi ini.';
    END IF;

    -- Validasi status transaksi
    IF v_status <> 'pending' THEN
        RAISE EXCEPTION 'Hanya transaksi berstatus pending yang dapat dibatalkan (status saat ini: %).', v_status;
    END IF;

    -- Update transaksi ke 'dibatalkan'
    -- (Catatan: items.status otomatis dikembalikan ke 'tersedia' oleh trigger sync_item_status)
    UPDATE transactions
    SET status = 'dibatalkan'
    WHERE id = p_transaction_id;

    RAISE NOTICE 'Transaksi % berhasil dibatalkan oleh user ID %. Catatan: %',
        p_transaction_id, p_actor_id, COALESCE(p_reason, 'Tanpa keterangan');
END;
$$;

COMMENT ON PROCEDURE cancel_transaction IS
    'Membatalkan transaksi pending oleh pembeli/penjual; status item otomatis kembali tersedia via trigger';

-- 4. Complete Transaction (Dapat dipanggil oleh Buyer maupun Seller)
CREATE OR REPLACE PROCEDURE complete_transaction(
    p_transaction_id INT,
    p_actor_id       INT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_buyer_id   INT;
    v_seller_id  INT;
    v_status     transaction_status;
BEGIN
    -- Ambil data transaksi
    SELECT buyer_id, seller_id, status
    INTO v_buyer_id, v_seller_id, v_status
    FROM transactions
    WHERE id = p_transaction_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Transaksi dengan ID % tidak ditemukan.', p_transaction_id;
    END IF;

    -- Validasi otorisasi pemanggil
    IF p_actor_id <> v_buyer_id AND p_actor_id <> v_seller_id THEN
        RAISE EXCEPTION 'Anda tidak memiliki hak akses untuk menyelesaikan transaksi ini.';
    END IF;

    -- Validasi status transaksi
    IF v_status <> 'pending' THEN
        RAISE EXCEPTION 'Hanya transaksi berstatus pending yang dapat diselesaikan (status saat ini: %).', v_status;
    END IF;

    -- Update transaksi ke 'selesai'
    -- (Catatan: items.status otomatis diubah ke 'terjual' oleh trigger sync_item_status)
    UPDATE transactions
    SET status = 'selesai',
        completed_at = NOW()
    WHERE id = p_transaction_id;

    RAISE NOTICE 'Transaksi % berhasil diselesaikan oleh user ID %.', p_transaction_id, p_actor_id;
END;
$$;

COMMENT ON PROCEDURE complete_transaction IS
    'Konfirmasi COD selesai: dapat dieksekusi buyer atau seller; status item otomatis terjual via trigger';

-- 5. Release Expired Bookings (Rilis barang yang booking-nya kedaluwarsa)
CREATE OR REPLACE PROCEDURE release_expired_bookings()
LANGUAGE plpgsql
AS $$
DECLARE
    v_count INT;
BEGIN
    -- Update semua transaksi pending yang sudah melewati batas waktu menjadi 'dibatalkan'
    -- (Trigger sync_item_status otomatis mengembalikan status item menjadi 'tersedia')
    WITH expired AS (
        UPDATE transactions
        SET status = 'dibatalkan'
        WHERE status = 'pending'
          AND expires_at < NOW()
        RETURNING id
    )
    SELECT COUNT(*) INTO v_count FROM expired;

    RAISE NOTICE 'Sebanyak % transaksi kedaluwarsa berhasil dibatalkan dan barang dirilis.', v_count;
END;
$$;

COMMENT ON PROCEDURE release_expired_bookings IS
    'Membatalkan transaksi pending yang melewati batas expires_at dan merilis item kembali ke tersedia';

-- ============================================================
--  VIEWS (untuk fitur laporan/dashboard SIM)
-- ============================================================

-- View: rating rata-rata & jumlah ulasan per penjual
CREATE OR REPLACE VIEW seller_ratings AS
SELECT
    u.id           AS seller_id,
    u.name         AS seller_name,
    u.nim          AS seller_nim,
    COUNT(r.id)    AS total_reviews,
    ROUND(AVG(r.rating), 2) AS avg_rating,
    COUNT(CASE WHEN r.rating = 5 THEN 1 END) AS five_star,
    COUNT(CASE WHEN r.rating >= 4 THEN 1 END) AS four_plus_star
FROM users u
LEFT JOIN reviews r ON r.seller_id = u.id
WHERE u.role = 'mahasiswa'
GROUP BY u.id, u.name, u.nim;

COMMENT ON VIEW seller_ratings IS 'Dashboard: reputasi penjual berdasarkan rata-rata rating ulasan';

-- View: statistik transaksi per kategori (laporan SIM)
CREATE OR REPLACE VIEW category_stats AS
SELECT
    c.id            AS category_id,
    c.name          AS category_name,
    COUNT(i.id)     AS total_listings,
    COUNT(t.id)     AS total_transactions,
    SUM(CASE WHEN t.status = 'selesai' THEN 1 ELSE 0 END) AS completed_transactions,
    ROUND(AVG(t.agreed_price), 0) AS avg_transaction_price
FROM categories c
LEFT JOIN items i        ON i.category_id = c.id
LEFT JOIN transactions t ON t.item_id = i.id
GROUP BY c.id, c.name
ORDER BY total_transactions DESC;

COMMENT ON VIEW category_stats IS 'Dashboard admin: statistik listing & transaksi per kategori';

-- View: ringkasan aktivitas bulanan (laporan SIM)
CREATE OR REPLACE VIEW monthly_summary AS
SELECT
    DATE_TRUNC('month', t.created_at) AS month,
    COUNT(t.id)                         AS total_transactions,
    SUM(CASE WHEN t.status = 'selesai'    THEN 1 ELSE 0 END) AS completed,
    SUM(CASE WHEN t.status = 'dibatalkan' THEN 1 ELSE 0 END) AS cancelled,
    ROUND(AVG(t.agreed_price), 0)       AS avg_price,
    COUNT(DISTINCT t.buyer_id)          AS unique_buyers,
    COUNT(DISTINCT t.seller_id)         AS unique_sellers
FROM transactions t
GROUP BY DATE_TRUNC('month', t.created_at)
ORDER BY month DESC;

COMMENT ON VIEW monthly_summary IS 'Dashboard admin: ringkasan aktivitas transaksi per bulan';
