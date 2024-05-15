<?php 
  session_start(); 

  if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$db = mysqli_connect('localhost', 'root', '', 'project');

// Fetch rules from the database
$rules_query = "SELECT * FROM rules";
$rules_result = mysqli_query($db, $rules_query);

// Check for query success
if (!$rules_result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch data and store rules in an array
$rules = mysqli_fetch_all($rules_result, MYSQLI_ASSOC);

$modules_query = "SELECT DISTINCT module FROM rules";
$modules_result = mysqli_query($db, $modules_query);

// Fetch data and store modules in an array
$modules = mysqli_fetch_all($modules_result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rules</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="multiselect.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


	
    <style>
        /* Add your styles here */
        .menu-card {
            width: 300px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: inline-block;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; 
        }

        body {
            font-size: 110%;
            background: #ffffff;
            margin-left: 10;
            overflow-x: hidden;
        }

        .header {
            width: 50%;
            padding-bottom: 100px;
            color: #6b4b6b;
            background: #ffffff;
            text-align: center;
            padding: 10px; 
            margin-left:300px;
        }

        form,
        .content {
            width: 100%;
            padding: 10px;
            border-radius: 10px; 
            background: #dadae4;
        }

        .input-group {
            margin: 10px 0;
        }

        .input-group label {
            display: block;
            text-align: left;
            margin: 3px;
        }

        .input-group input {
            height: 30px;
            width: 100%;
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 5;
            border: 1px solid gray;
        }

        .btn {
            padding: 10px;
            font-size: 15px;
            color: white;
            background: #6b4b6b;
            border: none;
            border-radius: 5px;
        }


       

        p {
            font-size: 18px;
            color: #000000;
            margin-top: 10px;
        }

        form {
            max-width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 50px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p.text-danger {
            color: #ff0000;
            margin-top: 10px;
        }

        

        p.response {
            margin: 0px;
            color: #3c763d;
            font-weight: bold;
        }

        p.re {
            color: #ff0000;
        }

        a {
            margin-left: auto; 
            padding: 10px; 
        }

        /* Media Query for tablets and smaller screens */
        @media (max-width: 768px) {
            form {
                max-width: 100%;
                margin-left: 0;
            }
        }





.logo {
    margin-left: 2px;
    width: 90%;
    height: auto;
}


.input-container {
    margin-top: 20px;
    transition: margin-top 0.3s ease;
    width: 90%;
}

/* Adjust the margin-top when the dropdown is clicked */
select:focus + .input-container {
    margin-top: 100px; /* Adjusted margin-top for a bigger dropdown */
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
    label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #6b4b6b;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .multiselect-container {
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 5px;
        }

        .btn {
            background-color: #6b4b6b;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #593c59;
        }

    .sidebar a {
        padding: 10px 16px;
        text-decoration: none;
        font-size: 18px;
        color: white;
        display: block;
    }

    .sidebar a:hover {
        background-color: #4a314a;
    }

    .conten{
        margin-left: 250px;
        padding: 16px;
    }

    </style>
    
  
</head>
<body>


<div class="sidebar">
   <img class="logo" src="P&P Logo-Black.png" alt="Logo">
   <a href="index.php">Home</a>
   <a href="rules.php">Rules</a>
   <a href="choosefiles.php">Choose Files</a>
   <a href="?logout">Logout</a>
</div>

<div class="conten">
    <h2>Rules</h2>

    <form>
            <label for="moduleDropdown">Module:</label>
            <select id="moduleDropdown" name="module" required class="custom-dropdown">
                <option value="" selected disabled>Select Module</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?php echo $module['module']; ?>"><?php echo $module['module']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="labelDropdown">Label:</label>
            <select id="labelDropdown" name="label" required class="dropdown">
                <option value="">Select Label</option>
            </select>

            <label for="ruleDropdown">Rules:</label>
            <div class = "checkbox-container">
            
            <select id="ruleDropdown" name="rule[]" multiple class="multiselect">
                
                    <option value="">Select Rule</option>
                 
                </select>
                
            </div>
              
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>

   


    
   

</body>
</html>