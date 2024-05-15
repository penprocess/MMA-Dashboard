<?php
// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unit']) && isset($_POST['label'])) {
    // Sanitize input
    $unit = mysqli_real_escape_string($db, $_POST['unit']);
    $label = mysqli_real_escape_string($db, $_POST['label']);

    // Fetch userprompt value for the selected module
    $query = "SELECT userprompt,userprompt_input FROM rules WHERE unit = '$unit' AND label = '$label' LIMIT 1";
    $result = mysqli_query($db, $query);

    // Check for query success
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userprompt = $row['userprompt'];
        $userpromptInput = $row['userprompt_input'];

        // Send both values as JSON response
        echo json_encode(['userprompt' => $userprompt, 'userpromptInput' => $userpromptInput]);
    } else {
        // Default to not showing the input field if userprompt value is not found
        echo json_encode(['userprompt' => '0', 'userpromptInput' => '']);
    }
} else {
    // Handle invalid request
    echo json_encode(['userprompt' => '0', 'userpromptInput' => '']);
}
?>
