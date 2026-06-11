<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$selected_month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = date('Y');

/* MONTHLY INCOME */
$stmt = $conn->prepare("
    SELECT SUM(received_amount) AS income
    FROM fee_receipts_v2
    WHERE MONTH(receipt_date) = ?
      AND YEAR(receipt_date) = ?
");
$stmt->bind_param("ii", $selected_month, $year);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$monthly_income = $result['income'] ?? 0;

/* YEARLY TOTAL INCOME */
$totalStmt = $conn->prepare("
    SELECT SUM(received_amount) AS total_income
    FROM fee_receipts_v2
    WHERE YEAR(receipt_date) = ?
");
$totalStmt->bind_param("i", $year);
$totalStmt->execute();
$total_income = $totalStmt->get_result()->fetch_assoc()['total_income'] ?? 0;

$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Income Summary</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #0f172a;
            color: #fff;
            margin: 0;
            padding: 40px 20px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #ffffff;
            text-shadow: 1px 1px 3px #00f7ff;
        }

        .month-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px;

            margin-bottom: 40px;
        }

        .month-card {
            padding: 12px 20px;
            background: #1e293b;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            position: relative;
            transition: 0.3s ease;
            border: 2px solid transparent;
        }

        .month-card.active,
        .month-card:hover {
            border: 2px solid #00f7ff;
            background: #1abc9c;
            color: #fff;
        }

        .card {
            max-width: 500px;
            margin: 0 auto 30px;
            background: rgba(255, 255, 255, 0.05);
            padding: 40px 30px;
            border-radius: 16px;
            border: 2px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .card h2 {
            font-size: 30px;
            color: #fff;
            margin-bottom: 20px;

        }

        .amount {
            font-size: 44px;
            color: #ffffffff;
            font-weight: bold;

        }

        .total-summary {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            max-width: 400px;
            margin: 0 auto 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            font-size: 20px;
            color: #f5f5f5;
            border: 2px solid #1abc9c;
        }

        .back-btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .back-btn:hover {
            background: #1abc9c;
        }

        @media(max-width: 600px) {

            .card,
            .total-summary {
                padding: 25px 15px;
            }

            .month-cards {
                gap: 10px;
            }

            .month-card {
                font-size: 14px;
                padding: 10px 16px;
            }
        }

        body.dark {
            background: #1e1e2f !important;
            color: #f1f1f1;
        }

        body.dark .card,
        body.dark .summary-card,
        body.dark .main-content,
        body.dark .header,
        body.dark .footer {
            background-color: #2e2e3e !important;
            color: #fff !important;
        }

        body.dark .sidebar {
            background: #181818 !important;
        }

        body.dark .sidebar a {
            color: #eee;
        }

        body.dark .sidebar a:hover {
            background: #333;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <h1>Monthly Income Summary - <?= $year ?></h1>

    <div class="month-cards">
        <?php
        foreach ($months as $num => $name) {
            $active = ($selected_month == $num) ? 'active' : '';
            echo "<a class='month-card $active' href='?month=$num'>$name</a>";
        }
        ?>
    </div>
    <div class="card">
        <h2 style="color: white; font-weight: 800;">
            <?= $months[$selected_month] ?> Income
        </h2>
        <div class="amount" style="color: white; font-weight: 800; font-size: 40px;">
            Rs. <?= number_format($monthly_income) ?>
        </div>
    </div>


    <div class="total-summary">
        <strong>Total Income (<?= $year ?>):</strong> Rs. <?= number_format($total_income) ?>
    </div>

    <a href="dashboard.php" class="back-btn">&larr; Back to Dashboard</a>

</body>
<?php include "session_timer.php"; ?>


</html>
<script>
    const darkMode = localStorage.getItem("dark-mode");
    if (darkMode === "enabled") {
        document.body.classList.add("dark");
    }
</script>
