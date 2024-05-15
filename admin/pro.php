<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
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
    $project_name = $_POST['project_name'];
    
    // Insert project into the database
    $sql = "INSERT INTO pro (project_name) VALUES ('$project_name')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New project added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Fetch existing projects and units from the database

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #a396a3;
            padding-top: 20px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Add Project button styles */
        #addProjectBtn {
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

        #addProjectBtn:hover {
            background-color: #6b4b6b;
        }

        /* Project card styles */
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 10px;
            text-align: center;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            background-color: #f2f2f2;
        }

        /* Style the list */
        ul.breadcrumb {
            padding: 10px 16px;
            list-style: none;
            background-color: #eee;
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
    <button id="addProjectBtn">Add Project</button>
    <div id="projectContainer">
        
    </div>
</div>

<script>
    document.getElementById("addProjectBtn").addEventListener("click", function() {
        var projectName = prompt("Enter the project name:");
        if (projectName) {
            createProjectCard(projectName);
            // Send the project and unit names to PHP to store in database using POST method
            fetch("unit.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'name=' + encodeURIComponent(projectName)
            });
        }
    });

    function createProjectCard(projectName) {
        var card = document.createElement("div");
        card.classList.add("card");
        card.textContent = projectName
        // Append the card to the container
        document.getElementById("projectContainer").appendChild(card);

        // Add event listener to handle clicks on project cards
        card.addEventListener("click", function() {
            redirectToUnit(projectName);
        });
    }

    function redirectToUnit(projectName) {
    window.location.href = "unit.php?project=" + encodeURIComponent(projectName)
}
</script>

</body>
</html>
