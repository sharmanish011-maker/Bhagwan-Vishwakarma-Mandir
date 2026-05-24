<?php
/**
 * Admin Logout
 */
define('BVM_ROOT', dirname(__DIR__));
require_once BVM_ROOT . '/includes/config/config.php';
require_once BVM_ROOT . '/includes/config/constants.php';
require_once BVM_ROOT . '/includes/functions/database.php';
require_once BVM_ROOT . '/includes/functions/security.php';
require_once BVM_ROOT . '/includes/functions/helpers.php';
require_once BVM_ROOT . '/includes/functions/auth.php';
session_start();
adminLogout();
header('Location: login.php');
exit;
