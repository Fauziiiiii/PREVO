<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';

csrf_verify();

$identifier = trim($_POST['identifier'] ?? $_POST['email'] ?? $_POST['nim'] ?? '');
$password   = $_POST['password'] ?? '';

if ($identifier === '' || $password === '') {
    set_flash('error', 'NIM/Email dan kata sandi wajib diisi.');
    header('Location: login.php');
    exit;
}

try {
    // Cari user berdasarkan NIM atau Email kampus
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = :id_email OR nim = :id_nim) LIMIT 1");
    $stmt->execute([
        'id_email' => $identifier,
        'id_nim'   => $identifier,
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if (isset($user['is_active']) && !$user['is_active']) {
            set_flash('error', 'Akun Anda dinonaktifkan oleh administrator.');
            header('Location: login.php');
            exit;
        }

        // Regenerasi session ID untuk mencegah serangan session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['nim']     = $user['nim'] ?? '';
        $_SESSION['email']   = $user['email'] ?? '';
        $_SESSION['jurusan'] = $user['jurusan'] ?? '';
        $_SESSION['prodi']   = $user['prodi'] ?? '';
        $_SESSION['role']    = $user['role'] ?? 'mahasiswa';

        set_flash('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        // Direct ke dashboard admin jika role admin, atau beranda jika mahasiswa
        if ($_SESSION['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin/index.php');
        } else {
            header('Location: ' . BASE_URL . 'home/index.php');
        }
        exit;
    }

    set_flash('error', 'NIM/Email atau kata sandi yang Anda masukkan salah.');
    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    set_flash('error', 'Terjadi gangguan sistem koneksi database. Silakan coba sesaat lagi.');
    header('Location: login.php');
    exit;
}