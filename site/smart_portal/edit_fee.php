<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "Invalid ID.";
    exit;
}

$sql = "SELECT * FROM admission_fees WHERE id=$id";
$result = $conn->query($sql);

if (!$result) {
    echo "Database query failed: " . $conn->error;
    exit;
}

if ($result->num_rows != 1) {
    echo "Record not found.";
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your update code here...
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Fee Record - CISD ACADEMY</title>
    <style>
        /* Basic reset */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease forwards;
        }

        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
            text-align: center;
            font-weight: 700;
            letter-spacing: 1.2px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #34495e;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"] {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="datetime-local"]:focus {
            border-color: #27ae60;
            outline: none;
            box-shadow: 0 0 8px rgba(39, 174, 96, 0.3);
        }

        input[type="submit"] {
            background-color: #27ae60;
            border: none;
            color: white;
            font-size: 18px;
            padding: 12px 0;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #219150;
            transform: scale(1.05);
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2980b9;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a.back-link:hover {
            color: #1c5980;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 520px) {
            body {
                padding: 20px 10px;
            }

            .container {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>
    <div class="container">
        <h2>Edit Fee Record</h2>
        <form method="post" action="">
            <label>Student Name:</label>
            <input type="text" name="student_name" value="<?= htmlspecialchars($row['student_name']) ?>" required>

            <label>Class:</label>
            <input type="text" name="class" value="<?= htmlspecialchars($row['class']) ?>" required>

            <label>Month:</label>
            <input type="text" name="month" value="<?= htmlspecialchars($row['month']) ?>" required>

            <label>Total Fee (PKR):</label>
            <input type="number" step="0.01" name="total_fee" value="<?= $row['total_fee'] ?>" required>

            <label>Paid Amount (PKR):</label>
            <input type="number" step="0.01" name="paid_amount" value="<?= $row['paid_amount'] ?>" required>

            <label>Remaining Amount (PKR):</label>
            <input type="number" step="0.01" name="remaining_amount" value="<?= $row['remaining_amount'] ?>" required>

            <label>Submission Date:</label>
            <input type="datetime-local" name="submission_date" value="<?= date('Y-m-d\TH:i', strtotime($row['submission_date'])) ?>" required>

            <input type="submit" value="Update Record">
        </form>
        <a href="fee_submission_list.php" class="back-link">← Back to Fee List</a>
    </div>
</body>

</html>