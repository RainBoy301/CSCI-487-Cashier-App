<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");
if($_SESSION["userType"] !== "A"){
	verify_login();
	verify_owner();
}

$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



if (isset($_POST["submit"])) {
	if (!empty($_POST["username"])&&!empty($_POST["First_Name"])&&!empty($_POST["Last_Name"]) && !empty($_POST["Commission"]) && $_POST["password"] !== ""){
				////Generate password and prepare query////
		$password = password_encrypt($_POST["password"]);
		$query = "SELECT * FROM Users WHERE userName = ?";
		$stmt = $mysqli -> prepare($query);
		$stmt -> execute([$_POST["username"]]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount() > 0) {  //Check if this username already exists in database
			$_SESSION["message"] = "The username already exists";
			redirect("addLogin.php");
		}

		else{

			$query = "SET FOREIGN_KEY_CHECKS = 0;
								INSERT INTO Users (userName, hashed_password, userType) VALUES (?,?,?);
								SET FOREIGN_KEY_CHECKS = 1;";
			$stmt = $mysqli -> prepare($query);
			$stmt -> execute([$_POST["username"], $password, $_POST["usertype"]]);

			if($stmt) {
				//////////////////Check the user type of User, then use the correct query/////////////
        $query = "SELECT max(userID) as newID FROM Users";

        $stmt = $mysqli -> prepare($query);
        $stmt -> execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                  ///Create Employee User////
        if($_POST["usertype"]=='E'){

					$query2 = "SET FOREIGN_KEY_CHECKS = 0;
                    INSERT INTO Employee (employeeID, First_Name, Last_Name, Unchecked_Ticket, Current_TicketID, Money_Collected,Commission) VALUES(?,?,?,0,0,0,?);
										SET FOREIGN_KEY_CHECKS = 1;";
					$stmt = $mysqli -> prepare($query2);
					$stmt -> execute([$row['newID'],$_POST["First_Name"],$_POST["Last_Name"],$_POST["Commission"]]);

					$_SESSION["message"] = "Employee ".$row["newID"]." successfully created";

				}else{
            ////Create Owner User////
          $query2 = "SET FOREIGN_KEY_CHECKS = 0;
                    INSERT INTO Owner (ownerID, First_Name, Last_Name, Unchecked_Ticket, Current_TicketID, Money_Collected,Commission) VALUES(?,?,?,0,0,0,?);
                    SET FOREIGN_KEY_CHECKS = 1;";
          $stmt = $mysqli -> prepare($query2);
          $stmt -> execute([$row['newID'],$_POST["First_Name"],$_POST["Last_Name"],$_POST["Commission"]]);
					$_SESSION["message"] = "An Owner was created";
				}

				///////////////Delete admin after created account - Debatable - May be implemented - Maybe not//////

				///Delete admin after first use//////
				if($_SESSION["userType"] == "A"){
					//deleteAdmin();
				}


			}
			else {
				$_SESSION["message"] = "User could not be created";
			}
///////////////////Redirect to log in page for admin account//////////
			if($_SESSION["userType"] == "A"){
				redirect("home.php");
			}
///////////////////////////////////////////////////////////////////////////////
		redirect("main.php");
		}

	}else{
			$_SESSION["message"] = "Please fill in all data";
			redirect ("addLogin.php");
	}
}

?>

  <head>
    <title> Cashier App ADD LOGIN </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href ="stylesheets/addLogin.css">
  </head>
  <body>
    <?php
		if($_SESSION['userType']!=="A"){
			echoNav();
		}

    ?>
    <div class="container">
    <div class = "loginbox">
      <?php
      if (($output = message()) !== null) {
        echo $output;
      }
       ?>
      <h1>ADD LOGIN HERE</h1>
      <form action="addLogin.php" method="post">
        <div class="form-group">
          <label for="email">User Name</label>
          <input type="username" name ="username" class="form-control" id="email" aria-describedby="emailHelp">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" name="password" class="form-control" id="password">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">First Name</label>
          <input name="First_Name" class="form-control" >
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Last Name</label>
          <input type="username" name="Last_Name" class="form-control" >
        </div>
        <div class="form-group">
          <label> Commission rate (Eg: 0.6 for 60%) </label>
          <input type="number" min="0" max="1" step="0.01" name="Commission" class="form-control" >
        </div>
        <div class="drop-down">
          <label for="dropdown">User Type</label>
          <br>
          <select class="dropdown" name="usertype">
					<option value="O">Owner</option>
					<option value="E">Employee</option>

				</select>
        </div>
        <div class="home-button">
          <button type="submit" name="submit" class="">Submit</button>
        </div>
      </form>
			<button name="button" onclick="window.location.replace('https://turing.cs.olemiss.edu/~bqhoang/csci487/main.php')">Cancel</button>
    </div>
  </div>
  </body>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
