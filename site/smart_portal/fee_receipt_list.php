<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear  = $_GET['year'] ?? date('Y');

$limit = 10;
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';

/* ===== TOTAL PAID (MONTH) ===== */
$paidResult = $conn->query("
    SELECT SUM(received_amount) AS total_paid
    FROM fee_receipts_v2
    WHERE MONTH(receipt_date) = '$selectedMonth'
      AND YEAR(receipt_date)  = '$selectedYear'
");
$totalPaid = $paidResult->fetch_assoc()['total_paid'] ?? 0;

/* ===== TOTAL REMAINING ===== */
$remainingResult = $conn->query("
    SELECT SUM(f.remaining_amount) AS total_remaining
    FROM fee_receipts_v2 f
    INNER JOIN (
        SELECT admission_id, MAX(id) AS max_id
        FROM fee_receipts_v2
        GROUP BY admission_id
    ) x ON f.id = x.max_id
    WHERE f.remaining_amount > 0
");
$totalRemaining = $remainingResult->fetch_assoc()['total_remaining'] ?? 0;

/* ===== WHERE CLAUSE ===== */
$where = "
    WHERE MONTH(fr.receipt_date) = '$selectedMonth'
      AND YEAR(fr.receipt_date)  = '$selectedYear'
";

if ($search != '') {
    $search = $conn->real_escape_string($search);
    $where .= " AND (
        fr.admission_id LIKE '%$search%' OR
        a.name LIKE '%$search%' OR
        a.course LIKE '%$search%'
    )";
}

/* ===== MAIN DATA QUERY ===== */
$sql = "
    SELECT 
        fr.admission_id,
        a.name,
        a.course,
        SUM(fr.received_amount) AS total_paid,
        SUM(fr.discount) AS total_discount,
        MAX(fr.receipt_date) AS last_payment
    FROM fee_receipts_v2 fr
    JOIN admissions a ON fr.admission_id = a.id
    $where
    GROUP BY fr.admission_id
    ORDER BY last_payment DESC
    LIMIT $limit OFFSET $offset
";
/* ===== COUNT TOTAL RECORDS FOR PAGINATION ===== */
$countSql = "
SELECT COUNT(DISTINCT fr.admission_id) AS total
FROM fee_receipts_v2 fr
JOIN admissions a ON fr.admission_id = a.id
$where
";

$countResult = $conn->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalRecords / $limit);


$result = $conn->query($sql);

/* ===== TOTAL ROWS (FOR PAGINATION) ===== */
$countSql = "
    SELECT COUNT(DISTINCT fr.admission_id) AS total
    FROM fee_receipts_v2 fr
    JOIN admissions a ON fr.admission_id = a.id
    $where
";

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>


