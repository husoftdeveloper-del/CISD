<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Paginated query with father_name
$stmt = $conn->prepare("SELECT name, father_name, course FROM admissions ORDER BY id DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Total records count
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM admissions");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Course counts
function countCourse($conn, $course)
{
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM admissions WHERE course = ?");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

$pythonCount  = countCourse($conn, 'Python');
$shortCount   = countCourse($conn, 'Short Course');
$graphicCount = countCourse($conn, 'Graphic Design');
$webCount     = countCourse($conn, 'Web Development');
$videoCount   = countCourse($conn, 'Video Editing');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Students & Courses</title>
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
            margin-bottom: 20px;
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

        h2 {
            text-align: center;
            color: #2c3e50;
            margin: 20px 0;
            font-size: 2rem;
        }

        .summary-box {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(60deg, #1abc9c, #3498db, #9b59b6);
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .course-info {
            position: relative;
            cursor: pointer;
        }

        .course-info .tooltip {
            display: none;
            position: absolute;
            top: 30px;
            left: 0;
            background: #ffffff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            padding: 10px 15px;
            z-index: 100;
            white-space: nowrap;
            font-size: 13px;
            min-width: 120px;
            max-height: 220px;
            /* ~10 students */
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .course-info .tooltip::-webkit-scrollbar {
            width: 6px;
        }

        .course-info .tooltip::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .course-info:hover .tooltip {
            display: block;
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
            font-size: 0.9rem;
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
        <div class="summary-box">
            <div class="course-info">
                Python: <?= $pythonCount ?>
                <div class="tooltip">
                    <?php
                    $res = $conn->query("SELECT name FROM admissions WHERE course = 'Python' LIMIT 10");
                    while ($r = $res->fetch_assoc()) echo "<div>" . htmlspecialchars($r['name']) . "</div>";
                    ?>
                </div>
            </div>
            <div class="course-info">
                Short Course: <?= $shortCount ?>
                <div class="tooltip">
                    <?php
                    $res = $conn->query("SELECT name FROM admissions WHERE course = 'Short Course'");
                    while ($r = $res->fetch_assoc()) {
                        echo "<div>" . htmlspecialchars($r['name']) . "</div>";
                    }
                    ?>
                </div>

            </div>
            <div class="course-info">
                Graphic Design: <?= $graphicCount ?>
                <div class="tooltip">
                    <?php
                    $res = $conn->query("SELECT name FROM admissions WHERE course = 'Graphic Design'");
                    while ($r = $res->fetch_assoc()) echo "<div>" . htmlspecialchars($r['name']) . "</div>";
                    ?>
                </div>
            </div>
            <div class="course-info">
                Web Dev: <?= $webCount ?>
                <div class="tooltip">
                    <?php
                    $res = $conn->query("SELECT name FROM admissions WHERE course = 'Web Development'");
                    while ($r = $res->fetch_assoc()) echo "<div>" . htmlspecialchars($r['name']) . "</div>";
                    ?>
                </div>
            </div>
            <div class="course-info">
                Video Editing: <?= $videoCount ?>
                <div class="tooltip">
                    <?php
                    $res = $conn->query("SELECT name FROM admissions WHERE course = 'Video Editing'");
                    while ($r = $res->fetch_assoc()) echo "<div>" . htmlspecialchars($r['name']) . "</div>";
                    ?>
                </div>
            </div>
        </div>
    </div>

    <h2>Students and Their Courses</h2>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Course</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['father_name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i == $page ? 'active' : '' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
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
