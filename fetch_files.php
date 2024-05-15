<?php
// Include your database connection file here if it's not already included
// Example: include_once "db_connection.php";
$db = mysqli_connect('localhost', 'root', '', 'project');
// Check if the unit is set and not empty
if (isset($_POST['unit']) && !empty($_POST['unit'])) {
    // Sanitize the unit value
    $unit = $_POST['unit'];
    
    // Assuming you have a database connection
    $db = mysqli_connect('localhost', 'root', '', 'project');
    // Prepare a query to fetch files associated with the selected unit
    $query = "SELECT file FROM files WHERE unit = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $unit);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $filename);

    // Start generating HTML options for files dropdown
    $options = '<option value="" selected disabled>Select File</option>';
    
    // Fetch files and generate options
    while (mysqli_stmt_fetch($stmt)) {
        $options .= '<option value="' . $filename . '">' . $filename . '</option>';
    }
    
    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    // Return options for the files dropdown
    echo $options;
} else {
    // If unit is not set or empty, return an empty string
    echo '';
}
?>

