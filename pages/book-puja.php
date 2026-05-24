<?php
/**
 * Book a Puja — Multi-Step Booking Form
 */
$metaTitle = 'Book a Puja Online | ' . SITE_NAME;
$metaDescription = 'Book your puja online at Bhagwan Vishwakarma Mandir. Select from various puja services, choose your date, and complete your booking.';
require_once TEMPLATES_PATH . '/header.php';
$lang = getCurrentLang();

// Fetch active pujas
try {
    $pujas = Database::query("SELECT * FROM pujas WHERE is_active = 1 ORDER BY sort_order");
} catch (Exception $e) { $pujas = []; }

$selectedPujaId = isset($_GET['puja_id']) ? sanitizeInt($_GET['puja_id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken()) {
        setFlash('danger', 'Security validation failed. Please try again.');
        redirect(BASE_URL . '/book-puja');
    }

    $missing = validateRequired(['puja_id', 'puja_date', 'devotee_name', 'devotee_phone']);
    if (!empty($missing)) {
        setFlash('warning', 'Please fill all required fields.');
        redirect(BASE_URL . '/book-puja');
    }

    $pujaId = sanitizeInt($_POST['puja_id']);
    $pujaDate = sanitize($_POST['puja_date']);
    $devoteeName = sanitize($_POST['devotee_name']);
    $devoteeEmail = !empty($_POST['devotee_email']) ? sanitizeEmail($_POST['devotee_email']) : null;
    $devoteePhone = sanitizePhone($_POST['devotee_phone']);
    $gotra = sanitize($_POST['gotra'] ?? '');
    $numPersons = max(1, sanitizeInt($_POST['num_persons'] ?? 1));
    $specialReq = sanitize($_POST['special_requests'] ?? '');

    // Get puja details for price
    $puja = Database::queryOne("SELECT * FROM pujas WHERE id = :id AND is_active = 1", ['id' => $pujaId]);
    if (!$puja) {
        setFlash('danger', 'Invalid puja selected.');
        redirect(BASE_URL . '/book-puja');
    }

    $totalAmount = $puja['price'] * $numPersons;
    $bookingNumber = generateBookingNumber();

    try {
        Database::beginTransaction();

        // Insert or find user
        $userId = null;
        if ($devoteeEmail) {
            $existingUser = Database::queryOne("SELECT id FROM users WHERE email = :email", ['email' => $devoteeEmail]);
            if ($existingUser) {
                $userId = $existingUser['id'];
            } else {
                $userId = Database::insert(
                    "INSERT INTO users (name, email, phone, gotra) VALUES (:name, :email, :phone, :gotra)",
                    ['name' => $devoteeName, 'email' => $devoteeEmail, 'phone' => $devoteePhone, 'gotra' => $gotra]
                );
            }
        }

        // Insert booking
        Database::insert(
            "INSERT INTO bookings (booking_number, user_id, puja_id, devotee_name, devotee_email, devotee_phone, gotra, puja_date, num_persons, special_requests, total_amount, status) 
             VALUES (:bn, :uid, :pid, :name, :email, :phone, :gotra, :date, :persons, :req, :amount, 'pending')",
            [
                'bn' => $bookingNumber, 'uid' => $userId, 'pid' => $pujaId,
                'name' => $devoteeName, 'email' => $devoteeEmail, 'phone' => $devoteePhone,
                'gotra' => $gotra, 'date' => $pujaDate, 'persons' => $numPersons,
                'req' => $specialReq, 'amount' => $totalAmount
            ]
        );

        Database::commit();

        // Send confirmation email
        if ($devoteeEmail) {
            $bookingData = ['booking_number' => $bookingNumber, 'devotee_name' => $devoteeName, 'devotee_email' => $devoteeEmail, 'puja_date' => $pujaDate, 'total_amount' => $totalAmount, 'status' => 'pending'];
            sendBookingConfirmation($bookingData, $puja);
        }

        setFlash('success', __('booking.success') . ' Booking #: ' . $bookingNumber);
        redirect(BASE_URL . '/book-puja');

    } catch (Exception $e) {
        Database::rollback();
        if (BVM_ENV === 'development') {
            setFlash('danger', 'Error: ' . $e->getMessage());
        } else {
            setFlash('danger', 'An error occurred. Please try again.');
        }
        redirect(BASE_URL . '/book-puja');
    }
}
?>

