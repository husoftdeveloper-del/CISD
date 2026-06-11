<?php
if (!isset($conn)) {
    die("Database connection missing");
}

// TOTAL PAID
$paid = $conn->query(
    "SELECT SUM(received_amount) AS total_paid FROM fee_receipts_v2"
)->fetch_assoc()['total_paid'] ?? 0;

// TOTAL EXPENDITURE
$expenditure = $conn->query(
    "SELECT SUM(amount) AS total_expense FROM expenditures"
)->fetch_assoc()['total_expense'] ?? 0;

// TOTAL CEO CASH
$ceoCash = $conn->query(
    "SELECT SUM(amount) AS total_ceo_cash FROM ceo_cash"
)->fetch_assoc()['total_ceo_cash'] ?? 0;

// FINAL CASH IN HAND (ONE SOURCE OF TRUTH)
$cashInHand = $paid + $ceoCash - $expenditure;

// NEGATIVE CHECK
$isCashNegative = ($cashInHand < 0);
