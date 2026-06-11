<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}
$id = (int)$_GET['id'];

// Delete the record
$stmt = $conn->prepare("DELETE FROM admissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back to whichever list you came from:
header("Location: remaining_fee_list.php");
exit;
