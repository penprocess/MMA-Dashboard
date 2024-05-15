<?php
// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check for AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit']) && isset($_POST['module']) && isset($_POST['label'])) {
    // Sanitize input
    $unit = mysqli_real_escape_string($db, $_POST['unit']);
    $module = mysqli_real_escape_string($db, $_POST['module']);
    $label = mysqli_real_escape_string($db, $_POST['label']);

    // Fetch rules from the database based on unit, module, and label
    $query = "SELECT * FROM rules WHERE unit = '$unit' AND module = '$module' AND label = '$label'";
    $result = mysqli_query($db, $query);

    // Check for query success
    if ($result) {
        // Fetch and display rules
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>{$row['rule']}</p>";
        }
    } else {
        // Handle query failure
        echo "Error fetching rules: " . mysqli_error($db);
    }
} else {
    echo "Invalid request.";
}
?>
