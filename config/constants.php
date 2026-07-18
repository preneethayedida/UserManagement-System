<?php
/**
 * System Constants
 */

// Define directory separator
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Define Root Path
define('ROOT_PATH', dirname(__DIR__) . DS);

// Determine Base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'] ?? 'localhost';
// Get subfolder if running in a subdirectory
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$subFolder = str_replace('/index.php', '', $scriptName);
define('BASE_URL', $protocol . $domainName . $subFolder);

// App Details
define('APP_NAME', 'Secure User Management System');
define('APP_VERSION', '1.0.0');

// Database Details (Defaults)
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'user_management_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
