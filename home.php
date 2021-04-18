<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");


$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


////////////////////Log in function//////////////////////
	if (isset($_POST["submit"])) {
    if (isset($_POST["username"]) && $_POST["password"] !== ""){

      $query = "SELECT * FROM Users WHERE username = ? LIMIT 1";
      $stmt = $mysqli -> prepare($query);
      $stmt -> execute([$_POST["username"]]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($stmt) {  //Check if we have a username in database

        if(password_check($_POST["password"], $row["hashed_password"])){ //Check password on that username
					/////if admin is used/////
					if($_POST["username"]=="admin"){
						$_SESSION['userType']='A';
						redirect("addLogin.php");

					}else{///password matched////
						///////Create Session Key/////
						$_SESSION["id"] = $row["userID"];
						$_SESSION['userType'] = $row["userType"];
						/////redirect pages/////
							if($row["userType"]=='O'){
								redirect("main.php");
							}
							else{
								redirect("main.php");
							}
					}


        }else{ // If password does not match
          $_SESSION["message"] = "Username/Password could not be found";
          redirect ("home.php");
        }

      }else{ // If username does not exist in database
        $_SESSION["message"] = "Username/Password could not be found";
        redirect ("home.php");
        }


      }else{ // If username/ password are empty
				$_SESSION["message"] = "Please log in first";
				redirect ("home.php");
			}
  }

?>




<head>
	<title> Cashier App ADD LOGIN </title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href ="stylesheets/home.css">
</head>
<body>
	<div class="container">
	<div class = "loginbox">
		<img src="images/logo.png" class="avatar">
		<h1>LOGIN HERE</h1>

		<?php if (($output = message()) !== null) {
				echo $output;
			} ?>

		<form action="home.php" method="post">
			<div class="form-group">
				<label for="email">User Name</label>
				<input type="username" name ="username" class="form-control" id="email" aria-describedby="emailHelp">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" name="password" class="form-control" id="password">
			</div>

			<div class="home-button">
				<button type="submit" name="submit" class="">Submit</button>
			</div>

		</form>
	</div>
</div>
</body>

<?php

Database::dbDisconnect();


 ?>
