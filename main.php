<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");
verify_login();


  $mysqli = Database::dbConnect();
  $mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
////////////////////////Gather user infor//////////////////////
  if ($_SESSION['userType']=='O'){
		  $query = "SELECT * FROM Owner WHERE ownerID = ? LIMIT 1";
	}else{
		  $query = "SELECT * FROM Employee WHERE employeeID = ? LIMIT 1";
	}
	$stmt = $mysqli -> prepare($query);
  $stmt -> execute([$_SESSION["id"]]);

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
//////////////////////////////////////////////////////

?>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <title>Employee</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	 <link rel="stylesheet" type="text/css" href ="stylesheets/employee.css">
</head>
<body>	<?php
	echoNav();
?>

     <div class="row">
       <div class="col-md-3  col-lg-4"></div>
    <div class = "bill col-md-6 col-lg-4" >
      <header>
		    <img src="images/avatar.png" class="avatar">
        <?php

        echo"<p class='h1-text'>Welcome back</p>";
        echo "<p class='h1-text'>" .$row["First_Name"]. " " .$row["Last_Name"]. "</p>";

        ?>
      </header>
      <br><br>
		<?php
		if (($output = message()) !== null) {
				echo $output;
			}
			?>
			<br><br>
      <div>

      <?php
      ///////////////Checking pending ticket////////////////////
          if( !$row["Unchecked_Ticket"]==1){
            echo"<p class='h2-text'>You don't have any pending tickets!</p> ";
            echo "<button id='create' type='submit' >Create New Ticket</button>";
          }else{
            echo"<p class='h2-text'>You have a pending tickets! </p> ";
            echo "<button id='create' type='submit' >Check out current Ticket</button>";
      ////////////////////////////////////////////////////////////
          }
      ?>
      </div>
    </div>
    <div class="col-md-3 col-lg-4 "></div>
    </div>

<?php
Database::dbDisconnect();
?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
document.getElementById("create").addEventListener("click", function() {
window.location.replace('https://turing.cs.olemiss.edu/~bqhoang/csci487/test.php');
});



</script>
</html>
