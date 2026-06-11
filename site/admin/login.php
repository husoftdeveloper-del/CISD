<?php
session_start();
require_once __DIR__ . '/../config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$notice = '';
if (isset($_GET['timeout']) && $_GET['timeout'] == '1') {
    $error = 'Session expired due to inactivity. Please log in again.';
}
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $notice = 'You have been logged out successfully.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Please enter both login and password.';
    } elseif (!$pdo) {
        $error = 'Database connection unavailable. Please try again later.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE email = ? OR username = ?');
            $stmt->execute([$login, $login]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['loggedin'] = true;
                $_SESSION['last_activity'] = time();
                header('Location: dashboard.php');
                exit();
            }

            $error = 'Invalid login or password.';
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — CISD Institute</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5a 100%);
            padding: 20px;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card h1 { text-align: center; color: #0f172a; margin-bottom: 10px; font-size: 28px; }
        .login-card .subtitle { text-align: center; color: #64748b; margin-bottom: 8px; }
        .login-card .hint { text-align: center; color: #94a3b8; font-size: 13px; margin-bottom: 30px; }
        .login-card .field { margin-bottom: 20px; }
        .login-card label { display: block; margin-bottom: 8px; color: #334155; font-weight: 500; }
        .login-card input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
        }
        .login-card input:focus { outline: none; border-color: #0f172a; }
        .login-card .btn {
            width: 100%;
            padding: 14px;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .login-card .btn:hover { background: #1e3a5a; }
        .login-card .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .login-card .notice {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .login-card .back-link { text-align: center; margin-top: 20px; }
        .login-card .back-link a { color: #0f172a; text-decoration: none; }
        .login-card .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Admin Login</h1>
            <p class="subtitle">CISD Institute Control Panel</p>
            <p class="hint">Website content · Students · Fees · Teachers · Courses</p>

            <?php if ($notice): ?>
                <div class="notice"><?= e($notice) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="field">
                    <label for="login">Email or Username</label>
                    <input type="text" id="login" name="login" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>

            <div class="back-link">
                <a href="../index.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
