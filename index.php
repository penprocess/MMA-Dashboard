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

$uploadedFilesDir = '/xampp/htdocs/Project/MMA/admin/uploads';
$uploadedFiles = scandir($uploadedFilesDir);

// Filter out non-PDF and non-text files
$allowedExtensions = ['pdf', 'txt'];
$filteredFiles = array_filter($uploadedFiles, function ($file) use ($allowedExtensions) {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    return in_array($extension, $allowedExtensions);
});

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Fetch distinct units from the database
$units_query = "SELECT DISTINCT unit FROM rules";
$units_result = mysqli_query($db, $units_query);

$units_q = "SELECT DISTINCT unit_name FROM units";
$units_r = mysqli_query($db, $units_q);
$units_a = mysqli_fetch_all($units_r, MYSQLI_ASSOC);
// Check for query success
if (!$units_result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch data and store units in an array
$units = mysqli_fetch_all($units_result, MYSQLI_ASSOC);



// Fetch distinct rules from the database

$rules_query = "SELECT DISTINCT rule FROM rules";
$rules_result = mysqli_query($db, $rules_query);

// Check for query success
if (!$rules_result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch data and store rules in an array
$rules = mysqli_fetch_all($rules_result, MYSQLI_ASSOC);



?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Home</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="multiselect.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	
<style>
     
      
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
        .response-container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .response {
        font-size: 18px;
        color: #6b4b6b;
        margin-bottom: 10px;
    }

    .text-danger {
        color: #ff0000;
    }
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

        .header {
            width: 50%;
            padding-bottom: 100px;
            color: #6b4b6b;
            background: #f4f4f4;
            text-align: center;
            padding: 5px; 
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
        margin-bottom: 20px;
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
            font-size: 16px;
            color: #808080;
            margin-top: 10px;
        }

        form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
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
            color: #000000;
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


.input-container {
    margin-top: 20px;
    transition: margin-top 0.3s ease;
    width: 90%;
}


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
        display: block;
        margin-bottom: 8px;
        color: #6b4b6b;
    }

    select,
    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
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
            margin-right: 0px;
        }

        .btn {
        background-color: #6b4b6b;
        color: #ffffff;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
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
    .custom-dropdown {
            width: 160px;
            padding: 10px;
            font-size: 15px;
            border-radius: 5px;
           
        }


        .input-container {
            margin-top: 20px;
            transition: margin-top 0.3s ease;
        }
        #ruleContainer {
    margin-top: 2px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    width: 100%; 
    min-height: 0px; 
    overflow: auto; 
    font-size: 16px; 
    line-height: 1; 
    color:#000;
}
.small-input {
    width: 80px; /* Adjust the width as needed */
    font-size: 12px;
    border-radius: 5px;
    border: 1px solid gray;
    height: 40px;
    border: 1px solid #ccc;
}



    </style>
</head>

<body>

<div class = "sidebar">

<img class="logo" src="P&P Logo-Black.png" alt="Logo">
<a href="index.php">Home</a>
<a href="rules.php">Rules</a>
<a href="choosefiles.php">Choose Files</a>
<a href="?logout">Logout</a>

</div>


<div class="conten">
  <div class="header">

	<h2> Technical Writing Assistant</h2>
    <p><?php echo "Logged in as " . $_SESSION['username'] . "!"; ?></p>
   
</div>

<form action="index.php" method="post" enctype="multipart/form-data" id="queryForm">

<div class="input-group">
        <label for="unitDropdown"> Unit:</label>
        <select id="unitDropdown" name="unit" required class="custom-dropdown">
    <option value="" selected disabled>Select Unit</option>
    <?php foreach ($units_a as $unit): ?>
        <option value="<?php echo $unit['unit_name']; ?>"><?php echo $unit['unit_name']; ?></option>
    <?php endforeach; ?>
</select>
    </div>

    <div class="input-group">
        <label for="uploadedFile"> File:</label>
        <select id="uploadedFile" name="selectedFilePath" onchange="updateSelectedFilePath()" required class="custom-dropdown">
        <option value="" selected disabled>Select File</option>
            <?php foreach ($filteredFiles as $file): ?>
                <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="selectedFilePath" id="selectedFilePath" value=""> 
    </div>

    <div>
    <label for="pageNumbers">Page Numbers:</label>
    <input type="number" name="startPage" id="startPage" class="small-input" min="1" step="1" placeholder="Start">
    <input type="number" name="endPage" id="endPage" class="small-input" min="1" step="1" placeholder="End">
