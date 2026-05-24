<?php
/**
 * =====================================================
 * HOME PAGE — Bhagwan Vishwakarma Mandir
 * =====================================================
 */

$metaTitle = 'Bhagwan Vishwakarma Mandir - Divine Architect Temple | Darshan, Puja, Events';
$metaDescription = 'Welcome to Bhagwan Vishwakarma Mandir - A sacred temple dedicated to Lord Vishwakarma, the divine architect of the universe. Book puja, make donations, and plan your visit.';
$metaKeywords = 'Vishwakarma Mandir, Vishwakarma Temple, Hindu Temple, Puja Booking, Darshan, Donations';

require_once TEMPLATES_PATH . '/header.php';

// Fetch data for homepage
try {
    $upcomingEvents = Database::query(
        "SELECT * FROM events WHERE is_active = 1 AND start_date > NOW() ORDER BY start_date LIMIT 6"
    );
} catch (Exception $e) { $upcomingEvents = []; }

try {
    $activePujas = Database::query(
        "SELECT * FROM pujas WHERE is_active = 1 ORDER BY sort_order LIMIT 4"
    );
} catch (Exception $e) { $activePujas = []; }

try {
    $testimonials = Database::query(
        "SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY is_featured DESC, id DESC LIMIT 6"
    );
} catch (Exception $e) { $testimonials = []; }

try {
    $nextFestival = Database::queryOne(
        "SELECT * FROM events WHERE is_featured = 1 AND start_date > NOW() AND is_active = 1 ORDER BY start_date LIMIT 1"
    );
} catch (Exception $e) { $nextFestival = null; }

try {
    $galleryImages = Database::query(
        "SELECT gi.*, ga.title_en as album_title FROM gallery_images gi 
         JOIN gallery_albums ga ON gi.album_id = ga.id 
         WHERE gi.is_active = 1 ORDER BY gi.id DESC LIMIT 6"
    );
} catch (Exception $e) { $galleryImages = []; }

$lang = getCurrentLang();
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="hero">
    <!-- Floating Particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <div class="hero-overlay"></div>

    <div class="hero-content fade-in-up">
        <span class="hero-om">ॐ</span>
        <p class="hero-welcome"><?= __('hero.welcome') ?></p>
        <h1 class="hero-title"><?= __('hero.temple_name') ?></h1>
        <p class="hero-tagline"><?= __('hero.tagline') ?></p>
        <p class="hero-subtitle"><?= __('hero.subtitle') ?></p>
        <div class="hero-buttons">
            <a href="<?= BASE_URL ?>/darshan" class="btn btn-primary-saffron btn-lg">
                <i class="fas fa-hands-praying me-2"></i><?= __('hero.plan_visit') ?>
            </a>
            <a href="<?= BASE_URL ?>/donations" class="btn btn-outline-gold btn-lg">
                <i class="fas fa-hand-holding-heart me-2"></i><?= __('hero.donate_now') ?>
            </a>
        </div>
    </div>

    <a href="#quick-info" class="hero-scroll" aria-label="Scroll down">
        <i class="fas fa-chevron-down"></i>
    </a>
</section>