<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-calendar-check me-2"></i><?= __('booking.title') ?></h1>
        <?= breadcrumb([['label' => __('nav.home'), 'url' => BASE_URL], ['label' => __('nav.seva_puja'), 'url' => BASE_URL . '/seva-puja'], ['label' => __('booking.title')]]) ?>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Step Indicators -->
                <div class="booking-steps mb-4 fade-in-up">
                    <div class="booking-step active"><span class="step-number">1</span> <?= __('booking.select_puja') ?></div>
                    <div class="booking-step-connector"></div>
                    <div class="booking-step"><span class="step-number">2</span> <?= __('booking.select_date') ?></div>
                    <div class="booking-step-connector"></div>
                    <div class="booking-step"><span class="step-number">3</span> Details</div>
                    <div class="booking-step-connector"></div>
                    <div class="booking-step"><span class="step-number">4</span> <?= __('booking.confirm') ?></div>
                </div>

                <form method="POST" action="" id="bookingForm" class="bvm-form">
                    <?= csrfField() ?>

                    <!-- Step 1: Select Puja -->
                    <div class="step-content active" id="step1">
                        <h3 class="mb-4 text-maroon"><?= __('booking.select_puja') ?></h3>
                        <div class="puja-radio-group">
                            <?php foreach ($pujas as $puja): ?>
                            <label class="puja-select-card d-block mb-3 p-3 border rounded-3" style="cursor:pointer; transition: all 0.3s ease;">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" name="puja_id" value="<?= $puja['id'] ?>" class="form-check-input" required <?= $selectedPujaId == $puja['id'] ? 'checked' : '' ?>>
                                    <div class="puja-icon" style="width:50px;height:50px;font-size:1rem;"><i class="fas <?= e($puja['icon']) ?>"></i></div>
                                    <div class="flex-grow-1">
                                        <strong class="puja-name"><?= e(getBilingualContent($puja, 'name')) ?></strong>
                                        <div class="text-muted" style="font-size:0.85rem;"><?= e($puja['duration']) ?></div>
                                    </div>
                                    <div class="puja-price-text fw-bold text-maroon"><?= formatCurrency((float) $puja['price']) ?></div>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-primary-saffron mt-3" onclick="nextStep(1)"><?= __('common.next') ?> <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>

                    <!-- Step 2: Select Date -->
                    <div class="step-content" id="step2">
                        <h3 class="mb-4 text-maroon"><?= __('booking.select_date') ?></h3>
                        <div class="mb-3">
                            <label class="form-label"><?= __('booking.select_date') ?> <span class="text-danger">*</span></label>
                            <input type="date" name="puja_date" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= $lang === 'hi' ? 'पसंदीदा समय' : 'Preferred Time' ?></label>
                            <select name="puja_time" class="form-select">
                                <option value=""><?= $lang === 'hi' ? 'मंदिर तय करेगा' : 'Temple will decide' ?></option>
                                <option value="06:00"><?= $lang === 'hi' ? 'प्रातः 6:00 बजे' : 'Morning 6:00 AM' ?></option>
                                <option value="09:00"><?= $lang === 'hi' ? 'प्रातः 9:00 बजे' : 'Morning 9:00 AM' ?></option>
                                <option value="11:00"><?= $lang === 'hi' ? 'दोपहर 11:00 बजे' : 'Morning 11:00 AM' ?></option>
                                <option value="17:00"><?= $lang === 'hi' ? 'सायं 5:00 बजे' : 'Evening 5:00 PM' ?></option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-saffron" onclick="prevStep(0)"><i class="fas fa-arrow-left me-2"></i><?= __('common.previous') ?></button>
                            <button type="button" class="btn btn-primary-saffron" onclick="nextStep(2)"><?= __('common.next') ?> <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Devotee Details -->
                    <div class="step-content" id="step3">
                        <h3 class="mb-4 text-maroon"><?= $lang === 'hi' ? 'भक्त विवरण' : 'Devotee Details' ?></h3>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= __('booking.devotee_name') ?> <span class="text-danger">*</span></label>
                                <input type="text" name="devotee_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= __('booking.phone') ?> <span class="text-danger">*</span></label>
                                <input type="tel" name="devotee_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= __('booking.email') ?></label>
                                <input type="email" name="devotee_email" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= __('booking.gotra') ?></label>
                                <input type="text" name="gotra" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?= __('booking.persons') ?></label>
                                <input type="number" name="num_persons" class="form-control" value="1" min="1" max="50">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label"><?= __('booking.special_req') ?></label>
                                <textarea name="special_requests" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-saffron" onclick="prevStep(1)"><i class="fas fa-arrow-left me-2"></i><?= __('common.previous') ?></button>
                            <button type="button" class="btn btn-primary-saffron" onclick="nextStep(3)"><?= __('common.next') ?> <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div class="step-content" id="step4">
                        <h3 class="mb-4 text-maroon"><?= __('booking.confirm') ?></h3>
                        <div id="bookingReview" class="mb-4 p-3 bg-cream rounded-3"></div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-saffron" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i><?= __('common.previous') ?></button>
                            <button type="submit" class="btn btn-gold btn-lg">
                                <i class="fas fa-check-circle me-2"></i><?= __('booking.submit') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once TEMPLATES_PATH . '/footer.php'; ?>
