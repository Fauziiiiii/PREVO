# Dokumen Desain Teknis & Arsitektur PREVO (Rebuild)

- **Tanggal:** 2026-10-26
- **Topik:** Rebuild Marketplace Barang Bekas Civitas Akademika Polinema (PREVO)
- **Penanggung Jawab:** Muhammad Fauzi Fadillah (Full-stack Rebuild)
- **Status:** Menunggu Persetujuan Desain (Design Gate)

---

## 1. Latar Belakang & Tujuan (Intent & The Why)

### 1.1 Masalah di Proyek Sebelumnya
Pada pengembangan Milestone 1 (Minggu 1–8), tim PREVO mengalami hambatan signifikan:
1. **Kode Front End Kotor Hasil Generator:** Slicing antarmuka diambil langsung dari tool export ("Stitch"/Figma generator) yang menghasilkan ribuan baris class Tailwind CSS tidak terstruktur, tidak modular, dan sulit dihubungkan ke PHP dinamis.
2. **Scope Creep (Fitur Liar Tanpa Sinkronisasi):** Desain antarmuka menambahkan fitur-fitur di luar proposal resmi dan di luar skema basis data (seperti keranjang belanja multi-item dan live chat internal), yang memicu kebingungan teknis dan menghambat integrasi backend.
3. **Keterbatasan Kontribusi Tim:** Peran UI/UX tidak menghasilkan rancangan yang konsisten dan siap pakai, sementara tenggat waktu Milestone 2 (Minggu 12) menyisakan kurang dari 3 minggu.

### 1.2 Solusi & Tujuan Rebuild
Melakukan penulisan ulang (*rebuild*) arsitektur kode secara terpadu oleh Fauzi:
- Mengunci ruang lingkup (*scope lock*) 100% selaras dengan Dokumen Proposal, RPP PBL Semester 3, dan skema database PostgreSQL yang sudah final di `db/prevo_schema.sql`.
- Menerapkan pendekatan **Page Controller dengan Service Layer & Reusable Partials** berbasis PHP Native dan Tailwind CSS v4 yang modular, bersih, dan bebas kode generator.
- Membangun alur jual-beli barang bekas kampus berbasis COD yang aman, tepercaya, dan terverifikasi identitas resmi mahasiswa Polinema.

---

## 2. Sasaran, Batasan, dan Anti-Goals

### 2.1 Sasaran (Goals)
1. **Autentikasi & Otorisasi Ketat:** Mendukung 2 peran pengguna (`mahasiswa` dan `admin`) dengan verifikasi NIM unik, pembatasan domain email resmi (`@student.polinema.ac.id` dan `@polinema.ac.id`), hash password aman *bcrypt*, session guard, dan token CSRF.
2. **Katalog & Kurasi Produk:** Pencarian teks penuh (*Full-Text Search*), filter kategori instan, filter kondisi fisik barang, dan pengurutan harga/tanggal.
3. **Alur Moderasi Admin (SIM & BDL):** Listing barang baru wajib melewati antrean moderasi admin (`items.status = 'menunggu_persetujuan'`) sebelum tayang publik, lengkap dengan pencatatan alasan penolakan (`rejection_reason`) atau persetujuan (`approved_by`, `approved_at`).
4. **Detail Barang & Transaksi COD Terintegrasi:** Tombol langsung menuju WhatsApp penjual dengan format pesan dinamis, tombol booking 1-klik yang mengeksekusi Stored Procedure atomik PostgreSQL `create_booking` dengan *row-level lock* (`FOR UPDATE`).
5. **Penyelesaian Transaksi & Rating:** Eksekusi Stored Procedure `complete_transaction` / `cancel_transaction`, pemberian ulasan & rating bintang 1–5 (`reviews`), dan agregasi reputasi penjual (`seller_ratings`).
6. **Dashboard Eksekutif SIM:** Menampilkan indikator statistik manajerial dari views basis data: `monthly_summary`, `category_stats`, dan `seller_ratings`.

### 2.2 Batasan (Constraints)
- **Teknologi:** PHP Native (versi 8.x) tanpa framework pihak ketiga, PostgreSQL 15+, Tailwind CSS v4, PDO driver.
- **Waktu:** Target siap uji dan demonstrasi penuh pada Milestone 2 (Minggu 12).
- **Lingkungan Pengujian:** Harus dapat berjalan lancar di server lokal bawaan PHP (`php -S localhost:8000`), XAMPP, atau Laragon tanpa dependensi ekstensi server non-standar.

