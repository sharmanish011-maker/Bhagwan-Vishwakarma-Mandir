<?php
/**
 * Donations Page
 */
$metaTitle = 'Donate Online | ' . SITE_NAME;
$metaDescription = 'Support Bhagwan Vishwakarma Mandir with your generous donations. Contribute to temple construction, anna daan, education, and community welfare.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();

// Handle donation form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken()) {
        setFlash('danger', 'Security validation failed.');
        redirect(BASE_URL . '/donations');
    }

    $missing = validateRequired(['donor_name', 'amount', 'category']);
    if (!empty($missing)) {
        setFlash('warning', 'Please fill all required fields.');
        redirect(BASE_URL . '/donations');
    }

    $donorName = sanitize($_POST['donor_name']);
    $donorEmail = !empty($_POST['donor_email']) ? sanitizeEmail($_POST['donor_email']) : null;
    $donorPhone = !empty($_POST['donor_phone']) ? sanitizePhone($_POST['donor_phone']) : null;
    $donorAddress = sanitize($_POST['donor_address'] ?? '');
    $donorPan = sanitize($_POST['donor_pan'] ?? '');
    $amount = sanitizeFloat($_POST['amount']);
    $category = sanitize($_POST['category']);
    $isAnonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $receiptNumber = generateReceiptNumber();

    if ($amount < 1) {
        setFlash('warning', 'Please enter a valid donation amount.');
        redirect(BASE_URL . '/donations');
    }

    try {
        $userId = null;
        if ($donorEmail) {
            $existing = Database::queryOne("SELECT id FROM users WHERE email = :email", ['email' => $donorEmail]);
            $userId = $existing ? $existing['id'] : Database::insert(
                "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)",
                ['name' => $donorName, 'email' => $donorEmail, 'phone' => $donorPhone]
            );
        }

        Database::insert(
            "INSERT INTO donations (receipt_number, user_id, donor_name, donor_email, donor_phone, donor_address, donor_pan, amount, category, is_anonymous, payment_method, status) 
             VALUES (:rn, :uid, :name, :email, :phone, :addr, :pan, :amt, :cat, :anon, 'online', 'pending')",
            ['rn' => $receiptNumber, 'uid' => $userId, 'name' => $donorName, 'email' => $donorEmail, 'phone' => $donorPhone, 'addr' => $donorAddress, 'pan' => $donorPan, 'amt' => $amount, 'cat' => $category, 'anon' => $isAnonymous]
        );

        if ($donorEmail) {
            sendDonationReceipt(['receipt_number' => $receiptNumber, 'donor_name' => $donorName, 'donor_email' => $donorEmail, 'amount' => $amount, 'category' => $category, 'created_at' => date('Y-m-d H:i:s')]);
        }

        setFlash('success', __('donation.thank_you') . ' Receipt #: ' . $receiptNumber);
        redirect(BASE_URL . '/donations');
    } catch (Exception $e) {
        setFlash('danger', 'An error occurred. Please try again.');
        redirect(BASE_URL . '/donations');
    }
}
?>

<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-hand-holding-heart me-2"></i><?= __('donation.title') ?></h1>
        <?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('donation.title')]]) ?>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5 fade-in-up">
                    <h2 class="text-maroon"><?= __('donation.subtitle') ?></h2>
                    <p class="text-muted"><?= __('sections.donation_text') ?></p>
                </div>

                <!-- Donation Categories -->
                <h5 class="mb-3 text-maroon fade-in-up"><?= __('donation.category') ?></h5>
                <div class="row g-3 mb-4 fade-in-up">
                    <?php
                    $categories = [
                        'general' => ['icon' => 'fa-hand-holding-heart', 'label' => __('donation.categories.general')],
                        'temple_construction' => ['icon' => 'fa-place-of-worship', 'label' => __('donation.categories.temple_construction')],
                        'anna_daan' => ['icon' => 'fa-utensils', 'label' => __('donation.categories.anna_daan')],
                        'gaushala' => ['icon' => 'fa-paw', 'label' => __('donation.categories.gaushala')],
                        'education' => ['icon' => 'fa-graduation-cap', 'label' => __('donation.categories.education')],
                        'festival' => ['icon' => 'fa-star', 'label' => __('donation.categories.festival')],
                    ];
                    foreach ($categories as $key => $cat): ?>
                    <div class="col-md-4 col-6">
                        <div class="donation-category-card <?= $key === 'general' ? 'active' : '' ?>" onclick="selectCategory(this, '<?= $key ?>')">
                            <i class="fas <?= $cat['icon'] ?> d-block mb-2"></i>
                            <small class="fw-600"><?= $cat['label'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Donation Form -->
                <form method="POST" class="bvm-form fade-in-up">
                    <?= csrfField() ?>
                    <input type="hidden" name="category" id="donationCategory" value="general">

                    <!-- Amount Presets -->
                    <h5 class="mb-3 text-maroon"><?= __('donation.amount') ?></h5>
                    <div class="amount-presets mb-3">
                        <button type="button" class="amount-btn" data-amount="101">₹101</button>
                        <button type="button" class="amount-btn" data-amount="251">₹251</button>
                        <button type="button" class="amount-btn" data-amount="501">₹501</button>
                        <button type="button" class="amount-btn active" data-amount="1001">₹1,001</button>
                        <button type="button" class="amount-btn" data-amount="2101">₹2,101</button>
                        <button type="button" class="amount-btn" data-amount="5001">₹5,001</button>
                        <button type="button" class="amount-btn" data-amount="11001">₹11,001</button>
                    </div>
                    <div class="mb-4">
                        <input type="number" name="amount" id="donationAmount" class="form-control form-control-lg" placeholder="<?= __('donation.amount') ?>" value="1001" min="1" required>
                    </div>

                    <!-- Donor Details -->
                    <h5 class="mb-3 text-maroon"><?= $lang === 'hi' ? 'दानकर्ता विवरण' : 'Donor Details' ?></h5>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('donation.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="donor_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('donation.email') ?></label>
                            <input type="email" name="donor_email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('donation.phone') ?></label>
                            <input type="tel" name="donor_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= __('donation.pan') ?></label>
                            <input type="text" name="donor_pan" class="form-control" maxlength="10" placeholder="ABCDE1234F">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label"><?= __('common.address') ?></label>
                            <textarea name="donor_address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_anonymous" class="form-check-input" id="anonymousDonation">
                                <label class="form-check-label" for="anonymousDonation"><?= __('donation.anonymous') ?></label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold btn-lg w-100 mt-3">
                        <i class="fas fa-hand-holding-heart me-2"></i><?= __('donation.submit') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function selectCategory(el, category) {
    document.querySelectorAll('.donation-category-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('donationCategory').value = category;
}
</script>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
