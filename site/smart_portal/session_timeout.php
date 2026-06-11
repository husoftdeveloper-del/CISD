<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $timeout = ($_POST['timeout'] === 'custom') ? intval($_POST['custom_timeout']) : intval($_POST['timeout']);
    if ($timeout >= 1 && $timeout <= 240) {
        file_put_contents("session_timeout.txt", $timeout);
        $message = "✅ Timeout updated to $timeout minutes.";
    } else {
        $message = "❌ Please enter a valid timeout (1-240 minutes).";
    }
}

$current_timeout = file_exists("session_timeout.txt") ? file_get_contents("session_timeout.txt") : 5;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Session Timeout - CISD ACADEMY</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(to right top, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(14px);
            border-radius: 18px;
            padding: 35px 30px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            font-size: 26px;
            color: #1c1c1c;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        select,
        input[type="number"] {
            padding: 12px 16px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        button {
            background: linear-gradient(135deg, #1abc9c, #3498db, #9b59b6);
            color: white;
            padding: 12px 20px;
            border: none;
            font-size: 16px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        button:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #3498db, #1abc9c, #8e44ad);
        }

        .msg {
            margin-top: 15px;
            font-weight: 500;
            color: #2c3e50;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background: #2c3e50;
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #1abc9c;
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="container">
        <h2>⏱️ Session Timeout Settings</h2>

        <?php if (isset($message)): ?>
            <div class="msg"><?= $message ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="timeout" style="font-weight:500;">Select inactivity timeout:</label>
            <select name="timeout" id="timeout" onchange="toggleCustom(this.value)" required>
                <option value="1" <?= $current_timeout == 1 ? "selected" : "" ?>>1 Minute</option>
                <option value="2" <?= $current_timeout == 2 ? "selected" : "" ?>>2 Minutes</option>
                <option value="5" <?= $current_timeout == 5 ? "selected" : "" ?>>5 Minutes</option>
                <option value="10" <?= $current_timeout == 10 ? "selected" : "" ?>>10 Minutes</option>
                <option value="15" <?= $current_timeout == 15 ? "selected" : "" ?>>15 Minutes</option>
                <option value="30" <?= $current_timeout == 30 ? "selected" : "" ?>>30 Minutes</option>
                <option value="60" <?= $current_timeout == 60 ? "selected" : "" ?>>1 Hour</option>
                <option value="custom">Custom (1-240)</option>
            </select>

            <input type="number" min="1" max="240" name="custom_timeout" id="customTimeoutInput"
                placeholder="Enter custom minutes" style="display:none;">

            <button type="submit">Save Settings</button>
        </form>

        <a href="settings.php" class="back-btn">← Back to Settings</a>
    </div>

    <script>
        function toggleCustom(value) {
            const customInput = document.getElementById("customTimeoutInput");
            customInput.style.display = (value === "custom") ? "block" : "none";
        }
    </script>

</body>

</html>