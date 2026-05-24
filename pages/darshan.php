<?php
/** Darshan & Visit */ $metaTitle = 'Darshan & Visit | ' . SITE_NAME; $metaDescription = 'Plan your visit to Bhagwan Vishwakarma Mandir. Darshan timings, rules, and guidelines.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang(); ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-hands-praying me-2"></i><?= __('nav.darshan') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.darshan')]]) ?></div></section>
<section class="section-padding"><div class="container"><div class="row g-5">
    <div class="col-lg-7 fade-in-left">
        <h2 class="text-maroon mb-4"><?= $lang === 'hi' ? 'दर्शन की जानकारी' : 'Darshan Information' ?></h2>
        <div class="bvm-card mb-4"><div class="card-body">
            <h4><i class="fas fa-clock text-saffron me-2"></i><?= __('info.timings') ?></h4>
            <table class="table mb-0"><tbody>
                <tr><td><?= $lang === 'hi' ? 'प्रातः दर्शन' : 'Morning Darshan' ?></td><td class="fw-bold"><?= e(getSetting('morning_opening')) ?> - <?= e(getSetting('morning_closing')) ?></td></tr>
                <tr><td><?= $lang === 'hi' ? 'सायं दर्शन' : 'Evening Darshan' ?></td><td class="fw-bold"><?= e(getSetting('evening_opening')) ?> - <?= e(getSetting('evening_closing')) ?></td></tr>
                <tr><td><?= $lang === 'hi' ? 'विशेष दर्शन' : 'Special Darshan' ?></td><td class="fw-bold"><?= e(getSetting('special_darshan')) ?></td></tr>
            </tbody></table>
        </div></div>
        <h4 class="text-maroon mb-3"><i class="fas fa-list-check me-2"></i><?= $lang === 'hi' ? 'नियम एवं दिशानिर्देश' : 'Rules & Guidelines' ?></h4>
        <ul class="list-unstyled">
            <?php $rules = $lang === 'hi' 
                ? ['मंदिर परिसर में शांति बनाए रखें', 'मोबाइल फोन साइलेंट रखें', 'शालीन वस्त्र पहनें', 'जूते-चप्पल बाहर रखें', 'फोटोग्राफी अनुमति से', 'प्रसाद हॉल में ग्रहण करें']
                : ['Maintain silence in temple premises', 'Keep mobile phones on silent', 'Wear modest clothing', 'Remove footwear before entering', 'Photography only with permission', 'Accept prasad in the designated hall'];
            foreach ($rules as $r): ?>
            <li class="mb-2"><i class="fas fa-check-circle text-saffron me-2"></i><?= e($r) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-lg-5 fade-in-right">
        <div class="gallery-placeholder mb-4" style="aspect-ratio:3/4;font-size:5rem;border-radius:16px;"><span>🛕</span></div>
        <div class="text-center">
            <a href="<?= BASE_URL ?>/book-puja" class="btn btn-primary-saffron btn-lg"><i class="fas fa-calendar-check me-2"></i><?= __('nav.book_puja') ?></a>
        </div>
    </div>
</div></div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
