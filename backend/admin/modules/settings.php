<?php
/** Admin Settings Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $settings = $_POST;
    unset($settings['csrf_token']);
    foreach ($settings as $key => $value) {
        $key = sanitize($key);
        $value = sanitize($value);
        $existing = Database::queryOne("SELECT id FROM settings WHERE setting_key = :k", ['k' => $key]);
        if ($existing) {
            Database::execute("UPDATE settings SET setting_value = :v WHERE setting_key = :k", ['v' => $value, 'k' => $key]);
        } else {
            Database::insert("INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)", ['k' => $key, 'v' => $value]);
        }
    }
    setFlash('success', 'Settings saved successfully.');
    redirect('index.php?module=settings');
}

$activeTab = sanitize($_GET['tab'] ?? 'general');
?>

<h5 class="mb-4"><i class="fas fa-cog me-2"></i>Site Settings</h5>

<ul class="nav nav-tabs mb-4">
    <?php foreach (['general' => 'General', 'timings' => 'Timings', 'social' => 'Social Media', 'seo' => 'SEO'] as $tab => $label): ?>
    <li class="nav-item"><a class="nav-link <?= $activeTab === $tab ? 'active' : '' ?>" href="?module=settings&tab=<?= $tab ?>"><?= $label ?></a></li>
    <?php endforeach; ?>
</ul>

<form method="POST" class="admin-form-card">
    <?= csrfField() ?>

    <?php if ($activeTab === 'general'): ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Site Name (EN)</label><input type="text" name="site_name_en" class="form-control" value="<?= e(getSetting('site_name_en', SITE_NAME)) ?>"></div>
        <div class="col-md-6"><label class="form-label">Site Name (HI)</label><input type="text" name="site_name_hi" class="form-control" value="<?= e(getSetting('site_name_hi', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Tagline (EN)</label><input type="text" name="site_tagline_en" class="form-control" value="<?= e(getSetting('site_tagline_en', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Tagline (HI)</label><input type="text" name="site_tagline_hi" class="form-control" value="<?= e(getSetting('site_tagline_hi', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="site_phone" class="form-control" value="<?= e(getSetting('site_phone', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="site_email" class="form-control" value="<?= e(getSetting('site_email', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="text" name="site_whatsapp" class="form-control" value="<?= e(getSetting('site_whatsapp', '')) ?>"></div>
        <div class="col-12"><label class="form-label">Address (EN)</label><textarea name="site_address_en" class="form-control" rows="2"><?= e(getSetting('site_address_en', '')) ?></textarea></div>
        <div class="col-12"><label class="form-label">Address (HI)</label><textarea name="site_address_hi" class="form-control" rows="2"><?= e(getSetting('site_address_hi', '')) ?></textarea></div>
        <div class="col-12"><label class="form-label">Google Maps Embed URL</label><input type="url" name="google_maps_embed" class="form-control" value="<?= e(getSetting('google_maps_embed', '')) ?>"></div>
    </div>

    <?php elseif ($activeTab === 'timings'): ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Morning Opening</label><input type="time" name="morning_opening" class="form-control" value="<?= e(getSetting('morning_opening', '05:00')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Morning Closing</label><input type="time" name="morning_closing" class="form-control" value="<?= e(getSetting('morning_closing', '12:00')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Evening Opening</label><input type="time" name="evening_opening" class="form-control" value="<?= e(getSetting('evening_opening', '16:00')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Evening Closing</label><input type="time" name="evening_closing" class="form-control" value="<?= e(getSetting('evening_closing', '21:00')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Morning Aarti</label><input type="time" name="morning_aarti" class="form-control" value="<?= e(getSetting('morning_aarti', '06:00')) ?>"></div>
        <div class="col-md-6"><label class="form-label">Evening Aarti</label><input type="time" name="evening_aarti" class="form-control" value="<?= e(getSetting('evening_aarti', '19:00')) ?>"></div>
        <div class="col-12"><label class="form-label">Special Darshan</label><input type="text" name="special_darshan" class="form-control" value="<?= e(getSetting('special_darshan', '11:00 - 12:00')) ?>"></div>
    </div>

    <?php elseif ($activeTab === 'social'): ?>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label"><i class="fab fa-facebook me-1"></i>Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="<?= e(getSetting('facebook_url', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label"><i class="fab fa-instagram me-1"></i>Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="<?= e(getSetting('instagram_url', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label"><i class="fab fa-youtube me-1"></i>YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="<?= e(getSetting('youtube_url', '')) ?>"></div>
        <div class="col-md-6"><label class="form-label"><i class="fab fa-twitter me-1"></i>Twitter URL</label><input type="url" name="twitter_url" class="form-control" value="<?= e(getSetting('twitter_url', '')) ?>"></div>
    </div>

    <?php elseif ($activeTab === 'seo'): ?>
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="<?= e(getSetting('meta_title', '')) ?>"></div>
        <div class="col-12"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="3"><?= e(getSetting('meta_description', '')) ?></textarea></div>
        <div class="col-12"><label class="form-label">Google Analytics ID</label><input type="text" name="analytics_id" class="form-control" value="<?= e(getSetting('analytics_id', '')) ?>" placeholder="G-XXXXXXXXXX"></div>
    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-admin-primary mt-4"><i class="fas fa-save me-2"></i>Save Settings</button>
</form>
