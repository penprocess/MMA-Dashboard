<?php
// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check for AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit'])) {
    // Sanitize input
    $unit = mysqli_real_escape_string($db, $_POST['unit']);

    // Fetch modules for the selected unit
    $query = "SELECT DISTINCT module FROM rules WHERE unit = '$unit'";
    $result = mysqli_query($db, $query);

    // Check for query success
    if ($result) {
        // Generate options for module dropdown
        $options = '<option value="" selected disabled>Select Module</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='{$row['module']}'>{$row['module']}</option>";
        }
        echo $options;
    } else {
        // Handle query failure
        echo "Error fetching modules: " . mysqli_error($db);
    }
} else {
    // Handle invalid request
    echo "Invalid request.";
}
?>
