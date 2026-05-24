<?php
/** Temple Committee */ $metaTitle = 'Temple Committee | ' . SITE_NAME; $metaDescription = 'Meet the dedicated committee members of Bhagwan Vishwakarma Mandir.'; require_once TEMPLATES_PATH . '/header.php'; $lang = getCurrentLang();
$members = [
    ['name' => 'Shri Ramesh Vishwakarma', 'role_en' => 'President', 'role_hi' => 'अध्यक्ष', 'icon' => '👤'],
    ['name' => 'Shri Suresh Kumar', 'role_en' => 'Vice President', 'role_hi' => 'उपाध्यक्ष', 'icon' => '👤'],
    ['name' => 'Shri Mahesh Sharma', 'role_en' => 'Secretary', 'role_hi' => 'सचिव', 'icon' => '👤'],
    ['name' => 'Shri Dinesh Patel', 'role_en' => 'Treasurer', 'role_hi' => 'कोषाध्यक्ष', 'icon' => '👤'],
    ['name' => 'Smt. Sunita Devi', 'role_en' => 'Women Wing Head', 'role_hi' => 'महिला विंग प्रमुख', 'icon' => '👤'],
    ['name' => 'Shri Anil Vishwakarma', 'role_en' => 'Youth Wing Head', 'role_hi' => 'युवा विंग प्रमुख', 'icon' => '👤'],
    ['name' => 'Pandit Shiv Narayan', 'role_en' => 'Head Priest', 'role_hi' => 'प्रधान पुजारी', 'icon' => '🙏'],
    ['name' => 'Shri Vijay Kumar', 'role_en' => 'Event Coordinator', 'role_hi' => 'कार्यक्रम समन्वयक', 'icon' => '👤'],
]; ?>
<section class="page-header"><div class="container"><h1><i class="fas fa-users me-2"></i><?= __('nav.committee') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.committee')]]) ?></div></section>
<section class="section-padding"><div class="container">
    <h2 class="section-title fade-in-up"><?= $lang === 'hi' ? 'हमारी समर्पित टीम' : 'Our Dedicated Team' ?></h2>
    <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'मंदिर प्रबंधन के लिए समर्पित सदस्य' : 'Dedicated members for temple management' ?></p>
    <div class="row g-4"><?php foreach ($members as $m): ?>
    <div class="col-lg-3 col-md-4 col-6 fade-in-up"><div class="team-card">
        <div class="team-photo"><?= $m['icon'] ?></div>
        <h5 class="mb-1"><?= e($m['name']) ?></h5>
        <p class="text-saffron fw-600 mb-0"><?= e($lang === 'hi' ? $m['role_hi'] : $m['role_en']) ?></p>
    </div></div>
    <?php endforeach; ?></div>
</div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
