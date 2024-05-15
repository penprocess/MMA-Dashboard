<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if unit name and project name are provided
if(isset($_GET['name']) && isset($_GET['project'])) {
    $unitName = $_GET['name'];
    $projectName = $_GET['project'];

    // Database connection
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'project';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the insert statement
    $stmt = $conn->prepare("INSERT INTO units (project_id, unit) VALUES (?, ?)");
    $stmt->bind_param("ss", $projectName, $unitName);

    // Execute the statement
    $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // You can return a response if needed
    echo "Unit added successfully.";
} else {
    // Handle case where parameters are missing
    echo "Error: Missing parameters.";
}
?>