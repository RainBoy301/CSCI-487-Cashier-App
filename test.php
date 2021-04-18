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

///////////////////////////////////////When User is Owner//////////////////////////////
	if($_SESSION['userType']=='O'){

		$query = "SELECT * FROM Owner WHERE ownerID = ? LIMIT 1";
		$stmt = $mysqli -> prepare($query);
		$stmt -> execute([$_SESSION["id"]]);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if( !$row["Unchecked_Ticket"]==1){

			$query2 = "BEGIN;
								SET FOREIGN_KEY_CHECKS = 0;
								UPDATE Owner SET Current_TicketID = Current_TicketID + 1 WHERE ownerID =? ;
								UPDATE Owner SET Unchecked_Ticket = 1 WHERE ownerID =? ;
								SET FOREIGN_KEY_CHECKS = 1;
								COMMIT";
			$stmt = $mysqli -> prepare($query2);
			$stmt -> execute([$_SESSION["id"],$_SESSION["id"]]);
			$row["Current_TicketID"] = $row["Current_TicketID"]+1;

		}

	}




///////////////////////////////When User is Employee///////////////////////////////////
	else{
		$query = "SELECT * FROM Employee WHERE employeeID = ? LIMIT 1";
		$stmt = $mysqli -> prepare($query);
		$stmt -> execute([$_SESSION["id"]]);

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if( !$row["Unchecked_Ticket"]==1){

			$query2 = "BEGIN;
								SET FOREIGN_KEY_CHECKS = 0;
								UPDATE Employee SET Current_TicketID = Current_TicketID + 1 WHERE employeeID =? ;
								UPDATE Employee SET Unchecked_Ticket = 1 WHERE employeeID =? ;
								SET FOREIGN_KEY_CHECKS = 1;
								COMMIT";
			$stmt = $mysqli -> prepare($query2);
			$stmt -> execute([$_SESSION["id"],$_SESSION["id"]]);
			$row["Current_TicketID"] = $row["Current_TicketID"]+1;

		}

	}


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
	<?php echoNav(); ?>
	<div class="row">
		 <div class="col-md-3  col-lg-4"></div>
		 <div class = "bill col-md-6 col-lg-4" >

    <header>
		    <img src="images/avatar.png" class="avatar">
        <?php
        echo "<p id='ticketID' style='display: none ;'>".$row["Current_TicketID"]."</p>";
        echo"<br>";
        echo "<p class='h1-text'>" .$row["First_Name"]." ".$row["Last_Name"]. "'s Bill</p>";
        echo "<p> Ticket ID ".$row["Current_TicketID"]."</p>";
        ?>
    </header>

    <ul class ="order-items">
      <?php
      $query2 = "SELECT * FROM Ticket NATURAL JOIN Service WHERE userID = ? AND ticketID =?";
      $stmt = $mysqli -> prepare($query2);
      $stmt -> execute([$_SESSION["id"],$row["Current_TicketID"]]);

      if($stmt->rowCount() > 0){
        while($row2 = $stmt->fetch(PDO::FETCH_ASSOC)){

          echo "<li class='order-item'>";

            echo "<div id='serviceID' style='display: none ;'>".$row2["serviceID"]."</div>";
						echo "<div id='price".$row2["serviceID"]."' style='display: none;'>".$row2["Service_Price"]."</div>";
            echo "<span class='item-name'>".$row2["Service_Name"]."</span>";
            echo"<span class='quantity'>";
              echo"<button id='dec".$row2["serviceID"]."'class='dec' onclick='decrementValue(".$row2["serviceID"].")' type='button' name='subtract'>-</button>";
              echo "<input id='quantity".$row2["serviceID"]."' type='number' value='".$row2["quantity"]."'/>";
              echo"<button class='inc' onclick='incrementValue(".$row2["serviceID"].")' type='button' name='add'>+</button>";
            echo"</span>";

            echo"<span class='price'>";
            echo"<span>$ ".$row2["Service_Price"]."</span>";

            echo"<button type='button' onclick='deleteService(".$row2["serviceID"].")' name='Delete'>X</button>";
          echo"</span>";
          echo"</li>";
        }

    }else{
      echo"You don't have any service on this ticket";
    }

      ?>
    </ul>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                 <h4 class="modal-title" id="myModalLabel">Adding Service</h4>

            </div>
            <div class="modal-body">

		<?php
		////////////////Printing service in add Item///////////////////////
		$query3 = "SELECT * FROM Service";
		$stmt = $mysqli -> prepare($query3);
		$stmt -> execute();
		if($stmt->rowCount() > 0){
			echo"<table class='table table-bordered'>";
					echo"<thead>";
							echo"<tr>";
							echo"<th>#</th>";
							echo"		<th>Service</th>";
							echo"<th>Price</th>";
							echo"<th>Action</th>";
							echo"</tr>";
					echo"</thead>";
					echo"<tbody>";
			while($row3 = $stmt->fetch(PDO::FETCH_ASSOC)){

				echo"<tr>";
				echo	"<th scope='row'>".$row3["serviceID"]."</th>";
				echo		"<td>".$row3["Service_Name"]."</td>";
				echo 	"<div id='price".$row3["serviceID"]."' style='display: none;'>".$row3["Service_Price"]."</div>";
				echo		"<td>$".$row3["Service_Price"]."</td>";
				echo		"<td><button id='".$row3["serviceID"]."' onclick='addService(".$row3["serviceID"].")'>Add</button></td>";
				echo "</tr>";

			}
			echo	"</tbody>";
		echo "</table>";
		}else{
			echo"There is no service option. Please contact an owner to add a new service";
		}
		////////////////////////////////////////////////////////////////////////////////
		?>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="window.location.reload()"> Save and Close</button>
            </div>
        </div>
    </div>
</div>
		<button id="btn-add"  name="add-item" data-toggle="modal" data-target="#myModal" data-backdrop="false">Add Item</button>

    <div class="order-summary">
        <p>Total<span id="total" class= "money" ><?php totalMoney($row["Current_TicketID"])?></p>
    </div>
<button type="submit" name="checkout" onclick="checkout()">Checkout</button>
<br>
<button type="submit" name="cancel" onclick="cancel()">Cancel</button>
</div>
<div class="col-md-3  col-lg-4"></div>
</div>
<?php
Database::dbDisconnect();
?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="script/Employee.js"></script>
</html>
