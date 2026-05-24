<?php
/**
 * Admin Login Page
 */
define('BVM_ROOT', dirname(dirname(__DIR__)));
require_once BVM_ROOT . '/backend/config/config.php';
require_once BVM_ROOT . '/backend/config/constants.php';
require_once FUNCTIONS_PATH . '/database.php';
require_once FUNCTIONS_PATH . '/security.php';
require_once FUNCTIONS_PATH . '/helpers.php';
require_once FUNCTIONS_PATH . '/auth.php';
session_start();
setSecurityHeaders();

// Already logged in?
if (isAdminLoggedIn()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken()) {
        $error = 'Security validation failed.';
    } else {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $result = adminLogin($username, $password);
        if ($result['success'] === true) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — <?= SITE_NAME ?></title>
    <link href="<?= BOOTSTRAP_CSS ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= FONTAWESOME_CSS ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #5C0A0A, #800020, #FF9933); background-size:400% 400%; animation:gradientShift 15s ease infinite; }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .login-card { background:rgba(255,255,255,0.95); backdrop-filter:blur(10px); border-radius:16px; padding:40px; max-width:420px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.3); }
        .login-brand { text-align:center; margin-bottom:30px; }
        .login-brand .om { font-size:3rem; color:#D4AF37; text-shadow:0 0 20px rgba(212,175,55,0.5); }
        .login-brand h2 { color:#800020; font-size:1.5rem; margin:10px 0 5px; }
        .login-brand p { color:#888; font-size:0.85rem; }
        .form-control { border:2px solid #e0e0e0; border-radius:10px; padding:12px 16px; transition:all 0.3s; }
        .form-control:focus { border-color:#FF9933; box-shadow:0 0 0 3px rgba(255,153,51,0.15); }
        .btn-login { background:linear-gradient(135deg,#FF9933,#E65100); border:none; padding:12px; border-radius:10px; color:#fff; font-weight:600; width:100%; font-size:1rem; transition:all 0.3s; }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 5px 20px rgba(255,153,51,0.4); color:#fff; }
        .alert { border-radius:10px; font-size:0.9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <div class="om">ॐ</div>
            <h2><?= SITE_NAME ?></h2>
            <p>Admin Panel — Secure Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($flash = getFlash()): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form method="POST">
            <?= csrfField() ?>
            <div class="mb-3">
                <label class="form-label fw-600"><i class="fas fa-user me-1"></i> Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-600"><i class="fas fa-lock me-1"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>" style="color:#888;font-size:0.85rem;"><i class="fas fa-arrow-left me-1"></i>Back to Website</a>
        </div>
    </div>
</body>
</html>
