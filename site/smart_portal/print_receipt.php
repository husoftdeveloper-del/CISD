<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
<div class="print-area">

    <div class="print-logo">
        <img src="logo.png" alt="CISD Logo">
    </div>

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
            <td>Exam Fee 1</td>
            <td><?= $_POST['examination_fee_1'] ?? '' ?></td>
        </tr>
        <tr>
            <td>Exam Fee 2</td>
            <td><?= $_POST['examination_fee_2'] ?? '' ?></td>
            <td>Exam Fee 3</td>
            <td><?= $_POST['examination_fee_3'] ?? '' ?></td>
        </tr>

        <tr>
            <th colspan="4">Payment Summary</th>
        </tr>
        <tr>
            <td>Previous Dues</td>
            <td><?= $_POST['previous_dues'] ?? '' ?></td>
            <td>Discount</td>
            <td><?= $_POST['discount'] ?? '' ?></td>
        </tr>
        <tr>
            <td><b>Received Amount</b></td>
            <td colspan="3"><b><?= $_POST['received_amount'] ?? '' ?></b></td>
        </tr>
        <tr>
            <td>Date</td>
            <td colspan="3"><?= $_POST['receipt_date'] ?? date('Y-m-d') ?></td>
        </tr>
    </table>

    <div class="sign-area">
        <div>Student Signature</div>
        <div>Authorized Signature</div>
    </div>

</div>
<style>
    .print-area{
    display:none;
}

/* ===== A4 FULL PAGE PRINT ===== */
@page{
    size: A4;
    margin: 12mm;
}

@media print{

    body{
        background:#fff;
        font-family: "Segoe UI", Arial;
        font-size:14px;
    }

    /* hide form & buttons */
    form,
    .btn-group{
        display:none !important;
    }

    .print-area{
        display:block;
        width:100%;
    }

    h2{
        text-align:center;
        font-size:22px;
        margin-bottom:18px;
        letter-spacing:1px;
        text-transform:uppercase;
    }

    .print-logo{
        text-align:center;
        margin-bottom:10px;
    }

    .print-logo img{
        max-width:160px;
        height:auto;
        display:inline-block;
    }

    .print-table{
        width:100%;
        border-collapse:collapse;
        font-size:14px;
    }

    .print-table th{
        background:#f2f2f2;
        font-weight:700;
        text-align:left;
        padding:10px;
        border:2px solid #000;
    }

    .print-table td{
        padding:10px;
        border:1.5px solid #000;
    }

    .sign-area{
        margin-top:50px;
        display:flex;
        justify-content:space-between;
        font-weight:600;
    }

    .sign-area div{
        width:40%;
        text-align:center;
        border-top:2px solid #000;
        padding-top:6px;
    }
}

</style>
