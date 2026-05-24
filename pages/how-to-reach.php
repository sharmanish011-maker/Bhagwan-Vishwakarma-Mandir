<?php
/** How to Reach */ $metaTitle = 'How to Reach | ' . SITE_NAME; $metaDescription = 'Directions to Bhagwan Vishwakarma Mandir. Find by road, train, or air.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang(); ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-map-marker-alt me-2"></i><?= __('nav.how_to_reach') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.how_to_reach')]]) ?></div></section>
<section class="section-padding"><div class="container">
    <div class="row g-4 mb-5">
        <div class="col-md-4 fade-in-up"><div class="significance-card"><div class="significance-icon"><i class="fas fa-car"></i></div><h4><?= $lang === 'hi' ? 'सड़क मार्ग से' : 'By Road' ?></h4><p class="text-muted"><?= $lang === 'hi' ? 'शहर के केंद्र से 5 किमी, NH-XX से जुड़ा। पर्याप्त पार्किंग उपलब्ध।' : 'Located 5km from city center, connected via NH-XX. Ample parking available.' ?></p></div></div>
        <div class="col-md-4 fade-in-up"><div class="significance-card"><div class="significance-icon"><i class="fas fa-train"></i></div><h4><?= $lang === 'hi' ? 'रेल मार्ग से' : 'By Train' ?></h4><p class="text-muted"><?= $lang === 'hi' ? 'निकटतम रेलवे स्टेशन 3 किमी दूर। ऑटो और टैक्सी उपलब्ध।' : 'Nearest railway station is 3km away. Auto-rickshaws and taxis available.' ?></p></div></div>
        <div class="col-md-4 fade-in-up"><div class="significance-card"><div class="significance-icon"><i class="fas fa-plane"></i></div><h4><?= $lang === 'hi' ? 'हवाई मार्ग से' : 'By Air' ?></h4><p class="text-muted"><?= $lang === 'hi' ? 'निकटतम हवाई अड्डा 30 किमी दूर। प्रीपेड टैक्सी सेवा उपलब्ध।' : 'Nearest airport is 30km away. Pre-paid taxi services available.' ?></p></div></div>
    </div>
    <div class="row justify-content-center"><div class="col-lg-10 fade-in-up">
        <h3 class="section-title"><?= $lang === 'hi' ? 'मानचित्र' : 'Map Location' ?></h3>
        <div class="rounded-3 overflow-hidden mt-4" style="height:400px;background:var(--cream);">
            <?php $map = getSetting('google_maps_embed'); if (!empty($map)): ?>
                <iframe src="<?= e($map) ?>" width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center h-100"><div class="text-center text-muted"><i class="fas fa-map-marked-alt" style="font-size:3rem;"></i><p class="mt-2"><?= $lang === 'hi' ? 'गूगल मैप्स यहाँ दिखेगा' : 'Google Maps embed will appear here' ?></p></div></div>
            <?php endif; ?>
        </div>
    </div></div>
</div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