### 2.3 Non-Goals & Anti-Goals
- **Anti-Goal 1 (Tidak Ada Payment Gateway):** Tidak mengimplementasikan dompet digital atau pembayaran online perbankan. Seluruh pembayaran dilakukan secara tunai/transfer langsung saat COD tatap muka di area kampus.
- **Anti-Goal 2 (Tidak Ada In-App Live Chat):** Tidak membuat sistem WebSocket atau live chat dalam web. Komunikasi dialihkan 100% ke direct link WhatsApp penjual.
- **Anti-Goal 3 (Tidak Ada Keranjang Belanja Multi-Item):** Sistem barang bekas mahasiswa adalah *one-off item* (satu barang satu pemilik). Transaksi dilakukan per barang melalui booking 1-klik, bukan multi-item checkout cart.
- **Anti-Goal 4 (Tidak Ada Fitur Lelang/Bidding):** Negosiasi harga dilakukan secara personal via WhatsApp; harga kesepakatan final dicatat oleh penjual di web melalui SP `update_booking_deal`.

---

## 3. Evaluasi Pendekatan Arsitektur (Approaches)

Tiga alternatif arsitektur dipertimbangkan:

1. **Pendekatan 1: Page Controller dengan Service Layer & Reusable Partials (DIPILIH)**
   - *Deskripsi:* Mengarahkan URL langsung ke file halaman modul (`auth/login.php`, `barang/detail.php`, `transaksi/riwayat.php`, `admin/dashboard.php`). Seluruh operasi basis data dan logika bisnis dipisahkan ke dalam kelas service mandiri di `services/` (misal: `AuthService.php`, `ItemService.php`, `TransactionService.php`). Antarmuka dipecah menjadi komponen parsial di `views/partials/` (`header.php`, `navbar.php`, `card-item.php`, `footer.php`).
   - *Trade-off:* Memenuhi kriteria akademis DPW (pemisahan tampilan, logika, dan data), zero-configuration server (tidak butuh mod_rewrite `.htaccess`), dan mudah dipahami rekan tim (Adit) bila kembali bergabung. URL mengandung ekstensi `.php`.

2. **Pendekatan 2: Single Front Controller (Mini-MVC)**
   - *Deskripsi:* Seluruh request melalui `index.php` dengan router custom yang memetakan route ke controller dan model.
   - *Trade-off:* URL bersih tanpa `.htaccess`, tetapi rentan galat 404 pada konfigurasi Apache lokal yang berbeda antar-laptop penguji/dosen. Ditolak untuk meminimalkan risiko teknis demo.

3. **Pendekatan 3: Modular Feature Packages (Screaming Architecture)**
   - *Deskripsi:* File dikelompokkan dalam modul mandiri (`modules/katalog/`, `modules/transaksi/`).
   - *Trade-off:* Kohesi fitur tinggi, tetapi rentan kerumitan path include layout global (`../../includes`). Ditolak demi kesederhanaan pemeliharaan.

---

## 4. Struktur Direktori Proyek

