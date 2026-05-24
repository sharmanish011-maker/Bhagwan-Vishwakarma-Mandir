<?php /** Video Gallery */ $metaTitle = 'Video Gallery | ' . SITE_NAME; $metaDescription = 'Watch videos of pujas, festivals, and events at Bhagwan Vishwakarma Mandir.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang(); ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-video me-2"></i><?= __('nav.video_gallery') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.video_gallery')]]) ?></div></section>
<section class="section-padding"><div class="container">
    <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'मंदिर के दिव्य वीडियो' : 'Divine videos from the temple' ?></p>
    <div class="row g-4">
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="col-lg-4 col-md-6 fade-in-up">
            <div class="bvm-card">
                <div style="aspect-ratio:16/9;background:linear-gradient(135deg,var(--cream),rgba(212,175,55,0.1));display:flex;align-items:center;justify-content:center;">
                    <div class="text-center text-muted">
                        <i class="fas fa-play-circle" style="font-size:3rem;color:var(--saffron);"></i>
                        <p class="mt-2 mb-0"><?= __('common.video_coming_soon') ?></p>
                    </div>
                </div>
                <div class="card-body">
                    <h5><?= __('common.temple_video') . ' ' . $i ?></h5>
                    <p class="text-muted small mb-0"><?= __('common.youtube_embed_here') ?></p>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
