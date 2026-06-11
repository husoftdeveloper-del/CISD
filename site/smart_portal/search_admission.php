<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";


function formatDate($date)
{
    return ($date === '0000-00-00' || empty($date)) ? '—' : date('d F Y', strtotime($date));
}

$searchTerm = '';
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = trim($_POST["search"]);
    $stmt = $conn->prepare("SELECT * FROM admissions WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ?");
    $like = "%" . $searchTerm . "%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Admissions - CISD ACADEMY</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            animation: backgroundAnimation 8s infinite;
            padding: 40px;
            background-color: #f2f6fc;
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

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        form {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: 60%;
            padding: 12px;
            border: 2px solid #3498db;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            margin-left: 10px;
            cursor: pointer;
            transition: 0.3s ease;
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

        a.edit-btn,
        a.delete-btn {
            display: inline-block;
            padding: 6px 10px;
            font-weight: bold;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
            text-decoration: none;
        }

        a.edit-btn {
            background-color: #27ae60;
            color: white;
        }

        a.edit-btn:hover {
            background-color: #219150;
        }

        a.delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        a.delete-btn:hover {
            background-color: #c0392b;
        }

        .actions-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
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
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>

    <div class="list-container">
        <h2>🔍 Search Student Records</h2>

        <form method="POST" action="">
            <input type="text" name="search" placeholder="Enter name, email, phone, or course..." value="<?= htmlspecialchars($searchTerm) ?>" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $results): ?>
            <?php if ($results->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Total Fee</th>
                            <th>Paid</th>
                            <th>Paid Date</th>
                            <th>Remaining</th>
                            <th>Remaining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $results->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["id"] ?></td>
                                <td><?= htmlspecialchars($row["name"]) ?></td>
                                <td><?= htmlspecialchars($row["father_name"]) ?></td>
                                <td><?= htmlspecialchars($row["phone"]) ?></td>
                                <td><?= htmlspecialchars($row["course"]) ?></td>
                                <td><?= htmlspecialchars($row["total_fee"]) ?></td>
                                <td><?= htmlspecialchars($row["paid_amount"]) ?></td>
                                <td><?= formatDate($row["paid_date"]) ?></td>
                                <td><?= htmlspecialchars($row["remaining"]) ?></td>
                                <td><?= formatDate($row["remaining_date"]) ?></td>
                                <td><?= htmlspecialchars($row["course_status"]) ?></td>
                                <td>
                                    <div class="actions-buttons">
                                        <a href="edit_admission.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                                        <a href="delete_admission.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?');" class="delete-btn">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: red; font-weight: bold;">No records found for "<?= htmlspecialchars($searchTerm) ?>"</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>