```
PBL-2/
├── config/
│   ├── database.php          # Koneksi PDO PostgreSQL & konfigurasi koneksi
│   └── app.php               # Konfigurasi aplikasi (BASE_URL, nama app, domain kampus)
├── includes/
│   ├── session.php           # Session guard, helper login/auth, flash message
│   ├── security.php          # Proteksi CSRF (generate & verify token), XSS sanitize
│   └── helpers.php           # Format rupiah, format tanggal, sanitasi input, redirect
├── services/
│   ├── AuthService.php       # Registrasi, verifikasi password bcrypt, session login
│   ├── ItemService.php       # CRUD items, filter FTS, upload foto, status moderasi
│   ├── TransactionService.php# SP create_booking, update_booking_deal, complete, cancel
│   ├── ReviewService.php     # Insert ulasan & rating 1-5, fetch seller reviews
│   └── AdminService.php      # Moderasi approve/reject listing, kelola kategori, query view SIM
├── views/
│   └── partials/
│       ├── header.php        # Dokumen HTML, meta, link Tailwind CSS, flash alert
│       ├── navbar.php        # Navigasi utama (logo, search FTS, menu role mahasiswa/admin)
│       ├── card-item.php     # Komponen kartu produk katalog terstandarisasi
│       ├── modal-booking.php # Modal konfirmasi booking 1-klik
│       └── footer.php        # Footer penutup & script inisialisasi
├── uploads/
│   └── items/                # Direktori penyimpanan foto listing barang
├── assets/
│   └── css/
│       └── tailwind.css      # Aset style Tailwind terkompilasi / CDN Tailwind v4
├── auth/
│   ├── login.php             # Halaman & form login
│   ├── register.php          # Halaman & form registrasi mahasiswa
│   ├── verifikasi_email.php  # Verifikasi aktivasi token email kampus
│   ├── verifikasi_wa.php     # Verifikasi OTP nomor WhatsApp
│   ├── profil.php            # Halaman profil pengguna (edit no WA, jurusan, prodi)
│   └── logout.php            # Handler pembersihan sesi
├── barang/
│   ├── index.php             # Beranda & katalog utama barang (filter & search)
│   ├── detail.php            # Halaman detail produk, info penjual, tombol WA, booking & modal lapor
│   ├── tambah.php            # Formulir unggah barang jualan (status: menunggu_persetujuan)
│   ├── kelola.php            # Halaman kelola listing penjual (status kurasi, alasan tolak, aksi edit/hapus)
│   ├── edit.php              # Formulir ubah informasi & foto barang
│   ├── hapus.php             # Handler soft-delete (status: dihapus)
│   └── favorit.php           # Daftar barang yang di-bookmark user
├── transaksi/
│   ├── proses_booking.php    # Handler eksekusi SP create_booking
│   ├── riwayat.php           # Daftar transaksi aktif & selesai (tab Pembeli & Penjual)
│   ├── update_deal.php       # Handler SP update_booking_deal (penjual update harga/meetup)
│   ├── selesai.php           # Handler SP complete_transaction
│   ├── batal.php             # Handler SP cancel_transaction
│   └── beri_ulasan.php       # Form & handler input rating 1-5 bintang pasca-COD
├── admin/
│   ├── dashboard.php         # Dashboard eksekutif SIM (View monthly_summary & stats)
│   ├── moderasi.php          # Antrean persetujuan listing (Setujui / Tolak barang)
│   ├── kategori.php          # CRUD master kategori barang
│   └── laporan.php           # Daftar & penanganan laporan pelanggaran (reports)
├── db/
│   ├── prevo_schema.sql      # Skema DDL 7 tabel, 5 SP, trigger, 3 view
│   └── prevo_seed_data.sql   # Data awal terverifikasi (hash bcrypt riil)
└── index.php                 # Entry point root -> redirect ke barang/index.php
```

---

## 5. Integrasi Basis Data & Penyelarasan Skema

Skema basis data menggunakan berkas `db/prevo_schema.sql` tanpa mengubah struktur tabel resmi:
- **Tabel Utama (7):** `users`, `categories`, `items`, `favorites`, `transactions`, `reviews`, `reports`.
- **Stored Procedures (5):**
  1. `create_booking(p_item_id, p_buyer_id)`: Atomic booking dengan `SELECT ... FOR UPDATE` dan masa aktif 24 jam.
  2. `update_booking_deal(p_transaction_id, p_seller_id, p_agreed_price, p_meetup_location, p_meetup_time)`: Memperbarui detail transaksi pasca negosiasi WhatsApp.
  3. `cancel_transaction(p_transaction_id, p_actor_id, p_reason)`: Membatalkan booking pending, trigger otomatis mengembalikan item ke `tersedia`.
  4. `complete_transaction(p_transaction_id, p_actor_id)`: Menandai COD sukses, trigger otomatis mengubah item ke `terjual`.
  5. `release_expired_bookings()`: Membatalkan transaksi pending yang melewati batas 24 jam. Dipanggil secara otomatis via hook di `config/database.php` pada request awal berkala.
- **Views SIM (3):**
  1. `seller_ratings`: Agregasi bintang rating dan jumlah ulasan penjual.
  2. `category_stats`: Statistik transaksi dan listing per kategori barang.
  3. `monthly_summary`: Tren transaksi bulanan, volume penjualan, dan pengguna unik.
- **Penambahan Kolom Verifikasi Akun (`users`):**
  Untuk mendukung verifikasi 2-tahap tanpa merusak relasi dan struktur yang ada, tabel `users` dilengkapi dengan:
  - `email_verified_at TIMESTAMPTZ` & `verification_token VARCHAR(64)`: status & token aktivasi email.
  - `whatsapp_verified_at TIMESTAMPTZ` & `whatsapp_otp VARCHAR(6)`: status & kode OTP verifikasi WhatsApp.
- **Koreksi Data Seed (`db/prevo_seed_data.sql`):**
  Seluruh akun seed (Admin `admin@prevo.polinema.ac.id`, Mahasiswa: Reyhan, Fauzi, Vito, Adit, Budi, Siti) ditandai sudah terverifikasi penuh (`email_verified_at = NOW()`, `whatsapp_verified_at = NOW()`, `is_active = TRUE`) dan menggunakan hash bcrypt riil yang valid untuk kata sandi default `Test@12345`.

---

