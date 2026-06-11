<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
session_start();
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $current = $_POST["current_password"];
    $new = $_POST["new_password"];

    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $current);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $update = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
        if (!$update) {
            die("Prepare (update) failed: " . $conn->error);
        }
        $update->bind_param("ss", $new, $username);
        $update->execute();
        $msg = "<span style='color:limegreen;'>✅ Password changed successfully!</span>";
    } else {
        $msg = "<span style='color:red;'>❌ Invalid current credentials.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Password - CISD ACADEMY</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bgPulse 10s infinite alternate;
        }

        @keyframes bgPulse {
            0% {
                background: linear-gradient(135deg, #3498db, #2ecc71);
            }

            100% {
                background: linear-gradient(135deg, #9b59b6, #1abc9c);
            }
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 35px 30px;
            width: 420px;
            border-radius: 20px;
            backdrop-filter: blur(12px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            outline: none;
            transition: background 0.3s ease;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #eee;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            background: rgba(255, 255, 255, 0.3);
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        input[type="submit"]:hover {
            transform: scale(1.03);
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            color: #fff;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="glass-box">
        <h2>🔐 Change Password</h2>
        <form method="post">
            <input type="text" name="username" placeholder="👤 Username" required>
            <input type="password" name="current_password" placeholder="🔑 Current Password" required>
            <input type="password" name="new_password" placeholder="🆕 New Password" required>
            <input type="submit" value="Change Password">
        </form>
        <?php if (!empty($msg)) echo "<div class='message'>$msg</div>"; ?>
        <a class="back-link" href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</body>

</html>