</div>


    <!-- Add the unit dropdown -->
    <div class="input-group">
        <label for="unitDropdow">Product:</label>
        <select id="unitDropdow" name="unit" required class="custom-dropdown">
            <option value="" selected disabled>Select Product</option>
            <?php foreach ($units as $unit): ?>
                <option value="<?php echo $unit['unit']; ?>"><?php echo $unit['unit']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Add the module dropdown -->
    <div class="input-group">
        <label for="moduleDropdown">Module:</label>
        <select id="moduleDropdown" name="module" required class="custom-dropdown">
            <option value="">Select Module</option>
            <?php foreach ($modules as $module): ?>
                <option value="<?php echo $module['module']; ?>"><?php echo $module['module']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="input-group">
    <label for="labelDropdown">Label:</label>
            <select id="labelDropdown" name="label" required class="custom-dropdown">
                <option value="">Select Label</option>
            </select>
            </div>  


            <div class="input-group">
    <label for="ruleDropdown">Rule:</label>
    <select id="ruleDropdown" name="rule" required class="custom-dropdown">
        <option value="" selected disabled>Select Rule</option>
    </select>
</div>



    
           <div id="ruleContainer" style="display: none;"></div>

    <div id="userInputContainer" style="display: none;">
    <label for="user_input">Additional User Input:</label>
    <input type="text" name="user_input" id="user_input" placeholder="">
</div>

<button type="submit" style="background:#6b4b6b" onclick="submitForm()">Generate</button>


<div class="loader-container" id="loaderContainer">
    <div class="loader"></div>
</div>
            
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to the label dropdown
    document.getElementById("labelDropdown").addEventListener("change", function() {
        var selectedLabel = this.value;
        var ruleContainer = document.getElementById("ruleContainer");
        if (selectedLabel) {
            // If a label is selected, show the rule container
            ruleContainer.style.display = "block";
        } else {
            // If no label is selected, hide the rule container
            ruleContainer.style.display = "none";
        }
    });
});

</script>




<script>
function updateSelectedFilePath() {
    var selectedFileDropdown = document.getElementById("uploadedFile");
    var selectedFileName = selectedFileDropdown.value;
    
    // Get the selected unit name
    var selectedUnitDropdown = document.getElementById("unitDropdown");
    var selectedUnitName = selectedUnitDropdown.value;

    // Construct the file path based on the selected unit name and file name
    var selectedFilePathInput = document.getElementById("selectedFilePath");
    selectedFilePathInput.value = "/xampp/htdocs/Project/MMA/admin/uploads/" + selectedUnitName + "/" + selectedFileName;
    
}



</script>
    

<script>
document.getElementById("unitDropdown").addEventListener("change", function() {
    var unit = this.value;
    if (unit) {
        // Fetch files based on the selected unit
        $.ajax({
            url: "fetch_files.php", 
            type: "POST",
            data: { unit: unit },
            success: function(response) {
                // Update file dropdown with fetched files
                $("#uploadedFile").html(response);
            }
        });
    } else {
        // Clear file dropdown if unit is not selected
        $("#uploadedFile").html('<option value="" selected disabled>Select File</option>');
    }
});
</script>



<script>  
function updateSelectedRules() {
    var selectedRules = [];
    var ruleDropdown = document.getElementById("ruleDropdown");
    for (var option of ruleDropdown.options) {
        if (option.selected) {
            selectedRules.push(option.value);
        }
    }
    // Join the selected rules into a single string
    var concatenatedRules = selectedRules.join(' ');

    // Update the hidden input field value
    var selectedRuleInput = document.getElementById("selected_rule");
    selectedRuleInput.value = concatenatedRules;
}

// Event listener for changes in the rule dropdown
document.getElementById("ruleDropdown").addEventListener("change", updateSelectedRules);

</script>


<script>
function submitForm() {
    var selectedRule = document.getElementById("ruleContainer").innerText.trim();
    var userInput = document.getElementById("user_input").value.trim();
    var selectedFilePath = document.getElementById("selectedFilePath").value.trim();
    
    // Update the user input field with the concatenated input
    document.getElementById("user_input").value = selectedRule + " " + userInput;

    // Set the value of the hidden input field with the selected file path
    document.getElementById("selectedFilePath").value = selectedFilePath;

    // Display the loader container
    document.getElementById('loaderContainer').style.display = 'flex';

    // Submit the form
    document.getElementById('queryForm').submit();
    console.log(selectedFilePath)
}

</script>

<!-- Add event listeners to unit, module, and label dropdowns -->
<script>
   // Event listener for changes in the unit dropdown
