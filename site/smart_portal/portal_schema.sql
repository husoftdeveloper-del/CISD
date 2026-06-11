-- CISD Institute Portal Database
-- Import this into a SEPARATE database on Hostinger (e.g. u328011253_cisd_portal)
-- The website uses u328011253_cisd_db — keep them separate to avoid table conflicts.

CREATE DATABASE IF NOT EXISTS u328011253_cisd_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u328011253_cisd_portal;

-- Source: smart_portal/nice.sql (extended for fees, photos, etc.)

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `admin` (`id`, `username`, `password`) VALUES (1, 'admin', 'admin123');

CREATE TABLE IF NOT EXISTS `admissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `domicile` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(10) NOT NULL DEFAULT '',
  `course` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `total_fee` decimal(10,2) DEFAULT 0,
  `paid_amount` decimal(10,2) DEFAULT 0,
  `paid_date` date DEFAULT NULL,
  `remaining` decimal(10,2) DEFAULT 0,
  `remaining_date` date DEFAULT NULL,
  `course_status` varchar(50) DEFAULT NULL,
  `online_application_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `teacher_salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `salary_amount` decimal(10,2) NOT NULL,
  `salary_month` varchar(50) NOT NULL,
  `paid_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `expenditures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `exp_date` date NOT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ceo_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `received_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `fee_receipts_v2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_id` int(11) NOT NULL,
  `monthly_fee` int(11) DEFAULT 0,
  `admission_fee` int(11) DEFAULT 0,
  `registration_fee` int(11) DEFAULT 0,
  `examination_fee_1` int(11) DEFAULT 0,
  `examination_fee_2` int(11) DEFAULT 0,
  `examination_fee_3` int(11) DEFAULT 0,
  `previous_dues` int(11) DEFAULT 0,
  `discount` int(11) DEFAULT 0,
  `received_amount` int(11) DEFAULT 0,
  `remaining_amount` int(11) DEFAULT 0,
  `receipt_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
