<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* MONTH FILTER */
$month = $_GET['month'] ?? date('Y-m');

/* DELETE */
if(isset($_POST['delete_id'])){
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM teacher_salary WHERE id=$id");
}

/* FETCH SALARY DATA */
$sql = "
SELECT s.*, t.name AS teacher_name, t.designation
FROM teacher_salary s
INNER JOIN teachers t ON s.teacher_id = t.id
WHERE s.salary_month = ?
ORDER BY s.paid_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$month);
$stmt->execute();
$data = $stmt->get_result();

/* TOTAL SALARY */
$totalSalary = $conn->prepare("
    SELECT SUM(salary_amount) AS total
    FROM teacher_salary
    WHERE salary_month=?
");
$totalSalary->bind_param("s",$month);
$totalSalary->execute();
$total = $totalSalary->get_result()->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Salary List</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:Poppins;
    background:#f0f4f8;
    margin:0;
    padding:40px;
}
.container{
    max-width:1100px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
    position:relative;
}
.container::before{
    content:"";
    position:absolute;
    inset:-3px;
    background:linear-gradient(135deg,#1abc9c,#3498db,#9b59b6);
    border-radius:22px;
    z-index:-1;
    background-size:300% 300%;
    animation:borderMove 6s infinite alternate;
}
@keyframes borderMove{
    0%{background-position:0% 50%;}
    100%{background-position:100% 50%;}
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}
.header h2{
    margin:0;
    color:#2c3e50;
    font-weight:700;
}
.back{
    padding:10px 22px;
    background:linear-gradient(135deg,#34495e,#2c3e50);
    color:#fff;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
}
.back:hover{opacity:0.9}

/* FILTER */
.filter{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    gap:20px;
}
.filter input{
    padding:10px 14px;
    border-radius:10px;
    border:1px solid #ccc;
    font-family:Poppins;
}

/* TOTAL CARD */
.total-card{
    background:linear-gradient(135deg,#1abc9c,#3498db);
    color:#fff;
    padding:18px 25px;
    border-radius:16px;
    font-weight:700;
    font-size:18px;
    box-shadow:0 8px 25px rgba(26,188,156,0.45);
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:linear-gradient(135deg,#3498db,#1abc9c);
    color:#fff;
    padding:14px;
}
td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}
tr:hover{background:#f1fdfb}

.amount{
    background:#27ae60;
    color:#fff;
    padding:5px 14px;
    border-radius:20px;
    font-weight:600;
}

/* ACTIONS */
.actions{
    display:flex;
    justify-content:center;
    gap:8px;
}
.btn-del{
    background:#e74c3c;
    color:#fff;
    border:none;
    padding:6px 14px;
    border-radius:20px;
    cursor:pointer;
    font-weight:600;
}
.btn-del:hover{background:#c0392b}

.empty{
    text-align:center;
    padding:20px;
    color:#999;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

    <div class="header">
        <h2>💼 Teacher Salary Records</h2>
        <a href="dashboard.php" class="back">⬅ Dashboard</a>
    </div>

    <div class="filter">
        <form method="GET">
            <input type="month" name="month" value="<?= $month ?>" onchange="this.form.submit()">
        </form>

        <div class="total-card">
            Total Salary: Rs <?= number_format($total) ?>
        </div>
    </div>

    <table>
        <tr>
            <th>#</th>
            <th>Teacher</th>
            <th>Designation</th>
            <th>Month</th>
            <th>Paid Date</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>

        <?php if($data->num_rows==0): ?>
        <tr>
            <td colspan="8" class="empty">No salary record found</td>
        </tr>
        <?php endif; ?>

        <?php $i=1; while($row=$data->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['teacher_name']) ?></td>
            <td><?= htmlspecialchars($row['designation']) ?></td>
            <td><?= date("F Y", strtotime($row['salary_month']."-01")) ?></td>
            <td><?= $row['paid_date'] ?></td>
            <td><span class="amount">Rs <?= number_format($row['salary_amount']) ?></span></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
            <td>
                <div class="actions">
                    <form method="POST" onsubmit="return confirm('Delete this salary record?')">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button class="btn-del">🗑</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>

