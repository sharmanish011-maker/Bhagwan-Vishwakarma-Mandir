<?php
/** Temple Timings Page */
$metaTitle = 'Temple Timings | ' . SITE_NAME;
$metaDescription = 'Check darshan and aarti timings at Bhagwan Vishwakarma Mandir. Morning and evening schedules.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();
?>
<section class="page-header"><div class="container"><h1><i class="fas fa-clock me-2"></i><?= __('nav.timings') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.timings')]]) ?></div></section>
<section class="section-padding"><div class="container"><div class="row justify-content-center"><div class="col-lg-8">
    <div class="bvm-card mb-4 fade-in-up"><div class="card-body">
        <h3 class="text-maroon mb-4 text-center"><i class="fas fa-sun me-2 text-saffron"></i><?= $lang === 'hi' ? 'दैनिक दर्शन समय' : 'Daily Darshan Timings' ?></h3>
        <table class="table table-hover"><thead class="table-light"><tr><th><?= $lang === 'hi' ? 'सेवा' : 'Service' ?></th><th><?= $lang === 'hi' ? 'समय' : 'Time' ?></th></tr></thead><tbody>
            <tr><td><i class="fas fa-door-open text-saffron me-2"></i><?= $lang === 'hi' ? 'प्रातः दर्शन' : 'Morning Darshan' ?></td><td><strong><?= e(getSetting('morning_opening','05:00')) ?> - <?= e(getSetting('morning_closing','12:00')) ?></strong></td></tr>
            <tr><td><i class="fas fa-door-open text-saffron me-2"></i><?= $lang === 'hi' ? 'सायं दर्शन' : 'Evening Darshan' ?></td><td><strong><?= e(getSetting('evening_opening','16:00')) ?> - <?= e(getSetting('evening_closing','21:00')) ?></strong></td></tr>
            <tr><td><i class="fas fa-fire text-saffron me-2"></i><?= $lang === 'hi' ? 'प्रातः आरती' : 'Morning Aarti' ?></td><td><strong><?= e(getSetting('morning_aarti','06:00')) ?></strong></td></tr>
            <tr><td><i class="fas fa-fire text-saffron me-2"></i><?= $lang === 'hi' ? 'सायं आरती' : 'Evening Aarti' ?></td><td><strong><?= e(getSetting('evening_aarti','19:00')) ?></strong></td></tr>
            <tr><td><i class="fas fa-star text-gold me-2"></i><?= $lang === 'hi' ? 'विशेष दर्शन' : 'Special Darshan' ?></td><td><strong><?= e(getSetting('special_darshan','11:00 - 12:00')) ?></strong></td></tr>
        </tbody></table>
    </div></div>
    <div class="alert alert-warning fade-in-up"><i class="fas fa-info-circle me-2"></i><?= $lang === 'hi' ? 'त्यौहारों और विशेष अवसरों पर समय में परिवर्तन हो सकता है। कृपया पहले संपर्क करें।' : 'Timings may change during festivals and special occasions. Please contact us beforehand.' ?></div>
    <div class="text-center fade-in-up"><a href="<?= BASE_URL ?>/contact" class="btn btn-primary-saffron"><i class="fas fa-phone me-2"></i><?= __('nav.contact') ?></a></div>
</div></div></div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
