<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Backup configuration
$host     = 'localhost';
$user     = 'root';
$password = '';
$database = 'CISD';

// File name with date
$backupFile = 'student_db_backup_' . date('Y-m-d_H-i-s') . '.sql';

// Command for mysqldump (XAMPP users usually don't need password)
$command = "mysqldump --user=$user --password=$password --host=$host $database > $backupFile";

// Execute
system($command, $output);

// Download the backup file
header('Content-Description: File Transfer');
header('Content-Type: application/sql');
header("Content-Disposition: attachment; filename=\"$backupFile\"");
header('Content-Length: ' . filesize($backupFile));
readfile($backupFile);

// Delete file after download (optional)
unlink($backupFile);
exit;

