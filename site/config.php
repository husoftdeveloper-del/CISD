<?php
// Database configuration for XAMPP/localhost
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'novaskills';

// SMTP configuration for PHPMailer
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_PORT = 587;
$SMTP_USER = 'hu.softdeveloper@gmail.com'; // replace with actual Gmail address
$SMTP_PASS = 'rqtj yplb pjrt nmpt'; // replace with actual Gmail App password
$SMTP_SECURE = 'tls';


// MySQLi connection (for legacy code)
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// PDO connection (for admin dashboard and new features)
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('PDO Database connection failed: ' . $e->getMessage());
}

// Institute details (edit freely)
$INSTITUTE = [
  'name'      => 'CISD INSTITUTE',
  'tagline'   => 'Professional IT & Digital Skills Training Institute',
  'phone'     => '+923705040330',
  'whatsapp'  => '923705040330',
  'email'     => 'usmanalishah5040@gmail.com',
  'address'   => 'Main Sardheri Bazar Wardagha Road',
  'maps'      => 'https://www.google.com/maps?q=Main+Boulevard+Lahore&output=embed',
];

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
