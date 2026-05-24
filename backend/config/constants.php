<?php
/**
 * =====================================================
 * Bhagwan Vishwakarma Mandir — Path Constants
 * =====================================================
 */

// Prevent direct access
if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

// =====================================================
// PATH CONSTANTS
// =====================================================
define('BACKEND_PATH', BVM_ROOT . '/backend');
define('INCLUDES_PATH', BACKEND_PATH); // Backward compatibility alias
define('CONFIG_PATH', BACKEND_PATH . '/config');
define('FUNCTIONS_PATH', BACKEND_PATH . '/functions');
define('MODELS_PATH', BACKEND_PATH . '/models');
define('FRONTEND_PATH', BVM_ROOT . '/frontend');
define('TEMPLATES_PATH', FRONTEND_PATH . '/templates');
define('PAGES_PATH', FRONTEND_PATH . '/pages');
define('ADMIN_PATH', BACKEND_PATH . '/admin');
define('UPLOADS_PATH', BVM_ROOT . '/uploads');
define('GALLERY_UPLOAD_PATH', UPLOADS_PATH . '/gallery');
define('GALLERY_PATH', GALLERY_UPLOAD_PATH); // Alias
define('EVENTS_UPLOAD_PATH', UPLOADS_PATH . '/events');
define('LANG_PATH', FRONTEND_PATH . '/lang');
define('ASSETS_PATH', FRONTEND_PATH . '/assets');

// =====================================================
// URL CONSTANTS
// =====================================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Dynamically determine project subfolder relative to DOCUMENT_ROOT
$rootPath = str_replace('\\', '/', BVM_ROOT);
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
$subFolder = '';
if (!empty($docRoot) && stripos($rootPath, $docRoot) === 0) {
    $subFolder = substr($rootPath, strlen($docRoot));
} else {
    $subFolder = '/Bhagwan-Vishwakarma-Mandir';
}
$subFolder = '/' . trim(str_replace('\\', '/', $subFolder), '/');
if ($subFolder === '/') {
    $subFolder = '';
}

define('BASE_URL', $protocol . '://' . $host . $subFolder);
define('ASSETS_URL', BASE_URL . '/frontend/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMAGES_URL', ASSETS_URL . '/images');
define('UPLOADS_URL', BASE_URL . '/uploads');
define('GALLERY_URL', UPLOADS_URL . '/gallery');
define('EVENTS_URL', UPLOADS_URL . '/events');
define('ADMIN_URL', BASE_URL . '/admin');

// =====================================================
// CDN URLS (Bootstrap, Font Awesome, etc.)
// =====================================================
define('BOOTSTRAP_CSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
define('BOOTSTRAP_JS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');
define('FONTAWESOME_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
define('GOOGLE_FONTS', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');
define('GLIGHTBOX_CSS', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css');
define('GLIGHTBOX_JS', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js');
define('RAZORPAY_CHECKOUT_JS', 'https://checkout.razorpay.com/v1/checkout.js');
