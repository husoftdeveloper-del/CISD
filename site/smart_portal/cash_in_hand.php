<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

/* ===== TOTAL PAID ===== */
$totalPaid = $conn->query("
    SELECT SUM(received_amount) AS total
    FROM fee_receipts_v2
    WHERE MONTH(receipt_date)='$month'
      AND YEAR(receipt_date)='$year'
")->fetch_assoc()['total'] ?? 0;

/* ===== TOTAL EXPENDITURE ===== */
$totalExpense = $conn->query("
    SELECT SUM(amount) AS total
    FROM expenditures
    WHERE MONTH(exp_date)='$month'
      AND YEAR(exp_date)='$year'
")->fetch_assoc()['total'] ?? 0;
/* ===== CEO CASH (MONTHLY) ===== */
$ceoCash = $conn->query("
    SELECT SUM(amount) AS total
    FROM ceo_cash
    WHERE MONTH(received_date)='$month'
      AND YEAR(received_date)='$year'
")->fetch_assoc()['total'] ?? 0;


/* ===== CASH IN HAND ===== */
$cashInHand = $totalPaid + $ceoCash - $totalExpense;
$isNegative = ($cashInHand < 0);


include "includes/cash_calculation.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cash In Hand</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#eef2f6;
}

/* MAIN CONTAINER */
.container{
    width:96%;
    max-width:1400px;
    margin:40px auto;
    background:#fff;
    padding:40px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,.14);
    animation:fade .6s ease;
}
@keyframes fade{
    from{opacity:0;transform:translateY(25px)}
    to{opacity:1}
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}
.header h2{
    margin:0;
    color:#2c3e50;
    font-weight:700;
    font-size:26px;
}

.back-btn{
    padding:10px 22px;
    background:#34495e;
    color:#fff;
    text-decoration:none;
    border-radius:25px;
    font-weight:600;
    transition:.3s;
}
.back-btn:hover{
    background:#2c3e50;
    transform:translateY(-2px);
}

/* MONTH FILTER */
.month-bar{
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:12px;
    margin:30px 0 45px;
}

.month-btn{
    padding:10px 20px;
    border-radius:22px;
    font-weight:600;
    font-size:15px;
    text-decoration:none;
    background:#f1f3f5;
    color:#2c3e50;
    border:1px solid #dcdde1;
    transition:.25s;
}
.month-btn:hover{
    background:#1abc9c;
    color:#fff;
    border-color:#1abc9c;
}
.month-btn.active{
    background:linear-gradient(135deg,#1abc9c,#16a085);
    color:#fff;
    border-color:#16a085;
    box-shadow:0 8px 18px rgba(26,188,156,.35);
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:30px;
}

.card{
    background:#fff;
    padding:30px 32px;
    border-radius:18px;
    box-shadow:0 10px 28px rgba(0,0,0,.14);
    transition:.35s;
    position:relative;
    overflow:hidden;
}
.card:hover{
    transform:translateY(-8px);
}

/* shine animation */
.card::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,.6),transparent);
    transform:translateX(-100%);
}
.card:hover::after{
    animation:shine 1.1s;
}
@keyframes shine{
    to{transform:translateX(100%)}
}

.card h4{
    margin:0;
    font-size:16px;
    font-weight:600;
    color:#7f8c8d;
    letter-spacing:.3px;
}
.card h1{
    margin:14px 0 0;
    font-size:36px;
    font-weight:700;
    color:#2c3e50;
}

/* DASHBOARD COLORS */
.paid{ border-left:7px solid #27ae60; } /* GREEN */
.exp{  border-left:7px solid #e74c3c; } /* RED */
.cash{ border-left:7px solid #16a085; } /* DARK GREEN */
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h2>💰 Cash In Hand (Monthly)</h2>
        <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>

    <!-- MONTH FILTER -->
    <div class="month-bar">
        <?php for($m=1;$m<=12;$m++): ?>
            <a class="month-btn <?= ($m==(int)$month)?'active':'' ?>"
               href="?month=<?= sprintf('%02d',$m) ?>&year=<?= $year ?>">
                <?= date('M', mktime(0,0,0,$m,1)) ?>
            </a>
        <?php endfor; ?>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="cards">

        <div class="card paid">
            <h4>Total Paid</h4>
            <h1>Rs <?= number_format($totalPaid) ?></h1>
        </div>

        <div class="card exp">
            <h4>Total Expenditure</h4>
            <h1>Rs <?= number_format($totalExpense) ?></h1>
        </div>

       <div class="card <?= $isNegative ? 'exp' : 'cash' ?>">

            <h4>Cash In Hand</h4>
            <h1>Rs <?= number_format($cashInHand) ?></h1>
        </div>

    </div>

</div>

</body>
</html>

