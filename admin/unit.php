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

// Retrieve project and unit information from URL parameters
if (isset($_GET['project']) && isset($_GET['unit'])) {
    $projectName = $_GET['project'];
    $unitName = $_GET['unit'];
} else {
    // Redirect if parameters are missing
    header('Location: dashboard.php');
    exit();
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Page</title>
    <!-- Add any necessary CSS styles -->
</head>
<body>

<div>
    <h1>Project: <?php echo $projectName; ?></h1>
    <h2>Unit: <?php echo $unitName; ?></h2>
    <!-- You can add more content here as needed -->
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Page</title>
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

        /* Breadcrumb navigation styles */
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .breadcrumb-item {
            display: inline-block;
            margin-right: 5px;
            color: #333;
        }

        .breadcrumb-item.active {
            font-weight: bold;
        }
        
        /* Add Unit button styles */
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

        /* Unit card styles */
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
    <div class="breadcrumb">
        <span class="breadcrumb-item">Project:</span>
        <span class="breadcrumb-item active"><?php echo $projectName; ?></span>
    </div>
    <h2>Units for <?php echo $projectName; ?></h2>
    <button id="addUnitBtn">Add Unit</button>
    <div id="unitContainer">
        <!-- Unit cards will be displayed here -->
    </div>
</div>

<script>
    document.getElementById("addUnitBtn").addEventListener("click", function() {
        var unitName = prompt("Enter the unit name:");
        if (unitName) {
            createUnitCard(unitName);
            // Send the unit name and current project to PHP to handle
            fetch("add_unit.php?name=" + encodeURIComponent(unitName) + "&project=" + encodeURIComponent('<?php echo $projectName; ?>'));
        }
    });

    function createUnitCard(unitName) {
        var card = document.createElement("div");
        card.classList.add("card");
        card.textContent = unitName;
        // Additional functionality can be added here, e.g., editing or deleting units
        document.getElementById("unitContainer").appendChild(card);
    }
</script>

</body>
</html>