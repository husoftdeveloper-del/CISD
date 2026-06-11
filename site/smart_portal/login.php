<?php
session_start();

if ((isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true)
    || (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)) {
    header('Location: dashboard.php');
    exit();
}

header('Location: ../admin/login.php');
exit();
