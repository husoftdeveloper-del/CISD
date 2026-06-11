<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

if(isset($_POST['save'])){
    $name        = $_POST['name'];
    $designation = $_POST['designation'];
    $phone       = $_POST['phone'];
    $joining     = $_POST['joining_date'];

    $stmt = $conn->prepare("
        INSERT INTO teachers (name,designation,phone,joining_date)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param("ssss",$name,$designation,$phone,$joining);
    $stmt->execute();

    header("Location: teacher_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Teacher</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    min-height:100vh;
    font-family:Poppins;
    background:linear-gradient(135deg,#eef2f7,#f9fbff);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* Card */
.box{
    width:520px;
    background:#fff;
    padding:35px;
    border-radius:22px;
    box-shadow:0 18px 45px rgba(0,0,0,.15);
    animation:fadeUp .6s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(25px)}
    to{opacity:1}
}

/* Header */
.header{
    text-align:center;
    margin-bottom:25px;
}
.header i{
    font-size:42px;
    color:#1abc9c;
    margin-bottom:8px;
}
.header h2{
    margin:0;
    color:#2c3e50;
    font-weight:700;
}
.header p{
    margin-top:4px;
    font-size:14px;
    color:#7f8c8d;
}

/* Form */
.form-group{
    margin-bottom:16px;
}
label{
    display:block;
    font-weight:600;
    font-size:14px;
    color:#34495e;
    margin-bottom:6px;
}
.input-wrap{
    position:relative;
}
.input-wrap i{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    font-size:15px;
    color:#7f8c8d;
}

.input-wrap input{
    width:90%;
    height:46px;
    padding:0 14px 0 42px;
    border-radius:12px;
    border:1px solid #dcdde1;
    font-size:14px;
    line-height:46px;
}

.input-wrap input:focus{
    outline:none;
    border-color:#1abc9c;
    box-shadow:0 0 0 4px rgba(26,188,156,.15);
}

/* Button */
button{
    width:100%;
    margin-top:18px;
    height:48px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#1abc9c,#3498db);
    color:#fff;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:.35s;
}
button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(26,188,156,.45);
}

/* Back */
.back{
    display:block;
    text-align:center;
    margin-top:18px;
    color:#34495e;
    font-weight:600;
    text-decoration:none;
}
.back:hover{color:#1abc9c}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="box">

    <div class="header">
        <i class="fas fa-user-tie"></i>
        <h2>Add Teacher</h2>
        <p>Enter teacher professional details</p>
    </div>

    <form method="POST">

        <div class="form-group">
            <label>Teacher Name</label>
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
        </div>

        <div class="form-group">
            <label>Designation</label>
            <div class="input-wrap">
                <i class="fas fa-briefcase"></i>
                <input type="text" name="designation" placeholder="e.g. English Teacher">
            </div>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <div class="input-wrap">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" placeholder="03xx-xxxxxxx">
            </div>
        </div>

        <div class="form-group">
            <label>Joining Date</label>
            <div class="input-wrap">
                <i class="fas fa-calendar"></i>
                <input type="date" name="joining_date" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <button name="save">💾 Save Teacher</button>

        <a href="teacher_list.php" class="back">⬅ Back to Teacher List</a>
    </form>

</div>

</body>
</html>

