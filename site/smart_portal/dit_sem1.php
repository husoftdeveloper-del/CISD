<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DIT – First Semester</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#eaf2ff,#f8fbff);
    padding:30px;
}

/* ===== Header ===== */
.header{
    max-width:1000px;
    margin:auto;
    padding:35px;
    background:linear-gradient(135deg,#3498db,#1abc9c);
    color:#fff;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,0.18);
    text-align:center;
}

.header h1{
    margin:0;
    font-size:32px;
    letter-spacing:1px;
}

.header p{
    margin-top:8px;
    font-size:16px;
    opacity:0.95;
}

/* ===== Main Card ===== */
.container{
    max-width:1000px;
    margin:30px auto;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 18px 40px rgba(0,0,0,0.12);
}

/* ===== Table ===== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    overflow:hidden;
    border-radius:14px;
}

th{
    background:linear-gradient(to right,#2980b9,#1abc9c);
    color:#fff;
    padding:14px;
    font-size:14px;
    text-transform:uppercase;
}

td{
    padding:14px;
    border-bottom:1px solid #e6e6e6;
    text-align:center;
    font-size:14px;
}

.subject{
    text-align:left;
    font-weight:600;
    color:#2c3e50;
}

tr:hover{
    background:#f1fbff;
}

.total-row{
    background:#ecfdfb;
    font-weight:700;
}

/* ===== Info Cards ===== */
.info-box{
    margin-top:30px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:18px;
}

.info-card{
    background:linear-gradient(135deg,#3498db,#2ecc71);
    color:#fff;
    padding:18px;
    border-radius:16px;
    text-align:center;
    font-weight:600;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    transition:0.3s;
}

.info-card:hover{
    transform:translateY(-5px);
}

/* ===== Buttons ===== */
.actions{
    margin-top:35px;
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:15px;
}

.btn{
    text-decoration:none;
    padding:14px 30px;
    border-radius:40px;
    font-weight:600;
    color:#fff;
    display:inline-flex;
    align-items:center;
    gap:8px;
    transition:0.35s;
    box-shadow:0 10px 25px rgba(0,0,0,0.18);
}

.btn:hover{
    transform:translateY(-3px);
    box-shadow:0 16px 35px rgba(0,0,0,0.25);
}

.btn-admission{
    background:linear-gradient(to right,#1abc9c,#16a085);
}

.btn-fee{
    background:linear-gradient(to right,#9b59b6,#8e44ad);
}

.btn-dashboard{
    background:linear-gradient(to right,#e67e22,#e74c3c);
}

/* ===== Print ===== */
@media print{
    body{background:#fff;padding:0;}
    .actions{display:none;}
    .header{box-shadow:none;}
    .container{box-shadow:none;border:1px solid #000;}
}
</style>
</head>

<body>
<?php portal_chrome_bar(); ?>


<div class="header">
    <h1>DIT – First Semester</h1>
    <p>Diploma in Information Technology</p>
</div>

<div class="container">

<table>
<tr>
    <th>S.No</th>
    <th>Subject</th>
    <th>Theory Hours</th>
    <th>Practical Hours</th>
    <th>Theory Marks</th>
    <th>Practical Marks</th>
</tr>

<tr>
    <td>1</td>
    <td class="subject">Introduction to Information & Communication Technologies (ICT)</td>
    <td>40</td>
    <td>80</td>
    <td>75</td>
    <td>25</td>
</tr>

<tr>
    <td>2</td>
    <td class="subject">Introduction to MS Office</td>
    <td>40</td>
    <td>80</td>
    <td>75</td>
    <td>25</td>
</tr>

<tr>
    <td>3</td>
    <td class="subject">Computer Networks</td>
    <td>40</td>
    <td>80</td>
    <td>75</td>
    <td>25</td>
</tr>

<tr>
    <td>4</td>
    <td class="subject">Operating Systems</td>
    <td>40</td>
    <td>80</td>
    <td>75</td>
    <td>25</td>
</tr>

<tr>
    <td>5</td>
    <td class="subject">Introduction to Programming</td>
    <td>40</td>
    <td>80</td>
    <td>75</td>
    <td>25</td>
</tr>

<tr class="total-row">
    <td colspan="4">TOTAL MARKS</td>
    <td>375</td>
    <td>125</td>
</tr>

<tr class="total-row">
    <td colspan="5">GRAND TOTAL</td>
    <td>500</td>
</tr>
</table>

<div class="info-box">
    <div class="info-card">⏱ Duration<br>6 Months</div>

    <div class="info-card">
        📝 Admission Fee<br>
        Rs. 3,000
    </div>

    <div class="info-card">
        💼 Semester Fee<br>
        Rs. 9,000
    </div>

    <div class="info-card">
        🆔 Registration Fee<br>
        Rs. 4,200
    </div>

    <div class="info-card">
        🧾 Examination Fee<br>
        Rs. 4,500
    </div>

    <!-- 🔥 NEW TOTAL CARD -->
    <div class="info-card" style="
        background:linear-gradient(135deg,#e67e22,#e74c3c);
        font-size:18px;
        letter-spacing:0.5px;
    ">
        💰 Total Fee<br>
        <strong>Rs. 20,700</strong>
    </div>
</div>

<div class="actions">
    <a href="admission.php" class="btn btn-admission">🧑‍🎓 New Admission</a>
    <a href="fee_receipt.php" class="btn btn-fee">💳 Fee Receipt</a>
    <a href="dashboard.php" class="btn btn-dashboard">⬅ Dashboard</a>
</div>

</div>

</body>
</html>
