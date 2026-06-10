<?php
// If you have a local environment, prefer overriding credentials from config_local.php
// (keeps this config.php usable for production-style deployment).
if (file_exists(__DIR__ . '/config_local.php')) {
    require_once __DIR__ . '/config_local.php';
}

// Defaults (used when config_local.php is missing)
$host = $host ?? 'localhost';
$user = $user ?? 'u328011253_cisd_db';
$pass = $pass ?? 'Y9:>5L4VF5p$';
$db   = $db   ?? 'u328011253_cisd_db';

// SMTP configuration for PHPMailer (can be overridden in config_local.php)
$SMTP_HOST = $SMTP_HOST ?? 'smtp.gmail.com';
$SMTP_PORT = $SMTP_PORT ?? 587;
$SMTP_USER = $SMTP_USER ?? 'hu.softdeveloper@gmail.com';
$SMTP_PASS = $SMTP_PASS ?? 'rqtj yplb pjrt nmpt';
$SMTP_SECURE = $SMTP_SECURE ?? 'tls';

// MySQLi connection (legacy)
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// PDO connection (admin + new features)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('PDO Database connection failed: ' . $e->getMessage());
}

require_once __DIR__ . '/includes/db-migrate.php';
require_once __DIR__ . '/includes/site-data.php';
cisd_run_migrations($pdo);
$SITE_SETTINGS = cisd_load_site_settings($pdo);

// Institute details (edit freely). Can be overridden in config_local.php.
if (!isset($INSTITUTE)) {
    $INSTITUTE = [
        'name'      => 'CISD INSTITUTE',
        'tagline'   => 'Professional IT & Digital Skills Training Institute',
        'phone'     => '+923705040330',
        'whatsapp'  => '923705040330',
        'email'     => 'usmanalishah5040@gmail.com',
        'address'   => 'Main Sardheri Bazar Wardagha Road',
        'maps'      => 'https://www.google.com/maps?q=Main+Boulevard+Lahore&output=embed',
    ];
}

cisd_apply_institute_from_settings();

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
