<?php
/**
 * =====================================================
 * Language / i18n Functions — Hindi + English
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Get current language
 */
function getCurrentLang(): string
{
    return $_SESSION['lang'] ?? getSetting('default_language', 'en');
}

/**
 * Set language
 */
function setLanguage(string $lang): void
{
    $allowed = ['en', 'hi'];
    if (in_array($lang, $allowed)) {
        $_SESSION['lang'] = $lang;
    }
}

/**
 * Load language file and cache translations
 */
function loadTranslations(): array
{
    static $translations = null;

    if ($translations === null) {
        $lang = getCurrentLang();
        $file = LANG_PATH . '/' . $lang . '.php';

        if (file_exists($file)) {
            $translations = require $file;
        } else {
            $translations = require LANG_PATH . '/en.php';
        }
    }

    return $translations;
}

/**
 * Translate a key — main translation function
 *
 * @param string $key     Translation key (e.g., 'nav.home')
 * @param array  $replace Replacements for placeholders
 * @return string          Translated text or key if not found
 */
function __(string $key, array $replace = []): string
{
    $translations = loadTranslations();

    // Support dot notation (e.g., 'nav.home')
    $keys = explode('.', $key);
    $value = $translations;

    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $key; // Return key if translation not found
        }
    }

    if (!is_string($value)) {
        return $key;
    }

    // Replace placeholders like :name
    foreach ($replace as $placeholder => $replacement) {
        $value = str_replace(':' . $placeholder, $replacement, $value);
    }

    return $value;
}

/**
 * Get bilingual content from database columns
 * Returns the value based on current language
 */
function getBilingualContent(array $row, string $fieldBase): string
{
    $lang = getCurrentLang();
    $field = $fieldBase . '_' . $lang;
    $fallback = $fieldBase . '_en';

    return $row[$field] ?? $row[$fallback] ?? '';
}

/**
 * Get language toggle link
 */
function getLanguageToggle(): string
{
    $currentLang = getCurrentLang();
    $toggleLang = $currentLang === 'en' ? 'hi' : 'en';
    $toggleLabel = $currentLang === 'en' ? 'हिंदी' : 'English';
    $currentUrl = getCurrentUrl();

    $separator = strpos($currentUrl, '?') !== false ? '&' : '?';

    return '<a href="' . e($currentUrl) . $separator . 'lang=' . $toggleLang . '" class="lang-toggle" title="Switch to ' . $toggleLabel . '">'
        . '<i class="fas fa-globe"></i> ' . $toggleLabel . '</a>';
}

/**
 * Handle language switch from URL parameter
 */
function handleLanguageSwitch(): void
{
    if (isset($_GET['lang'])) {
        setLanguage($_GET['lang']);
        // Remove lang param and redirect
        $url = strtok(getCurrentUrl(), '?');
        $params = $_GET;
        unset($params['lang'], $params['url']);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        redirect($url);
    }
}
