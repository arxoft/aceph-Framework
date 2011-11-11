<?php

class sample extends db_operations {
	
	function __construct() {
		
		/*
		 * The DB table this class will use
		 */
		$this->str_table = 'sample';
		
		
		
		/*
		 * Make table calculations
		 */
		 $this->_getColumns();
		
		
		
	}
	
	
	function getUsername($pos=0){
		
		return $this->_getRecordValue('username', $pos);
	}
	
	function getPassword($pos=0){
		
		return $this->_getRecordValue('password', $pos);
	}
}

?>