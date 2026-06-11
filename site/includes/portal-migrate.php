<?php
/**
 * Institute portal schema (separate database from the public website).
 */
function cisd_portal_run_migrations(mysqli $conn): void
{
    $tables = [
        "CREATE TABLE IF NOT EXISTS `admin` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(100) DEFAULT NULL,
            `password` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `admissions` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `teachers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `designation` varchar(100) DEFAULT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `joining_date` date DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `teacher_salary` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `teacher_id` int(11) NOT NULL,
            `salary_amount` decimal(10,2) NOT NULL,
            `salary_month` varchar(50) NOT NULL,
            `paid_date` date NOT NULL,
            `remarks` text DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `expenditures` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `amount` decimal(10,2) NOT NULL,
            `exp_date` date NOT NULL,
            `remarks` text DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `ceo_cash` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `amount` decimal(10,2) NOT NULL,
            `note` text DEFAULT NULL,
            `received_date` date NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
        "CREATE TABLE IF NOT EXISTS `fee_receipts_v2` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
    ];

    foreach ($tables as $sql) {
        $conn->query($sql);
    }

    $conn->query("INSERT IGNORE INTO `admin` (`id`, `username`, `password`) VALUES (1, 'admin', 'admin123')");

    cisd_portal_add_column_if_missing($conn, 'admissions', 'online_application_id', 'INT(11) DEFAULT NULL');
}

function cisd_portal_add_column_if_missing(mysqli $conn, string $table, string $column, string $definition): void
{
    $tableEsc = $conn->real_escape_string($table);
    $columnEsc = $conn->real_escape_string($column);
    $result = $conn->query("SHOW COLUMNS FROM `$tableEsc` LIKE '$columnEsc'");
    if ($result && $result->num_rows === 0) {
        $conn->query("ALTER TABLE `$tableEsc` ADD COLUMN `$columnEsc` $definition");
    }
}

/**
 * Copy all portal tables from another database on the same server (e.g. local CISD → Hostinger portal DB).
 */
function cisd_portal_import_from_database(mysqli $conn, string $sourceDb): array
{
    $portalTables = ['admin', 'admissions', 'teachers', 'teacher_salary', 'expenditures', 'ceo_cash', 'fee_receipts_v2'];
    $sourceDbEsc = $conn->real_escape_string($sourceDb);
    $imported = [];
    $errors = [];

    cisd_portal_run_migrations($conn);

    foreach ($portalTables as $table) {
        $check = $conn->query("SHOW TABLES FROM `$sourceDbEsc` LIKE '$table'");
        if (!$check || $check->num_rows === 0) {
            continue;
        }

        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $conn->query("TRUNCATE TABLE `$table`");
        $sql = "INSERT INTO `$table` SELECT * FROM `$sourceDbEsc`.`$table`";
        if ($conn->query($sql)) {
            $imported[] = $table;
        } else {
            $errors[] = "$table: " . $conn->error;
        }
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
    }

    return ['imported' => $imported, 'errors' => $errors];
}
