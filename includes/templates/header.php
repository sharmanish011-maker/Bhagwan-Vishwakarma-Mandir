<?php
/**
 * =====================================================
 * Header Template — HTML Head & Meta
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

// Default meta tags - can be overridden by pages
$metaTitle = $metaTitle ?? getSetting('meta_title', SITE_NAME . ' - ' . SITE_TAGLINE);
$metaDescription = $metaDescription ?? getSetting('meta_description', '');
$metaKeywords = $metaKeywords ?? '';
$ogImage = $ogImage ?? IMAGES_URL . '/og-image.jpg';
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLang() ?>" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <?= getMetaTags([
        'title'       => $metaTitle,
        'description' => $metaDescription,
        'keywords'    => $metaKeywords,
        'image'       => $ogImage,
    ]) ?>

    <!-- CSRF Token for AJAX -->
    <?= csrfMeta() ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_URL ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?= IMAGES_URL ?>/apple-touch-icon.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="<?= GOOGLE_FONTS ?>" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="<?= BOOTSTRAP_CSS ?>" rel="stylesheet" crossorigin="anonymous">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="<?= FONTAWESOME_CSS ?>">

    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="<?= GLIGHTBOX_CSS ?>">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css?v=<?= filemtime(ASSETS_PATH . '/css/style.css') ?? time() ?>">

    <!-- Schema.org Structured Data -->
    <?= getTempleSchema() ?>

    <?php if (isset($pageSchema)) echo $pageSchema; ?>

    <!-- Google Analytics -->
    <?= getAnalyticsScript() ?>

    <!-- Global Base URL for AJAX/JS -->
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body class="<?= isset($bodyClass) ? e($bodyClass) : '' ?>">

    <!-- Skip to main content (accessibility) -->
    <a class="skip-link visually-hidden-focusable" href="#main-content">Skip to main content</a>

    <!-- Live Announcement Bar -->
    <?php require_once TEMPLATES_PATH . '/announcement-bar.php'; ?>

    <!-- Navigation Bar -->
    <?php require_once TEMPLATES_PATH . '/navbar.php'; ?>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="container mt-3">
            <?= displayFlash() ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main id="main-content">
