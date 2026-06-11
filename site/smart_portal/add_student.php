<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Insert data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $sql = "INSERT INTO students (name, email) VALUES ('$name', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='text-align:center;color:green;'>New student added successfully!</p>";
    } else {
        echo "<p style='text-align:center;color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .dashboard-btn {
            text-align: right;
            margin-bottom: 20px;
        }

        .dashboard-btn a {
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .dashboard-btn a:hover {
            background-color: #2980b9;
        }

        .form-container {
            width: 400px;
            margin: auto;
            background: #fff;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="dashboard-btn">
        <a href="dashboard.php">🔙 Go to Dashboard</a>
    </div>

    <div class="form-container">
        <h2>Add Student</h2>
        <form method="POST" action="">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <input type="submit" value="Add Student">
        </form>
        <div class="link">
            <a href="index.php">View All Students</a>
        </div>
    </div>

</body>

</html>