## 6. Desain Visual & Antarmuka (Arah A: Modern Campus Bulletin)

- **Warna Pokok:**
  - *Polinema Deep Navy:* `#0F172A` (Header, navigasi utama, tipografi primer).
  - *Emerald Green:* `#059669` (Aksen sukses, badge ketersediaan barang, reputasi aman, tombol booking).
  - *Campus Blue Accent:* `#0284C7` (Badge identitas kampus, link navigasi aktif).
  - *Warm Amber:* `#D97706` (Badge booking pending, bintang ulasan).
  - *Clean Slate Surface:* `#F8FAFC` & `#FFFFFF` (Latar belakang konten dan kartu produk).
- **Tipografi:** Sans-serif sistem berbobot tegas (`Inter` / `system-ui`), keterbacaan tinggi dengan hierarki visual jelas, membatasi panjang teks deskripsi, serta menghindari ornamen dekorasi yang tidak fungsional.
- **Responsivitas:** Tata letak *mobile-first* yang adaptif mulai layar 360px hingga layar desktop lebar dengan transisi grid kartu (1 kolom di mobile, 2 kolom di tablet, 4 kolom di desktop).

---

## 7. Rincian Alur Pengguna (User Flows)

### 7.1 Alur Autentikasi & Verifikasi 2-Tahap (Email & WhatsApp)
1. **Registrasi Mahasiswa (`auth/register.php`):**
   - Mahasiswa menginput NIM numerik, email domain resmi (`@student.polinema.ac.id` atau `@polinema.ac.id`), nomor WhatsApp aktif (`08...` / `628...`), nama lengkap, pilihan Jurusan, Program Studi, dan kata sandi.
   - Kata sandi di-hash menggunakan `PASSWORD_BCRYPT`. Akun disimpan dengan `is_active = FALSE`.
2. **Tahap 1: Verifikasi Email Kampus (`auth/verifikasi_email.php`):**
   - Sistem menghasilkan token aktivasi unik. Di server lokal demo, layar menampilkan simulasi tautan aktivasi email kampus.
   - Mengklik tautan verifikasi mengubah `is_active = TRUE`, sehingga mahasiswa dapat melakukan login.
3. **Tahap 2: Verifikasi Nomor WhatsApp (`auth/verifikasi_wa.php`):**
   - Setelah login, sistem memeriksa apakah nomor WhatsApp mahasiswa sudah terverifikasi.
   - Jika belum, pengguna diminta memasukkan kode OTP 6-digit (disimulasikan di antarmuka).
   - **Kunci Fitur:** Mahasiswa yang belum memverifikasi nomor WhatsApp dibatasi hak aksesnya: tidak dapat memasang listing barang baru (`barang/tambah.php`) dan tidak dapat mem-booking barang.
4. **Pengelolaan Sesi:**
   - Sesi menyimpan `user_id`, `nim`, `name`, `role`, `whatsapp`, dan status verifikasi kontak. Hak akses diproteksi oleh `auth_guard('mahasiswa')` dan `auth_guard('admin')`.

### 7.2 Alur Posting, Kelola & Moderasi Barang
1. Mahasiswa mengunggah barang via `barang/tambah.php`: judul, kategori, kondisi, harga awal, deskripsi, lokasi COD, dan foto barang (disimpan di `uploads/items/`).
2. Data tersimpan di tabel `items` dengan status `menunggu_persetujuan`.
3. Penjual mengelola barang miliknya melalui `barang/kelola.php`:
   - Melihat daftar listing aktif (`tersedia`), dalam transaksi (`booking`), terjual (`terjual`), maupun ditolak (`ditolak`).
   - Jika status `ditolak`: penjual membaca pesan catatan admin di `rejection_reason`, lalu mengklik `edit.php`.
   - Ketika barang yang ditolak disimpan perbaikannya di `barang/edit.php`, sistem otomatis mereset status menjadi `menunggu_persetujuan` dan mengosongkan `rejection_reason` agar admin dapat meninjau kembali.
   - Penjual dapat melakukan *soft delete* listing miliknya via `barang/hapus.php` (mengubah `status = 'dihapus'`).
4. Admin membuka `admin/moderasi.php`, melihat antrean barang baru:
   - Jika disetujui: item di-update menjadi `status = 'tersedia'`, mencatat `approved_by` dan `approved_at = NOW()`. Barang langsung muncul di katalog publik.
   - Jika ditolak: admin mengisikan alasan di `rejection_reason` dan `status = 'ditolak'`. Penjual dapat melihat alasan penolakan pada `barang/kelola.php`.

