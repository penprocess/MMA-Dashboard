<?php
session_start();

$username = "";
$email    = "";
$errors = array(); 

$db = mysqli_connect('localhost', 'root', '', 'project');

if (isset($_POST['reg_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  if (empty($username)) { array_push($errors, "Username required"); }
  if (empty($email)) { array_push($errors, "Email required"); }
  if (empty($password_1)) { array_push($errors, "Password required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "Passwords do not match");
  }

  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { 
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }
    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }


if (count($errors) == 0) {
    $password = $password_1;
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
    mysqli_stmt_execute($stmt);

    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now registered and logged in";
    header('location: dashboard.php');
}

}

if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username required");
  }
  if (empty($password)) {
  	array_push($errors, "Password required");
  }
  if (count($errors) == 0) {
  	
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  
  	  header('location: dashboard.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}
?>
