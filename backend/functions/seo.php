<?php
/**
 * =====================================================
 * SEO Functions — Meta tags, Schema, Open Graph
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Generate page meta tags
 */
function getMetaTags(array $options = []): string
{
    $defaults = [
        'title'       => getSetting('meta_title', SITE_NAME),
        'description' => getSetting('meta_description', ''),
        'keywords'    => '',
        'image'       => IMAGES_URL . '/og-image.jpg',
        'url'         => getCurrentUrl(),
        'type'        => 'website',
        'locale'      => 'en_IN',
    ];

    $meta = array_merge($defaults, $options);

    $html = '';

    // Basic meta
    $html .= '<title>' . e($meta['title']) . '</title>' . "\n";
    $html .= '<meta name="description" content="' . e($meta['description']) . '">' . "\n";

    if (!empty($meta['keywords'])) {
        $html .= '<meta name="keywords" content="' . e($meta['keywords']) . '">' . "\n";
    }

    // Canonical URL
    $html .= '<link rel="canonical" href="' . e($meta['url']) . '">' . "\n";

    // Open Graph
    $html .= '<meta property="og:title" content="' . e($meta['title']) . '">' . "\n";
    $html .= '<meta property="og:description" content="' . e($meta['description']) . '">' . "\n";
    $html .= '<meta property="og:image" content="' . e($meta['image']) . '">' . "\n";
    $html .= '<meta property="og:url" content="' . e($meta['url']) . '">' . "\n";
    $html .= '<meta property="og:type" content="' . e($meta['type']) . '">' . "\n";
    $html .= '<meta property="og:locale" content="' . e($meta['locale']) . '">' . "\n";
    $html .= '<meta property="og:site_name" content="' . e(SITE_NAME) . '">' . "\n";

    // Twitter Card
    $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $html .= '<meta name="twitter:title" content="' . e($meta['title']) . '">' . "\n";
    $html .= '<meta name="twitter:description" content="' . e($meta['description']) . '">' . "\n";
    $html .= '<meta name="twitter:image" content="' . e($meta['image']) . '">' . "\n";

    return $html;
}

/**
 * Generate Schema.org JSON-LD for the temple (HinduTemple)
 */
function getTempleSchema(): string
{
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'HinduTemple',
        'name'        => getSetting('site_name_en', SITE_NAME),
        'alternateName' => getSetting('site_name_hi', SITE_NAME_HI),
        'description' => getSetting('meta_description', ''),
        'url'         => BASE_URL,
        'telephone'   => getSetting('site_phone', ''),
        'email'       => getSetting('site_email', ''),
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => getSetting('site_address_en', ''),
            'addressCountry'  => 'IN',
        ],
        'openingHours' => [
            'Mo-Su ' . getSetting('morning_opening', '05:00') . '-' . getSetting('morning_closing', '12:00'),
            'Mo-Su ' . getSetting('evening_opening', '16:00') . '-' . getSetting('evening_closing', '21:00')
        ],
        'sameAs' => array_filter([
            getSetting('facebook_url'),
            getSetting('instagram_url'),
            getSetting('youtube_url'),
            getSetting('twitter_url'),
        ]),
        'image' => IMAGES_URL . '/og-image.jpg',
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Generate breadcrumb Schema.org JSON-LD
 */
function getBreadcrumbSchema(array $items): string
{
    $listItems = [];
    foreach ($items as $i => $item) {
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $item['label'],
            'item'     => $item['url'] ?? BASE_URL,
        ];
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $listItems,
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Generate Event Schema.org JSON-LD
 */
function getEventSchema(array $event): string
{
    $schema = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Event',
        'name'      => $event['title_en'],
        'startDate' => date('c', strtotime($event['start_date'])),
        'location'  => [
            '@type' => 'Place',
            'name'  => getSetting('site_name_en', SITE_NAME),
            'address' => getSetting('site_address_en', ''),
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name'  => getSetting('site_name_en', SITE_NAME),
            'url'   => BASE_URL,
        ],
    ];

    if (!empty($event['end_date'])) {
        $schema['endDate'] = date('c', strtotime($event['end_date']));
    }

    if (!empty($event['description_en'])) {
        $schema['description'] = strip_tags($event['description_en']);
    }

    if (!empty($event['image'])) {
        $schema['image'] = EVENTS_URL . '/' . $event['image'];
    }

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

/**
 * Get current full URL
 */
function getCurrentUrl(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
}

/**
 * Generate Google Analytics script tag
 */
function getAnalyticsScript(): string
{
    $gaId = getSetting('google_analytics_id');
    if (empty($gaId)) return '';

    return <<<HTML
    <script async src="https://www.googletagmanager.com/gtag/js?id={$gaId}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{$gaId}');
    </script>
    HTML;
}
