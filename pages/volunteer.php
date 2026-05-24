<?php
/** Volunteer Registration Page */
$metaTitle = 'Volunteer Registration | ' . SITE_NAME;
$metaDescription = 'Join our temple family as a volunteer. Serve the community at Bhagwan Vishwakarma Mandir.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken()) { setFlash('danger', 'Security validation failed.'); redirect(BASE_URL . '/volunteer'); }
    $missing = validateRequired(['name', 'email', 'phone']);
    if (!empty($missing)) { setFlash('warning', 'Please fill all required fields.'); redirect(BASE_URL . '/volunteer'); }
    try {
        Database::insert("INSERT INTO volunteers (name, email, phone, age, city, skills, availability, experience) VALUES (:name, :email, :phone, :age, :city, :skills, :avail, :exp)",
            ['name' => sanitize($_POST['name']), 'email' => sanitizeEmail($_POST['email']), 'phone' => sanitizePhone($_POST['phone']), 'age' => sanitizeInt($_POST['age'] ?? 0), 'city' => sanitize($_POST['city'] ?? ''), 'skills' => sanitize($_POST['skills'] ?? ''), 'avail' => sanitize($_POST['availability'] ?? 'anytime'), 'exp' => sanitize($_POST['experience'] ?? '')]);
        setFlash('success', __('volunteer.success'));
    } catch (Exception $e) { setFlash('danger', 'An error occurred.'); }
    redirect(BASE_URL . '/volunteer');
}
?>
<section class="page-header"><div class="container"><h1><i class="fas fa-handshake me-2"></i><?= __('volunteer.title') ?></h1><?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('volunteer.title')]]) ?></div></section>

<section class="section-padding">
    <div class="container"><div class="row justify-content-center"><div class="col-lg-8">
        <div class="text-center mb-5 fade-in-up"><h2 class="text-maroon"><?= __('volunteer.subtitle') ?></h2></div>
        <form method="POST" class="bvm-form fade-in-up">
            <?= csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6 mb-3"><label class="form-label"><?= __('contact.name') ?> *</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label"><?= __('contact.email') ?> *</label><input type="email" name="email" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label"><?= __('contact.phone') ?> *</label><input type="tel" name="phone" class="form-control" required></div>
                <div class="col-md-3 mb-3"><label class="form-label"><?= $lang === 'hi' ? 'आयु' : 'Age' ?></label><input type="number" name="age" class="form-control" min="16" max="80"></div>
                <div class="col-md-3 mb-3"><label class="form-label"><?= $lang === 'hi' ? 'शहर' : 'City' ?></label><input type="text" name="city" class="form-control"></div>
                <div class="col-md-6 mb-3"><label class="form-label"><?= __('volunteer.skills') ?></label><textarea name="skills" class="form-control" rows="3" placeholder="<?= $lang === 'hi' ? 'जैसे: इवेंट मैनेजमेंट, फोटोग्राफी, खाना बनाना...' : 'e.g., Event management, photography, cooking...' ?>"></textarea></div>
                <div class="col-md-6 mb-3"><label class="form-label"><?= __('volunteer.availability') ?></label><select name="availability" class="form-select">
                    <option value="anytime"><?= __('volunteer.anytime') ?></option><option value="weekdays"><?= __('volunteer.weekdays') ?></option><option value="weekends"><?= __('volunteer.weekends') ?></option><option value="festivals"><?= __('volunteer.festivals') ?></option></select></div>
                <div class="col-12 mb-3"><label class="form-label"><?= __('volunteer.experience') ?></label><textarea name="experience" class="form-control" rows="3"></textarea></div>
            </div>
            <button type="submit" class="btn btn-primary-saffron btn-lg w-100 mt-3"><i class="fas fa-handshake me-2"></i><?= __('volunteer.submit') ?></button>
        </form>
    </div></div></div>
</section>
<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
