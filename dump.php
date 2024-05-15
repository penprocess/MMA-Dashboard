<form action="index.php" method="post" enctype="multipart/form-data" id="queryForm">
   <!-- Add a hidden input field to store selected rules -->
    <input type="hidden" name="selected_rules" id="selected_rules" value="">
    
    <label for="user_input"></label>
    <input type="text" name="user_input" id="user_input" placeholder="Enter your query" value="<?php echo isset($_POST['user_input']) ? htmlspecialchars($_POST['user_input']) : ''; ?>"><br>

    <!-- Add a hidden input field to store the selected rule -->
    <input type="hidden" name="selected_rule" id="selected_rule" value="">
    
    <button type="submit" style="background:#6b4b6b" onclick="submitForm()">Generate</button>

    <div class="loader-container" id="loaderContainer">
        <div class="loader"></div>
    </div>
</form> 






<form action="index.php" method="post" enctype="multipart/form-data" id="queryForm">
<div id="userInputContainer">
    <label for="user_input">User Input:</label>
    <input type="text" name="user_input" id="user_input" placeholder="">
    <button type="submit" style="background:#6b4b6b" onclick="submitForm()">Generate</button>
</div>

<div class="loader-container" id="loaderContainer">
    <div class="loader"></div>
</div>
</form>



$selectedFile = isset($_POST["selectedFile"]) ? $_POST["selectedFile"] : '';
    $selectedFilePath = isset($_POST["selectedFilePath"]) ? $_POST["selectedFilePath"] : '';
    
    if (empty($selectedFile)) {
        echo '<p class="text-danger">Please choose a file.</p>';
    } else {
        // Construct the file path based on the chosen file from the dropdown
        $file = "/xampp/htdocs/Project/MMA/admin/uploads/" . $selectedFile;
    }
    
<!-- 
organisation of the unit
general description
process description
primary equipment
-->
