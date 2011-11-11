<?php

class authenticate extends db_operations {
	
	private $str_username_field; 
	private $str_password_field;
	
	function __construct($str_table, $str_username_field, $str_password_field) {
		
		 
		
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
			//do nothing
		} else {
			$this->setSessionDefaults();
		}
		
		
		
		if(!DB_IS_CONNECTED){
			error('Class authenticate initialized while there is no DB.');
		}
		
		$this->str_table = $str_table;
		$this->str_username_field = $str_username_field;
		$this->str_password_field = $str_password_field;
		
		 
		
		$this->_getColumns();
		
	}
	
	
	function login($str_username, $str_password){
		/*
		 * Encrypt the password
		 */
		$str_password = $this->encrypt($str_password);
		
		 
		$this->_setQuery( "select id, users_id, type from {$this->str_table} where {$this->str_username_field}='{$str_username}' and {$this->str_password_field} = '{$str_password}' ");
		 
		$this->_executeDynamicQuery();
		if($this->_getTotal()){
			$int_auth_id = $this->getAuthID();
			$int_users_id = $this->getUsersID();
			$int_type = $this->getType();
			$this->setSession($int_auth_id, $int_users_id, $int_type);
			return $int_users_id;
			
		}else {
			$this->setSessionDefaults();	
			return 0;
		}
		
	}
	
	
	function setSession($int_auth_id, $int_users_id, $int_type){
		$_SESSION['logged_in'] = true;
		$_SESSION['logged_users_id'] = $int_users_id;
		$_SESSION['logged_auth_id'] = $int_auth_id;
		$_SESSION['logged_type'] = $int_type;
	}
	
	
	
	function getAuthID($pos=0){
		
		return $this->_getRecordValue('id', $pos);
	}
	function getType($pos=0){
		
		return $this->_getRecordValue('type', $pos);
	}
	
	
	function getUsersID($pos=0){
		
		return $this->_getRecordValue('users_id', $pos);
	}
	
	function getEmail($pos=0){
		
		return $this->_getRecordValue('email', $pos);
	}
	
	function encrypt($str){
		return md5($str);
	}
	
	
	function checkSession($loggedInUserID=0) {
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
			if(!$loggedInUserID){
				return true;
			} else {
				if($_SESSION['logged_type'] == $loggedInUserID) {
					return true;
				} else {
					return false;
				}
			}
		} else return false; 
		
		
		
	}
	
	
	function alreadyExists($str_value) {
		$str_query = "select count(*) as total from ". $this->str_table ."  where ". $this->str_username_field ." = '". mres($str_value) ."'; ";
		 
		$rsc_result = mysql_query($str_query);
		$arr_row = mysql_fetch_assoc($rsc_result);
		if($arr_row['total']>0) return true; 
		else return false;
			
	}
	function setSessionDefaults() {
			$_SESSION['logged_in'] = false;
			$_SESSION['logged_users_id'] = 0;
			$_SESSION['logged_auth_id'] = 0;
			$_SESSION['logged_type'] = 0;	
	}
	
	
	function logout(){
		$this->setSessionDefaults();
	}
	
}

?>