<?php
/**
 * Shared database connection for institute management portal.
 * Uses credentials from site/config.php and the portal database name.
 */
require_once __DIR__ . '/../../config.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    $portalDbName = $portal_db ?? $db;
    $conn = new mysqli($host, $user, $pass, $portalDbName);
    if ($conn->connect_error) {
        die('Institute portal database connection failed: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
}

require_once __DIR__ . '/../../includes/portal-migrate.php';
cisd_portal_run_migrations($conn);

require_once __DIR__ . '/portal-chrome.php';
