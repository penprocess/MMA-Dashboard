<?php
session_start();

// Check if the user is logged in and is an admin (you may need to adjust this check based on your user roles)
if (!isset($_SESSION['username']) || ($_SESSION['username'] !== 'admin' && $_SESSION['username'] !== 'superadmin')) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Include your database connection file here if it's not already included
// Example: include_once "db_connection.php";
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check if the database connection is successful
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the unit name from the URL query parameter
$unitName = $_GET['unit'];

// Define the upload directory based on the unit name
$uploadDirectory = "uploads/$unitName/";

// Create the upload directory if it doesn't exist
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

// Process file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // File details
    $file = $_FILES["file"];
    $fileName = basename($file["name"]);
    $targetPath = $uploadDirectory . $fileName;
    $fileType = pathinfo($targetPath, PATHINFO_EXTENSION);

    // Allow only PDF and text files
    $allowedFormats = array("pdf", "txt");
    if (!in_array(strtolower($fileType), $allowedFormats)) {
        echo "Sorry, only PDF and text files are allowed.";
        exit();
    }

    // Check if the file already exists
    if (file_exists($targetPath)) {
        echo "Sorry, file already exists.";
        exit();
    }

    // Check file size (you can adjust the limit)
    if ($file["size"] > 50000000) {
        echo "Sorry, your file is too large.";
        exit();
    }

    // Move the file to the desired directory
    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        // Insert file details into the database
        $sql = "INSERT INTO files (file, unit) VALUES (?, ?)";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $fileName, $unitName);
        mysqli_stmt_execute($stmt);

        echo "The file " . htmlspecialchars($fileName) . " has been uploaded and added to the database.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Retrieve the list of uploaded files in the unit directory
$uploadedFiles = scandir($uploadDirectory);
$uploadedFiles = array_diff($uploadedFiles, array('..', '.')); // Remove "." and ".."
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
    color: #333;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
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
    <h2>File Uploader</h2>

    <form action="upload.php?unit=<?php echo urlencode($unitName); ?>" method="post" enctype="multipart/form-data">
        <label for="file">Choose a file:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit" name="submit">Upload</button>
    </form>

    <!-- Display uploaded files -->
    <?php if (!empty($uploadedFiles)): ?>
        <h3>Uploaded Files</h3>
        <table border="1">
            <tr>
                <th>File Name</th>
            </tr>
            <?php foreach ($uploadedFiles as $file): ?>
                <tr>
                    <td><?php echo $file; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
