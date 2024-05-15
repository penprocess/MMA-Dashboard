<?php

$db = mysqli_connect('localhost', 'root', '', 'project');
// Retrieve the selected unit, module, and label from the POST data


$unit = $_POST['unit'];
$module = $_POST['module'];
$label = $_POST['label'];

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Fetch rules based on the selected label
$rules_query = "SELECT rule FROM rules WHERE unit = '$unit' AND module = '$module' AND label = '$label'";
$rules_result = mysqli_query($db, $rules_query);

// Build options for the rules dropdown
$options = '<option value="" selected disabled>Select Rule</option>';
while ($row = mysqli_fetch_assoc($rules_result)) {
    $options .= '<option value="' . $row['rule'] . '">' . $row['rule'] . '</option>';
}

echo $options;
?>

