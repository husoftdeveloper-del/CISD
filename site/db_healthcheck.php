<?php
require_once __DIR__ . '/config.php';

header('Content-Type: text/plain');

// MySQL connectivity check
try {
    if (!isset($conn)) {
        throw new Exception('mysqli connection ($conn) not initialized');
    }
    if (!($conn instanceof mysqli)) {
        throw new Exception('$conn is not a mysqli instance');
    }

    echo "mysqli connected\n";
    echo "Current DB: ".$conn->query('select database() as db')->fetch_assoc()['db']."\n";

    $res = $conn->query("SHOW DATABASES");
    $dbs = [];
    while ($row = $res->fetch_assoc()) {
        $dbs[] = $row['Database'];
    }
    echo "--- databases on this server ---\n";
    echo implode("\n", $dbs);

} catch (Throwable $e) {
    echo "DB healthcheck failed: ".$e->getMessage()."\n";
    exit(1);
}

