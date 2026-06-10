<?php
// Load local overrides (for XAMPP/dev only)
if (file_exists(__DIR__ . '/config_local.php')) {
    require_once __DIR__ . '/config_local.php';
}

// Defaults (when config_local.php is missing)
$host = $host ?? 'localhost';
$user = $user ?? 'u328011253_cisd_db';
$pass = $pass ?? 'Y9:>5L4VF5p$';
$db   = $db   ?? 'u328011253_cisd_db';

// SMTP configuration for PHPMailer
$SMTP_HOST = $SMTP_HOST ?? 'smtp.gmail.com';
$SMTP_PORT = $SMTP_PORT ?? 587;
$SMTP_USER = $SMTP_USER ?? 'hu.softdeveloper@gmail.com';
$SMTP_PASS = $SMTP_PASS ?? 'rqtj yplb pjrt nmpt';
$SMTP_SECURE = $SMTP_SECURE ?? 'tls';

// MySQLi connection (legacy)
// IMPORTANT: to avoid fatal errors when DB name doesn't exist yet,
// connect without selecting DB. Later code will still work once the DB exists.
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// Select database if it exists (no fatal error if it doesn't)
$dbSelected = false;
if (!empty($db)) {
    // suppress warnings, but also avoid fatal exceptions
    try {
        $dbSelected = $conn->select_db($db);
    } catch (Throwable $t) {
        $dbSelected = false;
    }
}

// PDO connection (admin + new features)
// Create PDO only if DB is available; otherwise keep $pdo undefined.
$pdo = null;
if (!empty($db) && $dbSelected) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // If PDO fails, don't crash the whole site; some pages can still work.
        $pdo = null;
    }
}

// Institute details
if (!isset($INSTITUTE)) {
    $INSTITUTE = [
        'name'      => 'CISD INSTITUTE',
        'tagline'   => 'Professional IT & Digital Skills Training Institute',
        'phone'     => '+923149284641',
        'whatsapp'  => '923149284641',
        'email'     => 'cisdsardheri@gmail.com',
        'address'   => 'Main Sardheri Bazar Wardagha Road',
        'maps'      => 'https://www.google.com/maps?q=Main+Boulevard+Lahore&output=embed',
    ];
}

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

