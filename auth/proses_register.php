<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/helpers.php';

csrf_verify();

$name     = trim($_POST['name'] ?? $_POST['fullName'] ?? '');
$nimInput = trim($_POST['nim'] ?? $_POST['identifier'] ?? '');
$email    = trim($_POST['email'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? $_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['password_confirmation'] ?? '';

// Jika identifier berupa alamat email, pisahkan email dan NIM secara cerdas
if (strpos($nimInput, '@') !== false) {
    if ($email === '') {
        $email = $nimInput;
    }
    $nimParts = explode('@', $nimInput);
    $nim = preg_replace('/[^0-9]/', '', $nimParts[0]);
} else {
    $nim = $nimInput;
    if ($email === '' && $nim !== '') {
        $email = $nim . '@student.polinema.ac.id';
    }
}

$errors = [];

if ($name === '') {
    $errors[] = "Nama lengkap wajib diisi sesuai identitas KTM.";
}

if ($nim === '') {
    $errors[] = "NIM mahasiswa wajib diisi.";
} elseif (!preg_match('/^[0-9]{8,14}$/', $nim)) {
    $errors[] = "Format NIM tidak valid (harus berupa 8-14 digit angka).";
}

if ($email === '') {
    $errors[] = "Email kampus wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format alamat email tidak valid.";
} else {
    $emailParts = explode('@', $email);
    $domain = strtolower(array_pop($emailParts));
    $allowedDomains = ['student.polinema.ac.id', 'polinema.ac.id'];

    if (!in_array($domain, $allowedDomains, true)) {
        $errors[] = "Registrasi PREVO khusus civitas Polinema (@student.polinema.ac.id).";
    }
}

if (strlen($password) < 6) {
    $errors[] = "Kata sandi minimal terdiri dari 6 karakter.";
}

if ($confirm !== '' && $password !== $confirm) {
    $errors[] = "Konfirmasi kata sandi tidak cocok.";
}

// Normalisasi nomor WhatsApp ke standar angka lokal/internasional
if ($whatsapp !== '') {
    $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
    if (str_starts_with($whatsapp, '08')) {
        $whatsapp = '628' . substr($whatsapp, 2);
    } elseif (str_starts_with($whatsapp, '8')) {
        $whatsapp = '628' . substr($whatsapp, 1);
    }
}

$redirectTo = $_POST['redirect_to'] ?? 'register.php';

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . $redirectTo);
    exit;
}

try {
    // Cek Email atau NIM sudah terdaftar sebelumnya
    $cek = $pdo->prepare("SELECT id FROM users WHERE email = :email OR nim = :nim LIMIT 1");
    $cek->execute([
        'email' => $email,
        'nim'   => $nim,
    ]);

    if ($cek->fetch()) {
        set_flash('error', 'NIM atau Email kampus ini sudah terdaftar. Silakan login ke akun Anda.');
        header('Location: ' . $redirectTo);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, nim, password, whatsapp, role, is_active) 
         VALUES (:name, :email, :nim, :password, :whatsapp, 'mahasiswa', TRUE)"
    );

    $stmt->execute([
        'name'     => $name,
        'email'    => $email,
        'nim'      => $nim,
        'password' => $passwordHash,
        'whatsapp' => $whatsapp !== '' ? $whatsapp : null,
    ]);

    set_flash('success', 'Registrasi berhasil! Silakan masuk menggunakan NIM atau Email kampus Anda.');
    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    set_flash('error', 'Gagal mendaftarkan akun baru. Terjadi kesalahan pada database: ' . $e->getMessage());
    header('Location: ' . $redirectTo);
    exit;
}
