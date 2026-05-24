<?php
/**
 * =====================================================
 * Live Announcement Bar — Scrolling Ticker
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

// Fetch active announcements
try {
    $announcements = Database::query(
        "SELECT * FROM announcements WHERE is_active = 1 
         AND (start_date IS NULL OR start_date <= CURDATE()) 
         AND (end_date IS NULL OR end_date >= CURDATE()) 
         ORDER BY id DESC LIMIT 5"
    );
} catch (Exception $e) {
    $announcements = [];
}

if (empty($announcements)) return;

$lang = getCurrentLang();
?>

<div class="announcement-bar" id="announcementBar">
    <div class="container-fluid">
        <div class="announcement-content">
            <span class="announcement-label">
                <i class="fas fa-bullhorn"></i>
                <?= $lang === 'hi' ? 'सूचना' : 'Notice' ?>
            </span>
            <div class="announcement-ticker">
                <div class="ticker-wrap">
                    <div class="ticker-move">
                        <?php foreach ($announcements as $ann): ?>
                            <span class="ticker-item">
                                <?php
                                    $typeIcons = ['info' => '📢', 'warning' => '⚠️', 'success' => '✅', 'festival' => '🎉'];
                                    echo $typeIcons[$ann['type']] ?? '📢';
                                ?>
                                <?= e($lang === 'hi' && !empty($ann['message_hi']) ? $ann['message_hi'] : $ann['message_en']) ?>
                                <?php if (!empty($ann['link'])): ?>
                                    <a href="<?= e($ann['link']) ?>" class="ticker-link"><?= __('common.learn_more') ?> →</a>
                                <?php endif; ?>
                                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <button class="announcement-close" id="closeAnnouncement" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
