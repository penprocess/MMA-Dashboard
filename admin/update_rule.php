<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_button'])) {
    if (isset($_POST['edit_id']) && isset($_POST['label']) && isset($_POST['rule'])) {
        $editId = $_POST['edit_id'];
        $label = $_POST['label'];
        $ruleText = $_POST['rule'];

        // Update the rule in the database
        $query = "UPDATE rules SET label = ?, rule = ? WHERE label = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "sss", $label, $ruleText, $editId);
        mysqli_stmt_execute($stmt);

        if (mysqli_affected_rows($db) > 0) {
            // Redirect to view_rules.php after successful update
            header('Location: view_rules.php');
            exit();
        } else {
            echo "Failed to update rule.";
        }
    }
}
?>
