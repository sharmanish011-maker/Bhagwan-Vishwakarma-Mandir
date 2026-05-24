<?php
/**
 * =====================================================
 * Navigation Bar — Sticky Top with Mega Menu
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bvm-navbar sticky-top" id="mainNavbar">
    <div class="container">
        <!-- Temple Logo & Name -->
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
            <span class="navbar-logo-icon">ॐ</span>
            <div class="navbar-brand-text">
                <span class="brand-name"><?= getCurrentLang() === 'hi' ? getSetting('site_name_hi', SITE_NAME_HI) : getSetting('site_name_en', SITE_NAME) ?></span>
                <span class="brand-tagline"><?= getCurrentLang() === 'hi' ? getSetting('site_tagline_hi', 'ब्रह्मांड के दिव्य वास्तुकार') : getSetting('site_tagline_en', SITE_TAGLINE) ?></span>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link <?= activeClass('home') ?>" href="<?= BASE_URL ?>">
                        <i class="fas fa-home d-lg-none me-2"></i><?= __('nav.home') ?>
                    </a>
                </li>

                <!-- About Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage ?? '', ['about', 'history', 'committee']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-om d-lg-none me-2"></i><?= __('nav.about') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/about"><i class="fas fa-om me-2"></i><?= __('nav.about_vishwakarma') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/history"><i class="fas fa-landmark me-2"></i><?= __('nav.temple_history') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/committee"><i class="fas fa-users me-2"></i><?= __('nav.committee') ?></a></li>
                    </ul>
                </li>

                <!-- Darshan Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage ?? '', ['darshan', 'timings', 'how-to-reach']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-place-of-worship d-lg-none me-2"></i><?= __('nav.darshan') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/darshan"><i class="fas fa-hands-praying me-2"></i><?= __('nav.darshan') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/timings"><i class="fas fa-clock me-2"></i><?= __('nav.timings') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/how-to-reach"><i class="fas fa-map-marker-alt me-2"></i><?= __('nav.how_to_reach') ?></a></li>
                    </ul>
                </li>

                <!-- Seva & Puja -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage ?? '', ['seva-puja', 'book-puja']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-fire d-lg-none me-2"></i><?= __('nav.seva_puja') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/seva-puja"><i class="fas fa-fire me-2"></i><?= __('nav.seva_puja') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/book-puja"><i class="fas fa-calendar-check me-2"></i><?= __('nav.book_puja') ?></a></li>
                    </ul>
                </li>

                <!-- Events -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage ?? '', ['events', 'upcoming-events']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-days d-lg-none me-2"></i><?= __('nav.events') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/events"><i class="fas fa-calendar-days me-2"></i><?= __('nav.events') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/upcoming-events"><i class="fas fa-bell me-2"></i><?= __('nav.upcoming') ?></a></li>
                    </ul>
                </li>

                <!-- Gallery -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage ?? '', ['gallery', 'video-gallery']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-images d-lg-none me-2"></i><?= __('nav.gallery') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/gallery"><i class="fas fa-images me-2"></i><?= __('nav.photo_gallery') ?></a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/video-gallery"><i class="fas fa-video me-2"></i><?= __('nav.video_gallery') ?></a></li>
                    </ul>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a class="nav-link <?= activeClass('contact') ?>" href="<?= BASE_URL ?>/contact">
                        <i class="fas fa-envelope d-lg-none me-2"></i><?= __('nav.contact') ?>
                    </a>
                </li>

                <!-- Language Toggle -->
                <li class="nav-item ms-lg-2">
                    <?= getLanguageToggle() ?>
                </li>

                <!-- Donate CTA Button -->
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-donate-nav" href="<?= BASE_URL ?>/donations">
                        <i class="fas fa-hand-holding-heart me-1"></i><?= __('nav.donate_now') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
