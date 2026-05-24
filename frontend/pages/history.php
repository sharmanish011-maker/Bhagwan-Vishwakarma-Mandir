<?php
/** Temple History Page */
$metaTitle = 'Temple History | ' . SITE_NAME;
$metaDescription = 'Discover the rich history of Bhagwan Vishwakarma Mandir — from its founding to the present day.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();
?>
<section class="page-header"><div class="container"><h1><i class="fas fa-landmark me-2"></i><?= __('nav.temple_history') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.temple_history')]]) ?></div></section>
<section class="section-padding"><div class="container">
    <div class="row justify-content-center"><div class="col-lg-8 text-center mb-5 fade-in-up">
        <h2 class="text-maroon"><?= $lang === 'hi' ? 'हमारे मंदिर की गौरवशाली यात्रा' : 'The Glorious Journey of Our Temple' ?></h2>
        <p class="text-muted"><?= $lang === 'hi' ? 'भगवान विश्वकर्मा मंदिर की स्थापना से लेकर आज तक का इतिहास' : 'From the founding of Bhagwan Vishwakarma Mandir to the present day' ?></p>
    </div></div>
    <div class="timeline">
        <?php $milestones = [
            ['year' => '1985', 'title_en' => 'Foundation Stone Laid', 'title_hi' => 'शिलान्यास', 'desc_en' => 'The first foundation stone was laid with Vedic rituals by revered saints and community elders.', 'desc_hi' => 'सम्मानित संतों और समुदाय के बुजुर्गों द्वारा वैदिक अनुष्ठानों के साथ पहला शिलान्यास किया गया।'],
            ['year' => '1990', 'title_en' => 'Temple Construction Begins', 'title_hi' => 'मंदिर निर्माण प्रारंभ', 'desc_en' => 'With contributions from thousands of devotees, the main temple construction commenced.', 'desc_hi' => 'हजारों भक्तों के सहयोग से मुख्य मंदिर का निर्माण शुरू हुआ।'],
            ['year' => '1995', 'title_en' => 'Pran Pratishtha Ceremony', 'title_hi' => 'प्राण प्रतिष्ठा समारोह', 'desc_en' => 'The sacred Pran Pratishtha ceremony was performed, bringing divine energy to the deity idols.', 'desc_hi' => 'पवित्र प्राण प्रतिष्ठा समारोह सम्पन्न हुआ, जिससे मूर्तियों में दिव्य ऊर्जा का संचार हुआ।'],
            ['year' => '2005', 'title_en' => 'Community Hall Established', 'title_hi' => 'सामुदायिक भवन की स्थापना', 'desc_en' => 'A community hall was built for religious gatherings, marriages, and cultural events.', 'desc_hi' => 'धार्मिक सभाओं, विवाह और सांस्कृतिक कार्यक्रमों के लिए सामुदायिक भवन का निर्माण किया गया।'],
            ['year' => '2015', 'title_en' => 'Renovation & Expansion', 'title_hi' => 'जीर्णोद्धार एवं विस्तार', 'desc_en' => 'Major renovation project completed with expanded prayer hall and modernized facilities.', 'desc_hi' => 'विस्तारित प्रार्थना हॉल और आधुनिक सुविधाओं के साथ प्रमुख जीर्णोद्धार परियोजना पूरी हुई।'],
            ['year' => '2025', 'title_en' => 'Digital Transformation', 'title_hi' => 'डिजिटल परिवर्तन', 'desc_en' => 'Launch of the temple website with online puja booking, donations, and virtual darshan capabilities.', 'desc_hi' => 'ऑनलाइन पूजा बुकिंग, दान और वर्चुअल दर्शन क्षमताओं के साथ मंदिर वेबसाइट का शुभारंभ।'],
        ];
        foreach ($milestones as $m): ?>
        <div class="timeline-item fade-in-up">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <span class="timeline-year"><?= $m['year'] ?></span>
                <h4><?= e($lang === 'hi' ? $m['title_hi'] : $m['title_en']) ?></h4>
                <p class="text-muted mb-0"><?= e($lang === 'hi' ? $m['desc_hi'] : $m['desc_en']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div></section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
