<?php /** Upcoming Events */ $metaTitle = 'Upcoming Events | ' . SITE_NAME; $metaDescription = 'Upcoming events and festivals at Bhagwan Vishwakarma Mandir.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang();
try { $events = Database::query("SELECT * FROM events WHERE is_active = 1 AND start_date > NOW() ORDER BY start_date"); } catch (Exception $e) { $events = []; } ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-bell me-2"></i><?= __('nav.upcoming') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.upcoming')]]) ?></div></section>
<section class="section-padding"><div class="container">
    <?php if (!empty($events)): ?><div class="row g-4"><?php foreach ($events as $ev): ?>
    <div class="col-lg-6 fade-in-up"><div class="bvm-card"><div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div><span class="event-category-badge"><?= e(ucfirst($ev['category'])) ?></span><?php if ($ev['is_featured']): ?><span class="badge bg-warning ms-1">⭐</span><?php endif; ?></div>
            <div class="text-end"><small class="text-muted"><?= formatDateTime($ev['start_date']) ?></small></div>
        </div>
        <h4 class="text-maroon"><?= e(getBilingualContent($ev, 'title')) ?></h4>
        <p class="text-muted"><?= truncate(e(getBilingualContent($ev, 'description')), 150) ?></p>
        <div class="countdown mt-3" data-target-date="<?= e($ev['start_date']) ?>">
            <div class="countdown-item" style="padding:10px 15px;min-width:auto;background:rgba(255,153,51,0.1);border-color:var(--saffron);"><div class="countdown-number" style="font-size:1.5rem;color:var(--saffron);">--</div><div class="countdown-label" style="color:var(--dark-brown);"><?= __('common.days') ?></div></div>
            <div class="countdown-item" style="padding:10px 15px;min-width:auto;background:rgba(255,153,51,0.1);border-color:var(--saffron);"><div class="countdown-number" style="font-size:1.5rem;color:var(--saffron);">--</div><div class="countdown-label" style="color:var(--dark-brown);"><?= __('common.hours') ?></div></div>
            <div class="countdown-item" style="padding:10px 15px;min-width:auto;background:rgba(255,153,51,0.1);border-color:var(--saffron);"><div class="countdown-number" style="font-size:1.5rem;color:var(--saffron);">--</div><div class="countdown-label" style="color:var(--dark-brown);"><?= __('common.minutes') ?></div></div>
            <div class="countdown-item" style="padding:10px 15px;min-width:auto;background:rgba(255,153,51,0.1);border-color:var(--saffron);"><div class="countdown-number" style="font-size:1.5rem;color:var(--saffron);">--</div><div class="countdown-label" style="color:var(--dark-brown);"><?= __('common.seconds') ?></div></div>
        </div>
    </div></div></div>
    <?php endforeach; ?></div>
    <?php else: ?><div class="text-center py-5"><i class="fas fa-calendar text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3"><?= $lang === 'hi' ? 'वर्तमान में कोई आगामी कार्यक्रम नहीं है' : 'No upcoming events at this time' ?></p></div><?php endif; ?>
</div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
