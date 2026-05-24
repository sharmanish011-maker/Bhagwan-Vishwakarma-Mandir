<?php
/**
 * =====================================================
 * Security Functions — CSRF, Sanitization, XSS
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Generate or retrieve CSRF token
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden CSRF input field for forms
 */
function csrfField(): string
{
    $token = htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Get CSRF token for AJAX requests (meta tag)
 */
function csrfMeta(): string
{
    $token = htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8');
    return '<meta name="csrf-token" content="' . $token . '">';
}

/**
 * Validate CSRF token from POST request
 */
function validateCsrfToken(): bool
{
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Regenerate CSRF token (call after successful form submission)
 */
function regenerateCsrfToken(): void
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Sanitize string input — prevent XSS
 */
function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize email
 */
function sanitizeEmail(string $email): string
{
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

/**
 * Validate email format
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize phone number (keep digits, +, -, spaces)
 */
function sanitizePhone(string $phone): string
{
    return preg_replace('/[^\d+\-\s()]/', '', trim($phone));
}

/**
 * Sanitize integer input
 */
function sanitizeInt($value): int
{
    return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitize float input
 */
function sanitizeFloat($value): float
{
    return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Sanitize URL
 */
function sanitizeUrl(string $url): string
{
    return filter_var(trim($url), FILTER_SANITIZE_URL);
}

/**
 * Clean HTML content (allow basic safe tags for CMS content)
 */
function sanitizeHtml(string $html): string
{
    $allowedTags = '<p><br><strong><b><em><i><u><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><table><tr><td><th><thead><tbody>';
    return strip_tags($html, $allowedTags);
}

/**
 * Escape output for HTML display
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Rate limiting check based on session
 *
 * @param string $action  Action identifier
 * @param int    $maxAttempts Max allowed attempts
 * @param int    $windowMinutes Time window in minutes
 * @return bool  True if within limit, false if exceeded
 */
function checkRateLimit(string $action, int $maxAttempts = 5, int $windowMinutes = 15): bool
{
    $key = 'rate_limit_' . $action;
    $now = time();
    $windowSeconds = $windowMinutes * 60;

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    // Remove expired entries
    $_SESSION[$key] = array_filter(
        $_SESSION[$key],
        fn($timestamp) => ($now - $timestamp) < $windowSeconds
    );

    // Check if limit exceeded
    if (count($_SESSION[$key]) >= $maxAttempts) {
        return false;
    }

    // Record this attempt
    $_SESSION[$key][] = $now;
    return true;
}

/**
 * Validate required fields in POST data
 *
 * @param array $fields Array of field names
 * @return array Array of missing field names
 */
function validateRequired(array $fields): array
{
    $missing = [];
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $missing[] = $field;
        }
    }
    return $missing;
}

/**
 * Generate a secure random token
 */
function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

/**
 * Get client IP address
 */
function getClientIp(): string
{
    $headers = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'REMOTE_ADDR'];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = explode(',', $_SERVER[$header])[0];
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

/**
 * Set security headers
 */
function setSecurityHeaders(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
