<?php
// Check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}


// Check if the user has the superadmin role
if ($_SESSION['username'] !== 'superadmin') {
    // Redirect to a different page or show an error message
    echo "Access denied. You must be a superadmin to access this page.";
    exit();
}

// Rest of your HTML and PHP code for the settings page goes here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Page</title>
    <!-- Add your CSS and other head elements here -->
</head>
<body>
    <h1>Superadmin Settings Page</h1>
    <!-- Add your settings form and other content here -->

    <!-- Example logout link -->
    <a href="?logout">Logout</a>
</body>
</html>
