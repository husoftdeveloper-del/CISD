<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
    echo json_encode(["status"=>"error"]);
    exit;
}

$id     = (int)($_GET['id'] ?? 0);
$course = $_GET['course'] ?? '';

/* ===============================
   ENGLISH LANGUAGE COURSE
   =============================== */
if($course === "ENGLISH LANGUAGE"){

    $q = $conn->prepare("
        SELECT 
            paid_fee,
            remaining_fee
        FROM english_language_students
        WHERE admission_id=?
    ");
    $q->bind_param("i",$id);
    $q->execute();
    $r = $q->get_result()->fetch_assoc();

    echo json_encode([
        "status"=>"ok",
        "monthly_fee"=>0,
        "previous_dues"=>$r['remaining_fee'] ?? 0
    ]);
    exit;
}

/* ===============================
   ALL OTHER COURSES (DIT etc)
   =============================== */

/* LAST MONTHLY FEE */
$m = $conn->prepare("
    SELECT monthly_fee
    FROM fee_receipts_v2
    WHERE admission_id=?
    ORDER BY receipt_date DESC
    LIMIT 1
");
$m->bind_param("i",$id);
$m->execute();
$monthly = $m->get_result()->fetch_assoc()['monthly_fee'] ?? 0;

/* TOTAL FEE */
$f = $conn->prepare("
    SELECT IFNULL(SUM(
        monthly_fee + admission_fee + registration_fee +
        examination_fee_1 + examination_fee_2 + examination_fee_3
    ),0) AS total_fee
    FROM fee_receipts_v2
    WHERE admission_id=?
");
$f->bind_param("i",$id);
$f->execute();
$total_fee = $f->get_result()->fetch_assoc()['total_fee'];

/* TOTAL PAID */
$p = $conn->prepare("
    SELECT IFNULL(SUM(received_amount),0) AS total_paid
    FROM fee_receipts_v2
    WHERE admission_id=?
");
$p->bind_param("i",$id);
$p->execute();
$total_paid = $p->get_result()->fetch_assoc()['total_paid'];

echo json_encode([
    "status"=>"ok",
    "monthly_fee"=>$monthly,
    "previous_dues"=>($total_fee - $total_paid)
]);

