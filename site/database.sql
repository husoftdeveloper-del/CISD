-- CISD INSTITUTE database
<<<<<<< HEAD
CREATE DATABASE IF NOT EXISTS u328011253_cisd_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

=======
CREATE DATABASE IF NOT EXISTS novaskills CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE novaskills;
>>>>>>> 39242820cb49393c9ee47326a9c79f854b5ffe8a

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


-- Insert default admin user (email: cisdinstitute@gmail.com, password: Collegedata1)
INSERT INTO admin_users (username, password, email) VALUES 
('cisdinstitute', '$2y$10$8K1p/a0dL5xL7ZqX9mYqOeZJvN3kHmPqR5sT2uV4wX6yZ8aB0cD1eF2gH3iJ4kL5mN6oP7qR8sT9uV0wX1yZ2', 'cisdinstitute@gmail.com');

-- Create table for success stories
CREATE TABLE IF NOT EXISTS success_stories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image VARCHAR(255) NOT NULL,
  name VARCHAR(120) NOT NULL,
  father_name VARCHAR(120) NOT NULL,
  course VARCHAR(120) NOT NULL,
  quote TEXT NOT NULL,
  location VARCHAR(255) NULL,
  contact VARCHAR(255) NULL,
  email VARCHAR(255) NULL,
  education VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


INSERT INTO statistics (stat_key, stat_value) VALUES 
('students_trained', 500),
('modern_courses', 12),
('years_experience', 5),
('success_stories', 150);
