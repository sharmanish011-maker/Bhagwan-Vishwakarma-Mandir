<?php
/**
 * =====================================================
 * Bhagwan Vishwakarma Mandir — Main Configuration
 * =====================================================
 * Central configuration for database, site settings,
 * and environment-specific options.
 */

// Prevent direct access
if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

// =====================================================
// ENVIRONMENT
// =====================================================
define('BVM_ENV', 'development'); // 'development' or 'production'

// =====================================================
// DATABASE CONFIGURATION
// =====================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'bvm_temple');
define('DB_USER', 'root');
define('DB_PASS', '');          // Change in production
define('DB_CHARSET', 'utf8mb4');

// =====================================================
// SITE CONFIGURATION
// =====================================================
define('SITE_NAME', 'Bhagwan Vishwakarma Mandir');
define('SITE_NAME_HI', 'भगवान विश्वकर्मा मंदिर');
define('SITE_TAGLINE', 'Divine Architect of the Universe');
define('SITE_EMAIL', 'info@bvm-temple.com');
define('SITE_PHONE', '+91-XXXXXXXXXX');

// =====================================================
// ERROR HANDLING
// =====================================================
if (BVM_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('log_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', BVM_ROOT . '/logs/error.log');
}

// =====================================================
// TIMEZONE
// =====================================================
date_default_timezone_set('Asia/Kolkata');

// =====================================================
// SESSION CONFIGURATION
// =====================================================
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '1800'); // 30 minutes

// Enable secure cookies in production
if (BVM_ENV === 'production') {
    ini_set('session.cookie_secure', '1');
}

// =====================================================
// UPLOAD CONFIGURATION
// =====================================================
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// =====================================================
// PAGINATION
// =====================================================
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// =====================================================
// RATE LIMITING
// =====================================================
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_MINUTES', 15);
define('MAX_FORM_SUBMISSIONS_PER_HOUR', 10);
