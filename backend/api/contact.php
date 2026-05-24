<?php
/**
 * Contact Form API Endpoint
 */
define('BVM_ROOT', dirname(__DIR__));
require_once BVM_ROOT . '/includes/config/config.php';
require_once BVM_ROOT . '/includes/config/constants.php';
require_once BVM_ROOT . '/includes/functions/database.php';
require_once BVM_ROOT . '/includes/functions/security.php';
require_once BVM_ROOT . '/includes/functions/helpers.php';
require_once BVM_ROOT . '/includes/functions/mail.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = sanitize($_POST['name'] ?? '');
$email = sanitizeEmail($_POST['email'] ?? '');
$phone = sanitizePhone($_POST['phone'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

try {
    Database::insert(
        "INSERT INTO contact_messages (name, email, phone, subject, message, ip_address) VALUES (:name, :email, :phone, :subject, :message, :ip)",
        ['name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'message' => $message, 'ip' => getClientIp()]
    );
    echo json_encode(['success' => true, 'message' => '🙏 Thank you for your message. We will respond soon.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
}
