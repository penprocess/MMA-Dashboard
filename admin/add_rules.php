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

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// Handle the form submission to add a new rule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the form data as needed
    $module = isset($_POST['module']) ? trim($_POST['module']) : '';
    $label = isset($_POST['label']) ? trim($_POST['label']) : '';
    $rule = isset($_POST['rule']) ? trim($_POST['rule']) : '';
    $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
    $user_prompt = isset($_POST['user_prompt']) ? ($_POST['user_prompt'] === 'enable' ? 1 : 0) : 0;
    $user_prompt_input = isset($_POST['user_prompt_input']) ? trim($_POST['user_prompt_input']) : '';

    // Perform necessary validation and database operations to add the new rule
    if (!empty($module) && !empty($label) && !empty($rule) && !empty($unit)) {
        // Insert the new rule into the database
        $query = "INSERT INTO rules (module, label, rule, unit, userprompt, userprompt_input) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssis", $module, $label, $rule, $unit, $user_prompt, $user_prompt_input);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Redirect to the same page to prevent form resubmission on refresh
            header('Location: view_rules.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
    if(isset($_GET['product_name'])) {
        $product_name = $_GET['product_name'];
    } else {
        // Redirect to a default page or display an error message if the product name is not provided
        header("Location: sat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Add Rules</title>
    <style>
       body {
  font-size: 100%;
  background: #fff;
}
.header {
  width: 30%;
  margin: 50px auto 0px;
  color: #6b4b6b;
  background: #ffffff;
  text-align: center;
  border: 0px solid #ffffff;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
}

        .content {
            width: 30%;
  margin: 0px auto;
  padding: 20px; 
  border: 0px solid #ffffff;
  background: rgb(255,255,255);
  border-radius: 0px 0px 10px 10px;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        input[type="text"],
        textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
    height: auto; /* Allow the textarea to grow vertically */
    overflow: auto; /* Enable horizontal and vertical scrolling */
}


        input[type="radio"] {
            margin-right: 5px;
        }

        button[type="submit"] {
            background-color: #6b4b6b;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #865c85;
        }

        #userPromptInput {
            display: none;
        }

        .radio-label {
            margin-right: 20px;
            font-size: 16px;
            color: #666;
        }
        .logo {
      width: 30%; 
      margin-bottom: 20px; 
      
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
        <h2>Add Rules</h2>

        <!-- Add Rules Form -->
        <form method="post" action="add_rules.php">
            <!-- Module dropdown -->
            <label for="module">TOC Division:</label>
            <input type="text" id="module" name="module" required>

            <!-- Hidden input field to store the selected unit -->
            <input type="hidden" name="unit" value="<?php echo isset($_GET['product_name']) ? $_GET['product_name'] : ''; ?>">

            <!-- Input container for label and rule -->
            <div class="input-container">
                <label for="label">TOC Header:</label>
                <input type="text" id="label" name="label" required>

                <label for="rule">Rule:</label>
        <textarea id="rule" name="rule" rows="8" style="height: auto; overflow: auto;" required></textarea>
    </div>

            <!-- Radio button to enable user prompt -->
            <label class="radio-label">
                <input type="radio" name="user_prompt" value="enable" onclick="toggleInputField(true)"> Enable User Prompt
            </label>
            <label class="radio-label">
                <input type="radio" name="user_prompt" value="disable" onclick="toggleInputField(false)" checked> Disable User Prompt
            </label>

            <!-- Input field for user prompt -->
            <div id="userPromptInput">
               
                <input type="text" id="user_prompt_input" name="user_prompt_input" placeholder="Enter user prompt">
            </div>

            <button type="submit">Add Rule</button>
        </form>
    </div>

    <script>
        function toggleInputField(enable) {
            var inputField = document.getElementById('userPromptInput');
            if (enable) {
                inputField.style.display = 'block';
            } else {
                inputField.style.display = 'none';
            }
        }
    </script>
</body>
</html>