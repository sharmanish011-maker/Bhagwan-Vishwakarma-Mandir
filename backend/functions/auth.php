<?php
/**
 * =====================================================
 * Authentication Functions
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Attempt admin login
 *
 * @return array ['success' => bool, 'message' => string]
 */
function adminLogin(string $username, string $password): array
{
    // Rate limiting
    if (!checkRateLimit('admin_login', MAX_LOGIN_ATTEMPTS, LOGIN_LOCKOUT_MINUTES)) {
        return [
            'success' => false,
            'message' => 'Too many login attempts. Please try again after ' . LOGIN_LOCKOUT_MINUTES . ' minutes.'
        ];
    }

    // Find admin by username or email
    $admin = Database::queryOne(
        "SELECT * FROM admins WHERE (username = :username OR email = :email) AND is_active = 1",
        ['username' => $username, 'email' => $username]
    );

    if (!$admin) {
        return ['success' => false, 'message' => 'Invalid username or password.'];
    }

    // Check if account is locked
    if ($admin['locked_until'] && strtotime($admin['locked_until']) > time()) {
        $remaining = ceil((strtotime($admin['locked_until']) - time()) / 60);
        return [
            'success' => false,
            'message' => "Account is locked. Try again in {$remaining} minutes."
        ];
    }

    // Verify password
    if (!password_verify($password, $admin['password_hash'])) {
        // Increment login attempts
        $attempts = $admin['login_attempts'] + 1;
        $lockUntil = null;

        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $lockUntil = date('Y-m-d H:i:s', strtotime('+' . LOGIN_LOCKOUT_MINUTES . ' minutes'));
        }

        Database::execute(
            "UPDATE admins SET login_attempts = :attempts, locked_until = :locked WHERE id = :id",
            ['attempts' => $attempts, 'locked' => $lockUntil, 'id' => $admin['id']]
        );

        return ['success' => false, 'message' => 'Invalid username or password.'];
    }

    // Successful login — reset attempts, update last login
    Database::execute(
        "UPDATE admins SET login_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = :id",
        ['id' => $admin['id']]
    );

    // Regenerate session ID to prevent fixation
    session_regenerate_id(true);

    // Set session data
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['last_activity'] = time();

    return ['success' => true, 'message' => 'Login successful!'];
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn(): bool
{
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }

    // Check session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
        adminLogout();
        return false;
    }

    // Update activity timestamp
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Require admin login — redirect to login page if not authenticated
 */
function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        setFlash('warning', 'Please login to access the admin panel.');
        redirect(ADMIN_URL . '/login.php');
    }
}

/**
 * Check if admin has a specific role
 */
function hasRole(string $role): bool
{
    $roles = ['super_admin' => 3, 'admin' => 2, 'editor' => 1];
    $userRole = $_SESSION['admin_role'] ?? 'editor';

    return ($roles[$userRole] ?? 0) >= ($roles[$role] ?? 0);
}

/**
 * Require specific admin role
 */
function requireRole(string $role): void
{
    requireAdmin();
    if (!hasRole($role)) {
        setFlash('danger', 'You do not have permission to access this resource.');
        redirect(ADMIN_URL);
    }
}

/**
 * Admin logout
 */
function adminLogout(): void
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

/**
 * Change admin password
 */
function changeAdminPassword(int $adminId, string $currentPassword, string $newPassword): array
{
    $admin = Database::queryOne("SELECT password_hash FROM admins WHERE id = :id", ['id' => $adminId]);

    if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
        return ['success' => false, 'message' => 'Current password is incorrect.'];
    }

    if (strlen($newPassword) < 8) {
        return ['success' => false, 'message' => 'New password must be at least 8 characters.'];
    }

    $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    Database::execute(
        "UPDATE admins SET password_hash = :hash WHERE id = :id",
        ['hash' => $hash, 'id' => $adminId]
    );

    return ['success' => true, 'message' => 'Password changed successfully!'];
}

/**
 * Get current admin info
 */
function getCurrentAdmin(): ?array
{
    if (!isAdminLoggedIn()) {
        return null;
    }

    return [
        'id'       => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'],
        'name'     => $_SESSION['admin_name'],
        'role'     => $_SESSION['admin_role'],
        'email'    => $_SESSION['admin_email']
    ];
}