document.getElementById("unitDropdow").addEventListener("change", function() {
    var unit = this.value;
    if (unit) {
        // Fetch modules based on the selected unit
        $.ajax({
            url: "fetch_modules.php", // Change to the correct PHP script path
            type: "POST",
            data: { unit: unit },
            success: function(response) {
                // Update module dropdown with fetched modules
                $("#moduleDropdown").html(response);
            }
        });
    } else {
        // Clear module and label dropdowns if unit is not selected
        $("#moduleDropdown").html('<option value="" selected disabled>Select Module</option>');
        $("#labelDropdown").html('<option value="" selected disabled>Select Label</option>');
    }
});

// Event listener for changes in the module dropdown
document.getElementById("moduleDropdown").addEventListener("change", function() {
    var unit = document.getElementById("unitDropdow").value;
    var module = this.value;
    if (unit && module) {
        // Fetch labels based on the selected unit and module
        $.ajax({
            url: "fetch_labels.php", // Change to the correct PHP script path
            type: "POST",
            data: { unit: unit, module: module },
            success: function(response) {
                // Update label dropdown with fetched labels
                $("#labelDropdown").html(response);
            }
        });
    } else {
        // Clear label dropdown if module is not selected
        $("#labelDropdown").html('<option value="" selected disabled>Select Label</option>');
    }
});


// Event listener for changes in the label dropdown
document.getElementById("labelDropdown").addEventListener("change", function() {
    var unit = document.getElementById("unitDropdow").value;
    var module = document.getElementById("moduleDropdown").value;
    var label = this.value;
    if (unit && module && label) {
        // Fetch the userprompt value and input placeholder based on the selected label
        $.ajax({
            url: "fetch_userprompt.php", // Update to the correct PHP script path
            type: "POST",
            data: { unit: unit, module: module, label: label },
            success: function(response) {
                var data = JSON.parse(response);
                var userprompt = data.userprompt;
                var userpromptInput = data.userpromptInput;

                // Show or hide the input field based on the userprompt value
                if (userprompt.trim() === '1') {
                    document.getElementById("userInputContainer").style.display = "block";
                } else {
                    document.getElementById("userInputContainer").style.display = "none";
                }

                // Set the placeholder of the input field to userpromptInput
                document.getElementById("user_input").placeholder = userpromptInput;
            }
        });
    } else {
        // Clear input field and hide it if label is not selected
        document.getElementById("user_input").placeholder = "";
        document.getElementById("userInputContainer").style.display = "none";
    }
});


// Event listener for changes in the label dropdown


// Event listener for changes in the label dropdown
document.getElementById("labelDropdown").addEventListener("change", function() {
    var unit = document.getElementById("unitDropdow").value;
    var module = document.getElementById("moduleDropdown").value;
    var label = this.value;
    if (unit && module && label) {
        // Fetch rules based on the selected unit, module, and label
        $.ajax({
            url: "fetch_rules.php", // Path to the PHP script that fetches the rules
            type: "POST",
            data: { unit: unit, module: module, label: label },
            success: function(response) {
                // Update rule dropdown with fetched rules
                $("#ruleDropdown").html(response);
            }
        });
    } else {
        // Clear rule dropdown if label is not selected
        $("#ruleDropdown").html('<option value="" selected disabled>Select Rule</option>');
    }
});


// Event listener for changes in the rule dropdown

document.getElementById("ruleDropdown").addEventListener("change", function() {
    var selectedRule = this.value;
    var ruleContainer = document.getElementById("ruleContainer");
    if (selectedRule) {
        // If a rule is selected, display it in the rule container
        ruleContainer.style.display = "block";
        ruleContainer.innerText = selectedRule;
    } else {
        // If no rule is selected, hide the rule container
        ruleContainer.style.display = "none";
    }
});




</script>

<script>
window.addEventListener('DOMContentLoaded', function() {
    // Set default width for the dropdowns
    setDefaultDropdownWidth('unitDropdown');
    setDefaultDropdownWidth('unitDropdow');
    setDefaultDropdownWidth('uploadedFile');
    setDefaultDropdownWidth('unitDropdow');
    setDefaultDropdownWidth('moduleDropdown');
    setDefaultDropdownWidth('labelDropdown');
 

    // Add event listeners to the dropdowns to adjust width on selection change
    addDropdownChangeListener('unitDropdown');
    addDropdownChangeListener('unitDropdow');
    addDropdownChangeListener('uploadedFile');
    addDropdownChangeListener('unitDropdow');
    addDropdownChangeListener('moduleDropdown');
    addDropdownChangeListener('labelDropdown');
    
});