### 7.3 Alur Detail Barang & Pembukaan Kontak WhatsApp Pasca-Booking (Opsi B)
1. Calon pembeli menjelajahi katalog dan membuka `barang/detail.php`.
2. Di halaman detail, pembeli melihat foto, deskripsi, kondisi, harga, lokasi umum COD, serta kartu reputasi penjual (NIM, jurusan, rating bintang).
3. **Proteksi Kontak (Opsi B):** Nomor WhatsApp penjual **tidak ditampilkan secara publik** di halaman detail barang maupun profil untuk mencegah spam dan pembeli yang tidak serius (*ghosting*).
4. Calon pembeli yang serius mengklik **"Booking 1-Klik"** -> modal konfirmasi -> mengeksekusi Stored Procedure PostgreSQL `create_booking(item_id, buyer_id)`.
5. Database mengunci baris barang (*row-level lock*), mengubah status barang menjadi `booking`, dan membuat transaksi `pending` aktif 24 jam.
6. **Membuka Kontak WhatsApp:** Segera setelah booking berhasil, sistem menampilkan tombol **"Chat Penjual di WhatsApp"** (`https://wa.me/{whatsapp}?text={pesan_otomatis}`) pada modal sukses booking dan halaman `transaksi/riwayat.php`. Dengan demikian, penjual hanya dihubungi oleh pembeli yang sudah resmi mengunci barang.

### 7.4 Alur Penyelesaian COD & Ulasan
1. Setelah bertemu tatap muka di area kampus Polinema (misal: Kantin Graha / Perpustakaan):
   - Penjual dapat memperbarui harga final kesepakatan lewat SP `update_booking_deal`.
   - Pembeli atau Penjual mengklik tombol **"Selesaikan Transaksi"** di `transaksi/riwayat.php`.
2. Stored Procedure `complete_transaction` dieksekusi: status transaksi menjadi `selesai`, dan trigger database otomatis mengubah status barang menjadi `terjual`.
3. Pembeli diarahkan ke formulir `transaksi/beri_ulasan.php` untuk memberikan rating 1–5 bintang dan testimoni ke tabel `reviews`. Nilai reputasi penjual langsung teragregasi secara otomatis pada view `seller_ratings`.

### 7.5 Alur Pelaporan Pelanggaran (Reports) & Takedown
1. Calon pembeli yang mendapati barang/deskripsi tidak wajar dapat mengklik tombol **"🚩 Laporkan"** pada `barang/detail.php`.
2. Form modal memunculkan pilihan alasan (`reason`: barang palsu, penipuan, foto tidak layak, dll.) dan deskripsi tambahan, lalu menyimpan ke tabel `reports` dengan `status = 'pending'`.
3. Admin meninjau laporan di `admin/laporan.php`:
   - Jika laporan terbukti melanggar: Admin dapat mengklik "Takedown Barang" (mengubah `items.status = 'ditolak'` atau `'dihapus'`) dan menandai laporan `status = 'ditangani'` disertai `admin_note`.
   - Jika laporan tidak valid: Admin menandai `status = 'ditolak'`.

### 7.6 Kebijakan Privasi Data Profil Pengguna (`auth/profil.php`)
- **Data Publik (Bisa Dilihat Seluruh Pengguna):** Nama Lengkap, Jurusan, Program Studi, NIM, Badge Verifikasi Kampus, Riwayat Ulasan & Rating Bintang, serta Daftar Listing Aktif.
- **Data Terproteksi (Hanya Terbuka untuk Mitra Transaksi):** Nomor WhatsApp penjual hanya terbuka bagi pembeli yang memiliki transaksi booking aktif dengan penjual tersebut.
- **Data Privat (Hanya Pemilik Akun & Admin):** Alamat email login kampus, nomor telepon reguler (`phone`), dan riwayat pelaporan akun. Pengguna dapat mengubah nomor WhatsApp dan kontak mereka sewaktu-waktu di halaman profil.

---

## 8. Pembagian Tanggung Jawab Pembina Sistem (What's Left to the Builder)

Hal-hal implementasi detail yang diserahkan sepenuhnya kepada pembuat kode:
1. Skrip pembantu sanitasi input (`security.php`) untuk pencegahan XSS dan SQL Injection via PDO Prepared Statements terpusat.
2. Penanganan pesan notifikasi instan (*flash messages*) berbasis sesi PHP (`$_SESSION['flash_success']`, `$_SESSION['flash_error']`).
3. Pengaturan ukuran maksimal file unggahan foto barang (maksimal 2MB, format JPG/PNG/WEBP) dengan penamaan berkas berbasis timestamp unik.
4. Fallback gambar default SVG yang rapi jika listing lama belum memiliki foto fisik.
