<?php
// Database Setup Script
require_once 'config.php';

echo "<h2>CISD Institute - Database Setup</h2>";

try {
    // Create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS admissions (
      id INT AUTO_INCREMENT PRIMARY KEY,
      full_name VARCHAR(120) NOT NULL,
      email VARCHAR(160) NOT NULL,
      phone VARCHAR(40) NOT NULL,
      course VARCHAR(120) NOT NULL,
      education VARCHAR(160),
      city VARCHAR(80),
      message TEXT,
      status ENUM('pending','approved','rejected') DEFAULT 'pending',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    
    CREATE TABLE IF NOT EXISTS contact_messages (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(120) NOT NULL,
      email VARCHAR(160) NOT NULL,
      phone VARCHAR(40),
      subject VARCHAR(160),
      message TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    
    CREATE TABLE IF NOT EXISTS admin_users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(50) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      email VARCHAR(160) NOT NULL UNIQUE,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    
    CREATE TABLE IF NOT EXISTS gallery (
      id INT AUTO_INCREMENT PRIMARY KEY,
      image_path VARCHAR(255) NOT NULL,
      title VARCHAR(120),
      description TEXT,
      display_order INT DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    
    CREATE TABLE IF NOT EXISTS courses (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(120) NOT NULL,
      description TEXT,
      duration VARCHAR(50),
      fees DECIMAL(10,2),
      image_path VARCHAR(255),
      display_order INT DEFAULT 0,
      status ENUM('active','inactive') DEFAULT 'active',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    
    CREATE TABLE IF NOT EXISTS statistics (
      id INT AUTO_INCREMENT PRIMARY KEY,
      stat_key VARCHAR(50) NOT NULL UNIQUE,
      stat_value INT NOT NULL DEFAULT 0,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS students (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(120) NOT NULL,
      father_name VARCHAR(120) NOT NULL,
      course VARCHAR(120) NOT NULL,
      description TEXT NOT NULL,
      image_path VARCHAR(255) NOT NULL,
      display_order INT DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS success_stories (
      id INT AUTO_INCREMENT PRIMARY KEY,
      image_path VARCHAR(255) NOT NULL,
      name VARCHAR(120) NOT NULL,
      course VARCHAR(120) NOT NULL,
      quote TEXT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    
    $pdo->exec($sql);
    echo "<p style='color:green'>✓ Database tables created successfully!</p>";
    
    // Delete existing admin user if exists
    $pdo->exec("DELETE FROM admin_users WHERE email = 'cisdinstitute@gmail.com'");
    
    // Insert admin user with correct password hash
    $password = 'Collegedata1';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute(['cisdinstitute', $passwordHash, 'cisdinstitute@gmail.com']);
    echo "<p style='color:green'>✓ Admin user created successfully!</p>";
    
    // Insert default statistics
    $pdo->exec("DELETE FROM statistics");
    $stmt = $pdo->prepare("INSERT INTO statistics (stat_key, stat_value) VALUES (?, ?)");
    $stats = [
        ['students_trained', 500],
        ['modern_courses', 12],
        ['years_experience', 5],
        ['success_stories', 150]
    ];
    foreach ($stats as $stat) {
        $stmt->execute($stat);
    }
    echo "<p style='color:green'>✓ Default statistics inserted!</p>";
    
    echo "<hr>";
    echo "<h3>Setup Complete!</h3>";
    echo "<p><strong>Login Credentials:</strong></p>";
    echo "<p>Email: cisdinstitute@gmail.com</p>";
    echo "<p>Password: Collegedata1</p>";
    echo "<p><a href='admin-login.php'>Go to Login Page</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>
