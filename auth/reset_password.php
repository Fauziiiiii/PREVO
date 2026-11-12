<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect('/barang/index.php');
}

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$errors = [];
$success = '';
$authService = new AuthService();
$tokenValid = false;

if ($token !== '') {
    $tokenValid = $authService->isResetTokenValid($token);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword  = $_POST['confirm_password'] ?? '';

    if (!$authService->isResetTokenValid($token)) {
        $errors[] = 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan ajukan permintaan baru.';
    } elseif (strlen($newPassword) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    } else {
        $result = $authService->resetPassword($token, $newPassword);
        if ($result['success']) {
            $success = "Password berhasil diperbarui! <a href='login.php'>Login sekarang</a>";
        } else {
            $errors[] = $result['error'];
        }
    }
}
$pageTitle = 'Reset Password';
require_once __DIR__ . '/../views/partials/header.php';
?>


<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>