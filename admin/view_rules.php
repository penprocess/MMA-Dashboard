<?php
session_start();

if (!isset($_SESSION['username']) == 'admin') {
    header('Location: login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Fetch all rules from the database
$query = "SELECT * FROM rules ORDER BY unit, module, label";
$result = mysqli_query($db, $query);

// Check for query success
if (!$result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch data and organize rules by unit and module
$groupedRules = [];
while ($rule = mysqli_fetch_assoc($result)) {
    $unit = $rule['unit'];
    $module = $rule['module'];
    $groupedRules[$unit][$module][] = $rule;
}


if (isset($_POST['delete_id'])) {
    $userToDelete = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM rules WHERE label = ?";
    $stmt = mysqli_prepare($db, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $userToDelete);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect to the same page to prevent form resubmission on refresh
        header('Location: view_rules.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rules</title>
    <link rel="stylesheet" href="style.css" type="text/css">
<style>
    body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}

.content {
    margin-left: 250px;
    padding: 16px;
}

h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table, th, td {
    border: 1px solid #ddd;
    text-align: left;
    padding: 5px; /* Increased padding for better spacing */
}

th {
    background-color: #f2f2f2;
    font-weight: bold; /* Make table headers bold */
}


h4{
    font-weight:bold;
}



/* Adjust the max-width according to your layout */
td.rule-cell {
    max-width: 600px;
    overflow: auto;
    word-wrap: break-word;
}

.rule-card {
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    padding: 16px;
}

.rule-content {
    padding: 0 10px;
}

.rule-card h4 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
    margin-top: 10px;
}

.rule-card table {
    width: 100%;
    border-collapse: collapse;
  
}

.rule-card table th,
.rule-card table td {
    border: 1px solid #ddd;
    text-align: left;
    padding: 5px;
}

.rule-card table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.rule-cell {
    max-width: 400px;
    overflow: auto;
    word-wrap: break-word;
}

.delete-btn {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 8px 16px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

</style>
</head>
<body>

<div class="sidebar">


    <img class="logo" src="P&P Logo-Black.png" alt="Logo">
    <a href="dashboard.php">Home</a>
<a href="sat.php">Project</a>
<a href="register.php">Add Users</a>


<a href="view_users.php">View Users</a>
<a href="view_rules.php">View Rules</a>

<a href="?logout" >Logout</a>

    
</div>

<div class="content">
    <h2>View Rules</h2>

    <!-- Display rules grouped by unit -->
    <?php foreach ($groupedRules as $unit => $unitModules): ?>
        <div class="rule-card">
            <h3><?php echo "Product: ". $unit; ?></h3>
            <?php foreach ($unitModules as $module => $moduleRules): ?>
                <h4><?php echo $module; ?></h4>
                <table>
                    <tr>
                        <th>Label</th>
                        <th>Rule</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach ($moduleRules as $rule): ?>
                        
                       
<tr>
    <td><?php echo $rule['label']; ?></td>
    <td class='rule-cell'><?php echo $rule['rule']; ?></td>
    
    <td>
        <form method='post' action='edit_rule.php'>
            <input type='hidden' name='edit_id' value='<?php echo $rule['label']; ?>'>
            <button type='submit' name='edit_button' class='edit-btn'>Edit</button>
        </form>
    </td>
    <td>
                                <form method='post' action='view_rules.php'>
                                    <input type='hidden' name='delete_id' value='<?php echo $rule['label']; ?>'>
                                    <button type='submit' name='delete_button' class='edit-btn'>Delete</button>
                                </form>
                            </td>
</tr>                         
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
// Handle delete request after the HTML content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_button'])) {
    // Check if delete_id is set in the POST data
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        // Perform delete operation
        $deleteQuery = "DELETE FROM rules WHERE label = ?";
        $deleteStmt = mysqli_prepare($db, $deleteQuery);

        if ($deleteStmt) {
            mysqli_stmt_bind_param($deleteStmt, "s", $deleteId);
            mysqli_stmt_execute($deleteStmt);
            mysqli_stmt_close($deleteStmt);
        
            exit();
        } else {
            die("Delete query failed: " . mysqli_error($db));
        }
    }
}
?>

</body>
</html>
