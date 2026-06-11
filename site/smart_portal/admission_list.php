<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";


// Function to format date safely
function formatDate($date)
{
    if ($date === '0000-00-00' || empty($date)) {
        return '—';
    }
    return date('d F Y', strtotime($date));
}

// Pagination setup
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$allowedLimits = [10, 30, 50];
if (!in_array($limit, $allowedLimits)) {
    $limit = 10;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$sql = "SELECT 
    id,
    registration_no,
    name,
    father_name,
    cnic,
    dob,
    email,
    domicile,
    address,
    gender,
    course,
    phone
FROM admissions
ORDER BY id ASC
LIMIT $limit OFFSET $offset";


$result = $conn->query($sql);

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM admissions");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admission List -</title>
    <style>
        /* [Same CSS as your file, no changes needed] */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-button,
        .dashboard-button {
            display: inline-block;
            padding: 10px 20px;
            animation: buttonGlow 6s infinite;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .search-button:hover,
        .dashboard-button:hover {
            transform: scale(1.05);
        }

        @keyframes backgroundAnimation {
            0% {
                background-color: #f2f6fc;
            }

            50% {
                background-color: #e3effa;
            }

            100% {
                background-color: #f2f6fc;
            }
        }

        @keyframes headerGradient {
            0% {
                background: linear-gradient(45deg, #3498db, #2ecc71);
            }

            50% {
                background: linear-gradient(45deg, #9b59b6, #1abc9c);
            }

            100% {
                background: linear-gradient(45deg, #3498db, #2ecc71);
            }
        }

        @keyframes buttonGlow {
            0% {
                background: linear-gradient(45deg, #2ecc71, #27ae60);
            }

            50% {
                background: linear-gradient(45deg, #27ae60, #1abc9c);
            }

            100% {
                background: linear-gradient(45deg, #2ecc71, #27ae60);
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: backgroundAnimation 8s infinite;
            padding: 40px;
        }

        .list-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .list-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        th,
        td {
            padding: 12px 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 17px;
            white-space: nowrap;
        }

        td {
            font-weight: 600;
            color: #2c3e50;
        }

        th {
            animation: headerGradient 5s infinite;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #d0f0ff;
        }

        a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #2c3e50;
            text-decoration: underline;
        }

        th.id-col {
            width: 5%;
        }

        th.name-col {
            width: 14%;
        }

        th.phone-col {
            width: 10%;
        }

        th.course-col {
            width: 10%;
        }

        th.fee-col,
        th.paid-col,
        th.paid-date-col,
        th.remaining-col,
        th.remaining-date-col,
        th.status-col {
            width: 10%;
        }

        th.actions-col {
            width: 12%;
            min-width: 120px;
        }

        .actions-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        a.edit-btn,
        a.print-btn,
        a.delete-btn {
            width: 47px;
            padding: 6px 0;
            font-weight: bold;
            border-radius: 4px;
            text-align: center;
            font-size: 13px;
            display: inline-block;
            color: white;
            text-decoration: none;
        }

        a.edit-btn {
            background-color: #27ae60;
        }

        a.edit-btn:hover {
            background-color: #219150;
        }

        a.print-btn {
            background-color: #2980b9;
        }

        a.print-btn:hover {
            background-color: #2471a3;
        }

        a.delete-btn {
            background-color: #e74c3c;
        }

        a.delete-btn:hover {
            background-color: #c0392b;
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
        /* ===== TABLE CONTROLS ===== */
.table-controls {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}

.limit-form {
    background: #f4f7fb;
    padding: 8px 14px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    font-weight: 600;
}

.limit-form select {
    margin: 0 6px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-weight: 600;
    cursor: pointer;
}

/* ===== PAGINATION ===== */
.pagination {
    margin-top: 25px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}

.page-btn {
    padding: 8px 14px;
    border-radius: 8px;
    background: #ecf0f1;
    color: #2c3e50;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.page-btn:hover {
    background: #3498db;
    color: #fff;
}

.page-btn.active {
    background: linear-gradient(45deg, #27ae60, #1abc9c);
    color: #fff;
    transform: scale(1.05);
}
table {
    margin-bottom: 100px;   /* table aur pagination ke beech gap */
   
}

.pagination {
    margin-top: 2px;      /* pagination ko thora neeche push karega */
    
}

    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="list-container">
        <div class="top-bar">
            <a href="dashboard.php" class="dashboard-button">⬅ Dashboard</a>
            <a href="search_admission.php" class="search-button">🔍 Search Record</a>
        </div>

        <h2>🎓 CISD CHD - Admission List</h2>
       <div class="table-controls">
    <form method="GET" class="limit-form">
        <span>Show</span>
        <select name="limit" onchange="this.form.submit()">
            <option value="10" <?= $limit==10?'selected':'' ?>>10</option>
            <option value="30" <?= $limit==30?'selected':'' ?>>30</option>
            <option value="50" <?= $limit==50?'selected':'' ?>>50</option>
        </select>
        <span>entries</span>
        <input type="hidden" name="page" value="1">
    </form>
</div>


        <table>
            <thead>
            <tr>
    <th>ID</th>
    <th>Reg No</th>
    <th>Name</th>
    <th>Father Name</th>
    <th>CNIC</th>
    <th>DOB</th>
    <th>Email</th>
    <th>Domicile</th>
    <th>Address</th>
    <th>gender</th>
    <th>Course</th>
    <th>Phone</th>
    <th>Photo</th>
    <th>Actions</th>
</tr>

            </thead>
           

            <tbody>
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['registration_no']) ?></td>
    <td>
    <a href="fee_receipt.php?student_id=<?= $row['id'] ?>" 
       style="font-weight:700;color:#2c3e50;">
        <?= htmlspecialchars($row['name']) ?>
    </a>
</td>

    <td><?= htmlspecialchars($row['father_name']) ?></td>
    <td><?= htmlspecialchars($row['cnic']) ?></td>
    <td><?= formatDate($row['dob']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['domicile']) ?></td>
    <td><?= htmlspecialchars($row['address']) ?></td>
    <td><?= htmlspecialchars($row['gender']) ?></td>
    <td><?= htmlspecialchars($row['course']) ?></td>
    <td><?= htmlspecialchars($row['phone']) ?></td>

    <!-- PHOTO -->
    <td>
        <?php if (!empty($row['photo'])): ?>
            <img src="<?= htmlspecialchars($row['photo']) ?>" 
                 style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
        <?php else: ?>
            —
        <?php endif; ?>
    </td>

   <td>
    <div class="actions-buttons">
        <a href="edit_admission.php?id=<?= $row['id'] ?>" class="edit-btn">✏</a>

        <!-- ✅ ADD / VIEW FEE -->
        <a href="fee_receipt.php?student_id=<?= $row['id'] ?>" 
           class="print-btn">💳</a>

        <a href="delete_admission.php?id=<?= $row['id'] ?>" 
           class="delete-btn"
           onclick="return confirm('Are you sure?')">🗑</a>
    </div>
</td>

</tr>

<?php endwhile; ?>
</tbody>

      <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&limit=<?= $limit ?>" class="page-btn">« Prev</a>
    <?php endif; ?>

    <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <a href="?page=<?= $i ?>&limit=<?= $limit ?>"
           class="page-btn <?= $i==$page?'active':'' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page+1 ?>&limit=<?= $limit ?>" class="page-btn">Next »</a>
    <?php endif; ?>
</div>

    </div>
    <?php include "session_timer.php"; ?>


</body>

</html>
<script>
    const darkMode = localStorage.getItem("dark-mode");
    if (darkMode === "enabled") {
        document.body.classList.add("dark");
    }
</script>
