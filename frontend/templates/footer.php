<?php
/**
 * =====================================================
 * Footer Template
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}
?>
    </main><!-- End Main Content -->

    <!-- Floating Donate Button -->
    <?php require_once TEMPLATES_PATH . '/floating-donate.php'; ?>

    <!-- Footer -->
    <footer class="bvm-footer" id="footer">
        <!-- Footer Top — Wave Separator -->
        <div class="footer-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path fill="currentColor" d="M0,50 C360,100 720,0 1080,50 C1260,75 1380,60 1440,50 L1440,100 L0,100 Z"></path>
            </svg>
        </div>

        <div class="footer-main">
            <div class="container">
                <div class="row g-4">
                    <!-- About Column -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand">
                            <span class="footer-logo-icon">ॐ</span>
                            <h4><?= getCurrentLang() === 'hi' ? getSetting('site_name_hi', SITE_NAME_HI) : getSetting('site_name_en', SITE_NAME) ?></h4>
                        </div>
                        <p class="footer-about-text"><?= __('footer.about_text') ?></p>
                        <div class="footer-social">
                            <h6><?= __('footer.follow_us') ?></h6>
                            <div class="social-icons">
                                <?php if ($fb = getSetting('facebook_url')): ?>
                                    <a href="<?= e($fb) ?>" target="_blank" rel="noopener" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if ($ig = getSetting('instagram_url')): ?>
                                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if ($yt = getSetting('youtube_url')): ?>
                                    <a href="<?= e($yt) ?>" target="_blank" rel="noopener" title="YouTube"><i class="fab fa-youtube"></i></a>
                                <?php endif; ?>
                                <?php if ($tw = getSetting('twitter_url')): ?>
                                    <a href="<?= e($tw) ?>" target="_blank" rel="noopener" title="Twitter"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if ($wa = getSetting('site_whatsapp')): ?>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa) ?>" target="_blank" rel="noopener" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-heading"><?= __('footer.quick_links') ?></h5>
                        <ul class="footer-links">
                            <li><a href="<?= BASE_URL ?>"><i class="fas fa-chevron-right"></i> <?= __('nav.home') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/about"><i class="fas fa-chevron-right"></i> <?= __('nav.about') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/darshan"><i class="fas fa-chevron-right"></i> <?= __('nav.darshan') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/events"><i class="fas fa-chevron-right"></i> <?= __('nav.events') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/gallery"><i class="fas fa-chevron-right"></i> <?= __('nav.gallery') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/volunteer"><i class="fas fa-chevron-right"></i> <?= __('nav.volunteer') ?></a></li>
                        </ul>
                    </div>

                    <!-- Services Links -->
                    <div class="col-lg-3 col-md-6">
                        <h5 class="footer-heading"><?= __('footer.services') ?></h5>
                        <ul class="footer-links">
                            <li><a href="<?= BASE_URL ?>/seva-puja"><i class="fas fa-chevron-right"></i> <?= __('nav.seva_puja') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/book-puja"><i class="fas fa-chevron-right"></i> <?= __('nav.book_puja') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/donations"><i class="fas fa-chevron-right"></i> <?= __('nav.donations') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/community"><i class="fas fa-chevron-right"></i> <?= __('nav.community') ?></a></li>
                            <li><a href="<?= BASE_URL ?>/privacy-policy"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                            <li><a href="<?= BASE_URL ?>/terms"><i class="fas fa-chevron-right"></i> Terms & Conditions</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info + Newsletter -->
                    <div class="col-lg-3 col-md-6">
                        <h5 class="footer-heading"><?= __('footer.contact_info') ?></h5>
                        <div class="footer-contact">
                            <p><i class="fas fa-map-marker-alt"></i> <?= e(getSetting('site_address_en', '')) ?></p>
                            <p><i class="fas fa-phone"></i> <a href="tel:<?= e(getSetting('site_phone', '')) ?>"><?= e(getSetting('site_phone', '')) ?></a></p>
                            <p><i class="fas fa-envelope"></i> <a href="mailto:<?= e(getSetting('site_email', '')) ?>"><?= e(getSetting('site_email', '')) ?></a></p>
                        </div>

                        <!-- Newsletter -->
                        <div class="footer-newsletter mt-3">
                            <h6><?= __('footer.newsletter') ?></h6>
                            <form class="newsletter-form" id="footerNewsletterForm">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="<?= __('contact.email') ?>" name="email" required>
                                    <button class="btn btn-gold" type="submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0"><?= __('footer.copyright') ?></p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> & 🙏 devotion</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="btn-back-to-top" id="backToTop" title="Back to Top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Bootstrap JS Bundle -->
    <script src="<?= BOOTSTRAP_JS ?>" crossorigin="anonymous"></script>

    <!-- GLightbox JS -->
    <script src="<?= GLIGHTBOX_JS ?>"></script>

    <!-- Custom JS -->
    <script src="<?= JS_URL ?>/main.js?v=<?= filemtime(ASSETS_PATH . '/js/main.js') ?? time() ?>"></script>

    <?php if (isset($pageScripts)) echo $pageScripts; ?>
</body>
</html>
