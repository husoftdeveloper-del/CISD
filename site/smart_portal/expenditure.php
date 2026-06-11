<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

if(isset($_POST['save'])){
    $title   = $_POST['title'];
    $amount  = $_POST['amount'];
    $date    = $_POST['exp_date'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("
        INSERT INTO expenditures (title,amount,exp_date,remarks)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param("siss",$title,$amount,$date,$remarks);
    $stmt->execute();

    header("Location: expenditure_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Expenditure</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:linear-gradient(135deg,#eef2f6,#f9fbfd);
}

/* MAIN CARD */
.box{
    max-width:600px;
    margin:50px auto;
    background:#fff;
    padding:40px;
    border-radius:22px;
    box-shadow:0 15px 40px rgba(0,0,0,.15);
    animation:fade .6s ease;
    position:relative;
}
@keyframes fade{
    from{opacity:0;transform:translateY(25px)}
    to{opacity:1}
}

/* HEADER */
.box h2{
    text-align:center;
    margin-bottom:30px;
    color:#2c3e50;
    font-weight:700;
    font-size:26px;
}

/* FORM */
.form-group{
    margin-bottom:18px;
}
label{
    display:block;
    font-weight:600;
    color:#34495e;
    margin-bottom:6px;
    font-size:15px;
}

input,textarea{
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid #dcdde1;
    font-size:15px;
    transition:.25s;
    outline:none;
}
textarea{resize:none;height:90px}

input:focus,textarea:focus{
    border-color:#1abc9c;
    box-shadow:0 0 0 3px rgba(26,188,156,.15);
}

/* BUTTONS */
.actions{
    display:flex;
    gap:14px;
    margin-top:25px;
}

button{
    flex:1;
    padding:14px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#1abc9c,#3498db);
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}
button:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,.18);
}

.back-btn{
    flex:1;
    text-align:center;
    padding:14px;
    border-radius:30px;
    background:#ecf0f1;
    color:#2c3e50;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
}
.back-btn:hover{
    background:#dfe6e9;
}

/* TOP ICON */
.icon{
    position:absolute;
    top:-28px;
    left:50%;
    transform:translateX(-50%);
    background:linear-gradient(135deg,#1abc9c,#3498db);
    width:56px;
    height:56px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:26px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="box">

<div class="icon">💸</div>

<h2>Add New Expenditure</h2>

<form method="POST">

    <div class="form-group">
        <label>Expense Title</label>
        <input type="text" name="title" placeholder="e.g. Electricity Bill" required>
    </div>

    <div class="form-group">
        <label>Amount (Rs)</label>
        <input type="number" name="amount" placeholder="Enter amount" required>
    </div>

    <div class="form-group">
        <label>Date</label>
        <input type="date" name="exp_date" value="<?=date('Y-m-d')?>" required>
    </div>

    <div class="form-group">
        <label>Remarks</label>
        <textarea name="remarks" placeholder="Optional notes..."></textarea>
    </div>

    <div class="actions">
        <button name="save">💾 Save Expenditure</button>
        <a href="expenditure_list.php" class="back-btn">⬅ Cancel</a>
    </div>

</form>

</div>

</body>
</html>

