<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* ===== MONTH FILTER ===== */
$month = $_GET['month'] ?? date('Y-m');

/* ===== TOTAL SALARY (MONTH) ===== */
$totalRes = $conn->query("
    SELECT SUM(s.salary_amount) AS total
    FROM teacher_salary s
    WHERE s.salary_month = '$month'
");
$totalSalary = $totalRes->fetch_assoc()['total'] ?? 0;

/* ===== FETCH MONTHLY SALARY ===== */
$data = $conn->query("
    SELECT 
        s.id,
        t.name,
        t.designation,
        s.salary_amount,
        s.paid_date,
        s.remarks
    FROM teacher_salary s
    JOIN teachers t ON s.teacher_id = t.id
    WHERE s.salary_month = '$month'
    ORDER BY s.paid_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Monthly Salary</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#eef2f6;
}
.container{
    max-width:1200px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
    animation:fade .6s ease;
}
@keyframes fade{
    from{opacity:0;transform:translateY(20px)}
    to{opacity:1}
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.header h2{
    margin:0;
    color:#2c3e50;
    font-weight:700;
}

.back-btn{
    padding:9px 20px;
    border-radius:25px;
    background:#34495e;
    color:#fff;
    text-decoration:none;
    font-weight:600;
}
.back-btn:hover{background:#2c3e50}

/* SUMMARY CARD */
.summary{
    background:#1abc9c;
    color:#fff;
    padding:20px;
    border-radius:16px;
    margin:20px 0;
}
.summary h3{
    margin:0;
    font-size:16px;
    font-weight:500;
}
.summary h1{
    margin-top:8px;
    font-size:32px;
}

/* MONTH PICKER */
.month-filter{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:25px 0;
}
.month-filter input{
    padding:8px 14px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th{
    background:linear-gradient(135deg,#3498db,#1abc9c);
    color:#fff;
    padding:14px;
    font-size:15px;
}
td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:15px;
}
tr:hover{background:#f1fdfb}

.badge{
    background:#27ae60;
    color:#fff;
    padding:5px 14px;
    border-radius:20px;
    font-weight:600;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

    <div class="header">
        <h2>💼 Teacher Monthly Salary</h2>
        <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>

    <!-- MONTH SELECT -->
    <div class="month-filter">
        <strong>Selected Month:</strong>
        <input type="month" value="<?= $month ?>"
               onchange="location='?month='+this.value">
    </div>

    <!-- TOTAL SALARY -->
    <div class="summary">
        <h3>Total Salary Paid</h3>
        <h1>Rs <?= number_format($totalSalary) ?></h1>
        <small><?= date('F Y', strtotime($month.'-01')) ?></small>
    </div>

    <!-- SALARY TABLE -->
    <table>
        <tr>
            <th>#</th>
            <th>Teacher Name</th>
            <th>Designation</th>
            <th>Salary</th>
            <th>Paid Date</th>
            <th>Remarks</th>
        </tr>

        <?php 
        $i=1;
        if($data->num_rows>0):
        while($row=$data->fetch_assoc()):
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['designation']) ?></td>
            <td><span class="badge">Rs <?= number_format($row['salary_amount']) ?></span></td>
            <td><?= $row['paid_date'] ?></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
            <td colspan="6">No salary record found for this month</td>
        </tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>

