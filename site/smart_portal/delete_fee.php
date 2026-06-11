<?php
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth_check.php";

// Check if ID is provided via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the record
    $sql = "DELETE FROM admission_fees WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the fee list after deletion
        header("Location: fee_submission_list.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
