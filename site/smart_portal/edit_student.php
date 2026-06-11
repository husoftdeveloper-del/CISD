<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Get ID and validate
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    die("Invalid student ID");
}

// Fetch existing record
$sql = "SELECT name, course, phone, total_fee, paid_amount, paid_date, remaining, remaining_date, course_status FROM admissions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
if (!$student) {
    die("Student not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name           = $_POST['name'];
    $course         = $_POST['course'];
    $phone          = $_POST['phone'];
    $total_fee      = $_POST['total_fee'];
    $paid_amount    = $_POST['paid_amount'];
    $remaining      = $total_fee - $paid_amount;
    $paid_date      = $_POST['paid_date'];
    $remaining_date = $_POST['remaining_date'];
    $status         = $_POST['course_status'];

    $update = "UPDATE admissions 
               SET name=?, course=?, phone=?, total_fee=?, paid_amount=?, paid_date=?, remaining=?, remaining_date=?, course_status=?
               WHERE id=?";
    $uStmt = $conn->prepare($update);
    $uStmt->bind_param(
        "sssisssssi",
        $name,
        $course,
        $phone,
        $total_fee,
        $paid_amount,
        $paid_date,
        $remaining,
        $remaining_date,
        $status,
        $id
    );
    if ($uStmt->execute()) {
        header("Location: remaining_fee_list.php");
        exit;
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Student — CISD ACADEMY</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #667eea, #764ba2);
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 35px 35px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(8px);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 700;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 14px 18px;
            margin-top: 8px;
            border: 1.5px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #4e91fc;
            box-shadow: 0 0 5px rgba(78, 145, 252, 0.5);
            outline: none;
        }

        input[readonly] {
            background: #f3f3f3;
            color: #777;
        }

        .buttons {
            margin-top: 30px;
            text-align: center;
        }

        .btn {
            padding: 12px 28px;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            margin: 0 12px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .btn.save {
            background: linear-gradient(45deg, #1abc9c, #16a085);
            color: #fff;
        }

        .btn.save:hover {
            background: linear-gradient(45deg, #16a085, #1abc9c);
            transform: scale(1.05);
        }

        .btn.cancel {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: #fff;
        }

        .btn.cancel:hover {
            background: linear-gradient(45deg, #c0392b, #e74c3c);
            transform: scale(1.05);
        }

        .error {
            margin-top: 15px;
            text-align: center;
            color: #e74c3c;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .form-container {
                padding: 25px 20px;
            }

            .btn {
                margin: 10px 5px;
                width: 100%;
                display: block;
            }
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="form-container">
        <h2>Edit Student Payment Details</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="<?= htmlspecialchars($student['name']) ?>" required>

            <label for="course">Course</label>
            <select id="course" name="course" required>
                <option value="">Select Course</option>

                <!-- Existing courses -->
                <option <?= $student['course'] === 'DIT' ? 'selected' : '' ?>>DIT</option>
                <option <?= $student['course'] === 'MS OFFICE' ? 'selected' : '' ?>>MS OFFICE</option>
                <option <?= $student['course'] === 'TYPING' ? 'selected' : '' ?>>TYPING</option>
                <option <?= $student['course'] === 'ENGLISH LANGUAGE' ? 'selected' : '' ?>>ENGLISH LANGUAGE</option>
                <option <?= $student['course'] === 'SHORT HAND' ? 'selected' : '' ?>>SHORT HAND</option>

                <!-- ✅ New courses -->
                <option <?= $student['course'] === 'WEB DEVELOPMENT' ? 'selected' : '' ?>>WEB DEVELOPMENT</option>
                <option <?= $student['course'] === 'APP DEVELOPMENT' ? 'selected' : '' ?>>APP DEVELOPMENT</option>
                <option <?= $student['course'] === 'AI & PYTHON' ? 'selected' : '' ?>>AI & PYTHON</option>
                <option <?= $student['course'] === 'GRAPHIC DESIGNING' ? 'selected' : '' ?>>GRAPHIC DESIGNING</option>
                <option <?= $student['course'] === 'YOUTUBE AUTOMATION' ? 'selected' : '' ?>>YOUTUBE AUTOMATION</option>
                <option <?= $student['course'] === 'DIGITAL MARKETING' ? 'selected' : '' ?>>DIGITAL MARKETING</option>
                <option <?= $student['course'] === 'BASIC COMPUTER SKILLS' ? 'selected' : '' ?>>BASIC COMPUTER SKILLS</option>
            </select>


            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="text" value="<?= htmlspecialchars($student['phone']) ?>" required>

            <label for="total_fee">Total Fee</label>
            <input id="total_fee" name="total_fee" type="number" step="0.01" value="<?= htmlspecialchars($student['total_fee']) ?>" required>

            <label for="paid_amount">Paid Amount</label>
            <input id="paid_amount" name="paid_amount" type="number" step="0.01" value="<?= htmlspecialchars($student['paid_amount']) ?>" required>

            <label for="paid_date">Paid Date</label>
            <input id="paid_date" name="paid_date" type="date" value="<?= $student['paid_date'] !== '0000-00-00' ? htmlspecialchars($student['paid_date']) : '' ?>" required>

            <label for="remaining">Remaining Amount</label>
            <input id="remaining" name="remaining" type="number" step="0.01" value="<?= htmlspecialchars($student['remaining']) ?>" readonly>

            <label for="remaining_date">Remaining Date</label>
            <input id="remaining_date" name="remaining_date" type="date" value="<?= $student['remaining_date'] !== '0000-00-00' ? htmlspecialchars($student['remaining_date']) : '' ?>" required>

            <label for="course_status">Status</label>
            <select id="course_status" name="course_status" required>
                <option value="Pending" <?= $student['course_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Completed" <?= $student['course_status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>

            <div class="buttons">
                <button type="submit" class="btn save">Save Changes</button>
                <a href="remaining_fee_list.php" class="btn cancel">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const totalFee = document.getElementById('total_fee');
        const paidAmount = document.getElementById('paid_amount');
        const remaining = document.getElementById('remaining');

        function calculateRemaining() {
            const total = parseFloat(totalFee.value) || 0;
            const paid = parseFloat(paidAmount.value) || 0;
            const rem = total - paid;
            remaining.value = rem.toFixed(2);
        }

        totalFee.addEventListener('input', calculateRemaining);
        paidAmount.addEventListener('input', calculateRemaining);
        window.addEventListener('DOMContentLoaded', calculateRemaining);
    </script>
    <?php include "session_timer.php"; ?>


</body>

</html>
