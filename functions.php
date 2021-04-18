<?php
require_once("session.php");
require_once("database.php");





  function redirect ($new_location){
    header('Location: ' . $new_location);
    exit;
  }

  function password_encrypt($password) {
	  $hash_format = "$2y$10$";   // Use Blowfish with a "cost" of 10
	  $salt_length = 22; 					// Blowfish salts should be 22-characters or more
	  $salt = generate_salt($salt_length);
	  $format_and_salt = $hash_format . $salt;
	  $hash = crypt($password, $format_and_salt);
	  return $hash;
	}

	function generate_salt($length) {
	  // MD5 returns 32 characters
	  $unique_random_string = md5(uniqid(mt_rand(), true));

	  // Valid characters for a salt are [a-zA-Z0-9./]
	  $base64_string = base64_encode($unique_random_string);

	  // Replace '+' with '.' from the base64 encoding
	  $modified_base64_string = str_replace('+', '.', $base64_string);

	  // Truncate string to the correct length
	  $salt = substr($modified_base64_string, 0, $length);

		return $salt;
	}

	function password_check($password, $existing_hash) {
	  // existing hash contains format and salt at start
	  $hash = crypt($password, $existing_hash);
	  if ($hash === $existing_hash) {
	    return true;
	  }
	  else {
	    return false;
	  }
	}
////////////////////////////Print total money////////////////////////////
  function totalMoney($ticketID){
    $mysqli = Database::dbConnect();
    $mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query3 = "SELECT ROUND(SUM(total),2) as total from Ticket where userID =? and TicketID =?";

    $stmt = $mysqli -> prepare($query3);
    $stmt->execute([$_SESSION['id'],$ticketID]);

    if($stmt->rowCount() > 0){

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo"$";
      echo"<span id='inner_total'>" .$row["total"]."</span>";
    }else{
      echo"fail to execute";
    }
  }
///////////////////Calculate Salary from total money * Commission///////////////////
  function calculateNetTotal(){
      ////If the user is Employee//
      $mysqli = Database::dbConnect();
      $mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      if($_SESSION['userType']=='O'){
        $query4 = "SELECT Round((Money_Collected*Commission),2) as net FROM Owner where ownerID = ?";

      }
      //////If user is Owner////////
      else{
        $query4 = "SELECT Round((Money_Collected*Commission),2) as net FROM Employee where employeeID = ?";

      }
      $stmt = $mysqli -> prepare($query4);
      $stmt->execute([$_SESSION['id']]);

      if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo"$";
        echo"<span id='inner_total'>" .$row["net"]."</span>";
      }else{
        echo"fail to execute";
      }
  }
//////////////////Print Navigation on page//////////////////////
function echoNav(){

  echo"<nav class='navbar navbar-expand-lg navbar-light bg-light'>";

  	echo "<button class='navbar-toggle' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>";
  	echo"	<span class='navbar-toggler-icon'></span>";
  	echo"</button>";

    echo  	"<div class='collapse navbar-collapse' id='navbarSupportedContent'>";
    echo  	"	<ul class='navbar-nav mr-auto'>";
    echo  			"<li class='nav-item active'>";
    echo  				"<a class='nav-link' href='main.php'>Main <span class='sr-only'>(current)</span></a>";
    echo  		"	</li>";
    echo  		"	<li class='nav-item active'>";
    echo  			"	<a class='nav-link' href='test.php'>Check out <span class='sr-only'>(current)</span></a>";
    echo  		"	</li>";
         ///////Options for Owner/////////
  	if($_SESSION['userType']== 'O'){
  				echo"<li class='nav-item active'>";
  			  echo"<a class='nav-link' href='addLogin.php'>Create User <span class='sr-only'>(current)</span></a>";
  			  echo"	</li>";
  				echo"<li class='nav-item active'>";
					echo"<a class='nav-link' href='createService.php'>Create Service <span class='sr-only'>(current)</span></a>";
  				echo"</li>";
  	}
      /////////////////////////////
        echo  			"<li class='nav-item active'>";
        echo  			"	<a class='nav-link' href='total.php'>Cheking total <span class='sr-only'>(current)</span></a>";
        echo  			"</li>";
        echo  			"<li class='nav-item active'>";
        echo  			"	<a class='nav-link' href='logout.php'>Log out <span class='sr-only'>(current)</span></a>";
        echo  		"	</li>";
        echo  		"</ul>";
        echo  	"</div>";
        echo  "</nav>";
}
////////////////Delete admin//////////////////
function deleteAdmin(){
  $mysqli = Database::dbConnect();
  $mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $mysqli -> prepare("Delete from Users Where userID = 1");
  $stmt -> execute();
}
  ?>
