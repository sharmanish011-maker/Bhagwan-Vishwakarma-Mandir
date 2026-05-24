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
define('INCLUDES_PATH', BVM_ROOT . '/includes');
define('CONFIG_PATH', INCLUDES_PATH . '/config');
define('FUNCTIONS_PATH', INCLUDES_PATH . '/functions');
define('MODELS_PATH', INCLUDES_PATH . '/models');
define('TEMPLATES_PATH', INCLUDES_PATH . '/templates');
define('PAGES_PATH', BVM_ROOT . '/pages');
define('ADMIN_PATH', BVM_ROOT . '/admin');
define('UPLOADS_PATH', BVM_ROOT . '/uploads');
define('GALLERY_UPLOAD_PATH', UPLOADS_PATH . '/gallery');
define('GALLERY_PATH', GALLERY_UPLOAD_PATH); // Alias
define('EVENTS_UPLOAD_PATH', UPLOADS_PATH . '/events');
define('LANG_PATH', BVM_ROOT . '/lang');
define('ASSETS_PATH', BVM_ROOT . '/assets');

// =====================================================
// URL CONSTANTS
// =====================================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . '://' . $host . '/BVM');
define('ASSETS_URL', BASE_URL . '/assets');
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
