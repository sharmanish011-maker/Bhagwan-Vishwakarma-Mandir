<?php
/**
 * Seva & Puja Page
 */
$metaTitle = 'Seva & Puja Services | ' . SITE_NAME;
$metaDescription = 'Explore our puja and seva services. Book special pujas, daily aarti, havan, and spiritual services at Bhagwan Vishwakarma Mandir.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();

try {
    $pujas = Database::query("SELECT * FROM pujas WHERE is_active = 1 ORDER BY sort_order");
} catch (Exception $e) { $pujas = []; }
?>

<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-fire me-2"></i><?= __('nav.seva_puja') ?></h1>
        <?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.seva_puja')]]) ?>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Category Filters -->
        <div class="filter-buttons fade-in-up">
            <button class="filter-btn active" data-category="all" data-target=".puja-filter-item"><?= __('common.all') ?></button>
            <button class="filter-btn" data-category="daily" data-target=".puja-filter-item"><?= __('categories.daily') ?></button>
            <button class="filter-btn" data-category="special" data-target=".puja-filter-item"><?= __('categories.special') ?></button>
            <button class="filter-btn" data-category="festival" data-target=".puja-filter-item"><?= __('categories.festival') ?></button>
            <button class="filter-btn" data-category="personal" data-target=".puja-filter-item"><?= __('categories.personal') ?></button>
        </div>

        <?php if (!empty($pujas)): ?>
        <div class="row g-4">
            <?php foreach ($pujas as $puja): ?>
            <div class="col-lg-4 col-md-6 puja-filter-item filter-item" data-category="<?= e($puja['category']) ?>">
                <div class="puja-card fade-in-up">
                    <div class="puja-icon"><i class="fas <?= e($puja['icon'] ?? 'fa-om') ?>"></i></div>
                    <h4><?= e(getBilingualContent($puja, 'name')) ?></h4>
                    <p class="text-muted" style="font-size: 0.9rem;"><?= truncate(e(getBilingualContent($puja, 'description')), 120) ?></p>
                    <p class="puja-price"><?= formatCurrency((float) $puja['price']) ?></p>
                    <p class="puja-duration"><i class="fas fa-clock me-1"></i><?= e($puja['duration'] ?? '') ?></p>
                    <span class="event-category-badge mb-3 d-inline-block"><?= e(__('categories.' . $puja['category'])) ?></span><br>
                    <a href="<?= BASE_URL ?>/book-puja?puja_id=<?= $puja['id'] ?>" class="btn btn-primary-saffron">
                        <i class="fas fa-calendar-check me-1"></i><?= __('booking.submit') ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-om text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3"><?= $lang === 'hi' ? 'पूजा सेवाएं जल्द उपलब्ध होंगी' : 'Puja services coming soon' ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
