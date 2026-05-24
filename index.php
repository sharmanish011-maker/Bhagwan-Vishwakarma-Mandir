<?php
/**
 * =====================================================
 * Bhagwan Vishwakarma Mandir — Front Controller
 * =====================================================
 * Single entry point for all frontend requests.
 * Routes URLs to page controllers.
 */

// Define root path constant
define('BVM_ROOT', __DIR__);

// =====================================================
// BOOTSTRAP: Load configuration and core files
// =====================================================
require_once BVM_ROOT . '/backend/config/constants.php';
require_once BVM_ROOT . '/backend/config/config.php';
require_once FUNCTIONS_PATH . '/database.php';
require_once FUNCTIONS_PATH . '/security.php';
require_once FUNCTIONS_PATH . '/helpers.php';
require_once FUNCTIONS_PATH . '/auth.php';
require_once FUNCTIONS_PATH . '/seo.php';
require_once FUNCTIONS_PATH . '/language.php';
require_once FUNCTIONS_PATH . '/upload.php';
require_once FUNCTIONS_PATH . '/mail.php';
require_once CONFIG_PATH . '/payment.php';


// =====================================================
// START SESSION
// =====================================================
session_start();

// Set security headers
setSecurityHeaders();

// Handle language switch
handleLanguageSwitch();

// =====================================================
// ROUTING
// =====================================================
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'home';
$url = filter_var($url, FILTER_SANITIZE_URL);

// Route map: URL slug => page file
$routes = [
    'home'              => 'home',
    ''                  => 'home',
    'about'             => 'about',
    'history'           => 'history',
    'committee'         => 'committee',
    'darshan'           => 'darshan',
    'timings'           => 'timings',
    'how-to-reach'      => 'how-to-reach',
    'seva-puja'         => 'seva-puja',
    'book-puja'         => 'book-puja',
    'events'            => 'events',
    'upcoming-events'   => 'upcoming-events',
    'gallery'           => 'gallery',
    'video-gallery'     => 'video-gallery',
    'donations'         => 'donations',
    'volunteer'         => 'volunteer',
    'community'         => 'community',
    'contact'           => 'contact',
    'privacy-policy'    => 'privacy-policy',
    'terms'             => 'terms',
];

// Set current page for navigation highlighting
$currentPage = $url;

// Determine page file
if (array_key_exists($url, $routes)) {
    $pageFile = PAGES_PATH . '/' . $routes[$url] . '.php';
} else {
    // 404 - Page not found
    http_response_code(404);
    $pageFile = PAGES_PATH . '/home.php';
    $currentPage = 'error';
    $errorCode = 404;
}

// Handle error pages
if (isset($_GET['code'])) {
    $errorCode = (int) $_GET['code'];
    $currentPage = 'error';
}

// =====================================================
// LOAD PAGE
// =====================================================
if (file_exists($pageFile)) {
    require_once $pageFile;
} else {
    // Fallback
    http_response_code(404);
    require_once TEMPLATES_PATH . '/header.php';
    echo '<div class="container py-5 text-center">';
    echo '<h1 class="display-4 text-maroon">🙏 ' . __('errors.404_title') . '</h1>';
    echo '<p class="lead">' . __('errors.404_text') . '</p>';
    echo '<a href="' . BASE_URL . '" class="btn btn-primary-saffron mt-3">' . __('errors.go_home') . '</a>';
    echo '</div>';
    require_once TEMPLATES_PATH . '/footer.php';
}
