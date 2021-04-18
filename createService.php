<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");
verify_login();
verify_owner();


$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


////////////////////////////////When form is Submit///////////////////////////
if (isset($_POST["submit"])) {
if(!empty($_POST["Service_Name"])&&!empty($_POST["Service_Price"])){
  $query = "INSERT INTO Service (Service_Name, Service_Price) VALUES (?,?)";
  $stmt = $mysqli -> prepare($query);
  $stmt -> execute([$_POST["Service_Name"],$_POST["Service_Price"]]);
  if($stmt){
    $_SESSION["message"] = "A new service has been added";
    redirect("main.php");
  }else{
    $_SESSION["message"] = "Service cannot be created. Please check your input";
    redirect("main.php");
  }
}else{
  $_SESSION["message"] = "Please fill in all data";

  }
}
//////////////////////////////////////////////////////////////////////////////
?>

  <head>
    <title> Cashier App ADD LOGIN </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href ="stylesheets/addLogin.css">
  </head>
  <body>
    <?php
    	echoNav();
    ?>
    <div class="container">
    <div class = "loginbox">
      <?php
      if (($output = message()) !== null) {
        echo $output;
      }
      ?>
      <h1>ADD SERVICE HERE</h1>
      <form action="createService.php" method="post">
        <div class="form-group">
          <label for="email">Service Name</label>
          <input type="text" name ="Service_Name" class="form-control" id="Service_Name" aria-describedby="emailHelp">
        </div>
        <div class="form-group">
          <label for="service" id="ServicePrice">Service Prices</label>
          <input type="number" min="0" step="0.01" name ="Service_Price" class="form-control" id="price" aria-describedby="emailHelp">
          <span>$</span>
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
