<?php /** Community Announcements */ $metaTitle = 'Community | ' . SITE_NAME; $metaDescription = 'Community announcements and updates from Bhagwan Vishwakarma Mandir.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang();
try { $announcements = Database::query("SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC"); } catch (Exception $e) { $announcements = []; } ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-bullhorn me-2"></i><?= __('nav.community') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.community')]]) ?></div></section>
<section class="section-padding"><div class="container"><div class="row justify-content-center"><div class="col-lg-8">
    <?php if (!empty($announcements)): foreach ($announcements as $ann):
    $typeColors = ['info' => 'primary', 'warning' => 'warning', 'success' => 'success', 'festival' => 'danger'];
    $typeIcons = ['info' => 'fa-info-circle', 'warning' => 'fa-exclamation-triangle', 'success' => 'fa-check-circle', 'festival' => 'fa-star']; ?>
    <div class="bvm-card mb-3 fade-in-up"><div class="card-body">
        <div class="d-flex align-items-start gap-3">
            <div class="icon-box" style="background:rgba(255,153,51,0.1);color:var(--saffron);flex-shrink:0;"><i class="fas <?= $typeIcons[$ann['type']] ?? 'fa-bell' ?>"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-<?= $typeColors[$ann['type']] ?? 'secondary' ?>"><?= ucfirst(e($ann['type'])) ?></span>
                    <small class="text-muted"><?= formatDate($ann['created_at']) ?></small>
                </div>
                <p class="mb-0"><?= e($lang === 'hi' && !empty($ann['message_hi']) ? $ann['message_hi'] : $ann['message_en']) ?></p>
                <?php if (!empty($ann['link'])): ?><a href="<?= e($ann['link']) ?>" class="mt-2 d-inline-block"><?= __('common.learn_more') ?> →</a><?php endif; ?>
            </div>
        </div>
    </div></div>
    <?php endforeach; else: ?>
    <div class="text-center py-5"><i class="fas fa-bullhorn text-muted" style="font-size:3rem;"></i><p class="text-muted mt-3"><?= $lang === 'hi' ? 'कोई सूचना उपलब्ध नहीं' : 'No announcements at this time' ?></p></div>
    <?php endif; ?>
</div></div></div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
