<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* DELETE */
if(isset($_POST['delete_id'])){
    $id = (int)$_POST['delete_id'];
    $conn->query("DELETE FROM expenditures WHERE id=$id");
}

/* MONTH FILTER */
$monthFilter = $_GET['month'] ?? '';
$where = '';
if($monthFilter){
    $where = "WHERE DATE_FORMAT(exp_date,'%Y-%m')='$monthFilter'";
}

/* FETCH DATA */
$data = $conn->query("SELECT * FROM expenditures $where ORDER BY exp_date DESC");

/* TOTAL */
$totalRes = $conn->query("SELECT SUM(amount) total FROM expenditures $where");
$totalAmount = $totalRes->fetch_assoc()['total'] ?? 0;

/* MONTH LIST */
$months = $conn->query("
    SELECT DATE_FORMAT(exp_date,'%Y-%m') m,
           DATE_FORMAT(exp_date,'%M %Y') label
    FROM expenditures
    GROUP BY m
    ORDER BY m DESC
");

/* CHART DATA */
$chartQ = $conn->query("
    SELECT DATE_FORMAT(exp_date,'%Y-%m') m, SUM(amount) total
    FROM expenditures
    GROUP BY m
    ORDER BY m
");

$labels = [];
$values = [];
while($c = $chartQ->fetch_assoc()){
    $labels[] = $c['m'];
    $values[] = $c['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Expenditure Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{margin:0;font-family:Poppins;background:#f4f7fb;}
.container{max-width:1250px;margin:35px auto;background:#fff;padding:30px;border-radius:20px;box-shadow:0 12px 30px rgba(0,0,0,.15)}

.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
.header h2{margin:0;color:#2c3e50;font-weight:700}
.back{padding:9px 22px;border-radius:25px;background:#34495e;color:#fff;text-decoration:none;font-weight:600}

/* CARDS */
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-bottom:30px}
.card{background:linear-gradient(135deg,#3498db,#1abc9c);color:#fff;padding:25px;border-radius:18px}
.card h3{margin:0;font-size:16px;font-weight:500}
.card p{margin:10px 0 0;font-size:26px;font-weight:700}

/* MONTH FILTER */
.months{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px}
.months a{padding:7px 16px;border-radius:20px;background:#ecf0f1;color:#2c3e50;text-decoration:none;font-weight:600}
.months a.active,.months a:hover{background:#1abc9c;color:#fff}

/* TABLE */
table{width:100%;border-collapse:collapse;margin-top:20px}
th{background:linear-gradient(135deg,#3498db,#1abc9c);color:#fff;padding:14px}
td{padding:12px;text-align:center;border-bottom:1px solid #eee}
tr:hover{background:#f1fdfb}
.amount{background:#e74c3c;color:#fff;padding:4px 12px;border-radius:20px;font-weight:600}

/* ACTIONS */
.actions{display:flex;justify-content:center;gap:8px}
.btn-edit{background:#1abc9c;color:#fff;padding:6px 14px;border-radius:18px;text-decoration:none;font-weight:600}
.btn-del{background:#e74c3c;color:#fff;border:none;padding:6px 14px;border-radius:18px;font-weight:600;cursor:pointer}

/* CHART */
.chart-box{margin-top:40px}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

<div class="header">
    <h2>💸 Expenditure Dashboard</h2>

    <div style="display:flex;gap:12px">
        <a href="expenditure.php"
           style="
            padding:9px 22px;
            border-radius:25px;
            background:linear-gradient(135deg,#1abc9c,#3498db);
            color:#fff;
            text-decoration:none;
            font-weight:600;
            box-shadow:0 4px 12px rgba(0,0,0,.15);
           ">
           ➕ Add Expenditure
        </a>

        <a href="dashboard.php" class="back">⬅ Dashboard</a>
    </div>
</div>


<!-- CARDS -->
<div class="cards">
    <div class="card">
        <h3>Total Expenditure <?= $monthFilter ? "(Filtered)" : "" ?></h3>
        <p>Rs <?= number_format($totalAmount) ?></p>
    </div>
</div>

<!-- MONTH FILTER -->
<div class="months">
    <a href="expenditure_list.php" class="<?= !$monthFilter?'active':'' ?>">All</a>
    <?php while($m=$months->fetch_assoc()): ?>
        <a href="?month=<?= $m['m'] ?>" class="<?= $monthFilter==$m['m']?'active':'' ?>">
            <?= $m['label'] ?>
        </a>
    <?php endwhile; ?>
</div>

<!-- TABLE -->
<table>
<tr>
    <th>#</th>
    <th>Title</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Remarks</th>
    <th>Actions</th>
</tr>

<?php $i=1; while($row=$data->fetch_assoc()): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><span class="amount">Rs <?= number_format($row['amount']) ?></span></td>
    <td><?= $row['exp_date'] ?></td>
    <td><?= htmlspecialchars($row['remarks']) ?></td>
    <td>
        <div class="actions">
            <a href="edit_expenditure.php?id=<?= $row['id'] ?>" class="btn-edit">✏</a>
            <form method="POST" onsubmit="return confirm('Delete this record?')">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <button class="btn-del">🗑</button>
            </form>
        </div>
    </td>
</tr>
<?php endwhile; ?>
</table>

<!-- CHART -->
<div class="chart-box">
    <canvas id="expChart" height="90"></canvas>
</div>

</div>

<script>
new Chart(document.getElementById('expChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Monthly Expenditure',
            data: <?= json_encode($values) ?>,
            backgroundColor: '#1abc9c'
        }]
    },
    options: {
        responsive:true,
        plugins:{legend:{display:false}}
    }
});
</script>

</body>
</html>

