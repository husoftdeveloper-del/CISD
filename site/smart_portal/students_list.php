<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Pagination + Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated data
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT id, name, father_name, course, phone, photo 
                            FROM admissions 
                            WHERE name LIKE ? OR father_name LIKE ? OR course LIKE ? OR phone LIKE ? 
                            ORDER BY id DESC LIMIT ? OFFSET ?");
    $searchParam = "%$search%";
    $stmt->bind_param("ssssii", $searchParam, $searchParam, $searchParam, $searchParam, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT id, name, father_name, course, phone, photo 
                            FROM admissions 
                            ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Course counts
$msOfficeCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course LIKE '%ms office%'")->fetch_assoc()['total'];
$typingCount   = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course LIKE '%typing%'")->fetch_assoc()['total'];
$englishCount  = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course LIKE '%english language%'")->fetch_assoc()['total'];
$shorthandCount= $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course LIKE '%short hand%'")->fetch_assoc()['total'];
$ditCount      = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course LIKE '%dit%'")->fetch_assoc()['total'];

// ✅ New course counts (exact labels stored)
$webDevCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'WEB DEVELOPMENT'")->fetch_assoc()['total'];
$appDevCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'APP DEVELOPMENT'")->fetch_assoc()['total'];
$aiPyCount   = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'AI & PYTHON'")->fetch_assoc()['total'];
$graphicCount= $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'GRAPHIC DESIGNING'")->fetch_assoc()['total'];
$ytAutoCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'YOUTUBE AUTOMATION'")->fetch_assoc()['total'];
$digMktCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'DIGITAL MARKETING'")->fetch_assoc()['total'];
$basicCompCount = $conn->query("SELECT COUNT(*) AS total FROM admissions WHERE course = 'BASIC COMPUTER SKILLS'")->fetch_assoc()['total'];


// Total for pagination

if (!empty($search)) {
    $searchSql = $conn->prepare("SELECT COUNT(*) AS total 
                                 FROM admissions 
                                 WHERE name LIKE ? OR father_name LIKE ? OR course LIKE ? OR phone LIKE ?");
    $searchParam = "%$search%";
    $searchSql->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $searchSql->execute();
    $totalResult = $searchSql->get_result();
} else {
    $totalResult = $conn->query("SELECT COUNT(*) AS total FROM admissions");
}

$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Total Students List</title>
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
            margin-bottom: 25px;
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
            margin-bottom: 30px;
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
            padding: 10px;
            font-size: 1.1rem;
            color: #34495e;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, background 0.3s ease;
            height: 50px;
            /* ✅ row ki height fix */
            vertical-align: middle;
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

        /* Student photo fix size */
        .student-photo {
            width: 45px;
            height: 55px;
            /* ✅ row ke andar fit ho */
            overflow: hidden;
            border-radius: px;
            margin: auto;


        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* ✅ crop rectangle ke andar */
            cursor: pointer;
        }


        /* Modal (fullscreen image view) */
        #imgModal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.9);
        }

        #imgModal img {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }

        #imgModal span {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .search-form {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 20px;
        }

        .search-form input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            width: 220px;
            font-size: 14px;
        }

        .search-form button {
            padding: 10px 18px;
            margin-left: 8px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        /* Hover effect */
        .search-form button:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        /* Click effect */
        .search-form button:active {
            transform: translateY(0px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="top-bar">
        <a class="back" href="dashboard.php">&larr; Back to Dashboard</a>
        <!-- 🔍 Search Bar -->
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Search student..."
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
       <div class="summary-box">

    <div class="course-info">
        MS Office: <?= $msOfficeCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course LIKE '%ms office%'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">
        Typing Course: <?= $typingCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course LIKE '%typing%'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">
        English Language: <?= $englishCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'ENGLISH LANGUAGE'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">
        Short Hand: <?= $shorthandCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'SHORT HAND'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">
        DIT: <?= $ditCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'DIT'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <!-- ✅ New Courses in tooltip + counts -->
    <div class="course-info">Web Development: <?= $webDevCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'WEB DEVELOPMENT'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">App Development: <?= $appDevCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'APP DEVELOPMENT'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">AI & Python: <?= $aiPyCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'AI & PYTHON'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">Graphic Designing: <?= $graphicCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'GRAPHIC DESIGNING'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">YouTube Automation: <?= $ytAutoCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'YOUTUBE AUTOMATION'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">Digital Marketing: <?= $digMktCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'DIGITAL MARKETING'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

    <div class="course-info">Basic Computer Skills: <?= $basicCompCount ?>
        <div class="tooltip">
            <?php
            $res = $conn->query("SELECT name FROM admissions WHERE course = 'BASIC COMPUTER SKILLS'");
            while ($row = $res->fetch_assoc()) {
                echo "<div>".htmlspecialchars($row['name'])."</div>";
            }
            ?>
        </div>
    </div>

</div>


    </div>

    <h2>Total Admitted Students</h2>

    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Course</th>
                <th>Phone</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['photo'])): ?>
                            <div class="student-photo">
                                <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Student Photo" onclick="openModal(this)">
                            </div>

                        <?php else: ?>
                            <form method="POST" enctype="multipart/form-data" style="display:inline;" action="upload_photo.php">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <label style="color: blue; font-size: 12px; cursor:pointer;">
                                    + Add Photo
                                    <input type="file" name="photo" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                </label>
                            </form>

                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['father_name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="<?= $i == $page ? 'active' : '' ?>"
                href="?page=<?= $i ?>&search=<?= urlencode(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>


    <?php include "session_timer.php"; ?>
</body>
<!-- Modal -->
<div id="imgModal">
    <span onclick="closeModal()">&times;</span>
    <img id="modalImg">
</div>

<script>
    function openModal(img) {
        document.getElementById("imgModal").style.display = "block";
        document.getElementById("modalImg").src = img.src;
    }

    function closeModal() {
        document.getElementById("imgModal").style.display = "none";
    }
</script>


</html>
<script>
    const darkMode = localStorage.getItem("dark-mode");
    if (darkMode === "enabled") {
        document.body.classList.add("dark");
    }
</script>
