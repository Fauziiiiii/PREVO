<?php
// ============================================================
//  PREVO - Service Autentikasi & Verifikasi Mahasiswa
// ============================================================

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

class AuthService {
    private PDO $db;

    public function __construct() {
        $this->db = get_db();
    }

    /**
     * Pendaftaran mahasiswa baru
     */
    public function register(array $data): array {
        $nim = trim($data['nim'] ?? '');
        $email = strtolower(trim($data['email'] ?? ''));
        $name = trim($data['name'] ?? '');
        $password = $data['password'] ?? '';
        $whatsapp = trim($data['whatsapp'] ?? '');
        $jurusan = trim($data['jurusan'] ?? '');
        $prodi = trim($data['prodi'] ?? '');

        // 1. Validasi Kelengkapan
        if (empty($nim) || empty($email) || empty($name) || empty($password) || empty($whatsapp) || empty($jurusan) || empty($prodi)) {
            return ['success' => false, 'error' => 'Semua kolom wajib diisi.'];
        }

        // 2. Validasi NIM Numerik
        if (!preg_match('/^[0-9]{8,20}$/', $nim)) {
            return ['success' => false, 'error' => 'Format NIM tidak valid (harus berupa 8-20 digit angka).'];
        }

        // 3. Validasi Domain Email Kampus
        $emailParts = explode('@', $email);
        $domain = $emailParts[1] ?? '';
        if (!in_array($domain, ALLOWED_EMAIL_DOMAINS)) {
            return ['success' => false, 'error' => 'Pendaftaran dibatasi khusus email resmi Polinema (@student.polinema.ac.id atau @polinema.ac.id).'];
        }

        // 4. Validasi Format WhatsApp
        $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }
        if (!preg_match('/^628[0-9]{8,13}$/', $cleanWa)) {
            return ['success' => false, 'error' => 'Format nomor WhatsApp tidak valid (harus format nomor seluler Indonesia yang aktif).'];
        }

        // 5. Cek Duplikasi NIM atau Email
        $stmt = $this->db->prepare("SELECT id, nim, email FROM users WHERE nim = :nim OR email = :email");
        $stmt->execute(['nim' => $nim, 'email' => $email]);
        $existing = $stmt->fetch();
        if ($existing) {
            if ($existing['nim'] === $nim) {
                return ['success' => false, 'error' => 'NIM ' . e($nim) . ' sudah terdaftar di sistem PREVO.'];
            }
            if ($existing['email'] === $email) {
                return ['success' => false, 'error' => 'Email ' . e($email) . ' sudah digunakan.'];
            }
        }

        // 6. Buat Hash Kata Sandi & Token Verifikasi
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $emailToken = bin2hex(random_bytes(32));
        $whatsappOtp = sprintf('%06d', mt_rand(100000, 999999));

