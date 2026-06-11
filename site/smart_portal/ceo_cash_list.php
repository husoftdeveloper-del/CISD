<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* ===== DATABASE CONNECTION ===== */
    die("Database Error");
}

/* ===== DELETE CEO CASH ===== */
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM ceo_cash WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ceo_cash_list.php?deleted=1");
    exit;
}

/* ===== FETCH DATA ===== */
$result = $conn->query("SELECT * FROM ceo_cash ORDER BY received_date DESC");
$totalRows = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CEO Cash History</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
    min-height:100vh;
    font-family:'Poppins',sans-serif;
    background:#eef2f7;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* ===== ANIMATED PAGE BORDER ===== */
.page-frame{
    width:100%;
    max-width:1200px;
    padding:6px;
    border-radius:26px;
    background:linear-gradient(60deg,#1abc9c,#3498db,#9b59b6,#f39c12,#1abc9c);
    background-size:400% 400%;
    animation:frameMove 10s linear infinite;
}
@keyframes frameMove{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

/* ===== INNER PAGE ===== */
.page{
    background:#ffffff;
    border-radius:22px;
    padding:45px;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

/* ===== TOP BUTTONS ===== */
.top-actions{
    display:flex;
    justify-content:center;
    gap:16px;
    margin-bottom:35px;
    flex-wrap:wrap;
}

.action-btn{
    padding:14px 24px;
    border-radius:14px;
    font-weight:600;
    font-size:15px;
    text-decoration:none;
    color:#fff;
    display:flex;
    align-items:center;
    gap:8px;
    transition:.35s;
}
.dashboard-btn{background:linear-gradient(135deg,#34495e,#2c3e50)}
.add-btn{background:linear-gradient(135deg,#1abc9c,#16a085)}
.history-btn{background:linear-gradient(135deg,#3498db,#1abc9c)}

.action-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 14px 35px rgba(0,0,0,.25);
}

/* ===== TABLE ===== */
.table-wrap{
    margin-top:20px;
    border-radius:16px;
    overflow:hidden;
    border:1px solid #eef0f4;
}
table{width:100%;border-collapse:collapse}

thead{
    background:linear-gradient(135deg,#3498db,#1abc9c);
}
th{
    padding:18px;
    color:#fff;
    font-size:14px;
    text-transform:uppercase;
    font-weight:600;
    text-align:left;
}
td{
    padding:18px;
    font-size:15px;
    color:#2c3e50;
    border-bottom:1px solid #f0f0f0;
}
tbody tr:hover{background:#f6fbff}

.amount{
    font-weight:700;
    color:#27ae60;
}

/* DELETE BUTTON */
.delete-btn{
    padding:8px 14px;
    border-radius:10px;
    background:linear-gradient(135deg,#e74c3c,#c0392b);
    color:#fff;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:6px;
}
.delete-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(231,76,60,.45);
}

/* EMPTY */
.empty{
    padding:60px;
    text-align:center;
    font-size:18px;
    font-weight:600;
    color:#999;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="page-frame">
<div class="page">

    <div class="top-actions">
        <a href="dashboard.php" class="action-btn dashboard-btn">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="ceo_cash_add.php" class="action-btn add-btn">
            <i class="fas fa-plus-circle"></i> Add Cash
        </a>
        <a href="#" class="action-btn history-btn">
            <i class="fas fa-list"></i> Cash History
        </a>
    </div>

    <div class="table-wrap">
        <?php if($totalRows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; while($row=$result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= date('d M Y', strtotime($row['received_date'])) ?></td>
                    <td class="amount">Rs <?= number_format($row['amount']) ?></td>
                    <td><?= htmlspecialchars($row['note'] ?: '—') ?></td>
                    <td>
                        <a href="?delete_id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this record?')"
                           class="delete-btn">
                           <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty">No CEO cash records found</div>
        <?php endif; ?>
    </div>

</div>
</div>

</body>
</html>

