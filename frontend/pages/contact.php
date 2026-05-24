<?php
/**
 * Contact Us Page
 */
$metaTitle = 'Contact Us | ' . SITE_NAME;
$metaDescription = 'Get in touch with Bhagwan Vishwakarma Mandir. Find our address, phone number, email, and send us a message.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken()) { setFlash('danger', 'Security validation failed.'); redirect(BASE_URL . '/contact'); }
    
    $missing = validateRequired(['name', 'email', 'subject', 'message']);
    if (!empty($missing)) { setFlash('warning', 'Please fill all required fields.'); redirect(BASE_URL . '/contact'); }

    if (!checkRateLimit('contact_form', MAX_FORM_SUBMISSIONS_PER_HOUR, 60)) {
        setFlash('warning', 'Too many submissions. Please try again later.'); redirect(BASE_URL . '/contact');
    }

    try {
        Database::insert(
            "INSERT INTO contact_messages (name, email, phone, subject, message, ip_address) VALUES (:name, :email, :phone, :subject, :message, :ip)",
            ['name' => sanitize($_POST['name']), 'email' => sanitizeEmail($_POST['email']), 'phone' => sanitizePhone($_POST['phone'] ?? ''), 'subject' => sanitize($_POST['subject']), 'message' => sanitize($_POST['message']), 'ip' => getClientIp()]
        );
        setFlash('success', __('contact.success'));
    } catch (Exception $e) {
        setFlash('danger', 'An error occurred. Please try again.');
    }
    redirect(BASE_URL . '/contact');
}
?>

<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-envelope me-2"></i><?= __('contact.title') ?></h1>
        <?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('contact.title')]]) ?>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7 fade-in-left">
                <h3 class="text-maroon mb-4"><?= __('contact.get_in_touch') ?></h3>
                <form method="POST" class="bvm-form" id="contactForm">
                    <?= csrfField() ?>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('contact.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('contact.email') ?> <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('contact.phone') ?></label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('contact.subject') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label"><?= __('contact.message') ?> <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-saffron btn-lg">
                        <i class="fas fa-paper-plane me-2"></i><?= __('contact.submit') ?>
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5 fade-in-right">
                <h3 class="text-maroon mb-4"><?= __('contact.visit_us') ?></h3>
                
                <div class="bvm-card mb-4">
                    <div class="card-body">
                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-box icon-box-saffron"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h5 class="mb-1"><?= __('info.address') ?></h5>
                                <p class="text-muted mb-0"><?= e(getSetting($lang === 'hi' ? 'site_address_hi' : 'site_address_en', '')) ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-box icon-box-saffron"><i class="fas fa-phone"></i></div>
                            <div>
                                <h5 class="mb-1"><?= __('info.phone') ?></h5>
                                <p class="text-muted mb-0"><a href="tel:<?= e(getSetting('site_phone')) ?>"><?= e(getSetting('site_phone')) ?></a></p>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-box icon-box-saffron"><i class="fas fa-envelope"></i></div>
                            <div>
                                <h5 class="mb-1"><?= __('info.email') ?></h5>
                                <p class="text-muted mb-0"><a href="mailto:<?= e(getSetting('site_email')) ?>"><?= e(getSetting('site_email')) ?></a></p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="icon-box icon-box-saffron"><i class="fas fa-clock"></i></div>
                            <div>
                                <h5 class="mb-1"><?= __('info.timings') ?></h5>
                                <p class="text-muted mb-0"><?= e(getSetting('morning_opening')) ?> - <?= e(getSetting('morning_closing')) ?> | <?= e(getSetting('evening_opening')) ?> - <?= e(getSetting('evening_closing')) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp -->
                <?php $wa = getSetting('site_whatsapp'); if (!empty($wa)): ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $wa) ?>" target="_blank" class="btn btn-success btn-lg w-100 mb-4">
                    <i class="fab fa-whatsapp me-2"></i><?= __('contact.whatsapp') ?>
                </a>
                <?php endif; ?>

                <!-- Google Maps -->
                <div class="rounded-3 overflow-hidden" style="height: 250px; background: var(--cream);">
                    <?php $mapUrl = getSetting('google_maps_embed'); if (!empty($mapUrl)): ?>
                        <iframe src="<?= e($mapUrl) ?>" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marked-alt" style="font-size: 2.5rem;"></i>
                                <p class="mt-2"><?= $lang === 'hi' ? 'गूगल मैप्स यहाँ दिखेगा' : 'Google Maps will appear here' ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
