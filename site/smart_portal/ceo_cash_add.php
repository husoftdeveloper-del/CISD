<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
if(isset($_POST['save'])) {
    $amount = $_POST['amount'];
    $note   = $_POST['note'];
    $date   = $_POST['date'];

    $stmt = $conn->prepare(
        "INSERT INTO ceo_cash (amount, note, received_date) VALUES (?,?,?)"
    );
    $stmt->bind_param("dss",$amount,$note,$date);
    $stmt->execute();

    header("Location: ceo_cash_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CEO Cash Entry</title>

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

/* ===== PAGE BORDER ===== */
.page-border{
    width:100%;
    max-width:950px;
    padding:6px;
    border-radius:26px;
    background:linear-gradient(60deg,#1abc9c,#3498db,#9b59b6,#f39c12,#1abc9c);
    background-size:400% 400%;
    animation:borderFlow 10s linear infinite;
}
@keyframes borderFlow{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

/* ===== INNER WRAPPER ===== */
.form-wrapper{
    background:#fff;
    border-radius:22px;
    padding:45px 55px;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

/* ===== DASHBOARD BUTTON BAR ===== */
.top-actions{
    display:flex;
    justify-content:center;
    gap:16px;
    margin-bottom:35px;
    flex-wrap:wrap;
}

.action-btn{
    padding:14px 22px;
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
.list-btn{background:linear-gradient(135deg,#3498db,#1abc9c)}
.add-btn{
    background:linear-gradient(135deg,#1abc9c,#16a085);
    box-shadow:0 10px 25px rgba(26,188,156,.45);
}

.action-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 14px 35px rgba(0,0,0,.25);
}

/* ===== HEADER ===== */
.form-header{
    text-align:center;
    margin-bottom:35px;
}
.form-header i{
    font-size:54px;
    color:#1abc9c;
}
.form-header h1{
    margin-top:12px;
    font-size:34px;
    font-weight:700;
    color:#1f2d3d;
}
.form-header p{
    margin-top:6px;
    font-size:15px;
    color:#6c757d;
}

/* ===== FORM ===== */
.form-group{margin-bottom:26px}
label{
    font-size:15px;
    font-weight:600;
    color:#2c3e50;
}
input,textarea{
    width:100%;
    margin-top:10px;
    padding:16px 18px;
    border-radius:14px;
    border:1.5px solid #dfe6ee;
    font-size:16px;
    transition:.3s;
}
input:focus,textarea:focus{
    outline:none;
    border-color:#1abc9c;
    box-shadow:0 0 0 4px rgba(26,188,156,.18);
}
textarea{height:110px;resize:none}

/* ===== SAVE BUTTON ===== */
.save-btn{
    width:100%;
    margin-top:10px;
    padding:18px;
    border:none;
    border-radius:16px;
    font-size:18px;
    font-weight:700;
    cursor:pointer;
    color:#fff;
    background:linear-gradient(135deg,#1abc9c,#3498db);
    transition:.35s;
}
.save-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 40px rgba(26,188,156,.45);
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="page-border">
<div class="form-wrapper">

    <!-- DASHBOARD BUTTONS -->
    <div class="top-actions">
        <a href="dashboard.php" class="action-btn dashboard-btn">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <a href="ceo_cash_list.php" class="action-btn list-btn">
            <i class="fas fa-list"></i> Cash History
        </a>

        <a href="#" class="action-btn add-btn">
            <i class="fas fa-plus-circle"></i> Add Cash
        </a>
    </div>

    <!-- HEADER -->
    <div class="form-header">
        <i class="fas fa-briefcase"></i>
        <h1>CEO Cash Entry</h1>
        <p>Executive level cash record management</p>
    </div>

    <!-- FORM -->
    <form method="post">
        <div class="form-group">
            <label>Cash Amount (PKR)</label>
            <input type="number" step="0.01" name="amount" required>
        </div>

        <div class="form-group">
            <label>Received Date</label>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label>Remarks / Note</label>
            <textarea name="note"></textarea>
        </div>

        <button class="save-btn" name="save">
            <i class="fas fa-save"></i> Save Cash Entry
        </button>
    </form>

</div>
</div>

</body>
</html>

