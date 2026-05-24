<?php /** Terms & Conditions */ $metaTitle = 'Terms & Conditions | ' . SITE_NAME; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang();
try { $page = Database::queryOne("SELECT * FROM pages WHERE slug = 'terms'"); } catch (Exception $e) { $page = null; } ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-file-contract me-2"></i><?= $page ? e(getBilingualContent($page, 'title')) : 'Terms & Conditions' ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => 'Terms & Conditions']]) ?></div></section>
<section class="section-padding"><div class="container"><div class="row justify-content-center"><div class="col-lg-8 fade-in-up">
    <?php if ($page): echo getBilingualContent($page, 'content'); else: ?>
    <h2>Terms & Conditions</h2><p>By using the <?= SITE_NAME ?> website, you agree to these terms.</p>
    <?php endif; ?>
</div></div></div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
