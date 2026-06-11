<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
include "db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM fee_receipts WHERE id='$id'");
header("Location: fee_receipt_list.php");
exit;
