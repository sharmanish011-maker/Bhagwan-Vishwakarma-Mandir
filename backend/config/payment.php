<?php
/**
 * =====================================================
 * Payment Configuration (Razorpay-ready)
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Get Razorpay API credentials (from database settings)
 */
function getRazorpayConfig(): array
{
    return [
        'key_id'     => getSetting('razorpay_key_id', ''),
        'key_secret' => getSetting('razorpay_key_secret', ''),
        'test_mode'  => (bool) getSetting('razorpay_test_mode', '1'),
        'currency'   => getSetting('currency', 'INR'),
    ];
}

/**
 * Check if Razorpay is configured
 */
function isPaymentEnabled(): bool
{
    $config = getRazorpayConfig();
    return !empty($config['key_id']) && !empty($config['key_secret']);
}

/**
 * Create a Razorpay order via cURL
 *
 * @param float  $amount  Amount in rupees
 * @param string $receipt Receipt/reference number
 * @param array  $notes   Additional notes
 * @return array|null      Order data or null on failure
 */
function createRazorpayOrder(float $amount, string $receipt, array $notes = []): ?array
{
    $config = getRazorpayConfig();
    if (empty($config['key_id']) || empty($config['key_secret'])) {
        return null;
    }

    $data = [
        'amount'   => (int) ($amount * 100), // Convert to paise
        'currency' => $config['currency'],
        'receipt'  => $receipt,
        'notes'    => $notes ?: new \stdClass(),
    ];

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_USERPWD        => $config['key_id'] . ':' . $config['key_secret'],
        CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }

    error_log('Razorpay order creation failed: ' . $response);
    return null;
}

/**
 * Verify Razorpay payment signature
 */
function verifyRazorpayPayment(string $orderId, string $paymentId, string $signature): bool
{
    $config = getRazorpayConfig();
    $generated = hash_hmac('sha256', $orderId . '|' . $paymentId, $config['key_secret']);
    return hash_equals($generated, $signature);
}