<!-- ==================== QUICK INFO BAR ==================== -->
<section class="quick-info-bar" id="quick-info">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-4">
                <div class="quick-info-item fade-in-up">
                    <div class="quick-info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="quick-info-text">
                        <h5><?= __('info.timings') ?></h5>
                        <p><?= __('info.morning') ?>: <?= e(getSetting('morning_opening', '05:00')) ?> - <?= e(getSetting('morning_closing', '12:00')) ?></p>
                        <p><?= __('info.evening') ?>: <?= e(getSetting('evening_opening', '16:00')) ?> - <?= e(getSetting('evening_closing', '21:00')) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="quick-info-item fade-in-up">
                    <div class="quick-info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="quick-info-text">
                        <h5><?= __('info.address') ?></h5>
                        <p><?= e(getSetting($lang === 'hi' ? 'site_address_hi' : 'site_address_en', '')) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="quick-info-item fade-in-up">
                    <div class="quick-info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="quick-info-text">
                        <h5><?= __('info.contact') ?></h5>
                        <p><i class="fas fa-phone-alt me-1"></i> <?= e(getSetting('site_phone', '')) ?></p>
                        <p><i class="fas fa-envelope me-1"></i> <?= e(getSetting('site_email', '')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== ABOUT SECTION ==================== -->
<section class="section-padding bg-cream" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 fade-in-left">
                <h2 class="section-title text-start"><?= __('sections.about_title') ?></h2>
                <p class="text-muted mb-3" style="font-size: 1.1rem;"><?= __('sections.about_subtitle') ?></p>
                <p class="mb-4"><?= __('sections.about_text') ?></p>
                <a href="<?= BASE_URL ?>/about" class="btn btn-primary-saffron">
                    <?= __('sections.read_more') ?> <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <div class="col-lg-6 fade-in-right">
                <div class="position-relative animate-hover">
                    <img src="<?= BASE_URL ?>/assets/images/about_vishwakarma.jpg" alt="Bhagwan Vishwakarma" class="img-fluid rounded-4 shadow-lg" style="width: 100%; aspect-ratio: 4/3; object-fit: cover; border: 2px solid var(--gold); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <!-- Decorative element -->
                    <div style="position: absolute; bottom: -15px; right: -15px; width: 120px; height: 120px; border: 3px solid var(--gold); border-radius: 16px; z-index: -1;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TEMPLE SIGNIFICANCE ==================== -->
<section class="section-padding" id="significance">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('sections.significance') ?></h2>
        <p class="section-subtitle fade-in-up"><?= __('sections.about_subtitle') ?></p>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="significance-card">
                    <div class="significance-icon"><i class="fas fa-place-of-worship"></i></div>
                    <h4><?= __('significance.architecture') ?></h4>
                    <p><?= __('significance.architecture_desc') ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="significance-card">
                    <div class="significance-icon"><i class="fas fa-hands-helping"></i></div>
                    <h4><?= __('significance.community') ?></h4>
                    <p><?= __('significance.community_desc') ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="significance-card">
                    <div class="significance-icon"><i class="fas fa-om"></i></div>
                    <h4><?= __('significance.spirituality') ?></h4>
                    <p><?= __('significance.spirituality_desc') ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="significance-card">
                    <div class="significance-icon"><i class="fas fa-landmark"></i></div>
                    <h4><?= __('significance.heritage') ?></h4>
                    <p><?= __('significance.heritage_desc') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== UPCOMING EVENTS ==================== -->
<section class="section-padding bg-cream" id="events">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('sections.events_title') ?></h2>
        <p class="section-subtitle fade-in-up"><?= __('sections.events_subtitle') ?></p>

        <?php if (!empty($upcomingEvents)): ?>
        <div class="row g-4">
            <?php foreach (array_slice($upcomingEvents, 0, 3) as $event): ?>
            <div class="col-lg-4 col-md-6 fade-in-up">
                <div class="event-card">
                    <div class="event-image">
                        <?php if (!empty($event['image'])): ?>
                            <img src="<?= EVENTS_URL ?>/<?= e($event['image']) ?>" alt="<?= e(getBilingualContent($event, 'title')) ?>" loading="lazy">
                        <?php else: ?>
                            <i class="fas fa-calendar-star"></i>
                        <?php endif; ?>
                    </div>
                    <div class="event-date-badge">
                        <span class="day"><?= date('d', strtotime($event['start_date'])) ?></span>
                        <span class="month"><?= date('M', strtotime($event['start_date'])) ?></span>
                    </div>
                    <div class="card-body">
                        <span class="event-category-badge"><?= e(__('categories.' . $event['category'])) ?></span>
                        <h4 class="mt-2 mb-2"><?= e(getBilingualContent($event, 'title')) ?></h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i>
                            <?= formatDateTime($event['start_date'], 'd M Y, h:i A') ?>
                        </p>
                        <p class="mb-3"><?= truncate(e(getBilingualContent($event, 'short_desc') ?: getBilingualContent($event, 'description')), 100) ?></p>
                        <a href="<?= BASE_URL ?>/events" class="btn btn-outline-saffron btn-sm">
                            <?= __('common.learn_more') ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/upcoming-events" class="btn btn-primary-saffron">
                <?= __('common.view_all') ?> <?= __('nav.events') ?> <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php else: ?>
        <div class="text-center py-4">
            <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3"><?= $lang === 'hi' ? 'जल्द ही कार्यक्रम आ रहे हैं' : 'Events coming soon' ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ==================== SEVA & PUJA ==================== -->
<section class="section-padding bg-maroon" id="pujas">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('nav.seva_puja') ?></h2>
        <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'मंदिर में उपलब्ध पूजा सेवाएं' : 'Puja services available at the temple' ?></p>

        <?php if (!empty($activePujas)): ?>
        <div class="row g-4">
            <?php foreach ($activePujas as $puja): ?>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="puja-card">
                    <div class="puja-icon"><i class="fas <?= e($puja['icon'] ?? 'fa-om') ?>"></i></div>
                    <h4><?= e(getBilingualContent($puja, 'name')) ?></h4>
                    <p class="text-muted" style="font-size: 0.875rem;"><?= truncate(e(getBilingualContent($puja, 'short_desc') ?: getBilingualContent($puja, 'description')), 80) ?></p>
                    <p class="puja-price"><?= formatCurrency((float) $puja['price']) ?></p>
                    <p class="puja-duration"><i class="fas fa-clock me-1"></i><?= e($puja['duration'] ?? '') ?></p>
                    <a href="<?= BASE_URL ?>/book-puja?puja_id=<?= $puja['id'] ?>" class="btn btn-gold btn-sm">
                        <i class="fas fa-calendar-check me-1"></i><?= __('booking.submit') ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/seva-puja" class="btn btn-outline-gold btn-lg">
                <?= __('common.view_all') ?> <?= __('nav.seva_puja') ?> <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ==================== GALLERY STRIP ==================== -->
<section class="section-padding" id="gallery">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('sections.gallery_title') ?></h2>
        <p class="section-subtitle fade-in-up"><?= __('sections.gallery_subtitle') ?></p>

        <div class="gallery-grid fade-in-up">
            <?php if (!empty($galleryImages)): ?>
                <?php foreach ($galleryImages as $img): ?>
                <a href="<?= GALLERY_URL ?>/<?= e($img['image_path']) ?>" class="gallery-item glightbox" data-gallery="home-gallery" data-glightbox="title: <?= e($img['title'] ?? '') ?>">
                    <img src="<?= GALLERY_URL ?>/<?= e($img['thumbnail_path'] ?? $img['image_path']) ?>" alt="<?= e($img['alt_text'] ?? $img['title'] ?? 'Temple Photo') ?>" loading="lazy">
                    <div class="gallery-overlay">
                        <span class="gallery-title"><?= e($img['title'] ?? '') ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <?php 
                $placeholders = ['🛕', '🙏', '🪔', '🌺', '⛩️', '🔔'];
                foreach ($placeholders as $emoji): ?>
                <div class="gallery-placeholder">
                    <span><?= $emoji ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/gallery" class="btn btn-primary-saffron">
                <?= __('sections.view_gallery') ?> <i class="fas fa-images ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS ==================== -->
<?php if (!empty($testimonials)): ?>
<section class="section-padding bg-cream" id="testimonials">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('sections.testimonials') ?></h2>
        <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'हमारे भक्तों के अनुभव' : 'What our devotees say' ?></p>

        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <?php foreach (array_chunk($testimonials, 2) as $index => $chunk): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="row g-4 justify-content-center px-3">
                        <?php foreach ($chunk as $testimonial): ?>
                        <div class="col-md-6">
                            <div class="testimonial-card">
                                <div class="rating-stars mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?= $i <= $testimonial['rating'] ? '' : '-half-alt' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="testimonial-text"><?= e(getBilingualContent($testimonial, 'message')) ?></p>
                                <div class="testimonial-author">
                                    <div class="author-photo">
                                        <?= strtoupper(mb_substr($testimonial['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="author-name"><?= e($testimonial['name']) ?></div>
                                        <?php if (!empty($testimonial['location'])): ?>
                                            <div class="author-location"><i class="fas fa-map-marker-alt me-1"></i><?= e($testimonial['location']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($testimonials) > 2): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== FESTIVAL COUNTDOWN ==================== -->
<?php if ($nextFestival): ?>
<section class="section-padding bg-maroon" id="countdown">
    <div class="container text-center">
        <h2 class="section-title fade-in-up"><?= e(getBilingualContent($nextFestival, 'title')) ?></h2>
        <p class="section-subtitle fade-in-up"><?= truncate(e(getBilingualContent($nextFestival, 'description')), 150) ?></p>

        <div class="countdown fade-in-up" data-target-date="<?= e($nextFestival['start_date']) ?>">
            <div class="countdown-item">
                <div class="countdown-number" id="countdown-days">--</div>
                <div class="countdown-label"><?= __('common.days') ?></div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="countdown-hours">--</div>
                <div class="countdown-label"><?= __('common.hours') ?></div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="countdown-minutes">--</div>
                <div class="countdown-label"><?= __('common.minutes') ?></div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="countdown-seconds">--</div>
                <div class="countdown-label"><?= __('common.seconds') ?></div>
            </div>
        </div>

        <a href="<?= BASE_URL ?>/events" class="btn btn-gold btn-lg mt-4 fade-in-up">
            <i class="fas fa-calendar-alt me-2"></i><?= __('common.learn_more') ?>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- ==================== NEWSLETTER ==================== -->
<section class="section-padding newsletter-section" id="newsletter">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center fade-in-up">
                <h2 class="section-title"><?= __('sections.newsletter_title') ?></h2>
                <p class="mb-4" style="color: rgba(255,255,255,0.9);"><?= __('sections.newsletter_text') ?></p>
                <form class="newsletter-form" id="homeNewsletterForm">
                    <div class="input-group input-group-lg">
                        <input type="email" class="form-control" placeholder="<?= __('contact.email') ?>" name="email" required>
                        <button class="btn btn-gold" type="submit">
                            <i class="fas fa-paper-plane me-1"></i> <?= __('sections.newsletter_btn') ?>
                        </button>
                    </div>
                </form>
                <div id="newsletterMessage" class="mt-3" style="display:none;"></div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== DONATION CTA ==================== -->
<section class="section-padding bg-cream" id="donate-cta">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 fade-in-left">
                <h2 class="section-title text-start"><?= __('sections.donation_title') ?></h2>
                <p class="mb-4"><?= __('sections.donation_text') ?></p>
                <div class="amount-presets mb-3">
                    <button class="amount-btn" data-amount="101">₹101</button>
                    <button class="amount-btn" data-amount="501">₹501</button>
                    <button class="amount-btn active" data-amount="1001">₹1,001</button>
                    <button class="amount-btn" data-amount="5001">₹5,001</button>
                </div>
                <a href="<?= BASE_URL ?>/donations" class="btn btn-primary-saffron btn-lg">
                    <i class="fas fa-hand-holding-heart me-2"></i><?= __('hero.donate_now') ?>
                </a>
            </div>
            <div class="col-lg-5 text-center fade-in-right">
                <div style="font-size: 8rem; opacity: 0.2; color: var(--gold);">🙏</div>
            </div>
        </div>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