<!DOCTYPE html>
<html>
<head>
<title>Fee Receipt List</title>
<style>
body{font-family:Poppins;background:#eef2f6}
.container{width:95%;margin:30px auto;background:#fff;padding:25px;border-radius:12px}
h2{text-align:center;margin-bottom:15px}
.search-box{width:300px;padding:8px 12px;border:1px solid #ccc;border-radius:20px;margin-bottom:15px}
table{width:100%;border-collapse:collapse}
th,td{padding:12px;border:1px solid #ccc;text-align:center}
th{background:#2c3e50;color:#fff}
tr:hover{background:#f1f1f1}
.badge{
    padding:5px 12px;
    border-radius:20px;
    background:#1abc9c;
    color:#fff;
    font-weight:600;
    display:inline-block;
}

table a{
    text-decoration: none !important;
    color: inherit;
}

</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

<h2>📄 Fee Receipt List</h2>
<div style="text-align:left;margin-bottom:15px">
  <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>


<!-- 🔢 SUMMARY CARDS -->
<div style="display:flex;gap:15px;margin:15px 0">
  <div style="flex:1;background:#1abc9c;color:#fff;padding:18px;border-radius:14px">
    <div>Total Paid</div>
    <div style="font-size:26px;font-weight:700">Rs <?= number_format($totalPaid) ?></div>
    <small><?= date('F Y', mktime(0,0,0,$selectedMonth,1,$selectedYear)) ?></small>
  </div>

  <div style="flex:1;background:#e67e22;color:#fff;padding:18px;border-radius:14px">
    <div>Total Remaining</div>
    <div style="font-size:26px;font-weight:700">Rs <?= number_format($totalRemaining) ?></div>
    <small>Pending Fees</small>
  </div>
</div>

<!-- 📅 MONTH FILTER -->
<div class="month-bar">
<?php for($m=1;$m<=12;$m++): 
  $isActive = ($m == (int)$selectedMonth) ? 'active' : '';
?>
  <a class="month-btn <?= $isActive ?>"
     href="?month=<?= sprintf('%02d',$m) ?>&year=<?= $selectedYear ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">
     <?= date('M', mktime(0,0,0,$m,1)) ?>
  </a>
<?php endfor; ?>
</div>


<!-- 🔍 SEARCH -->
<form method="GET">
<input type="hidden" name="month" value="<?= $selectedMonth ?>">
<input type="hidden" name="year" value="<?= $selectedYear ?>">
<input type="text" name="search" class="search-box"
       placeholder="Search by ID, Name or Course"
       value="<?= htmlspecialchars($search) ?>">
</form>

<table>
<tr>
  <th>#</th>
  <th>Admission ID</th>
  <th>Name</th>
  <th>Course</th>
  <th>Paid</th>
  <th>Discount</th>
  <th>Last Date</th>
</tr>

<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $i++ ?></td>
  <td><?= $row['admission_id'] ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['course']) ?></td>
  <td>
    <a href="student_fee_details.php?admission_id=<?= $row['admission_id'] ?>">
      <span class="badge">Rs <?= number_format($row['total_paid']) ?></span>
    </a>
  </td>
  <td><?= $row['total_discount'] ?></td>
  <td><?= $row['last_payment'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<div class="pagination">

<?php if($page > 1): ?>
<a href="?page=<?= $page-1 ?>&month=<?= $selectedMonth ?>&year=<?= $selectedYear ?>&search=<?= urlencode($search) ?>">
⬅ Prev
</a>
<?php endif; ?>

<?php for($i=1;$i<=$totalPages;$i++): ?>
<a class="<?= ($i==$page)?'active':'' ?>"
   href="?page=<?= $i ?>&month=<?= $selectedMonth ?>&year=<?= $selectedYear ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
<?php endfor; ?>

<?php if($page < $totalPages): ?>
<a href="?page=<?= $page+1 ?>&month=<?= $selectedMonth ?>&year=<?= $selectedYear ?>&search=<?= urlencode($search) ?>">
Next ➡
</a>
<?php endif; ?>

</div>

</div>
</body>
<style>
  .back-btn{
  padding:8px 18px;
  background:#34495e;
  color:#fff;
  text-decoration:none;
  border-radius:20px;
  font-weight:600;
  transition:.3s;
}
.back-btn:hover{
  background:#2c3e50;
}
.month-bar{
  display:flex;
  justify-content:center;
  flex-wrap:wrap;
  gap:8px;
  margin:20px 0 25px;
}

.month-btn{
  padding:8px 16px;
  border-radius:20px;
  font-weight:600;
  font-size:14px;
  text-decoration:none;
  background:#f1f3f5;
  color:#2c3e50;
  border:1px solid #dcdde1;
  transition:.25s ease;
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
  box-shadow:0 6px 14px rgba(26,188,156,.35);
}
.pagination{
  display:flex;
  justify-content:center;
  margin:25px 0;
  gap:6px;
}

.pagination a{
  padding:7px 14px;
  border-radius:8px;
  background:#ecf0f1;
  color:#2c3e50;
  text-decoration:none;
  font-weight:600;
  transition:.25s;
}

.pagination a:hover{
  background:#1abc9c;
  color:#fff;
}

.pagination a.active{
  background:#1abc9c;
  color:#fff;
  box-shadow:0 4px 10px rgba(26,188,156,.35);
}



</style>
</html>


