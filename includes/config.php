<?php
/**
 * Configuration File
 * Loads environment variables and defines application constants
 */

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Application Configuration
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost');

// Database Configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'astegiudiziarie24');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_CHARSET', 'utf8mb4');

// Email Configuration
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.example.com');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
define('MAIL_USER', $_ENV['MAIL_USER'] ?? '');
define('MAIL_PASS', $_ENV['MAIL_PASS'] ?? '');
define('MAIL_FROM', $_ENV['MAIL_FROM'] ?? 'noreply@trovaveterinario.it');
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME'] ?? 'Trova Veterinario');
define('SITE_NAME', 'Trova Veterinario');

// Admin Configuration
define('ADMIN_EMAIL', $_ENV['ADMIN_EMAIL'] ?? 'info@trovaveterinario.it');

// reCAPTCHA Configuration
define('RECAPTCHA_SITE_KEY', $_ENV['RECAPTCHA_SITE_KEY'] ?? '');
define('RECAPTCHA_SECRET_KEY', $_ENV['RECAPTCHA_SECRET_KEY'] ?? '');

// Session Configuration
define('SESSION_LIFETIME', $_ENV['SESSION_LIFETIME'] ?? 7200);
define('SESSION_SECURE', filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN));

// Security Configuration
define('CSRF_TOKEN_NAME', $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token');
define('PASSWORD_MIN_LENGTH', $_ENV['PASSWORD_MIN_LENGTH'] ?? 8);

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('LOGS_PATH', ROOT_PATH . '/logs');

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Timezone
date_default_timezone_set('Europe/Rome');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

if (SESSION_SECURE) {
    ini_set('session.cookie_secure', 1);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
