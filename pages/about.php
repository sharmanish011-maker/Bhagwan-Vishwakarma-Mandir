<?php
/**
 * About Bhagwan Vishwakarma Page
 */
$metaTitle = 'About Bhagwan Vishwakarma - Divine Architect | ' . SITE_NAME;
$metaDescription = 'Learn about Bhagwan Vishwakarma, the divine architect of the universe. Discover the significance, divine creations, and cultural heritage.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-om me-2"></i><?= __('about.title') ?></h1>
        <?= breadcrumb([
            ['label' => __('nav.home'), 'url' => BASE_URL],
            ['label' => __('about.title')]
        ]) ?>
    </div>
</section>

<!-- About Section -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 fade-in-left">
                <h2 class="text-maroon"><?= __('about.subtitle') ?></h2>
                <div class="divider-ornament text-start">✦ ✦ ✦</div>
                <p class="mb-3"><?= __('sections.about_text') ?></p>
                <p><?= $lang === 'hi' 
                    ? 'ऋग्वेद में विश्वकर्मा का वर्णन "विश्वस्य कर्मन्" (सभी कर्मों के स्वामी) के रूप में किया गया है। वे देवताओं के शिल्पकार, वास्तुकार और इंजीनियर हैं। उन्होंने स्वर्ग, लंका, द्वारका और इंद्रप्रस्थ जैसी दिव्य नगरियों का निर्माण किया।'
                    : 'In the Rigveda, Vishwakarma is described as "Vishwasya Karman" (Lord of all actions). He is the craftsman, architect, and engineer of the gods. He created divine cities like Swarga (Heaven), Lanka, Dwarka, and Indraprastha.' 
                ?></p>
                <p><?= $lang === 'hi'
                    ? 'विश्वकर्मा पूजा प्रतिवर्ष भाद्रपद माह के अंतिम दिन (कन्या संक्रांति) को मनाई जाती है। यह विशेष रूप से शिल्पकारों, कारीगरों, इंजीनियरों, वास्तुकारों और फैक्ट्री श्रमिकों द्वारा मनाई जाती है।'
                    : 'Vishwakarma Puja is celebrated annually on the last day of Bhadrapada month (Kanya Sankranti). It is especially observed by craftsmen, artisans, engineers, architects, and factory workers who worship their tools and machinery.'
                ?></p>
            </div>
            <div class="col-lg-6 fade-in-right">
                <div class="gallery-placeholder" style="aspect-ratio: 3/4; font-size: 6rem; border-radius: 16px;">
                    <span>🙏</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Divine Creations -->
<section class="section-padding bg-cream">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= __('about.divine_title') ?></h2>
        <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'भगवान विश्वकर्मा की प्रमुख दिव्य रचनाएं' : 'Major divine creations by Lord Vishwakarma' ?></p>

        <div class="row g-4">
            <?php
            $creations = [
                ['icon' => 'fa-dharmachakra', 'title_en' => 'Sudarshan Chakra', 'title_hi' => 'सुदर्शन चक्र', 'desc_en' => 'The divine discus of Lord Vishnu, crafted from the dust of the Sun, capable of destroying any evil force.', 'desc_hi' => 'भगवान विष्णु का दिव्य चक्र, सूर्य की धूल से निर्मित, जो किसी भी बुरी शक्ति को नष्ट करने में सक्षम है।'],
                ['icon' => 'fa-plane', 'title_en' => 'Pushpak Vimana', 'title_hi' => 'पुष्पक विमान', 'desc_en' => 'The celestial flying chariot originally made for Lord Brahma, later used by Kubera and Ravana.', 'desc_hi' => 'मूल रूप से ब्रह्मा जी के लिए बनाया गया दिव्य उड़ने वाला रथ, बाद में कुबेर और रावण द्वारा उपयोग किया गया।'],
                ['icon' => 'fa-bolt', 'title_en' => 'Vajra (Thunderbolt)', 'title_hi' => 'वज्र (इंद्र का अस्त्र)', 'desc_en' => 'The powerful weapon of Lord Indra, made from the bones of Sage Dadhichi, shaped by Vishwakarma.', 'desc_hi' => 'भगवान इंद्र का शक्तिशाली अस्त्र, ऋषि दधीचि की अस्थियों से निर्मित, विश्वकर्मा द्वारा आकार दिया गया।'],
                ['icon' => 'fa-city', 'title_en' => 'Lanka & Dwarka', 'title_hi' => 'लंका एवं द्वारका', 'desc_en' => 'The golden city of Lanka and the magnificent Dwarka, both architectural marvels designed by Vishwakarma.', 'desc_hi' => 'लंका की सोने की नगरी और भव्य द्वारका, दोनों विश्वकर्मा द्वारा डिजाइन किए गए वास्तुशिल्प के चमत्कार हैं।'],
            ];
            foreach ($creations as $c): ?>
            <div class="col-lg-3 col-md-6 fade-in-up">
                <div class="significance-card">
                    <div class="significance-icon"><i class="fas <?= $c['icon'] ?>"></i></div>
                    <h4><?= e($lang === 'hi' ? $c['title_hi'] : $c['title_en']) ?></h4>
                    <p><?= e($lang === 'hi' ? $c['desc_hi'] : $c['desc_en']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section-padding bg-maroon">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6 fade-in-left">
                <div class="p-4 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2);">
                    <h3 class="text-gold mb-3"><i class="fas fa-bullseye me-2"></i><?= __('about.mission') ?></h3>
                    <p style="color: rgba(255,255,255,0.85);"><?= __('about.mission_text') ?></p>
                </div>
            </div>
            <div class="col-md-6 fade-in-right">
                <div class="p-4 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2);">
                    <h3 class="text-gold mb-3"><i class="fas fa-eye me-2"></i><?= __('about.vision') ?></h3>
                    <p style="color: rgba(255,255,255,0.85);"><?= __('about.vision_text') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section-padding text-center">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= $lang === 'hi' ? 'मंदिर में आइए' : 'Visit the Temple' ?></h2>
        <p class="section-subtitle fade-in-up"><?= $lang === 'hi' ? 'भगवान विश्वकर्मा के दर्शन कर आशीर्वाद प्राप्त करें' : 'Seek blessings of Lord Vishwakarma' ?></p>
        <div class="fade-in-up">
            <a href="<?= BASE_URL ?>/darshan" class="btn btn-primary-saffron btn-lg me-2"><i class="fas fa-hands-praying me-2"></i><?= __('hero.plan_visit') ?></a>
            <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-saffron btn-lg"><i class="fas fa-phone me-2"></i><?= __('nav.contact') ?></a>
        </div>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
