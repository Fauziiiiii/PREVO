<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../services/AuthService.php';

if (is_logged_in()) {
    redirect('/barang/index.php');
}

$message = '';
$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Masukkan email kampus yang valid.';
    } else {
        $authService = new AuthService();
        $result = $authService->requestPasswordReset($email);

        if ($result['success'] && !empty($result['token'])) {
            $resetLink = BASE_URL . '/auth/reset_password.php?token=' . urlencode($result['token']);
            // Placeholder integrasi PHPMailer SMTP
            $message = "Link reset password telah dibuat (Simulasi): <br><a href='$resetLink'>Klik Di Sini untuk Reset</a>";
        } else {
            $message = "Email tidak ditemukan dalam sistem.";
        }
    }
}

$pageTitle = 'Lupa Password';
require_once __DIR__ . '/../views/partials/header.php';
?>


<?php require_once __DIR__ . '/../views/partials/footer.php'; ?>