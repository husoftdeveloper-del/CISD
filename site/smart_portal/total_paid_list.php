<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($search !== '') {
    $searchTerm = "%$search%";
    $stmt = $conn->prepare("SELECT name, father_name, paid_amount, paid_date FROM admissions WHERE paid_amount > 0 AND name LIKE ? ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);

    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM admissions WHERE paid_amount > 0 AND name LIKE ?");
    $countStmt->bind_param("s", $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
} else {
    $stmt = $conn->prepare("SELECT name, father_name, paid_amount, paid_date FROM admissions WHERE paid_amount > 0 ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);

    $totalResult = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE paid_amount > 0");
    $totalRows = $totalResult->fetch_assoc()['total'];
}

$stmt->execute();
$result = $stmt->get_result();
$totalPages = ceil($totalRows / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Total Paid Students</title>
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
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        a.back {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(to right, #1abc9c, #3498db, #9b59b6);
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-form input[type="text"] {
            width: 250px;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .search-form button {
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

        .search-form button:hover {
            background: #1abc9c;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        th {
            background: linear-gradient(60deg, #1abc9c, #3498db, #9b59b6);
            color: white;
            padding: 14px;
            font-size: 0.85rem;
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
            transition: transform 0.3s ease, background 0.3s ease;
        }

        tr:hover td {
            transform: scale(1.01);
            background: #f4fcff;
        }

        table th:first-child,
        table td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        table th:last-child,
        table td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
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
            transition: 0.3s ease;
        }

        .pagination a:hover {
            background: #1abc9c;
        }

        .pagination .active {
            background: #9b59b6;
            pointer-events: none;
        }

        .highlight {
            background-color: rgb(85, 255, 43) !important;
            font-size: 14px;

            /* or any size like 20px, 1.2em, etc. */
            font-weight: bold;
        }

        /* Style only the Paid Amount TDs */
        td.paid-amount {
            background-color: rgb(62, 253, 62);

            /* light green background */
            font-weight: bold;
            /* bold text */
            font-size: 18px;
            /* increased size */
            color: rgb(0, 0, 0);
            /* dark green text */
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

        <form class="search-form" method="get">
            <input type="text" name="search" placeholder="Search by student name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <h2>Total Paid Students</h2>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Father Name</th>
                <th class="highlight"> Paid Amount</th>


                <th>Paid Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['father_name']) ?></td>
                        <td class="paid-amount">💸 Rs. <?= number_format((float)$row['paid_amount']) ?></td>




                        <td><?= date('d M Y', strtotime($row['paid_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No results found.</td>
                </tr>
            <?php endif; ?>
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
