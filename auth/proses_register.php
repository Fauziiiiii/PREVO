<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$nim = trim($_POST['nim'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];
if ($name === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($email === '') {
    $errors[] = "Email wajib diisi.";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
} else {
    $email_parts = explode('@', $email);
    $domain = array_pop($email_parts);

    $allowed_domains = ['polinema.ac.id'];

    if (!in_array($domain, $allowed_domains)) {
        $errors = "Maaf, pendaftaran hanya diperbolehkan menggunakan email kampus.";
    }
}
if ($nim === '') {
    $errors[] = "NIM wajib diisi.";
}
if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: register.php');
    exit;
}

$cek = $pdo->prepare("SELECT * FROM users WHERE email = :email OR nim = :nim");
$cek->execute([
    'email' => $email,
    'nim' => $nim,
    ]);
if ($cek->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Email atau NIM sudah digunakan.'];
    header('Location: register.php');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO users(name, email, nim, password, role) VALUES (:name, :email, :nim, :password, 'mahasiswa')"
);
$stmt->execute([
    'nama' => $name,
    'email' => $email,
    'nim' => $nim,
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Registrasi berhasil, silakan login.'];
header('Location: login.php');
exit;
