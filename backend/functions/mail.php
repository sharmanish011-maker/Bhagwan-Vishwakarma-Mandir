<?php
/**
 * =====================================================
 * Mail Functions (PHPMailer-ready structure)
 * =====================================================
 * Uses PHP's built-in mail() for basic functionality.
 * Can be upgraded to PHPMailer for production SMTP.
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Send email
 */
function sendEmail(string $to, string $subject, string $htmlBody, string $fromName = ''): bool
{
    $fromName = $fromName ?: getSetting('site_name_en', SITE_NAME);
    $fromEmail = getSetting('site_email', 'noreply@bvm-temple.com');

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
        'X-Mailer: PHP/' . phpversion(),
    ];

    return @mail($to, $subject, $htmlBody, implode("\r\n", $headers));
}

/**
 * Send booking confirmation email
 */
function sendBookingConfirmation(array $booking, array $puja): bool
{
    $subject = 'Booking Confirmation - ' . $booking['booking_number'] . ' | ' . SITE_NAME;
    $html = getEmailTemplate('booking_confirmation', [
        'booking' => $booking,
        'puja'    => $puja,
        'site'    => SITE_NAME,
    ]);

    return sendEmail($booking['devotee_email'], $subject, $html);
}

/**
 * Send donation receipt email
 */
function sendDonationReceipt(array $donation): bool
{
    $subject = 'Donation Receipt - ' . $donation['receipt_number'] . ' | ' . SITE_NAME;
    $html = getEmailTemplate('donation_receipt', [
        'donation' => $donation,
        'site'     => SITE_NAME,
    ]);

    return sendEmail($donation['donor_email'], $subject, $html);
}

/**
 * Get HTML email template
 */
function getEmailTemplate(string $template, array $data): string
{
    $siteName = $data['site'] ?? SITE_NAME;

    $header = <<<HTML
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"></head>
    <body style="font-family: 'Poppins', Arial, sans-serif; background-color: #FFF8E1; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <div style="background: linear-gradient(135deg, #FF9933, #800020); padding: 30px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 24px;">ॐ {$siteName}</h1>
        </div>
        <div style="padding: 30px;">
    HTML;

    $footer = <<<HTML
        </div>
        <div style="background: #3E2723; padding: 20px; text-align: center; color: #D4AF37; font-size: 13px;">
            <p style="margin: 0;">🙏 {$siteName}</p>
            <p style="margin: 5px 0 0; color: #aaa;">This is an automated email. Please do not reply.</p>
        </div>
    </div>
    </body>
    </html>
    HTML;

    $body = match ($template) {
        'booking_confirmation' => getBookingEmailBody($data),
        'donation_receipt'     => getDonationEmailBody($data),
        'contact_confirmation' => getContactEmailBody($data),
        default                => '<p>' . ($data['message'] ?? '') . '</p>',
    };

    return $header . $body . $footer;
}

function getBookingEmailBody(array $data): string
{
    $b = $data['booking'];
    $p = $data['puja'];
    return <<<HTML
    <h2 style="color: #800020; margin-top: 0;">🙏 Booking Confirmed</h2>
    <p>Dear <strong>{$b['devotee_name']}</strong>,</p>
    <p>Your puja booking has been received successfully.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr style="background: #FFF8E1;"><td style="padding: 10px; border: 1px solid #ddd;"><strong>Booking No.</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$b['booking_number']}</td></tr>
        <tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Puja</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$p['name_en']}</td></tr>
        <tr style="background: #FFF8E1;"><td style="padding: 10px; border: 1px solid #ddd;"><strong>Date</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$b['puja_date']}</td></tr>
        <tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Amount</strong></td><td style="padding: 10px; border: 1px solid #ddd;">₹{$b['total_amount']}</td></tr>
        <tr style="background: #FFF8E1;"><td style="padding: 10px; border: 1px solid #ddd;"><strong>Status</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$b['status']}</td></tr>
    </table>
    <p style="color: #666;">Our temple team will confirm your booking shortly.</p>
    HTML;
}

function getDonationEmailBody(array $data): string
{
    $d = $data['donation'];
    return <<<HTML
    <h2 style="color: #800020; margin-top: 0;">🙏 Thank You for Your Donation</h2>
    <p>Dear <strong>{$d['donor_name']}</strong>,</p>
    <p>We are grateful for your generous donation to the temple.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr style="background: #FFF8E1;"><td style="padding: 10px; border: 1px solid #ddd;"><strong>Receipt No.</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$d['receipt_number']}</td></tr>
        <tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Amount</strong></td><td style="padding: 10px; border: 1px solid #ddd;">₹{$d['amount']}</td></tr>
        <tr style="background: #FFF8E1;"><td style="padding: 10px; border: 1px solid #ddd;"><strong>Category</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$d['category']}</td></tr>
        <tr><td style="padding: 10px; border: 1px solid #ddd;"><strong>Date</strong></td><td style="padding: 10px; border: 1px solid #ddd;">{$d['created_at']}</td></tr>
    </table>
    <p style="color: #666;">May Lord Vishwakarma bless you abundantly. 🙏</p>
    HTML;
}

function getContactEmailBody(array $data): string
{
    return <<<HTML
    <h2 style="color: #800020; margin-top: 0;">📩 Message Received</h2>
    <p>Dear <strong>{$data['name']}</strong>,</p>
    <p>Thank you for contacting us. We have received your message and will respond soon.</p>
    <p style="color: #666;">— Temple Management</p>
    HTML;
}
