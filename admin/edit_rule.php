<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_button'])) {
    if (isset($_POST['edit_id'])) {
        $editId = $_POST['edit_id'];

        // Fetch the rule details from the database
        $query = "SELECT * FROM rules WHERE label = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $editId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rule = mysqli_fetch_assoc($result);

        if ($rule) {
            // Pre-fill form with existing rule data
            $label = $rule['label'];
            $ruleText = $rule['rule'];

            // Display edit form
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Edit Rule</title>
                <link rel="stylesheet" href="style.css" type="text/css">
                <style>

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.content {
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

button[type="submit"] {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

.error {
    color: red;
}

                </style>
            </head>
            <body>
                <div class="content">
                    <h2>Edit Rule</h2>
                    <form method="post" action="update_rule.php">
                        <input type="hidden" name="edit_id" value="<?php echo $label; ?>">
                        <label for="label">Label:</label>
                        <input type="text" id="label" name="label" value="<?php echo $label; ?>"><br>
                        <label for="rule">Rule:</label><br>
                        <textarea id="rule" name="rule" rows="4" cols="50"><?php echo $ruleText; ?></textarea><br>
                        <button type="submit" name="update_button">Update</button>
                    </form>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Rule not found.";
        }
    }
}
?>
