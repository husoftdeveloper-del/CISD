<?php
// Local-only configuration overrides.
// Create this file so you can keep site/config.php safe for production.

// Database credentials for your local XAMPP/MySQL
$host = "localhost";
$user = "root";
$pass = ""; // usually empty for XAMPP local root
// Your local DB name must exist in XAMPP MySQL.
// If you haven’t imported site/database.sql yet, create/import it and update this value.
$db   = "novaskills"; // use an existing local DB name

// SMTP configuration for PHPMailer (optional for local)
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_PORT = 587;
$SMTP_USER = 'hu.softdeveloper@gmail.com';
$SMTP_PASS = 'rqtj yplb pjrt nmpt';
$SMTP_SECURE = 'tls';

// Institute details (optional overrides)
$INSTITUTE = [
  'name'      => 'CISD INSTITUTE',
  'tagline'   => 'Professional IT & Digital Skills Training Institute',
  'phone'     => '+923149284641',
  'whatsapp'  => '923149284641',
  'email'     => 'cisdsardheri@gmail.com',
  'address'   => 'Main Sardheri Bazar Wardagha Road',
  'maps'      => 'https://www.google.com/maps?q=Main+Boulevard+Lahore&output=embed',
];

