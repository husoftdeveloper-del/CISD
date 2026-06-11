<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$res = $conn->query("SHOW TABLE STATUS LIKE 'admissions'");
$row = $res->fetch_assoc();
$next_id = $row['Auto_increment'];

$year = date('Y');
$q = $conn->query("SELECT registration_no FROM admissions 
                   WHERE registration_no LIKE 'CISD-$year-%' 
                   ORDER BY id DESC LIMIT 1");

$newNum = ($q && $q->num_rows) ? ((int)substr($q->fetch_assoc()['registration_no'], -3) + 1) : 1;
$defaultReg = "CISD-$year-" . str_pad($newNum, 3, "0", STR_PAD_LEFT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $registration_no = $_POST['registration_no'];
    $name        = $_POST['name'];
    $father_name = $_POST['father_name'];
    $cnic        = $_POST['cnic'];
    $dob         = $_POST['dob'];
    $email       = $_POST['email'];
    $domicile    = $_POST['domicile'];
    $address     = $_POST['address'];
    $gender      = $_POST['gender'];
    $phone       = $_POST['phone'];
    $course      = $_POST['course'];

    $conn->query("INSERT INTO admissions
    (registration_no,name,father_name,cnic,dob,email,domicile,address,gender,course,phone)
    VALUES
    ('$registration_no','$name','$father_name','$cnic','$dob','$email','$domicile','$address','$gender','$course','$phone')");

    echo "<script>alert('Admission Submitted Successfully');location='admission_list.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Admission</title>

<style>
body{
    margin:0;
    min-height:100vh;
    background:linear-gradient(135deg,#667eea,#764ba2);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
}

.outer-frame{
    padding:4px;
    border-radius:22px;
    background:linear-gradient(270deg,#6a11cb,#2575fc,#00d2ff,#6a11cb);
    background-size:600% 600%;
    animation:borderMove 6s ease infinite;
    box-shadow:0 25px 60px rgba(0,0,0,.45);
}
@keyframes borderMove{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

.form-wrapper{
    background:#fff;
    padding:40px 35px;
    border-radius:18px;
    width:600px;
}

h2{text-align:center;margin-bottom:25px}

/* 🔴 REQUIRED FIELD STYLE */
.field{
    position:relative;
}
.field.required::after{
    content:"*";
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    color:red;
    font-size:18px;
    font-weight:bold;
}

input,select,textarea{
    width:100%;
    padding:13px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
    box-sizing:border-box;
}
textarea{height:80px;resize:none}
input[readonly]{background:#f2f2f2;font-weight:bold}

.btn{
    margin-top:15px;
    width:100%;
    padding:15px;
    border:none;
    border-radius:30px;
    font-size:17px;
    font-weight:bold;
    color:#fff;
    cursor:pointer;
    background:linear-gradient(135deg,#2575fc,#6a11cb);
}
.btn:hover{box-shadow:0 10px 25px rgba(0,0,0,.35)}

.link-btn{
    display:block;
    text-align:center;
    margin-top:16px;
    padding:12px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
}
.list-btn{color:#2575fc}
.dashboard-btn{
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="outer-frame">
<div class="form-wrapper">

<h2>🎓 Student Admission Form</h2>

<form method="POST">

<div class="field required">
<input type="text" name="registration_no" value="<?=htmlspecialchars($defaultReg)?>" required>
</div>

<div class="field">
<input type="text" value="STU-<?=$next_id?>" readonly>
</div>

<div class="field required">
<input type="text" name="name" placeholder="Student Name" required>
</div>

<div class="field required">
<input type="text" name="father_name" placeholder="Father Name" required>
</div>

<div class="field required">
    <input type="text"
           name="cnic"
           id="cnic"
           placeholder="35202-1234567-1"
           maxlength="15"
           required>
</div>


<div class="field required">
<input type="date" name="dob" required>
</div>

<!-- ❌ Email OPTIONAL (NO STAR) -->
<div class="field">
<input type="email" name="email" placeholder="Email (optional)">
</div>

<div class="field required">
<input type="text" name="domicile" placeholder="Domicile" required>
</div>

<div class="field required">
<textarea name="address" placeholder="Full Address" required></textarea>
</div>

<div class="field required">
<select name="gender" required>
    <option value="">Select Gender</option>
    <option>Male</option>
    <option>Female</option>
</select>
</div>
<div class="field required">
    <input type="text"
           name="phone"
           id="phone"
           placeholder="0301-2345678"
           maxlength="12"
           required>
</div>


<div class="field required">
<select name="course" required>
    <option value="">Select Course</option>

    <!-- Existing courses -->
    <option>DIT</option>
    <option>MS OFFICE</option>
    <option>TYPING</option>
    <option>ENGLISH LANGUAGE</option>
    <option>SHORT HAND</option>

    <!-- ✅ New courses -->
    <option>WEB DEVELOPMENT</option>
    <option>APP DEVELOPMENT</option>
    <option>AI & PYTHON</option>
    <option>GRAPHIC DESIGNING</option>
    <option>YOUTUBE AUTOMATION</option>
    <option>DIGITAL MARKETING</option>
    <option>BASIC COMPUTER SKILLS</option>
</select>
</div>


<button class="btn">Submit Admission</button>

</form>

<a href="admission_list.php" class="link-btn list-btn">← Admission List</a>
<a href="dashboard.php" class="link-btn dashboard-btn">🏠 Dashboard</a>

</div>
</div>

<?php include "session_timer.php"; ?>
<script>
// ===== CNIC AUTO FORMAT =====
document.getElementById("cnic").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, ''); // only digits

    if (value.length > 5)
        value = value.slice(0,5) + '-' + value.slice(5);

    if (value.length > 13)
        value = value.slice(0,13) + '-' + value.slice(13,14);

    this.value = value;
});

// ===== PHONE AUTO FORMAT =====
document.getElementById("phone").addEventListener("input", function () {
    let value = this.value.replace(/\D/g, ''); // only digits

    // must start with 03
    if (!value.startsWith("03")) {
        value = "03";
    }

    // add dash after 4 digits → 03xx-
    if (value.length > 4) {
        value = value.slice(0,4) + '-' + value.slice(4,11);
    }

    this.value = value;
});
</script>

</body>
</html>

