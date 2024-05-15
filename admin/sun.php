<?php
// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "project"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the project name from the URL parameter
if(isset($_GET['project'])) {
    $project_name = $_GET['project'];
} else {
    // Redirect to a default page or display an error message if the project name is not provided
    header("Location: default.php");
    exit();
}

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Get unit name and description from form
    $product_name = $_POST['product_name'];
    
    
    // Insert unit into the database
    $sql = "INSERT INTO product (product_name, project_name) VALUES ('$product_name', '$project_name')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to refresh the page after adding unit
        header("Location: ".$_SERVER['PHP_SELF']."?project=".urlencode($project_name));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch units for the specified project from the database
$sql = "SELECT * FROM product WHERE project_name = '$project_name'";
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
        .unit-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1);
        }

        .product-name {
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

        <!-- Breadcrumb navigation -->
        <ul class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>
            <li> <a href="sat.php">Projects</a></li>
            <li> <a><?php echo $project_name; ?></a></li>
            <li><a><span id="selectedProject"></a></span></li>
            
        </ul>
        
        <button id="addUnitBtn" onclick="document.getElementById('addUnitForm').style.display='block'">Add Product</button> 
        <div id="addUnitForm" style="display:none;">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?project=".urlencode($project_name); ?>">

                <input type="text" id="product_name" name="product_name" placeholder="Product Name:"><br><br>     
                <input type="submit" name="submit" value="Add Product">
                <button type="button" onclick="document.getElementById('addUnitForm').style.display='none'">Cancel</button>
            </form>
        </div>
        
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
         
echo "<p class='product-name' onclick='navigateToRules(\"{$row['product_name']}\")'>{$row['product_name']}</p>";

                
            }
        } else {
            echo "No Products found";
        }
        ?>
    </div>

    <script>
        // Function to navigate to units.php with the selected project name
       // Function to navigate to add_rules.php with the selected product name
function navigateToRules(productName) {
    window.location.href = 'add_rules.php?product_name=' + encodeURIComponent(productName);
}

    </script>
</body>
</html>
<?php
// Close connection
$conn->close();
?>
