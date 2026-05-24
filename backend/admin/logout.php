<?php
/**
 * Admin Logout
 */
define('BVM_ROOT', dirname(dirname(__DIR__)));
require_once BVM_ROOT . '/backend/config/config.php';
require_once BVM_ROOT . '/backend/config/constants.php';
require_once FUNCTIONS_PATH . '/database.php';
require_once FUNCTIONS_PATH . '/security.php';
require_once FUNCTIONS_PATH . '/helpers.php';
require_once FUNCTIONS_PATH . '/auth.php';
session_start();
adminLogout();
header('Location: login.php');
exit;
