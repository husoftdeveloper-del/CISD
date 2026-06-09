<?php
session_start();
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $error = 'Please enter both login and password.';
    } else {
        try {
            // Try to find user by email or username
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ? OR username = ?");
            $stmt->execute([$login, $login]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $error = 'Invalid login or password.';
            }
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
    <title>Admin Login - CISD Institute</title>
    <link rel="stylesheet" href="css/style.css">
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
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card h1 {
            text-align: center;
            color: #0f172a;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .login-card p {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }
        .login-card .field {
            margin-bottom: 20px;
        }
        .login-card label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 500;
        }
        .login-card input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .login-card input:focus {
            outline: none;
            border-color: #0f172a;
        }
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
            transition: background 0.3s ease;
        }
        .login-card .btn:hover {
            background: #1e3a5a;
        }
        .login-card .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .login-card .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-card .back-link a {
            color: #0f172a;
            text-decoration: none;
        }
        .login-card .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Admin Login</h1>
            <p>CISD Institute Dashboard</p>
            
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
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
                <a href="index.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
