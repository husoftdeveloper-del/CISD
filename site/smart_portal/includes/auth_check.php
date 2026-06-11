<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeoutFile = __DIR__ . '/../session_timeout.txt';
$timeout = file_exists($timeoutFile) ? (int) file_get_contents($timeoutFile) : 15;
$timeoutSeconds = max(1, $timeout) * 60;

$isLoggedIn = (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true)
    || (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true);

if ($isLoggedIn && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutSeconds) {
    session_unset();
    session_destroy();
    header('Location: ../admin/login.php?timeout=1');
    exit;
}

if ($isLoggedIn) {
    $_SESSION['last_activity'] = time();
    $_SESSION['loggedin'] = true;
    return;
}

header('Location: ../admin/login.php');
exit;
