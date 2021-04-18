<?php


	session_start();


	function message() {
		if (isset($_SESSION["message"])) {

			$output = "<div class='row'>";
			$output .= "<div data-alert class='alert-box info round'>";
			// Convert all applicable characters to HTML entities
			$output .= htmlentities($_SESSION["message"]);

			$output .= "</div>";
			$output .= "</div>";

			// clear message after use
			$_SESSION["message"] = null;

			return $output;
		}
		else {
			return null;
		}
	}

	function errors() {
		if (isset($_SESSION["errors"])) {
			$errors = $_SESSION["errors"];

			$_SESSION["errors"] = null;

			return $errors;
		}
	}
////////////////////////////////////User Autherization/////////////////////////////////////////////////////////////////

	function verify_login() {
		if(!isset($_SESSION["id"])&& $_SESSION["id"] === NULL) {
			$_SESSION["message"] = "You must login in first!";
			redirect("home.php");
			exit;
		}
	}

//////////////////////////////////Owner Verification/////////////////////////////////////////////////////////////////

function verify_owner() {
	if($_SESSION["userType"] !== "O") {
		$_SESSION["message"] = "You do not have access to this function";
		redirect("main.php");
		exit;
	}
}

?>
