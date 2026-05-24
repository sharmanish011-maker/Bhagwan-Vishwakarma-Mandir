<?php
/**
 * Admin Panel — Main Controller
 */
ob_start();
define('BVM_ROOT', dirname(dirname(__DIR__)));
require_once BVM_ROOT . '/backend/config/config.php';
require_once BVM_ROOT . '/backend/config/constants.php';
require_once FUNCTIONS_PATH . '/database.php';
require_once FUNCTIONS_PATH . '/security.php';
require_once FUNCTIONS_PATH . '/helpers.php';
require_once FUNCTIONS_PATH . '/auth.php';
require_once FUNCTIONS_PATH . '/seo.php';
require_once FUNCTIONS_PATH . '/language.php';
require_once FUNCTIONS_PATH . '/upload.php';
require_once FUNCTIONS_PATH . '/mail.php';
require_once CONFIG_PATH . '/payment.php';
session_start();
setSecurityHeaders();

requireAdmin();

$module = sanitize($_GET['module'] ?? 'dashboard');
$validModules = ['dashboard','bookings','donations','events','gallery','pujas','pages','testimonials','newsletter','volunteers','contacts','announcements','settings'];
if (!in_array($module, $validModules)) $module = 'dashboard';

$adminUser = getCurrentAdmin();
$adminName = $adminUser['name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($module) ?> — Admin | <?= SITE_NAME ?></title>
    <?= csrfMeta() ?>
    <link href="<?= BOOTSTRAP_CSS ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= FONTAWESOME_CSS ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL ?>/admin.css">
</head>
<body class="admin-body">

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">ॐ</span>
            <div class="sidebar-brand">
                <h5><?= SITE_NAME ?></h5>
                <small>Management Panel</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php
            $menuItems = [
                ['module' => 'dashboard', 'icon' => 'fa-tachometer-alt', 'label' => 'Dashboard'],
                ['module' => 'bookings', 'icon' => 'fa-calendar-check', 'label' => 'Bookings'],
                ['module' => 'donations', 'icon' => 'fa-hand-holding-heart', 'label' => 'Donations'],
                ['module' => 'events', 'icon' => 'fa-calendar-days', 'label' => 'Events'],
                ['module' => 'pujas', 'icon' => 'fa-fire', 'label' => 'Pujas'],
                ['module' => 'gallery', 'icon' => 'fa-images', 'label' => 'Gallery'],
                ['module' => 'testimonials', 'icon' => 'fa-quote-left', 'label' => 'Testimonials'],
                ['module' => 'newsletter', 'icon' => 'fa-envelope-open-text', 'label' => 'Newsletter'],
                ['module' => 'volunteers', 'icon' => 'fa-handshake', 'label' => 'Volunteers'],
                ['module' => 'contacts', 'icon' => 'fa-inbox', 'label' => 'Messages'],
                ['module' => 'announcements', 'icon' => 'fa-bullhorn', 'label' => 'Announcements'],
                ['module' => 'settings', 'icon' => 'fa-cog', 'label' => 'Settings'],
            ];
            foreach ($menuItems as $item): ?>
            <a href="?module=<?= $item['module'] ?>" class="sidebar-link <?= $module === $item['module'] ? 'active' : '' ?>">
                <i class="fas <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>" target="_blank" class="sidebar-link"><i class="fas fa-external-link-alt"></i><span>View Website</span></a>
            <a href="logout.php" class="sidebar-link text-danger"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h4 class="topbar-title"><?= ucfirst($module) ?></h4>
            <div class="topbar-right">
                <span class="admin-greeting">🙏 <?= e($adminName) ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="admin-content">
            <?= displayFlash() ?>

            <?php
            $moduleFile = __DIR__ . '/modules/' . $module . '.php';
            if (file_exists($moduleFile)) {
                require_once $moduleFile;
            } else {
                echo '<div class="alert alert-warning">Module not found.</div>';
            }
            ?>
        </div>
    </div>

    <script src="<?= BOOTSTRAP_JS ?>"></script>
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
            document.querySelector('.admin-main').classList.toggle('sidebar-active');
        });

        // Auto-close sidebar on mobile after click
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    document.querySelector('.admin-sidebar').classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
