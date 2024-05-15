<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
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

// Fetch all users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($db, $query);

// Check for query success
if (!$result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch data and display users
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);


if (isset($_POST['delete_user'])) {
    $userToDelete = $_POST['delete_user'];
    $deleteQuery = "DELETE FROM users WHERE username = ?";
    $stmt = mysqli_prepare($db, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $userToDelete);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect to the same page to prevent form resubmission on refresh
        header('Location: view_users.php');
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
    <title>View Users</title>
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
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-button {
            background-color: #ff3333;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
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
    <h2>View Users</h2>

    <!-- Display the users in a table -->
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
            <th>Delete</th>
        </tr>

        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td>
                    <form method="post" action="view_users.php">
                        <input type="hidden" name="delete_user" value="<?php echo $user['username']; ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