function setDefaultDropdownWidth(dropdownId) {
    var defaultWidth = 160; 
    var dropdown = document.getElementById(dropdownId);
    dropdown.style.width = defaultWidth + 'px';
}

function addDropdownChangeListener(dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    dropdown.addEventListener('change', function() {
        adjustDropdownWidth(dropdownId);
    });
}

function adjustDropdownWidth(dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    var selectedOption = dropdown.options[dropdown.selectedIndex];
    var optionTextWidth = getTextWidth(selectedOption.innerText, dropdown);
    dropdown.style.width = optionTextWidth + 45 + 'px';
}

function getTextWidth(text, element) {
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    context.font = window.getComputedStyle(element).fontSize + ' ' + window.getComputedStyle(element).fontFamily;
    var metrics = context.measureText(text);
    return metrics.width;
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('queryForm').addEventListener('submit', function () {
        document.getElementById('loaderContainer').style.display = 'flex';
    });
});

</script>
<?php
// Fetch the userprompt value for the selected label from the database
$userprompt_query = "SELECT userprompt FROM rules WHERE unit = ? AND module = ? AND label = ?";
$userprompt_stmt = mysqli_prepare($db, $userprompt_query);
$userprompt_value = null;

if ($userprompt_stmt) {
    mysqli_stmt_bind_param($userprompt_stmt, "sss", $selected_unit, $selected_module, $selected_label);
    mysqli_stmt_execute($userprompt_stmt);
    mysqli_stmt_bind_result($userprompt_stmt, $userprompt_result);
    mysqli_stmt_fetch($userprompt_stmt);
    mysqli_stmt_close($userprompt_stmt);

    // If the query returned a result, update the userprompt value
    if ($userprompt_result !== null) {
        $userprompt_value = $userprompt_result;
    }
} else {
    echo "Error: " . mysqli_error($db);
}

// Check if the userprompt value is 1 to determine whether to display the input field
if ($userprompt_value === '1') {
    // Display the input field
    echo '<script>document.getElementById("userInputContainer").style.display = "block";</script>';
}
?>


<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected file path from the form data
    $file = isset($_POST["selectedFilePath"]) ? $_POST["selectedFilePath"] : '';
    // Retrieve the input entered by the user
    $user_input = isset($_POST["user_input"]) ? htmlspecialchars($_POST["user_input"]) : '';
    // Retrieve the page numbers entered by the user
    $startPage = "";
    $endPage = "";
    if (isset($_POST["startPage"]) && isset($_POST["endPage"])) {
        $startPage = $_POST["startPage"];
        $endPage = $_POST["endPage"];
       
    }

    // Retrieve the selected unit, module, and label
    $selected_unit = isset($_POST["unit"]) ? $_POST["unit"] : '';
    $selected_module = isset($_POST["module"]) ? $_POST["module"] : '';
    $selected_label = isset($_POST["label"]) ? $_POST["label"] : '';

    // Retrieve the selected rule based on the unit, module, and label
    // Retrieve the selected rule from the form data
    $selected_rule = "";

    // Connect to the database
    $db = mysqli_connect('localhost', 'root', '', 'project');

    // Fetch the selected rule from the database
    $query = "SELECT rule FROM rules WHERE unit = ? AND module = ? AND label = ?";
    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $selected_unit, $selected_module, $selected_label);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $selected_rule);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($db);
    }

    if (empty($user_input)) {
        echo '<p class="text-danger">Please enter your query.</p>';
    } else {
        // Concatenate the selected rule and user input
        $full_input = $selected_rule . ' ' . $user_input;

        // Pass the page numbers to the Python script
        $full_input = escapeshellarg($full_input);
        $file = escapeshellarg($file);
        $startPage = escapeshellarg($startPage);
        $endPage = escapeshellarg($endPage);
        $pythonExecutable = '/Users/INGEREM/AppData/Local/Microsoft/WindowsApps/python.exe';
        $pythonScript = "/xampp/htdocs/Project/mma/run.py";

        $command = "$pythonExecutable $pythonScript $full_input $file $startPage $endPage 2>&1";

        $output = shell_exec($command);

        echo '<div class="response-container">';
        echo '<p class="response">Response:</p>';
        if ($output === null) {
            echo '<p class="text-danger">Error executing the command </p>';
            echo '<p class="text-danger">' . $command . '</p>';
        } else {
            echo '<p>' . nl2br(htmlspecialchars($output)) . '</p>';
        }
        echo '</div>';
    }
}
?>
 
</div>
</body>
</html>
