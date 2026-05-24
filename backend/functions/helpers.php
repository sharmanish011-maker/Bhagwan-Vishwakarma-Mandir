<?php
/**
 * =====================================================
 * Helper Functions — Utility functions
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Get site setting from database
 */
function getSetting(string $key, string $default = ''): string
{
    static $settings = null;

    if ($settings === null) {
        $rows = Database::query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }

    return $settings[$key] ?? $default;
}

/**
 * Get all settings by group
 */
function getSettingsByGroup(string $group): array
{
    return Database::query(
        "SELECT * FROM settings WHERE setting_group = :group ORDER BY id",
        ['group' => $group]
    );
}

/**
 * Format currency (Indian Rupee)
 */
function formatCurrency(float $amount): string
{
    if ($amount == 0) {
        return 'Free';
    }
    return '₹' . number_format($amount, 2);
}

/**
 * Format date for display
 */
function formatDate(string $date, string $format = 'd M Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Format date with time
 */
function formatDateTime(string $datetime, string $format = 'd M Y, h:i A'): string
{
    return date($format, strtotime($datetime));
}

/**
 * Get relative time (e.g., "2 hours ago")
 */
function timeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    if ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';

    return formatDate($datetime);
}

/**
 * Generate URL-friendly slug
 */
function createSlug(string $text): string
{
    $slug = strtolower($text);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Generate booking number
 */
function generateBookingNumber(): string
{
    return 'BVM-BK-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
}

/**
 * Generate donation receipt number
 */
function generateReceiptNumber(): string
{
    return 'BVM-DON-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
}

/**
 * Truncate text with ellipsis
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Check if current page matches given slug
 */
function isActivePage(string $slug): bool
{
    global $currentPage;
    return ($currentPage ?? '') === $slug;
}

/**
 * Generate active class for navigation
 */
function activeClass(string $slug): string
{
    return isActivePage($slug) ? 'active' : '';
}

/**
 * Get pagination HTML
 */
function getPagination(int $totalItems, int $currentPageNum, int $perPage = ITEMS_PER_PAGE, string $baseUrl = ''): string
{
    $totalPages = ceil($totalItems / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

    // Previous
    $prevDisabled = $currentPageNum <= 1 ? 'disabled' : '';
    $prevPage = max(1, $currentPageNum - 1);
    $html .= '<li class="page-item ' . $prevDisabled . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $prevPage . '"><i class="fas fa-chevron-left"></i></a></li>';

    // Page numbers
    $start = max(1, $currentPageNum - 2);
    $end = min($totalPages, $currentPageNum + 2);

    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        $activeClass = $i === $currentPageNum ? 'active' : '';
        $html .= '<li class="page-item ' . $activeClass . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . '">' . $totalPages . '</a></li>';
    }

    // Next
    $nextDisabled = $currentPageNum >= $totalPages ? 'disabled' : '';
    $nextPage = min($totalPages, $currentPageNum + 1);
    $html .= '<li class="page-item ' . $nextDisabled . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $nextPage . '"><i class="fas fa-chevron-right"></i></a></li>';

    $html .= '</ul></nav>';
    return $html;
}

/**
 * Redirect to a URL
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Set flash message in session
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Display flash message as Bootstrap alert
 */
function displayFlash(): string
{
    $flash = getFlash();
    if (!$flash) return '';

    $type = e($flash['type']);
    $message = e($flash['message']);

    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">'
        . $message
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

/**
 * Get status badge HTML
 */
function statusBadge(string $status): string
{
    $badges = [
        'pending'   => 'warning',
        'approved'  => 'info',
        'completed' => 'success',
        'cancelled' => 'secondary',
        'rejected'  => 'danger',
        'paid'      => 'success',
        'unpaid'    => 'warning',
        'failed'    => 'danger',
        'refunded'  => 'info',
        'active'    => 'success',
        'inactive'  => 'secondary',
        'registered' => 'primary',
        'confirmed' => 'success',
    ];

    $badgeClass = $badges[$status] ?? 'secondary';
    return '<span class="badge bg-' . $badgeClass . '">' . ucfirst(e($status)) . '</span>';
}

/**
 * Generate breadcrumb HTML
 */
function breadcrumb(array $items): string
{
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    $lastIndex = count($items) - 1;

    foreach ($items as $index => $item) {
        if ($index === $lastIndex) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">' . e($item['label']) . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . e($item['url']) . '">' . e($item['label']) . '</a></li>';
        }
    }

    $html .= '</ol></nav>';
    return $html;
}

/**
 * Check if string contains Hindi characters
 */
function isHindi(string $text): bool
{
    return preg_match('/[\x{0900}-\x{097F}]/u', $text) === 1;
}

/**
 * Get countdown data for an event
 */
function getCountdown(string $targetDate): array
{
    $target = strtotime($targetDate);
    $now = time();
    $diff = $target - $now;

    if ($diff <= 0) {
        return ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0, 'expired' => true];
    }

    return [
        'days'    => floor($diff / 86400),
        'hours'   => floor(($diff % 86400) / 3600),
        'minutes' => floor(($diff % 3600) / 60),
        'seconds' => $diff % 60,
        'expired' => false
    ];
}

/**
 * Extract YouTube Video ID from URL
 */
function getYouTubeId(string $url): string|bool
{
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return false;
}
