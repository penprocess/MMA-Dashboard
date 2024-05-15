<?php
session_start();

// Check if the user is logged in and is an admin (you may need to adjust this check based on your user roles)
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin' && $_SESSION['username'] !== 'superadmin' ) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Database connection settings
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "project"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Get project name from form
    $unit_name = $_POST['unit_name'];
    
    // Insert project into the database
    $sql = "INSERT INTO units (unit_name) VALUES ('$unit_name')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to refresh the page after adding project
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch projects from the database
$sql = "SELECT * FROM units";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Units</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 20px;
        }

        /* Breadcrumb styles */
        ul.breadcrumb {
            padding: 10px 16px;
            list-style: none;
            background-color: #eee;
            margin-bottom: 20px;
        }

        /* Display list items side by side */
        ul.breadcrumb li {
            display: inline;
            font-size: 18px;
        }

        /* Add a slash symbol (/) before/behind each list item */
        ul.breadcrumb li+li:before {
            padding: 8px;
            color: black;
            content: "/\00a0";
        }

        /* Add a color to all links inside the list */
        ul.breadcrumb li a {
            color: #0275d8;
            text-decoration: none;
        }

        /* Add a color on mouse-over */
        ul.breadcrumb li a:hover {
            color: #01447e;
            text-decoration: underline;
        }

        /* Add Project button styles */
        #addUnitBtn {
            background-color: #865c85;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-bottom: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #addUnitBtn:hover {
            background-color: #6b4b6b;
        }

        /* Project name styles */
        .unit-name {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1);
        }

        .unit-name:hover {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        // JavaScript function to navigate to choosefiles.php with the selected unit name as a parameter
        function navigateToUnits(unitName) {
            // Redirect to choosefiles.php with the unit name as a parameter
            window.location.href = 'upload.php?unit=' + unitName;
        }
    </script>
</head>
<body>
    <h2>Units</h2>
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
        
    <button id="addUnitBtn" onclick="document.getElementById('addUnitForm').style.display='block'">Add Unit</button>
        <div id="addUnitForm" style="display:none;">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" id="unit_name" name="unit_name" placeholder="Unit Name:"><br><br>
                <input type="submit" name="submit" value="Add Unit">
                <button type="button" onclick="document.getElementById('addUnitForm').style.display='none'">Cancel</button>
            </form>
        </div>
        <br>
        <div>
            <?php
            // Display each project name
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Add onclick attribute to call the navigateToUnits function with the project name as argument
                    echo "<p class='unit-name' onclick = 'navigateToUnits(\"{$row['unit_name']}\")'>{$row['unit_name']}</p>" ;
                }
            } else {
                echo "No Units found";
            }
            ?>
        </div>
    </div>
    
</body>
</html>

<?php
// Close connection
$conn->close();
?>
