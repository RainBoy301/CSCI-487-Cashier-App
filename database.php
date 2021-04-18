<?php

require_once('/home/bqhoang/DBHoang.php');

class Database {

	private static $mysqli = null;

	public function __construct() {
		die('Init function error');
	}

	public static function dbConnect() {
		//try connecting to your database

		try {
			$mysqli = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME,USERNAME,PASSWORD);
		
		} catch ( PDOEXCEPTION $Exception ) {
			echo "Could not Connect";
		}

		//catch a potential error, if unable to connect


		return $mysqli;
	}

	public static function dbDisconnect() {
		$mysqli = null;
	}
}
?>
