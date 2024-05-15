<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
  font-size: 100%;
  background: #dadae4;
}
.header {
  width: 20%;
  margin: 50px auto 0px;
  color: #6b4b6b;
  background: #ffffff;
  text-align: center;
  border: 0px solid #ffffff;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
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
  width: 73%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid gray;
  
  margin-bottom: 20px;
}

form, .content {
  width: 20%;
  margin: 0px auto;
  padding: 20px; 
  border: 1px solid #ffffff;
  background: rgb(255,255,255);
  border-radius: 0px 0px 10px 10px;
}

.logo {
      width: 30%; 
      margin-bottom: 20px; 
      
    }
  h2{
   
    justify-content: center;
    align-items: center;
    align-self: center;
    text-align: center;
    
  }

  
  
  </style>
</head>
<body>
  <h2>VIRTUAL TRAINING ASSISTANT</h2>
  <div class="header">
  <img class="logo" src="P&P Logo-Black.png" alt="Logo">
  	<h2>Login</h2>
  </div>
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<input type="text" name="username" placeholder="Username">
  	</div>
  	<div class="input-group">
  		
  		<input type="password" name="password" placeholder="Password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>

  	
  </form>
</body>
</html>