<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
session_start();

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM admissions WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$admission = $stmt->get_result()->fetch_assoc();

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $registration_no = $_POST['registration_no'];
    $name           = $_POST['name'];
    $father_name    = $_POST['father_name'];
    $cnic           = $_POST['cnic'];
    $dob            = $_POST['dob'] ?: null;
    $gender         = $_POST['gender'];   // ✅ GENDER
    $email          = $_POST['email'];
    $domicile       = $_POST['domicile'];
    $address        = $_POST['address'];
    $phone          = $_POST['phone'];
    $course         = $_POST['course'];

    $photoPath = $admission['photo'];
    if(!empty($_FILES['photo']['name'])){
        if(!is_dir("uploads")) mkdir("uploads",0777,true);
        $photoPath = "uploads/".time()."_".$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'],$photoPath);
    }

    // ✅ UPDATE WITH GENDER
    $up = $conn->prepare("
        UPDATE admissions SET
        registration_no=?,
        name=?,
        father_name=?,
        cnic=?,
        dob=?,
        gender=?,
        email=?,
        domicile=?,
        address=?,
        phone=?,
        course=?,
        photo=?
        WHERE id=?
    ");

    $up->bind_param(
        "ssssssssssssi",
        $registration_no,
        $name,
        $father_name,
        $cnic,
        $dob,
        $gender,
        $email,
        $domicile,
        $address,
        $phone,
        $course,
        $photoPath,
        $id
    );
    $up->execute();

    echo "<script>alert('Admission Updated Successfully');location='admission_list.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Admission</title>

<style>
*{box-sizing:border-box;font-family:'Poppins',sans-serif}
body{
    background:linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
    padding:40px;
}
.card{
    max-width:900px;
    margin:auto;
    background:rgba(255,255,255,0.97);
    border-radius:18px;
    box-shadow:0 25px 60px rgba(0,0,0,0.25);
    padding:40px;
}
.header{text-align:center;margin-bottom:30px}
.header h2{font-size:28px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
label{font-weight:600;margin-bottom:6px;display:block}
input,select{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
}
.full{grid-column:1/3}
.photo-preview img{
    width:110px;border-radius:12px;margin-top:10px;border:2px solid #eee;
}
.actions{text-align:center;margin-top:30px}
button{
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;border:none;padding:14px 40px;
    font-size:16px;border-radius:30px;cursor:pointer;
}
.cancel{background:#e74c3c;margin-left:15px}
@media(max-width:768px){
    .grid{grid-template-columns:1fr}
    .full{grid-column:1}
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="card">
<div class="header">
    <h2>✏️ Edit Student Admission</h2>
    <p>Update student personal & academic details</p>
</div>

<form method="POST" enctype="multipart/form-data">
<div class="grid">

<div>
<label>Registration No</label>
<input type="text" name="registration_no" value="<?=htmlspecialchars($admission['registration_no'])?>" required>
</div>

<div>
<label>Course</label>
<select name="course">
    <option value="">Select Course</option>

    <!-- Existing courses -->
    <option value="DIT" <?=($admission['course']==="DIT")?'selected':''?>>DIT</option>
    <option value="MS OFFICE" <?=($admission['course']==="MS OFFICE")?'selected':''?>>MS OFFICE</option>
    <option value="TYPING" <?=($admission['course']==="TYPING")?'selected':''?>>TYPING</option>
    <option value="ENGLISH LANGUAGE" <?=($admission['course']==="ENGLISH LANGUAGE")?'selected':''?>>ENGLISH LANGUAGE</option>
    <option value="SHORT HAND" <?=($admission['course']==="SHORT HAND")?'selected':''?>>SHORT HAND</option>

    <!-- ✅ New courses -->
    <option value="WEB DEVELOPMENT" <?=($admission['course']==="WEB DEVELOPMENT")?'selected':''?>>WEB DEVELOPMENT</option>
    <option value="APP DEVELOPMENT" <?=($admission['course']==="APP DEVELOPMENT")?'selected':''?>>APP DEVELOPMENT</option>
    <option value="AI & PYTHON" <?=($admission['course']==="AI & PYTHON")?'selected':''?>>AI & PYTHON</option>
    <option value="GRAPHIC DESIGNING" <?=($admission['course']==="GRAPHIC DESIGNING")?'selected':''?>>GRAPHIC DESIGNING</option>
    <option value="YOUTUBE AUTOMATION" <?=($admission['course']==="YOUTUBE AUTOMATION")?'selected':''?>>YOUTUBE AUTOMATION</option>
    <option value="DIGITAL MARKETING" <?=($admission['course']==="DIGITAL MARKETING")?'selected':''?>>DIGITAL MARKETING</option>
    <option value="BASIC COMPUTER SKILLS" <?=($admission['course']==="BASIC COMPUTER SKILLS")?'selected':''?>>BASIC COMPUTER SKILLS</option>
</select>
</div>


<div>
<label>Student Name</label>
<input type="text" name="name" value="<?=htmlspecialchars($admission['name'])?>">
</div>

<div>
<label>Father Name</label>
<input type="text" name="father_name" value="<?=htmlspecialchars($admission['father_name'])?>">
</div>

<div>
<label>CNIC</label>
<input type="text" name="cnic" value="<?=htmlspecialchars($admission['cnic'])?>">
</div>

<div>
<label>Date of Birth</label>
<input type="date" name="dob" value="<?=htmlspecialchars($admission['dob'])?>">
</div>

<!-- ✅ GENDER FIELD -->
<div>
<label>Gender</label>
<select name="gender" required>
    <option value="">Select Gender</option>
    <option value="Male"   <?=($admission['gender']=="Male")?'selected':''?>>Male</option>
    <option value="Female" <?=($admission['gender']=="Female")?'selected':''?>>Female</option>
    <option value="Other"  <?=($admission['gender']=="Other")?'selected':''?>>Other</option>
</select>
</div>

<div>
<label>Phone</label>
<input type="text" name="phone" value="<?=htmlspecialchars($admission['phone'])?>">
</div>

<div>
<label>Email</label>
<input type="text" name="email" value="<?=htmlspecialchars($admission['email'])?>">
</div>

<div>
<label>Domicile</label>
<input type="text" name="domicile" value="<?=htmlspecialchars($admission['domicile'])?>">
</div>

<div class="full">
<label>Address</label>
<input type="text" name="address" value="<?=htmlspecialchars($admission['address'])?>">
</div>

<div class="full">
<label>Upload Photo</label>
<input type="file" name="photo">
<div class="photo-preview">
<?php if($admission['photo']): ?>
<img src="<?=$admission['photo']?>">
<?php endif; ?>
</div>
</div>

</div>

<div class="actions">
<button type="submit">💾 Update Record</button>
<a href="admission_list.php">
<button type="button" class="cancel">✖ Cancel</button>
</a>
</div>

</form>
</div>

</body>
</html>

