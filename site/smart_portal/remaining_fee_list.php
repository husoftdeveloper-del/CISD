<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

$courseTables = [
    "English Language" => "english_language_students",
    "MS Office"        => "ms_office_students",
    "Typing"           => "typing_students",
    "Short Hand"       => "short_hand_students",
    "DIT"              => "dit_students"
];


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle deletion with password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_month'])) {
    $month = $_POST['month_select'];
    $enteredPassword = $_POST['admin_password'];

    if (!empty($month) && !empty($enteredPassword)) {
        // Check password from DB
        $adminUser = $_SESSION['username'];
        $checkStmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
        $checkStmt->bind_param("s", $adminUser);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $adminData = $checkResult->fetch_assoc();

        if ($adminData && $enteredPassword === $adminData['password']) {
            $start = $month . "-01";
            $end = date("Y-m-t", strtotime($start));

            $deleteStmt = $conn->prepare("
                DELETE FROM admissions
                WHERE remaining_date BETWEEN ? AND ?
            ");
            $deleteStmt->bind_param("ss", $start, $end);
            $deleteStmt->execute();

            echo "<script>alert('✅ Records from $month deleted successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit;
        } else {
            echo "<script>alert('❌ Incorrect admin password. Deletion cancelled.');</script>";
        }
    }
}

// Query Records
// Query Records
if ($search !== '') {

    $searchTerm = "%$search%";

    $stmt = $conn->prepare("
        SELECT 
            f.*, 
            a.name AS student_name
        FROM fee_receipts_v2 f
        INNER JOIN (
            SELECT admission_id, MAX(id) AS max_id
            FROM fee_receipts_v2
            GROUP BY admission_id
        ) x ON f.id = x.max_id
        INNER JOIN admissions a 
            ON a.id = f.admission_id
        WHERE 
            a.name LIKE ? 
            OR f.admission_id LIKE ?
        ORDER BY f.receipt_date DESC
        LIMIT ?, ?
    ");

    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    /* ===== TOTAL ROWS FOR PAGINATION ===== */
    $countStmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM fee_receipts_v2 f
        INNER JOIN admissions a 
            ON a.id = f.admission_id
        WHERE 
            a.name LIKE ? 
            OR f.admission_id LIKE ?
    ");
    $countStmt->bind_param("ss", $searchTerm, $searchTerm);
    $countStmt->execute();

    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];

}
    