        $sql = "INSERT INTO users (
                    nim, email, name, password, jurusan, prodi, whatsapp, phone, role,
                    is_active, verification_token, whatsapp_otp
                ) VALUES (
                    :nim, :email, :name, :password, :jurusan, :prodi, :whatsapp, :phone, 'mahasiswa',
                    FALSE, :token, :otp
                ) RETURNING id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'nim'      => $nim,
                'email'    => $email,
                'name'     => $name,
                'password' => $hashedPassword,
                'jurusan'  => $jurusan,
                'prodi'    => $prodi,
                'whatsapp' => $cleanWa,
                'phone'    => $cleanWa,
                'token'    => $emailToken,
                'otp'      => $whatsappOtp
            ]);
            $userId = $stmt->fetchColumn();

            return [
                'success'  => true,
                'user_id'  => $userId,
                'token'    => $emailToken,
                'email'    => $email,
                'name'     => $name,
                'otp'      => $whatsappOtp
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Gagal mendaftar: ' . $e->getMessage()];
        }
    }

    /**
     * Verifikasi token aktivasi email
     */
    public function verifyEmailToken(string $token): array {
        if (empty($token)) {
            return ['success' => false, 'error' => 'Token verifikasi tidak valid.'];
        }

        $stmt = $this->db->prepare("SELECT id, name, email, is_active FROM users WHERE verification_token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'error' => 'Token verifikasi email sudah kedaluwarsa atau tidak ditemukan.'];
        }

        $update = $this->db->prepare("UPDATE users 
                                      SET is_active = TRUE, 
                                          email_verified_at = NOW(), 
                                          verification_token = NULL 
                                      WHERE id = :id");
        $update->execute(['id' => $user['id']]);

        return ['success' => true, 'name' => $user['name'], 'email' => $user['email']];
    }

    /**
     * Verifikasi kode OTP WhatsApp
     */
    public function verifyWhatsAppOtp(int $userId, string $otp): array {
        $stmt = $this->db->prepare("SELECT id, whatsapp_otp FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'error' => 'Pengguna tidak ditemukan.'];
        }

        if (trim($user['whatsapp_otp'] ?? '') !== trim($otp)) {
            return ['success' => false, 'error' => 'Kode OTP tidak cocok. Silakan periksa kembali.'];
        }

        $update = $this->db->prepare("UPDATE users 
                                      SET whatsapp_verified_at = NOW(), 
                                          whatsapp_otp = NULL 
                                      WHERE id = :id");
        $update->execute(['id' => $userId]);

        // Perbarui sesi
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $userId) {
            $_SESSION['user']['whatsapp_verified_at'] = date('c');
        }

        return ['success' => true];
    }

    /**
     * Login kredensial
     */
    public function login(string $identifier, string $password): array {
        $identifier = trim($identifier);
        if (empty($identifier) || empty($password)) {
            return ['success' => false, 'error' => 'NIM/Email dan Kata Sandi wajib diisi.'];
        }

        $stmt = $this->db->prepare("SELECT id, nim, email, name, password, role, is_active, 
                                           whatsapp, jurusan, prodi, whatsapp_verified_at, email_verified_at
                                    FROM users 
                                    WHERE email = :id_email OR nim = :id_nim");
        $stmt->execute(['id_email' => $identifier, 'id_nim' => $identifier]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Kombinasi NIM/Email atau Kata Sandi salah.'];
        }

        // Cek aktivasi email
        if (!$user['is_active']) {
            return [
                'success' => false, 
                'error'   => 'Akun Anda belum aktif. Silakan lakukan verifikasi email kampus terlebih dahulu.',
                'need_email_verification' => true
            ];
        }

        // Set Sesi Pengguna
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'                   => (int)$user['id'],
            'nim'                  => $user['nim'],
            'email'                => $user['email'],
            'name'                 => $user['name'],
            'role'                 => $user['role'],
            'whatsapp'             => $user['whatsapp'],
            'jurusan'              => $user['jurusan'],
            'prodi'                => $user['prodi'],
            'whatsapp_verified_at' => $user['whatsapp_verified_at'],
            'email_verified_at'    => $user['email_verified_at']
        ];

        return ['success' => true, 'role' => $user['role']];
    }

    /**
     * Update profil kontak & data diri
     */
    public function updateProfile(int $userId, array $data): array {
        $name = trim($data['name'] ?? '');
        $whatsapp = trim($data['whatsapp'] ?? '');
        $instagram = trim($data['instagram'] ?? '');
        $telegram = trim($data['telegram'] ?? '');

        if (empty($name) || empty($whatsapp)) {
            return ['success' => false, 'error' => 'Nama dan Nomor WhatsApp tidak boleh kosong.'];
        }

        $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }

        $stmt = $this->db->prepare("UPDATE users 
                                    SET name = :name, 
                                        whatsapp = :wa, 
                                        phone = :phone, 
                                        instagram = :ig, 
                                        telegram = :tg 
                                    WHERE id = :id");
        $stmt->execute([
            'name'  => $name,
            'wa'    => $cleanWa,
            'phone' => $cleanWa,
            'ig'    => $instagram ?: null,
            'tg'    => $telegram ?: null,
            'id'    => $userId
        ]);

        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $userId) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['whatsapp'] = $cleanWa;
        }

        return ['success' => true];
    }

    /**
     * Ambil data lengkap user berdasarkan ID
     */
    public function getUserById(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT u.*, sr.avg_rating, sr.total_reviews 
                                    FROM users u
                                    LEFT JOIN seller_ratings sr ON sr.seller_id = u.id
                                    WHERE u.id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Ambil data lengkap user berdasarkan Email
     */
    public function getUserByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT u.*, sr.avg_rating, sr.total_reviews 
                                    FROM users u
                                    LEFT JOIN seller_ratings sr ON sr.seller_id = u.id
                                    WHERE LOWER(u.email) = LOWER(:email)");
        $stmt->execute(['email' => trim($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
