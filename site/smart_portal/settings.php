<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";
// Session timeout logic
$timeout = file_exists("session_timeout.txt") ? intval(file_get_contents("session_timeout.txt")) : 15; // Default 15 min
$timeout_seconds = $timeout * 60;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_seconds) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit;
}
$_SESSION['last_activity'] = time();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings - CISD ACADEMY</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Rubik', sans-serif;
            margin: 0;
            padding: 60px 20px;
            background: linear-gradient(to right top, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            transition: 0.4s;
        }

        .container {
            width: 100%;
            max-width: 700px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            font-weight: 600;
            font-size: 30px;
            margin-bottom: 35px;
            color: #1c1c1c;
        }

        .setting-option {
            background: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
            border-radius: 14px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #1c1c1c;
            font-size: 17px;
            font-weight: 500;
            transition: 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .setting-option:hover {
            background: #e9f7fd;
            transform: translateY(-3px);
        }

        .setting-option i {
            margin-right: 14px;
            font-size: 20px;
            color: #2980b9;
        }

        .setting-option a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .setting-option a:hover {
            color: #1a5276;
        }

        .back-btn {
            display: inline-block;
            background: linear-gradient(135deg, #1abc9c, #3498db, #9b59b6);
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 12px;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .back-btn i {
            margin-right: 8px;
        }

        .back-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #3498db, #1abc9c, #8e44ad);
        }

        /* Dark Mode */
        body.dark {
            background: #1e1e2f;
        }

        body.dark .container {
            background: rgba(20, 20, 30, 0.75);
            color: #f1f1f1;
        }

        body.dark h2 {
            color: #f1f1f1;
        }

        body.dark .setting-option {
            background: rgba(50, 50, 70, 0.95);
            color: #f1f1f1;
        }

        body.dark .setting-option:hover {
            background: rgba(60, 60, 90, 1);
        }

        body.dark .setting-option i {
            color: #1abc9c;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            .setting-option {
                font-size: 16px;
                padding: 15px 18px;
            }

            h2 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>
<?php portal_chrome_bar(); ?>


    <div class="container">
        <h2>⚙️ Settings Panel</h2>

        <div class="setting-option">
            <a href="change_admin_password.php"><i class="fas fa-shield-alt"></i> Change Admin Password</a>
        </div>

        <div class="setting-option">
            <a href="backup.php"><i class="fas fa-database"></i> Backup Database</a>
        </div>

        <!-- ✅ Dark Mode Toggle Option -->
        <div class="setting-option">
            <i class="fas fa-adjust"></i>
            <label style="display:flex; align-items:center; justify-content:space-between; width:100%; cursor: pointer;">
                <span style="flex: 1;">Dark Mode</span>
                <input type="checkbox" id="darkToggle" style="transform: scale(1.3); cursor: pointer;">
            </label>
        </div>
        <div class="setting-option">
            <a href="session_timeout.php"><i class="fas fa-hourglass-half"></i> Session Timeout Settings</a>
        </div>


        <div class="setting-option">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <script>
        const toggle = document.getElementById('darkToggle');
        const body = document.body;

        if (localStorage.getItem('dark-mode') === 'enabled') {
            body.classList.add('dark');
            toggle.checked = true;
        }

        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                body.classList.add('dark');
                localStorage.setItem('dark-mode', 'enabled');
            } else {
                body.classList.remove('dark');
                localStorage.setItem('dark-mode', 'disabled');
            }
        });
    </script>
    <?php include "session_timer.php"; ?>

</body>

</html>