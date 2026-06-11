-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 05:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admission_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `registration_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `domicile` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(10) NOT NULL,
  `course` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `name`, `email`, `gender`, `course`, `message`, `phone`) VALUES
(1, 'kamil', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(2, 'abbas', 'abbas12@gmail.com', 'Male', 'Computer Science', 'ab', NULL),
(3, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(4, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(5, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(6, 'zahid', 'hunterkhan22330@gmail.com', 'Male', 'Business Management', 'abc', NULL),
(7, 'ali', 'ali@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(8, 'abbas', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(9, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Business Management', 'abc', NULL),
(10, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Business Management', 'abc', NULL),
(11, 'izaz', 'hunterkhan22330@gmail.com', 'Male', 'Business Management', 'abc', NULL),
(12, 'kamil', 'hunterkhan22330@gmail.com', 'Male', 'Computer Science', 'abc', NULL),
(13, 'Ali Khan', 'ali@gmail.com', '', '', NULL, '03001234567');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `course`, `details`, `application_date`, `status`) VALUES
(1, 8, 'python', 'i am atif', '2025-05-30 19:11:51', 'Pending'),
(2, 8, 'ms office', 'hy', '2025-05-30 19:17:14', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `created_at`, `password`) VALUES
(1, 'sajjad', 'sajjad123@gmail.com', '03129440021', '2025-05-30 18:23:46', '$2y$10$mZR89F/apueYpwCm8qIoRu1AzE1c2FsSCeZIFYcQo0of52XRNlEQu'),
(4, 'huzaifa', 'huzaifa@gmail.com', '03145454567', '2025-05-30 18:26:05', '$2y$10$NFwzMbpW.6RGGmcG/7bLtuSpXyM2MFEvq34mbm5jQNqucYu4pHWDO'),
(6, 'saeed', 'saeed@gmail.com', '03129440022', '2025-05-30 18:41:24', '$2y$10$sR2fxeEGtICMK8xXxK6eSOp02vLSPa3ZwmbqE8qHV7SJnZtpjBE/O'),
(8, 'atiq', 'atiq@gmail.com', '03129440024', '2025-05-30 18:51:57', '$2y$10$q6MidpDyff8Os7nPBQk.bOlF1bKD/tTNgu8Z.eDEi1at9u9yHh5Bm'),
(9, 'Nasir', 'nasir123@gmail.com', '03129440028', '2025-05-31 11:08:39', '$2y$10$IjTSx/eMOGbfuzMxyrg7OOR5n4F6yhTbEMwmAT9lDH6kszeasIchK');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `joining_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_salary`
--

CREATE TABLE `teacher_salary` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `salary_amount` decimal(10,2) NOT NULL,
  `salary_month` varchar(50) NOT NULL,
  `paid_date` date NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenditures`
--

CREATE TABLE `expenditures` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `exp_date` date NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ceo_cash`
--

CREATE TABLE `ceo_cash` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `received_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_receipts_v2`
--

CREATE TABLE `fee_receipts_v2` (
  `id` int(11) NOT NULL,
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
  `receipt_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'kamil', 'f865b53623b121fd34ee5426c792e5c33af8c227'),
(3, 'Admin', '7c222fb2927d828af22f592134e8932480637c0d'),
(4, 'khan', '8cb2237d0679ca88db6464eac60da96345513964');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_salary`
--
ALTER TABLE `teacher_salary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenditures`
--
ALTER TABLE `expenditures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ceo_cash`
--
ALTER TABLE `ceo_cash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_receipts_v2`
--
ALTER TABLE `fee_receipts_v2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_salary`
--
ALTER TABLE `teacher_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenditures`
--
ALTER TABLE `expenditures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ceo_cash`
--
ALTER TABLE `ceo_cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_receipts_v2`
--
ALTER TABLE `fee_receipts_v2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Seed course names (optional)
-- admissions.course is a varchar(100), so new course pages can store these values.
-- NOTE: If you already inserted admissions records, you can remove this seed section.
INSERT INTO `admissions` (`registration_no`,`name`,`father_name`,`cnic`,`dob`,`email`,`domicile`,`address`,`gender`,`course`,`message`,`phone`)
VALUES
('CISD-2026-001','Seed User','—','','0000-00-00','','','—','—','WEB DEVELOPMENT',NULL,''),
('CISD-2026-002','Seed User','—','','0000-00-00','','','—','—','APP DEVELOPMENT',NULL,''),
('CISD-2026-003','Seed User','—','','0000-00-00','','','—','—','AI & PYTHON',NULL,''),
('CISD-2026-004','Seed User','—','','0000-00-00','','','—','—','GRAPHIC DESIGNING',NULL,''),
('CISD-2026-005','Seed User','—','','0000-00-00','','','—','—','YOUTUBE AUTOMATION',NULL,''),
('CISD-2026-006','Seed User','—','','0000-00-00','','','—','—','DIGITAL MARKETING',NULL,''),
('CISD-2026-007','Seed User','—','','0000-00-00','','','—','—','BASIC COMPUTER SKILLS',NULL,'');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
