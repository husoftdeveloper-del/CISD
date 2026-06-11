<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

$data = $conn->query("SELECT * FROM teachers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Teachers List</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    min-height:100vh;
    font-family:Poppins;
    background:linear-gradient(135deg,#eef2f7,#f9fbff);
}

/* Container */
.container{
    width:92%;
    max-width:1100px;
    margin:40px auto;
    background:#fff;
    padding:28px;
    border-radius:20px;
    box-shadow:0 18px 45px rgba(0,0,0,.15);
    animation:fadeUp .6s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(25px)}
    to{opacity:1}
}

/* Header */
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
.add-btn{
    padding:10px 18px;
    border-radius:30px;
    background:linear-gradient(135deg,#1abc9c,#3498db);
    color:#fff;
    font-weight:600;
    text-decoration:none;
    transition:.3s;
}
.add-btn i{margin-right:6px}
.add-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(26,188,156,.45);
}

/* Table */
.table-wrap{
    overflow-x:auto;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
th{
    background:#2c3e50;
    color:#fff;
    padding:14px;
    font-size:14px;
    text-transform:uppercase;
}
td{
    padding:12px;
    border-bottom:1px solid #ecf0f1;
    text-align:center;
    font-size:14px;
}
tr:hover{
    background:#f6fffd;
}

/* Badge */
.badge{
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    background:#1abc9c;
    color:#fff;
    font-size:12px;
    font-weight:600;
}

/* Back */
.back{
    display:inline-block;
    margin-top:20px;
    color:#34495e;
    font-weight:600;
    text-decoration:none;
}
.back:hover{color:#1abc9c}

/* Empty */
.empty{
    padding:25px;
    text-align:center;
    color:#7f8c8d;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

    <div class="header">
        <h2><i class="fas fa-user-tie"></i> Teachers List</h2>
        <a href="add_teacher.php" class="add-btn">
            <i class="fas fa-plus"></i> Add Teacher
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Joining Date</th>
            </tr>

            <?php if($data->num_rows > 0): ?>
                <?php $i=1; while($r=$data->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td>
                        <span class="badge"><?= htmlspecialchars($r['designation']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($r['phone']) ?></td>
                    <td><?= date("d M Y", strtotime($r['joining_date'])) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty">No teachers found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <a href="dashboard.php" class="back">⬅ Back to Dashboard</a>

</div>

</body>
</html>

