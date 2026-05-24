<?php /** Privacy Policy */ $metaTitle = 'Privacy Policy | ' . SITE_NAME; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang();
try { $page = Database::queryOne("SELECT * FROM pages WHERE slug = 'privacy-policy'"); } catch (Exception $e) { $page = null; } ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-shield-alt me-2"></i><?= $page ? e(getBilingualContent($page, 'title')) : 'Privacy Policy' ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => 'Privacy Policy']]) ?></div></section>
<section class="section-padding"><div class="container"><div class="row justify-content-center"><div class="col-lg-8 fade-in-up">
    <?php if ($page): echo getBilingualContent($page, 'content'); else: ?>
    <h2>Privacy Policy</h2><p>Your privacy is important to us. This policy explains how we handle your information at <?= SITE_NAME ?>.</p>
    <?php endif; ?>
</div></div></div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
