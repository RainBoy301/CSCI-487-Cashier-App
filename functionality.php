
<?php

require_once("session.php");
require_once("functions.php");
require_once("database.php");
verify_login();

$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//////////////////function on ticket/////////////////////////////////////////////////////
if (isset($_GET['serviceID'])&&isset($_GET['ticketID'])&&isset($_GET['price'])&&isset($_GET['action'])){



  if ($_GET['action']=="add"){
    $query ="SELECT * FROM Ticket WHERE userID =? and TicketID =? and ServiceID =? ";
    $stmt = $mysqli -> prepare($query);
    $stmt->execute([$_SESSION['id'],$_GET['ticketID'],$_GET['serviceID']]);

                    //// If Item is on the ticket, then increase quantity/////
    if($stmt->rowCount() > 0){
      $query = "BEGIN;
                UPDATE Ticket SET quantity =quantity+1 WHERE userID =? and ticketID =? and serviceID =?;
                UPDATE Ticket SET total = quantity*? WHERE userID =? and ticketID =? and serviceID =?;
                COMMIT";

    }else{

      $query ="INSERT INTO Ticket (userID, TicketID, serviceID, quantity, total) VALUES (?,?,?,1,?) ";

      $stmt = $mysqli -> prepare($query);

      $stmt->execute([$_SESSION['id'],$_GET['ticketID'],$_GET['serviceID'],$_GET['price']]);

      exit("we are done");
    }
  }
  ///////////////////////////Increase or decrease quantity////////////////////
          ///Increase////
  if($_GET['action']=="increase"){
    $query = "BEGIN;
              UPDATE Ticket SET quantity =quantity+1 WHERE userID =? and ticketID =? and serviceID =?;
              UPDATE Ticket SET total = quantity*? WHERE userID =? and ticketID =? and serviceID =?;
              COMMIT";
  }
        ///Decrease////
    if($_GET['action']=="decrease"){
    $query = "BEGIN;
              UPDATE Ticket SET quantity =quantity-1 WHERE userID =? and ticketID =? and serviceID =?;
              UPDATE Ticket SET total = quantity*? WHERE userID =? and ticketID =? and serviceID =?;
              COMMIT";
  }
  $stmt = $mysqli -> prepare($query);
  $stmt->execute([$_SESSION['id'],$_GET['ticketID'],$_GET['serviceID'],$_GET['price'],$_SESSION['id'],$_GET['ticketID'],$_GET['serviceID']]);

    //////Calling function to update Total on ticket/////////////////
  totalMoney($_GET['ticketID']);
}

//////////////////////////////////////DELETE SERVICE////////////////////////////
  if (isset($_GET['serviceID'])&&isset($_GET['ticketID'])&&isset($_GET['action'])){
    if ($_GET['action']=="delete"){
    $query = "BEGIN;
              SET FOREIGN_KEY_CHECKS = 0;
              delete from Ticket Where userID =? and TicketID =? and ServiceID =?;
              SET FOREIGN_KEY_CHECKS = 1;
              COMMIT";

  $stmt = $mysqli -> prepare($query);
  $stmt->execute([$_SESSION['id'],$_GET['ticketID'],$_GET['serviceID']]);


        redirect("test.php");

  }
  }

//////////////////////////////////////Checkout TICKET ////////////////////////////
  if (isset($_GET['total'])){
    if(empty($_GET['total'])){
          $_SESSION["message"] ="There was no service on the ticket";
          redirect("main.php");
    }
            ////Owner////
    if($_SESSION['userType']=='O'){
      $query = "BEGIN;
                UPDATE Owner SET Unchecked_Ticket = 0 WHERE ownerID =?;
                UPDATE Owner SET Money_Collected = Round((Money_Collected +?),2) WHERE ownerID =?;
                COMMIT";
    }
        ////Employee//////
    else{
      $query = "BEGIN;
                UPDATE Employee SET Unchecked_Ticket = 0 WHERE employeeID =?;
                UPDATE Employee SET Money_Collected = Round((Money_Collected +?),2) WHERE employeeID =?;
                COMMIT";
    }
      $stmt = $mysqli -> prepare($query);
      $stmt -> execute([$_SESSION["id"],$_GET["total"],$_SESSION["id"]]);
      if($stmt){
        $_SESSION["message"] = "Ticket check out";
        redirect("main.php");
      }else{
        $_SESSION["message"] ="Ticket did not check out";
      }
  }
////////////////////////////DELETE Ticket///////////////////////////////////
  if (isset($_GET['cancel'])&&isset($_GET['ticketID'])){
        ////Owner////
    if($_SESSION['userType']=='O'){
      $query ="Begin;
            SET FOREIGN_KEY_CHECKS = 0;
            delete from Ticket Where userID =? and TicketID =? ;
            UPDATE Owner SET Unchecked_Ticket = 0 WHERE ownerID =?;
            UPDATE Owner SET Current_TicketID = Current_TicketID-1 WHERE ownerID =?;
            SET FOREIGN_KEY_CHECKS = 1;
          COMMIT";

    }
    //////Employee////
    else{
      $query ="Begin;
            SET FOREIGN_KEY_CHECKS = 0;
            delete from Ticket Where userID =? and TicketID =? ;
            UPDATE Employee SET Unchecked_Ticket = 0 WHERE employeeID =?;
            UPDATE Employee SET Current_TicketID = Current_TicketID-1 WHERE employeeID =?;
            SET FOREIGN_KEY_CHECKS = 1;
          COMMIT";

    }


    $stmt = $mysqli -> prepare($query);
    $stmt -> execute([$_SESSION["id"],$_GET["ticketID"],$_SESSION["id"],$_SESSION["id"]]);
    if($stmt){
    $_SESSION["message"] = "Ticket cancel";
      redirect("main.php");
    }else{
      $_SESSION["message"] = "Ticket did not cancel";
    }
  }

?>
