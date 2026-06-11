<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid ID");
}

$sql = "DELETE FROM admissions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admission_list.php");
exit;
?>

