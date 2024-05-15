<?php
session_start();



if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch the list of uploaded files from the server directory
$uploadedFilesDir = '/xampp/htdocs/Project/MMA/admin/uploads';
$uploadedFiles = scandir($uploadedFilesDir);

// Filter out non-PDF and non-text files
$allowedExtensions = ['pdf', 'txt'];
$filteredFiles = array_filter($uploadedFiles, function ($file) use ($allowedExtensions) {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    return in_array($extension, $allowedExtensions);
});

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve selected file name
    $selectedFile = isset($_POST["selectedFile"]) ? $_POST["selectedFile"] : '';

    if (empty($selectedFile)) {
        echo '<p class="text-danger">Please choose a file.</p>';
    } else {
        // Construct the file path
        $file = "uploads/" . $selectedFile;
        $_SESSION['selectedFilePath'] = $file;
    }
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose File</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="multiselect.js"></script>
    
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; 
        }

        body {
            font-size: 110%;
            background: #ffffff;
            margin: 0;
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

        .error {
            width: 92%;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #4249a9;
            color: red;
            background: #f2dede;
            border-radius: 5px;
            text-align: left;
        }

        .success {
            color: #365692;
            background: #dff0d8;
            border: 1px solid #365692;
            margin-bottom: 20px;
            font-size: 80%;
        }

       

        p {
            font-size: 18px;
            color: #000000;
            margin-top: 10px;
        }

        form {
            max-width: 100%; /* Adjusted max-width for responsiveness */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Adjusted margin */
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 10px; /* Adjusted margin */
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

        .response-container {
            max-width: 100%; /* Adjusted max-width for responsiveness */
            overflow-y: scroll;
            padding: 15px;
            background-color: rgba(218, 218, 228, 0.5);
            border: 1px solid #6b4b6b;
            border-radius: 4px;
            width: 100%; /* Adjusted width for responsiveness */
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

.loader-container {
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 999;
}

.loader {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.logo {
    margin-left: 2px;
    width: 90%;
    height: auto;
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

    .dropdown {
        display: block;
        position: relative;
        padding: 10px 16px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #a396a3;
        min-width: 160px;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        color: white;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #4a314a;
    }

   

    .conten{
        margin-left: 250px;
        padding: 16px;
    }


.checkbox-container {
    padding: 10px;
    background-color: #a396a3;
}

.rule-label {
    display: block;
    color: white;
    margin-bottom: 5px;
}



.checkbox-container input[type="checkbox"] {
    width: 16px; 
    height: 16px; 
    margin-right: 5px; 
}

    </style>
</head>
<body>

<div class = "sidebar">

<img class="logo" src="P&P Logo-Black.png" alt="Logo">
<a href="index.php">Home</a>
<a href="rules.php">Rules</a>
<div class="dropdown">
            <div class="checkbox-container">
            <div class="form-group">
    <select id="ruleDropdown" name="selectedRules[]" multiple>
        <?php foreach ($rules as $rule): ?>
            <option value="<?php echo $rule['rule']; ?>"><?php echo $rule['rule']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <br>
    <button type="button" class="btn btn-default" name="btn-save" id="btn-submit" onclick="submitForm()">
        Submit
    </button>
</div>
</div>
        </div>
<a href="choosefiles.php">Choose Files</a>
<a href="?logout">Logout</a>

</div>


<div class="conten">
    <h2>Choose an Uploaded File</h2>
    <form action="index.php" method="post" onsubmit="updateSelectedFilePathAndRedirect()">

    <div class="form-group">
        <label for="uploadedFile">Select File:</label>
        <select id="uploadedFile" name="selectedFile">
            <?php foreach ($filteredFiles as $file): ?>
                <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="hidden" name="selectedFilePath" id="selectedFilePath" value="">
    <button type="submit" style="background:#6b4b6b">Choose</button>
</form>



    
    
<script>
    function updateSelectedFilePathAndRedirect() {
        var selectedFileDropdown = document.getElementById("uploadedFile");
        var selectedFilePathInput = document.getElementById("selectedFilePath");
        selectedFilePathInput.value = "uploads/" + selectedFileDropdown.value;
    }
</script>

</div>

</body>
</html>