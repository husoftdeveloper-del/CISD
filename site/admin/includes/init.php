<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/site-data.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../admin-login.php');
    exit();
}

$adminPage = $adminPage ?? '';
$pending_apps = cisd_admin_pending_count($pdo);
$unread_messages = cisd_admin_unread_messages($pdo);
$adminMessage = $adminMessage ?? '';
$adminMessageType = $adminMessageType ?? '';
