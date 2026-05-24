<?php
/** Gallery Page */
$metaTitle = 'Photo Gallery | ' . SITE_NAME;
$metaDescription = 'Browse our photo gallery of Bhagwan Vishwakarma Mandir — temple architecture, festivals, daily rituals, and community service.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();
try { $albums = Database::query("SELECT ga.*, COUNT(gi.id) as image_count FROM gallery_albums ga LEFT JOIN gallery_images gi ON ga.id = gi.album_id AND gi.is_active = 1 WHERE ga.is_active = 1 GROUP BY ga.id ORDER BY ga.sort_order"); } catch (Exception $e) { $albums = []; }
try { $images = Database::query("SELECT gi.*, ga.title_en as album_title, ga.slug as album_slug FROM gallery_images gi JOIN gallery_albums ga ON gi.album_id = ga.id WHERE gi.is_active = 1 ORDER BY gi.sort_order, gi.id DESC LIMIT 24"); } catch (Exception $e) { $images = []; }
?>
<section class="page-header"><div class="container"><h1><i class="fas fa-images me-2"></i><?= __('nav.photo_gallery') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.photo_gallery')]]) ?></div></section>

<section class="section-padding">
    <div class="container">
        <!-- Album Filters -->
        <?php if (!empty($albums)): ?>
        <div class="filter-buttons mb-4 fade-in-up">
            <button class="filter-btn active" data-category="all" data-target=".gallery-filter-item"><?= __('common.all') ?></button>
            <?php foreach ($albums as $album): ?>
            <button class="filter-btn" data-category="<?= e($album['slug']) ?>" data-target=".gallery-filter-item"><?= e(getBilingualContent($album, 'title')) ?> (<?= $album['image_count'] ?>)</button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($images)): ?>
        <div class="gallery-grid fade-in-up">
            <?php foreach ($images as $img): ?>
            <a href="<?= GALLERY_URL ?>/<?= e($img['image_path']) ?>" class="gallery-item glightbox gallery-filter-item filter-item" data-gallery="main-gallery" data-glightbox="title: <?= e($img['title'] ?? '') ?>" data-category="<?= e($img['album_slug'] ?? '') ?>">
                <img src="<?= GALLERY_URL ?>/<?= e($img['thumbnail_path'] ?? $img['image_path']) ?>" alt="<?= e($img['alt_text'] ?? $img['title'] ?? 'Gallery Photo') ?>" loading="lazy">
                <div class="gallery-overlay"><span class="gallery-title"><?= e($img['title'] ?? '') ?></span></div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="gallery-grid fade-in-up">
            <?php foreach (['🛕','🙏','🪔','🌺','⛩️','🔔','🎆','🪷'] as $e): ?>
            <div class="gallery-placeholder"><span><?= $e ?></span></div>
            <?php endforeach; ?>
        </div>
        <p class="text-center text-muted mt-4"><?= $lang === 'hi' ? 'फोटो जल्द जोड़ी जाएंगी' : 'Photos will be added soon' ?></p>
        <?php endif; ?>
    </div>
</section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
