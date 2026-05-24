<?php
/**
 * Newsletter Subscription API Endpoint
 */
define('BVM_ROOT', dirname(__DIR__));
require_once BVM_ROOT . '/includes/config/config.php';
require_once BVM_ROOT . '/includes/config/constants.php';
require_once BVM_ROOT . '/includes/functions/database.php';
require_once BVM_ROOT . '/includes/functions/security.php';
require_once BVM_ROOT . '/includes/functions/helpers.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = sanitizeEmail($_POST['email'] ?? '');
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $existing = Database::queryOne("SELECT id FROM newsletter_subscribers WHERE email = :email", ['email' => $email]);
    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'You are already subscribed!']);
        exit;
    }
    Database::insert("INSERT INTO newsletter_subscribers (email, ip_address) VALUES (:email, :ip)", ['email' => $email, 'ip' => getClientIp()]);
    echo json_encode(['success' => true, 'message' => '🙏 Thank you for subscribing! You will receive temple updates.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
}
