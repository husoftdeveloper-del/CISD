<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* SAVE SALARY */
if(isset($_POST['save'])){
    $teacher  = $_POST['teacher_id'];
    $amount   = $_POST['amount'];
    $month    = $_POST['month'];
    $date     = $_POST['paid_date'];
    $remarks  = $_POST['remarks'];

    /* ===== INSERT TEACHER SALARY ===== */
    $stmt = $conn->prepare("
        INSERT INTO teacher_salary
        (teacher_id, salary_amount, salary_month, paid_date, remarks)
        VALUES (?,?,?,?,?)
    ");
    $stmt->bind_param("iisss",$teacher,$amount,$month,$date,$remarks);
    $stmt->execute();

    /* ===== FETCH TEACHER NAME ===== */
    $tRes = $conn->query("SELECT name FROM teachers WHERE id='$teacher'");
    $tRow = $tRes->fetch_assoc();
    $teacherName = $tRow['name'] ?? 'Teacher';

    /* ===== AUTO ADD TO EXPENDITURES ===== */
    $expTitle   = "Teacher Salary - ".$teacherName;
    $expRemarks = "Auto entry from teacher salary module";

    $expStmt = $conn->prepare("
        INSERT INTO expenditures (title, amount, exp_date, remarks)
        VALUES (?,?,?,?)
    ");
    $expStmt->bind_param("sdss",$expTitle,$amount,$date,$expRemarks);
    $expStmt->execute();

    header("Location: teacher_salary_list.php");
    exit;
}

/* TEACHERS LIST */
$teachers = $conn->query("SELECT id,name,designation FROM teachers ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Teacher Salary</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:Poppins;
    background:#f0f4f8;
    padding:40px;
}
.container{
    max-width:520px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:22px;
    position:relative;
    box-shadow:0 18px 40px rgba(0,0,0,.15);
}
.container::before{
    content:"";
    position:absolute;
    inset:-3px;
    border-radius:24px;
    background:linear-gradient(135deg,#1abc9c,#3498db,#9b59b6);
    z-index:-1;
    background-size:300% 300%;
    animation:borderMove 6s infinite alternate;
}
@keyframes borderMove{
    0%{background-position:0% 50%;}
    100%{background-position:100% 50%;}
}

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
    padding:9px 20px;
    background:linear-gradient(135deg,#34495e,#2c3e50);
    color:#fff;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
}
.back:hover{opacity:0.9}

label{
    font-weight:600;
    margin-top:14px;
    display:block;
    color:#2c3e50;
}
input,select,textarea{
    width:95%;
    padding:11px 14px;
    margin-top:6px;
    border-radius:12px;
    border:1px solid #ccc;
    font-family:Poppins;
    font-size:14px;
}
textarea{resize:none}

button{
    width:100%;
    margin-top:22px;
    padding:14px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:700;
    color:#fff;
    cursor:pointer;
    background:linear-gradient(135deg,#1abc9c,#3498db);
    box-shadow:0 8px 20px rgba(26,188,156,.45);
    transition:.3s;
}
button:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 28px rgba(26,188,156,.6);
}

.note{
    text-align:center;
    margin-top:12px;
    font-size:13px;
    color:#888;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

    <div class="header">
        <h2>💼 Add Teacher Salary</h2>
        <a href="dashboard.php" class="back">⬅ Dashboard</a>
    </div>

    <form method="POST">

        <label>Teacher Name</label>
        <select name="teacher_id" required>
            <option value="">-- Select Teacher --</option>
            <?php while($t=$teachers->fetch_assoc()): ?>
            <option value="<?= $t['id'] ?>">
                <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['designation']) ?>)
            </option>
            <?php endwhile; ?>
        </select>

        <label>Salary Amount (Rs)</label>
        <input type="number" name="amount" required min="1">

        <label>Salary Month</label>
        <input type="month" name="month" required>

        <label>Paid Date</label>
        <input type="date" name="paid_date" value="<?= date('Y-m-d') ?>" required>

        <label>Remarks</label>
        <textarea name="remarks" rows="3" placeholder="Optional"></textarea>

        <button name="save">💾 Save Salary</button>

    </form>

    <div class="note">
        Salary will appear in Monthly Salary Sheet
    </div>

</div>

</body>
</html>

