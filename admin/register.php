<?php include('server.php');

 if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit();
}





?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="style.css">
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
.btn {
  padding: 10px;
  font-size: 15px;
  color: white;
  background: #6b4b6b;
  border: none;
  border-radius: 5px;
  margin-top:7px;
}

.input-group input {
  height: 30px;
  width: 93%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid gray;
  margin-bottom: 20px;
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
<h2>Register Users</h2>

  <form method="post" action="register.php">
 
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  
  	  <input type="text" name="username" placeholder="Username"<?php echo $username; ?>">
  	</div>
  	<div class="input-group">

  	  <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  
  	  <input type="password" placeholder="Password" name="password_1">
  	</div>
  	<div class="input-group">
  	  
  	  <input type="password" name="password_2" placeholder="Confirm Password">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>



  </form>
</div>
</body>
</html>