else {

// MAIN QUERY (data)
$stmt = $conn->prepare("
    SELECT 
        f.*,
        a.name AS student_name
    FROM fee_receipts_v2 f
    INNER JOIN (
        SELECT admission_id, MAX(id) AS max_id
        FROM fee_receipts_v2
        GROUP BY admission_id
    ) x ON f.id = x.max_id
    INNER JOIN admissions a 
        ON a.id = f.admission_id
    WHERE f.remaining_amount > 0
    ORDER BY f.receipt_date DESC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
}

// TOTAL ROWS QUERY (pagination ke liye)
$countStmt = $conn->prepare("
    SELECT COUNT(DISTINCT f.admission_id) AS total
    FROM fee_receipts_v2 f
    WHERE f.remaining_amount > 0
");
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];

// NOW this is safe
$stmt->execute();
$result = $stmt->get_result();
$totalPages = ceil($totalRows / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remaining Fee List</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            padding: 40px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        a.back {
            padding: 10px 20px;
            background: linear-gradient(to right, #1abc9c, #3498db, #9b59b6);
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
        }

        form.search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        input[type="text"],
        input[type="month"],
        input[type="password"] {
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 18px;
            font-size: 16px;
            background: linear-gradient(to right, #1abc9c, #3498db, #9b59b6);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s ease;
        }

        button.delete-btn {
            background: linear-gradient(to right, #e74c3c, #c0392b);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin: 30px 0;
            font-size: 2rem;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        th {
            background: linear-gradient(60deg, #1abc9c, #3498db, #9b59b6);
            color: #fff;
            padding: 14px;
            font-size: .85rem;
            text-align: center;
            text-transform: uppercase;
            border-radius: 12px 12px 0 0;
            font-weight: bold;
        }

        td {
            background: #fff;
            padding: 14px;
            font-size: 1rem;
            color: #34495e;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform .3s, background .3s;
        }

        tr:hover td {
            transform: scale(1.01);
            background: #f4fcff;
        }

        .highlight-cell {
            background: rgb(255, 251, 22) !important;
            color: #000 !important;
            font-weight: 900;
        }

        .due-today {
            background: #e74c3c !important;
            color: #fff !important;
            font-weight: 900;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            color: #fff;
        }

        .edit-btn {
            background: #f39c12;
        }

        .delete-btn-inline {
            background: #e74c3c;
        }

        .pagination {
            text-align: center;
            margin-top: 30px;
        }

        .pagination a {
            display: inline-block;
            margin: 0 6px;
            padding: 8px 16px;
            color: #fff;
            background: #3498db;
            border-radius: 5px;
            text-decoration: none;
            transition: .3s;
        }

        .pagination a:hover {
            background: #1abc9c;
        }

        .pagination .active {
            background: #9b59b6;
            pointer-events: none;
        }

        .receipt-btn {
            background: #3498db;
            padding: 6px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
        }

        td.actions-col,
        th.actions-col {
            width: 150px;
            min-width: 120px;


        }

        .btn {
            padding: 7px 8;
            font-size: 13px;
        }

        body.dark {
            background: #1e1e2f !important;
            color: #f1f1f1;
        }

        body.dark .card,
        body.dark .summary-card,
        body.dark .main-content,
        body.dark .header,
        body.dark .footer {
            background-color: #2e2e3e !important;
            color: #fff !important;
        }

        body.dark .sidebar {
            background: #181818 !important;
        }

        body.dark .sidebar a {
            color: #eee;
        }

        body.dark .sidebar a:hover {
            background: #333;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="top-bar">
        <a class="back" href="dashboard.php">&larr; Back to Dashboard</a>
        <form class="search-form" method="get" action="">
            <input type="text" name="search" placeholder="Search student name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
        <form method="post" class="search-form">
            <input type="month" name="month_select" required>
            <input type="password" name="admin_password" placeholder="Enter Admin Password" required>
            <button type="submit" name="delete_month" class="delete-btn">🗑️ Delete</button>
        </form>
    </div>

    <h2>All Students & Remaining Fee</h2>

    <table>
 <thead>
<tr>
    <th>Admission ID</th>
        <th>Student Name</th> 
    <th>Monthly Fee</th>
    <th>Paid</th>
    <th>Remaining</th>
    <th>Receipt Date</th>
    <th>Status</th>
</tr>
</thead>


      <tbody>
<tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['admission_id'] ?></td>

    <td style="text-align:left;">
        <?= htmlspecialchars($row['student_name']) ?>
    </td>

    <td>Rs. <?= number_format(
        ($row['monthly_fee'] ?? 0) +
        ($row['registration_fee'] ?? 0) +
        ($row['admission_fee'] ?? 0) +
        ($row['examination_fee_1'] ?? 0) +
        ($row['examination_fee_2'] ?? 0) +
        ($row['examination_fee_3'] ?? 0) +
        ($row['previous_dues'] ?? 0)
    ) ?></td>

    <td>Rs. <?= number_format($row['received_amount']) ?></td>

    <td style="color:red;font-weight:bold;">
        Rs. <?= number_format($row['remaining_amount']) ?>
    </td>

    <td><?= date("d M Y", strtotime($row['receipt_date'])) ?></td>

    <td><span style="color:red;font-weight:bold;">DUE</span></td>
</tr>
<?php endwhile; ?>
</tbody>


    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i == $page ? 'active' : '' ?>" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

</body>
<?php include "session_timer.php"; ?>


</html>
<script>
    const darkMode = localStorage.getItem("dark-mode");
    if (darkMode === "enabled") {
        document.body.classList.add("dark");
    }
</script>
