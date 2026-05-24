<?php
/**
 * =====================================================
 * Floating Donate Button + WhatsApp
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

$whatsapp = getSetting('site_whatsapp', '');
$whatsappNumber = preg_replace('/[^0-9]/', '', $whatsapp);
?>

<div class="floating-buttons">
    <!-- WhatsApp Button -->
    <?php if (!empty($whatsappNumber)): ?>
    <a href="https://wa.me/<?= $whatsappNumber ?>?text=<?= urlencode('🙏 Namaste! I would like to know more about Bhagwan Vishwakarma Mandir.') ?>" 
       class="floating-btn floating-whatsapp" 
       target="_blank" 
       rel="noopener" 
       title="<?= __('contact.whatsapp') ?>">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>

    <!-- Donate Button -->
    <a href="<?= BASE_URL ?>/donations" class="floating-btn floating-donate" title="<?= __('nav.donate_now') ?>">
        <i class="fas fa-hand-holding-heart"></i>
        <span class="floating-donate-text"><?= __('nav.donate_now') ?></span>
    </a>
</div>
