<?php
  require_once("session.php");
  require_once("functions.php");


  if(!isset($_SESSION["id"])) {
  	$_SESSION["message"] = "You must login in first!";
  	redirect("home.php");
  }


  if (($output = message()) !== null) {
  	echo $output;
  }
  /////////////////////////////Delete Sessiion Key ////////////////////////////////////////////////////////////
  	 $_SESSION["id"] = NULL;
     $_SESSION['userType'] = NULL;
  ////////////////////////////////////////////////////////////////////////////////////////

    $_SESSION["message"] = "Log out Success";
   redirect("home.php");



?>
