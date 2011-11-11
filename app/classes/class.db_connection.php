<?php
/*
 * Class to control database connection.
 * Database details and constants are fetched 
 * from app/db/db.php
 * 
 */
class db_connection {
		private $str_host = DB_HOST;
		private $str_username = DB_USERNAME;
		private $str_password = DB_PASSWORD;
		private $str_database = DB_DATABASE;
		private $rsc_database_connection = '';
		private $bln_connected = false;
		private $str_error = '';
		
		
	function __construct($bln_connect = true){
	
		if($bln_connect){
			$this->connect();
		}
		
		
	}
	
	
	function verifyDBdetails() { 
		if($this->str_host && $this->str_database && $this->str_username ){
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	function connect() {
		if($this->verifyDBdetails()){
			if(!$this->rsc_database_connection = mysql_connect($this->str_host, $this->str_username, $this->str_password)) {
				$this->str_error = 'Error: Unable to connect to database server.';
				$this->bln_connected = false;
				
			} else {
				if (!mysql_select_db($this->str_database, $this->rsc_database_connection)) {
					$this->str_error = 'Error: Unable to select database.';
					$this->bln_connected = false;
				} else {
					$this->bln_connected = true;
				}
			}
			
			
		} else {
			$this->str_error = 'Error: No or Missing database connection details.';
			$this->bln_connected = false;
			
		}
		
	}
	
	
	
	function isDBconnected() {
		
		return $this->bln_connected;
	}
	
	
	function getDBconnection() {
		return $this->rsc_database_connection;
	}
	
	
}


?>