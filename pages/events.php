<?php
/** Events & Festivals Page */
$metaTitle = 'Events & Festivals | ' . SITE_NAME;
$metaDescription = 'Discover upcoming events, festivals, and celebrations at Bhagwan Vishwakarma Mandir.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();
try { $events = Database::query("SELECT * FROM events WHERE is_active = 1 ORDER BY start_date"); } catch (Exception $e) { $events = []; }
try { $featured = Database::queryOne("SELECT * FROM events WHERE is_featured = 1 AND is_active = 1 AND start_date > NOW() ORDER BY start_date LIMIT 1"); } catch (Exception $e) { $featured = null; }
?>
<section class="page-header"><div class="container"><h1><i class="fas fa-calendar-days me-2"></i><?= __('nav.events') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.events')]]) ?></div></section>

<?php if ($featured): ?>
<section class="section-padding bg-maroon">
    <div class="container text-center">
        <span class="event-category-badge mb-3 d-inline-block" style="background:rgba(255,215,0,0.2);color:var(--gold-light);">⭐ <?= __('common.featured_event') ?></span>
        <h2 class="text-gold mb-3"><?= e(getBilingualContent($featured, 'title')) ?></h2>
        <p style="color:rgba(255,255,255,0.8);" class="mb-4"><?= truncate(e(getBilingualContent($featured, 'description')), 200) ?></p>
        <div class="countdown" data-target-date="<?= e($featured['start_date']) ?>">
            <div class="countdown-item"><div class="countdown-number">--</div><div class="countdown-label"><?= __('common.days') ?></div></div>
            <div class="countdown-item"><div class="countdown-number">--</div><div class="countdown-label"><?= __('common.hours') ?></div></div>
            <div class="countdown-item"><div class="countdown-number">--</div><div class="countdown-label"><?= __('common.minutes') ?></div></div>
            <div class="countdown-item"><div class="countdown-number">--</div><div class="countdown-label"><?= __('common.seconds') ?></div></div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section-padding">
    <div class="container">
        <div class="filter-buttons mb-4 fade-in-up">
            <button class="filter-btn active" data-category="all" data-target=".event-filter-item"><?= __('common.all') ?></button>
            <button class="filter-btn" data-category="festival" data-target=".event-filter-item"><?= __('categories.festival') ?></button>
            <button class="filter-btn" data-category="puja" data-target=".event-filter-item"><?= __('categories.puja') ?></button>
            <button class="filter-btn" data-category="cultural" data-target=".event-filter-item"><?= __('categories.cultural') ?></button>
            <button class="filter-btn" data-category="community" data-target=".event-filter-item"><?= __('categories.community') ?></button>
        </div>
        <?php if (!empty($events)): ?>
        <div class="row g-4">
            <?php foreach ($events as $event): ?>
            <div class="col-lg-4 col-md-6 event-filter-item filter-item fade-in-up" data-category="<?= e($event['category']) ?>">
                <div class="event-card">
                    <div class="event-image">
                        <?php if (!empty($event['image'])): ?>
                            <img src="<?= EVENTS_URL ?>/<?= e($event['image']) ?>" alt="<?= e(getBilingualContent($event, 'title')) ?>" loading="lazy">
                        <?php else: ?>
                            <i class="fas fa-calendar-star"></i>
                        <?php endif; ?>
                    </div>
                    <div class="event-date-badge"><span class="day"><?= date('d', strtotime($event['start_date'])) ?></span><span class="month"><?= date('M Y', strtotime($event['start_date'])) ?></span></div>
                    <div class="card-body">
                        <span class="event-category-badge"><?= e(__('categories.' . $event['category'])) ?></span>
                        <?php if ($event['is_featured']): ?><span class="badge bg-warning ms-1">⭐ <?= __('common.featured') ?></span><?php endif; ?>
                        <h4 class="mt-2"><?= e(getBilingualContent($event, 'title')) ?></h4>
                        <p class="text-muted small"><i class="fas fa-clock me-1"></i><?= formatDateTime($event['start_date']) ?></p>
                        <p><?= truncate(e(getBilingualContent($event, 'description')), 100) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5"><i class="fas fa-calendar text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3"><?= __('common.no_events') ?></p></div>
        <?php endif; ?>
    </div>
</section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
