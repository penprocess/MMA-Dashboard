<?php
// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check for AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit']) && isset($_POST['module'])) {
    // Prepare and bind parameters to prevent SQL injection
    $query = "SELECT DISTINCT label FROM rules WHERE unit = ? AND module = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ss", $_POST['unit'], $_POST['module']);
    
    // Execute query
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Bind result variables
        mysqli_stmt_bind_result($stmt, $label);

        // Generate options for label dropdown
        $options = '<option value="" selected disabled>Select Label</option>';
        while (mysqli_stmt_fetch($stmt)) {
            $options .= "<option value='$label'>$label</option>";
        }
        echo $options;
    } else {
        // Handle query failure
        echo "Error fetching labels: " . mysqli_error($db);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    // Handle invalid request
    echo "Invalid request.";
}
?>
