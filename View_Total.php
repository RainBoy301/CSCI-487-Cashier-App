<?php
require_once("session.php");
require_once("functions.php");
require_once("database.php");
verify_login();
verify_owner();


$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $mysqli -> prepare("Select * from Owner Union Select * from Employee");
$stmt -> execute();

if(isset($_GET['id'])){

  if(!empty($_GET['id'])){
  ///////Owner views total from user by ID//////////////////////
    $query ="SELECT *, Round((Money_Collected*Commission),2) as net FROM (SELECT * FROM Employee UNION ALL SELECT * FROM Owner) t WHERE employeeID =? ";
    $stmt = $mysqli -> prepare($query);
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo  "<p>".$row["First_Name"]." ".$row["Last_Name"]." Collected<span  class= 'money' >$".$row["Money_Collected"]."</span></p>";
    echo  "<p> Commission rate <span class= 'money' >".$row["Commission"]."</span></p>";
    echo  "<p> Net Salary <span  class= 'money' >$<span id='inner_total'>".$row["net"]."</span></span></p>";
  }else{

    $stmt = $mysqli -> prepare("SELECT Round(SUM(Money_Collected),2) AS total FROM (SELECT Money_Collected FROM Employee UNION ALL SELECT Money_Collected FROM Owner) t");
    $stmt -> execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo  "<p> Total Money Shop Collected<span  class= 'money' >$".$row['total']."</span></p>";
  }

}else{
  ///////////When Owner select view total of the shop//////////////
  echo "<br><br><p>Please select a user<p>";
  echo"<select id='ID' onchange='view_individual()'><option value=''>View Shop Total</option>";

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<option value = '".$row['ownerID']."'>".$row['First_Name']." ".$row['Last_Name']."</option>";
  }

  $stmt = $mysqli -> prepare("SELECT Round(SUM(Money_Collected),2) AS total FROM (SELECT Money_Collected FROM Employee UNION ALL SELECT Money_Collected FROM Owner) t");
  $stmt -> execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  echo"</select><br><br>";
      echo"<div class='order-summary' id='order-summary'>";
      echo"<p>Total money from the shop are displayed below</p>";
      echo"<br>";
      echo  "<p> Total Money Shop Collected<span  class= 'money' >".$row['total']."</span></p>";
      echo"</div>";
}



?>
