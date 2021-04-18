<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");
verify_login();


if (($output = message()) !== null) {
		echo $output;
	}

  $mysqli = Database::dbConnect();
  $mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

////////////Getting user information////////////////////
	if($_SESSION['userType']=='O'){
		$query = "SELECT * FROM Owner WHERE ownerID = ? LIMIT 1";
	}else{
	$query = "SELECT * FROM Employee WHERE employeeID = ? LIMIT 1";
	}
/////////////////////////////////////////////////////////

  $stmt = $mysqli -> prepare($query);
  $stmt -> execute([$_SESSION["id"]]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <title>Employee</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	 <link rel="stylesheet" type="text/css" href ="stylesheets/employee.css">
</head>
<body>
	<?php
	echoNav();
?>
	<div class="row">
		<div class="col-md-3  col-lg-4"></div>
<div class = "bill bill col-md-6 col-lg-4" >
    <header>
		    <img src="images/avatar.png" class="avatar">

				<?php
        echo"<p class='h1-text'>Welcome back</p>";
        echo "<p class='h1-text'>" .$row["First_Name"]. " ".$row["Last_Name"]."</p>";
        ?>

	  </header>
		<div class="display_total" id="display_total">
			<br><br><p>Your current Total<p><br><br>

				<div class="order-summary">
						<p> Total Money Collected<span  class= "money" ><?php echo"$"; echo $row["Money_Collected"];?></p>
						<p> Commission rate <span class= "money" ><?php echo $row["Commission"];?></p>
						<p> Net Salary <span id="" class= "money" ><?php calculateNetTotal();?></p>
				</div>

				<?php
				/////////////Option to see other user money for owner///////////////////
				if($_SESSION['userType']=='O'){
					echo "<button id='view_other_button' onclick='view_other()'>View Total of other Users</button>";
				} ?>

		</div>
	</div>
	<div class="col-md-3  col-lg-4"></div>
</div>

<?php
Database::dbDisconnect();
?>
</body>
<script type="text/javascript" src="script/View_Total.js">

</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</html>
