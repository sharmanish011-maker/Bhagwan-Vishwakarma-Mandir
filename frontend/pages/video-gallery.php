<?php 
/** 
 * =====================================================
 * VIDEO GALLERY PAGE — Bhagwan Vishwakarma Mandir
 * =====================================================
 */
$metaTitle = 'Video Gallery | ' . SITE_NAME; 
$metaDescription = 'Watch videos of pujas, festivals, and events at Bhagwan Vishwakarma Mandir.'; 
$metaKeywords = 'Vishwakarma Temple Videos, Live Aarti, Temple Festivals, Devotional Videos';

require_once TEMPLATES_PATH . '/header.php'; 
$lang = getCurrentLang(); 

try {
    $videos = Database::query("SELECT * FROM videos WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
} catch (Exception $e) { 
    $videos = []; 
}
?>

<style>
    .video-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
        border-radius: var(--card-radius);
        overflow: hidden;
    }
    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
    }
    .video-thumb-container {
        position: relative;
        aspect-ratio: 16/9;
        background: #000;
        overflow: hidden;
    }
    .video-thumb-container img {
        transition: transform 0.5s ease;
    }
    .video-card:hover .video-thumb-container img {
        transform: scale(1.06) rotate(0.5deg);
    }
    .play-btn-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3.5rem;
        color: var(--saffron);
        filter: drop-shadow(0 4px 15px rgba(0,0,0,0.6));
        transition: transform 0.3s ease, color 0.3s ease, text-shadow 0.3s ease;
        cursor: pointer;
        z-index: 2;
    }
    .play-btn-overlay:hover {
        transform: translate(-50%, -50%) scale(1.15);
        color: var(--gold-light);
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
    }
    .video-duration-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.8);
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>

<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-video me-2 text-gold"></i><?= __('nav.video_gallery') ?></h1>
        <?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.video_gallery')]]) ?>
    </div>
</section>

<section class="section-padding bg-warm-white">
    <div class="container">
        <h2 class="section-title fade-in-up"><?= $lang === 'hi' ? 'दिव्य सत्संग और वीडियो' : 'Divine Satsang & Videos' ?></h2>
        <p class="section-subtitle fade-in-up mb-5"><?= $lang === 'hi' ? 'मंदिर में मनाए जाने वाले सभी उत्सवों, भजनों और धार्मिक कार्यक्रमों के वीडियो देखें।' : 'Watch divine playbacks of festivals, bhajans, daily rituals and grand celebrations.' ?></p>
        
        <div class="row g-4">
            <?php foreach ($videos as $vid): 
                $ytId = getYouTubeId($vid['youtube_url']);
                $thumbUrl = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : BASE_URL . "/frontend/assets/images/video_placeholder.jpg";
                $title = ($lang === 'hi' && !empty($vid['title_hi'])) ? $vid['title_hi'] : $vid['title_en'];
            ?>
            <div class="col-lg-4 col-md-6 fade-in-up">
                <div class="bvm-card video-card bg-white shadow-sm border-0">
                    <div class="video-thumb-container">
                        <img src="<?= $thumbUrl ?>" class="w-100 h-100" style="object-fit:cover; opacity: 0.85;" alt="<?= e($title) ?>" loading="lazy">
                        <!-- GLightbox YouTube Embed Trigger -->
                        <a href="<?= e($vid['youtube_url']) ?>" class="glightbox play-btn-overlay" aria-label="Play <?= e($title) ?>">
                            <i class="fas fa-circle-play"></i>
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-700 text-maroon mb-2 text-truncate-2" style="font-family: var(--font-body); font-size:1.15rem; min-height: 2.8rem; line-height: 1.4;"><?= e($title) ?></h5>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                            <span class="text-muted small"><i class="fab fa-youtube text-danger me-1"></i>YouTube Media</span>
                            <a href="<?= e($vid['youtube_url']) ?>" target="_blank" class="text-saffron-dark fw-600 small hover-underline" style="text-decoration:none;">
                                <?= $lang === 'hi' ? 'यूट्यूब पर खोलें' : 'Watch on YouTube' ?> <i class="fas fa-arrow-up-right-from-square ms-1" style="font-size: 0.75rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($videos)): ?>
            <div class="col-12 text-center py-5 fade-in-up">
                <div class="p-5 bg-white rounded-4 shadow-sm border border-light" style="max-width: 600px; margin: 0 auto;">
                    <i class="fas fa-video-slash text-saffron mb-3" style="font-size: 4rem; filter: drop-shadow(0 4px 10px rgba(255,153,51,0.2));"></i>
                    <h4 class="text-maroon fw-600 mb-2"><?= $lang === 'hi' ? 'जल्द ही वीडियो आ रहे हैं' : 'Divine Videos Coming Soon' ?></h4>
                    <p class="text-muted mb-0"><?= $lang === 'hi' ? 'हमारे दिव्य भजनों और त्योहारों के वीडियो यहां जल्द ही अपडेट किए जाएंगे। कृपया वापस आते रहें!' : 'We are preparing high-quality coverage of our daily darshans, events, and maha aartis. Please check back soon!' ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
