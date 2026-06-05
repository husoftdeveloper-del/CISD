<?php
// Reset Admin Password Script
require_once 'config.php';

echo "<h2>Reset Admin Password</h2>";

try {
    // Check if database connection works
    if (!isset($pdo)) {
        die("<p style='color:red'>Database connection not available.</p>");
    }
    
    // Delete existing admin user with this email
    $pdo->exec("DELETE FROM admin_users WHERE email = 'cisdcollege@gmail.com'");
    $pdo->exec("DELETE FROM admin_users WHERE username = 'cisdcollege'");
    
    // Create new admin user with correct password hash
    $password = 'Collegedata';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<p>Password hash: " . $passwordHash . "</p>";
    
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute(['cisdcollege', $passwordHash, 'cisdcollege@gmail.com']);
    
    echo "<p style='color:green'>✓ Admin user created successfully!</p>";
    echo "<p><strong>Email:</strong> cisdcollege@gmail.com</p>";
    echo "<p><strong>Password:</strong> Collegedata</p>";
    echo "<p><strong>Username:</strong> cisdcollege</p>";
    echo "<hr>";
    echo "<p><a href='admin-login.php' style='color:blue;text-decoration:underline;'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure the database 'novaskills' exists and the admin_users table is created.</p>";
}
?>
