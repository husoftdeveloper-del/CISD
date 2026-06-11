<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$student = null;

if (isset($_GET['student_id'])) {
    $student_id = (int)$_GET['student_id'];

    $stmt = $conn->prepare("
        SELECT id, registration_no, name, course, phone 
        FROM admissions 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

/* ===== SEARCH STUDENT ===== */
$student = null;
if(isset($_GET['search_id'])){
    $id = (int)$_GET['search_id'];
    $q = $conn->prepare("SELECT * FROM admissions WHERE id=?");
    $q->bind_param("i",$id);
    $q->execute();
    $student = $q->get_result()->fetch_assoc();
}

/* ===== SAVE RECEIPT ===== */
if(isset($_POST['save'])){

    $admission_id     = (int)$_POST['admission_id'];

    $monthly_fee      = (int)($_POST['monthly_fee'] ?? 0);
    $admission_fee    = (int)($_POST['admission_fee'] ?? 0);
    $registration_fee = (int)($_POST['registration_fee'] ?? 0);

    $exam1            = (int)($_POST['examination_fee_1'] ?? 0);
    $exam2            = (int)($_POST['examination_fee_2'] ?? 0);
    $exam3            = (int)($_POST['examination_fee_3'] ?? 0);

    $previous_dues    = (int)($_POST['previous_dues'] ?? 0);
    $discount         = (int)($_POST['discount'] ?? 0);
    $received_amount  = (int)($_POST['received_amount'] ?? 0);
    $receipt_date     = $_POST['receipt_date'];

    if($admission_id == 0){
        die("Admission ID Missing");
    }

    $stmt = $conn->prepare("
        INSERT INTO fee_receipts_v2
        (admission_id, monthly_fee, admission_fee, registration_fee,
         examination_fee_1, examination_fee_2, examination_fee_3,
         previous_dues, discount, received_amount, receipt_date)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "iiiiiiiiiss",
        $admission_id,
        $monthly_fee,
        $admission_fee,
        $registration_fee,
        $exam1,
        $exam2,
        $exam3,
        $previous_dues,
        $discount,
        $received_amount,
        $receipt_date
    );

    $stmt->execute();
    echo "<script>alert('Fee Receipt Saved Successfully');</script>";
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Monthly Fee Receipt</title>
<style>
/* ===== SCREEN VIEW ===== */
body{
    background:#eef2f6;
    font-family:'Segoe UI', Arial;
    margin:0;
    padding:0;
}

.receipt-box{
    width:950px;              /* ⬅ increased page size */
    margin:20px auto;
    background:#fff;
    padding:22px 26px;
    border:2px solid #2c3e50;
}

/* headings */
h2{
    text-align:center;
    font-size:24px;
    margin:0 0 10px;
    letter-spacing:.6px;
}

h3{
    background:#2c3e50;
    color:#fff;
    padding:7px 12px;
    font-size:15px;
    margin:18px 0 10px;
}

/* grids */
.grid-4{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
}

.grid-3{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:14px;
}

/* form */
.form-group{
    display:flex;
    flex-direction:column;
}

label{
    font-size:14px;
    font-weight:600;
    margin-bottom:4px;
    color:#2c3e50;
}

input,select{
    padding:9px 11px;
    font-size:14px;
    border:1px solid #bfc7d1;
}

input[readonly]{
    background:#f2f4f7;
    font-weight:600;
}

/* table */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    font-size:14px;
}

table td{
    border:1px solid #ccc;
    padding:9px 10px;
}

table td:first-child{
    font-weight:600;
    background:#f6f7f9;
    width:40%;
}

/* buttons */
.btn{
    padding:10px 26px;
    font-size:14px;
    border:none;
    color:#fff;
    cursor:pointer;
    border-radius:20px;
}

.save{background:#2c3e50}
.print{background:#27ae60}
.list{background:#1abc9c}

.btn-group{
    text-align:center;
    margin-top:16px;
}

/* ===== PRINT VIEW (FULL PAGE) ===== */
@page{
    size:A4;
    margin:10mm;
}

@media print{
    body{
        background:#fff;
    }

    .receipt-box{
        width:100%;
        margin:0;
        padding:18px;
        border:2px solid #000;
    }

    h2{
        font-size:22px;
        margin-bottom:8px;
    }

    h3{
        background:#fff;
        color:#000;
        border-bottom:1px solid #000;
        padding:5px 0;
        margin:14px 0 8px;
    }

    label{
        font-size:13px;
    }

    input{
        border:1px solid #000;
    }

    .btn-group,
    form:first-of-type{
        display:none; /* hide buttons & search */
    }
}
@page{
    size:A4;
    margin:12mm;
}

@media print{

    body{
        background:#fff;
        font-size:13px;
    }

    /* main receipt */
    .receipt-box{
        width:100%;
        border:2px solid #000;
        padding:18px 22px;
        margin:0;
    }

    /* hide search + buttons */
    form:first-of-type,
    .btn-group{
        display:none !important;
    }

    /* header */
    h2{
        font-size:20px;
        text-align:center;
        margin:0;
        padding-bottom:6px;
        border-bottom:2px solid #000;
    }

    h3{
        background:none;
        color:#000;
        font-size:14px;
        padding:4px 0;
        margin:16px 0 6px;
        border-bottom:1px solid #000;
    }

    /* labels */
    label{
        font-size:12px;
        font-weight:600;
        color:#000;
    }

    /* INPUTS LOOK LIKE TEXT */
    input{
        border:none !important;
        border-bottom:1px solid #000 !important;
        padding:2px 4px;
        font-size:13px;
        background:none;
    }

    input[readonly]{
        background:none;
        font-weight:600;
    }

    /* grid spacing tighter */
    .grid-4{
        gap:10px;
    }

    table{
        font-size:13px;
    }

    table td{
        border:none;
        border-bottom:1px solid #000;
        padding:6px 4px;
    }

    table td:first-child{
        font-weight:600;
        width:35%;
    }
}

.dashboard{
    background:#34495e;
}
.dashboard:hover{
    background:#2c3e50;
}
/* ===== PRINT ONLY TABLE ===== */
.print-area{
    display:none;
}

.print-table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

.print-table th,
.print-table td{
    border:1px solid #000;
    padding:8px;
}

.print-table th{
    background:#f2f2f2;
    text-align:left;
}

@media print{

    body{
        background:#fff;
    }

    /* hide everything */
    form,
    .btn-group{
        display:none !important;
    }

    /* show print table */
    .print-area{
        display:block;
    }

    h2{
        text-align:center;
        margin-bottom:12px;
    }
}


</style>



</head>

<body>
<?php portal_chrome_bar(); ?>


<!-- ================= SEARCH FORM ================= -->
 <div style="width:950px;margin:20px auto;text-align:right;">
    <a href="dashboard.php">
        <button type="button" class="btn dashboard">⬅ Dashboard</button>
    </a>
</div>

<form method="GET" class="receipt-box">

<h2>MONTHLY FEE RECEIPT</h2>
<h3>Search Student</h3>

<div class="grid-3">
<input type="number" id="search_id" name="search_id"
       placeholder="Enter Admission ID"
       value="<?= $_GET['search_id'] ?? '' ?>">

    <button type="submit" class="btn save">Search</button>
</div>
</form>
<!-- ================= PRINT VIEW ================= -->
<div class="print-area">

    <h2>MONTHLY FEE RECEIPT</h2>

    <table class="print-table">
        <tr>
            <th colspan="4">Student Information</th>
        </tr>
        <tr>
            <td><b>Name</b></td>
            <td><?= $student['name'] ?? '' ?></td>
            <td><b>Father Name</b></td>
            <td><?= $student['father_name'] ?? '' ?></td>
        </tr>
        <tr>
            <td><b>Course</b></td>
            <td><?= $student['course'] ?? '' ?></td>
            <td><b>Gender</b></td>
            <td><?= $student['gender'] ?? '' ?></td>
        </tr>

        <tr>
            <th colspan="4">Fee Details</th>
        </tr>
        <tr>
            <td>Admission Fee</td>
            <td><?= $_POST['admission_fee'] ?? '' ?></td>
            <td>Monthly Fee</td>
            <td><?= $_POST['monthly_fee'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Registration Fee</td>
            <td><?= $_POST['registration_fee'] ?? '' ?></td>
            <td>Previous Dues</td>
            <td><?= $_POST['previous_dues'] ?? '' ?></td>
        </tr>

        <tr>
            <th colspan="4">Payment</th>
        </tr>
        <tr>
            <td>Discount</td>
            <td><?= $_POST['discount'] ?? '' ?></td>
            <td>Received</td>
            <td><?= $_POST['received_amount'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td colspan="3"><?= date('d M Y') ?></td>
        </tr>
    </table>

</div>


<!-- ================= RECEIPT FORM ================= -->
<form method="POST" class="receipt-box">

<h3>Student Information</h3>

<input type="hidden" name="admission_id" value="<?= $student['id'] ?>">

<div class="grid-4">
    <div class="form-group">
        <label>Student Name</label>
        <input value="<?= $student['name'] ?? '' ?>" readonly>
    </div>

    <div class="form-group">
        <label>Father Name</label>
        <input value="<?= $student['father_name'] ?? '' ?>" readonly>
    </div>

    <div class="form-group">
        <label>Course</label>
        <input value="<?= $student['course'] ?? '' ?>" readonly>
    </div>

    <div class="form-group">
        <label>Gender</label>
        <input value="<?= $student['gender'] ?? '' ?>" readonly>
    </div>
</div>


<h3>Academic Fee Details</h3>
<div class="grid-4">
    <div class="form-group">
        <label>Admission Fee</label>
        <input type="number" name="admission_fee">
    </div>

    <div class="form-group">
        <label>Monthly Fee</label>
        <input type="number" name="monthly_fee" id="monthly_fee">
    </div>

    <div class="form-group">
        <label>Registration Fee</label>
        <input type="number" name="registration_fee">
    </div>

    <div class="form-group">
        <label>Examination Fee 1</label>
        <input type="number" name="examination_fee_1">
    </div>

    <div class="form-group">
        <label>Examination Fee 2</label>
        <input type="number" name="examination_fee_2">
    </div>

    <div class="form-group">
        <label>Examination Fee 3</label>
        <input type="number" name="examination_fee_3">
    </div>
</div>


<h3>Payment Details</h3>
<table>
<tr>
    <td>Previous Dues</td>
    <td><input type="number" name="previous_dues" id="previous_dues" readonly></td>
</tr>
<tr>
    <td>Discount</td>
    <td><input type="number" name="discount"></td>
</tr>
<tr>
    <td>Received Amount</td>
    <td><input type="number" name="received_amount" ></td>
</tr>
<tr>
    <td>Date</td>
    <td><input type="date" name="receipt_date" value="<?= date('Y-m-d') ?>"></td>
</tr>
</table>

<div class="btn-group">
    <button class="btn save" name="save">Save Receipt</button>
    <a href="fee_receipt_list.php"><button type="button" class="btn list">Receipt List</button></a>
    <button type="button" class="btn print" onclick="window.print()">
    🖨 Print Receipt
</button>

</div>

</form>

</body>
</html>
<script>
function loadFeeData(id){
    if(!id) return;

    fetch("fetch_student.php?id="+id)
    .then(r=>r.json())
    .then(d=>{
        if(d.status==="ok"){
            document.getElementById("monthly_fee").value = d.monthly_fee;
            document.getElementById("previous_dues").value = d.previous_dues;
        }
    });
}

window.addEventListener("DOMContentLoaded",()=>{
    const s = document.getElementById("search_id");
    if(s && s.value) loadFeeData(s.value);
});
</script>

