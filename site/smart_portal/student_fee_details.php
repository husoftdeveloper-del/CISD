<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$admission_id = (int)$_GET['admission_id'];

/* Student info */
$s = $conn->prepare("SELECT * FROM admissions WHERE id=?");
$s->bind_param("i",$admission_id);
$s->execute();
$student = $s->get_result()->fetch_assoc();

/* Fee receipts */
$r = $conn->prepare("
    SELECT * FROM fee_receipts_v2
    WHERE admission_id=?
    ORDER BY receipt_date ASC
");
$r->bind_param("i",$admission_id);
$r->execute();
$receipts = $r->get_result();

/* Totals */
$t = $conn->prepare("
    SELECT 
        SUM(monthly_fee + admission_fee + registration_fee +
            examination_fee_1 + examination_fee_2 + examination_fee_3
        ) AS total_fee,
        SUM(received_amount) AS total_paid
    FROM fee_receipts_v2
    WHERE admission_id=?
");
$t->bind_param("i",$admission_id);
$t->execute();
$total = $t->get_result()->fetch_assoc();

$remaining = ($total['total_fee'] ?? 0) - ($total['total_paid'] ?? 0);
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Fee Details</title>
<style>
body{font-family:Poppins;background:#eef2f6}
.container{width:95%;margin:25px auto;background:#fff;padding:25px;border-radius:12px}
h2{text-align:center}
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{border:1px solid #ccc;padding:10px;text-align:center}
th{background:#2c3e50;color:#fff}
.summary td{font-weight:bold}
.badge{padding:6px 14px;border-radius:20px;background:#1abc9c;color:#fff}
.due{background:#e74c3c}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>

<div class="container">

<h2>📊 Student Fee Ledger</h2>

<p>
<b><h2>Name:</b> <?= $student['name'] ?><br></h2>
<b><h2>Course:</b> <?= $student['course'] ?><br></h2>
<b><h2>Gender:</b> <?= $student['gender'] ?></h2>
</p>

<table class="summary">

<tr>
    <td>Total Paid</td>
    <td><span class="badge">Rs <?= number_format($total['total_paid'] ?? 0) ?></span></td>
</tr>
<tr>
    <td>Remaining</td>
    <td><span class="badge due">Rs <?= number_format($remaining) ?></span></td>
</tr>
</table>
<h3 style="margin-top:25px">Fee History</h3>

<table>
<tr>
    <th>Date</th>
    <th>Monthly</th>
    <th>Admission</th>
    <th>Registration</th>
    <th>Exam 1</th>
    <th>Exam 2</th>
    <th>Exam 3</th>
    <th>Paid</th>
</tr>

<?php while($row = $receipts->fetch_assoc()): ?>
<tr>
    <td><?= $row['receipt_date'] ?></td>
    <td><?= $row['monthly_fee'] ?></td>
    <td><?= $row['admission_fee'] ?></td>
    <td><?= $row['registration_fee'] ?></td>
    <td><?= $row['examination_fee_1'] ?></td>
    <td><?= $row['examination_fee_2'] ?></td>
    <td><?= $row['examination_fee_3'] ?></td>
    <td><b><?= $row['received_amount'] ?></b></td>
</tr>
<?php endwhile; ?>
</table>

</div>
</body>
</html>

