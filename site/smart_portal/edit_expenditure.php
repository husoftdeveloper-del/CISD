<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
/* ===== GET ID ===== */
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: expenditure_list.php");
    exit;
}

/* ===== UPDATE RECORD ===== */
if (isset($_POST['update'])) {

    $id       = (int)$_POST['id'];
    $title    = $conn->real_escape_string($_POST['title']);
    $amount   = (float)$_POST['amount'];
    $exp_date = $_POST['exp_date'];
    $remarks  = $conn->real_escape_string($_POST['remarks']);

    $update = "
        UPDATE expenditures SET
            title='$title',
            amount='$amount',
            exp_date='$exp_date',
            remarks='$remarks'
        WHERE id=$id
    ";

    if ($conn->query($update)) {
        header("Location: expenditure_list.php?updated=1");
        exit;
    } else {
        $error = "Update failed!";
    }
}

/* ===== FETCH EXISTING DATA ===== */
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];
$res = $conn->query("SELECT * FROM expenditures WHERE id=$id");
$row = $res->fetch_assoc();

if (!$row) {
    header("Location: expenditure_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Expenditure</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:#f4f7fb;
}
.container{
    max-width:650px;
    margin:50px auto;
    background:#fff;
    padding:35px;
    border-radius:18px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
    animation:fade .6s ease;
}
@keyframes fade{
    from{opacity:0;transform:translateY(20px)}
    to{opacity:1}
}

h2{
    margin:0 0 20px;
    text-align:center;
    color:#2c3e50;
}

label{
    display:block;
    margin-top:14px;
    font-weight:600;
    color:#2c3e50;
}

input, textarea{
    width:100%;
    padding:10px 14px;
    margin-top:6px;
    border-radius:10px;
    border:1px solid #ccc;
    font-family:Poppins;
    font-size:14px;
}

textarea{resize:none;height:90px}

.actions{
    display:flex;
    justify-content:space-between;
    margin-top:25px;
}

.btn{
    padding:10px 24px;
    border-radius:22px;
    font-weight:600;
    border:none;
    cursor:pointer;
    font-family:Poppins;
}

.save{
    background:#1abc9c;
    color:#fff;
}
.save:hover{background:#16a085}

.cancel{
    background:#bdc3c7;
    color:#2c3e50;
    text-decoration:none;
    display:flex;
    align-items:center;
}

.error{
    background:#e74c3c;
    color:#fff;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="container">

<h2>✏ Edit Expenditure</h2>

<?php if(isset($error)): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">

<input type="hidden" name="id" value="<?= $row['id'] ?>">

<label>Title</label>
<input type="text" name="title" required value="<?= htmlspecialchars($row['title']) ?>">

<label>Amount</label>
<input type="number" name="amount" step="0.01" required value="<?= $row['amount'] ?>">

<label>Date</label>
<input type="date" name="exp_date" required value="<?= $row['exp_date'] ?>">

<label>Remarks</label>
<textarea name="remarks"><?= htmlspecialchars($row['remarks']) ?></textarea>

<div class="actions">
    <a href="expenditure_list.php" class="btn cancel">⬅ Cancel</a>
    <button type="submit" name="update" class="btn save">💾 Update</button>
</div>

</form>

</div>

</body>
</html>

