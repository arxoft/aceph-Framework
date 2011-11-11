<?php

class user extends db_operations {
	
	function __construct($id = 0, $columns = '*') {
		
		/*
		 * The DB table this class will use
		 */
		$this->str_table = 'users';
		
		
		
		/*
		 * Make table calculations
		 */
		 $this->_getColumns();
		 
		 
		 /*
		  * 
		  * */
		if($id){
			$this->_executeDynamicQuery("select $columns from $this->str_table where $this->str_primary_key = $id;");
		}
		
		
	}
	
	
	function getSalutation($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_salutation', $pos, $not_found);
	}
	
	function getGivenName($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_given_name', $pos, $not_found);
	}
	
	function getFamilyName($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_family_name', $pos, $not_found);
	}
	
	function getAddress($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_address', $pos, $not_found);
	}
	function getZip($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_zip', $pos, $not_found);
	}
	
	function getCountry($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_country', $pos, $not_found);
	}
	
	function getCity($pos=0, $not_found = NOT_FOUND_TEXT){
		 
		return $this->_getRecordValue('users_city', $pos, $not_found);
	}
	
	function getYearOfBirth($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_year_of_birth', $pos, $not_found);
	}
	
	function getPhone($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_phone', $pos, $not_found);
	}
	
	function getMobile($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_mobile', $pos, $not_found);
	}
	
	function getSwissChampionShipNumber($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_swiss_number', $pos, $not_found);
	}
	
	function getDateRegistered($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_date_registered', $pos, $not_found);
	}
	
	function getMake($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_make', $pos, $not_found);
	}


	function getID($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_id', $pos, $not_found);
	}
	

	/*
	 * we better use join in query, instead of new one in this function.
	function getEmail($pos=0){
		$users_id = $this->getID($pos) ;
		 
		$obj_auth = new authenticate(AUTH_DB_TABLE,AUTH_USERNAME_FIELD,AUTH_PASSWORD_FIELD);
		$obj_auth -> _executeDynamicQuery("select email from authentications where users_id = $users_id"); 
		return $obj_auth->getEmail();
	}
	*/
	
	function getEmail($pos=0, $not_found = NOT_FOUND_TEXT){
		
		/*
		 * This function only works when users record
		 * has been fetched by joining it with authentications table
		 */
		return $this->_getRecordValue('email', $pos, $not_found);
	}
	
	function getMemo($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_memo', $pos, $not_found);
	}

	function getCategoryReserve($pos=0, $not_found = NOT_FOUND_TEXT){
		
		$int_categories_id = $this->getCategoryReserveID($pos);
		if($int_categories_id){
			$obj_category = new category();
			$obj_category -> _executeDynamicQuery("SELECT categories_name from categories where categories_id = $int_categories_id; ");
			return $obj_category->getCategoryName(0, $not_found);
		} else return $not_found;
		
		
	}

	function getCategoryReserveID($pos=0){
		
		return $this->_getRecordValue('users_categories_reserve', $pos, 0);
	}


	function getCategory($pos=0, $not_found = NOT_FOUND_TEXT){
		
		$int_categories_id = $this->getCategoryID($pos,0);
		 
		if($int_categories_id) {
			$obj_category = new category();
			$obj_category -> _executeDynamicQuery("select categories_name from categories where categories_id = $int_categories_id; ");
			return $obj_category->getCategoryName(0, $not_found);
		} else return $not_found;
		
	}


	function getCategoryID($pos=0){
		
		return $this->_getRecordValue('categories_id', $pos, 0);
	}

	function getVehicleRegistration($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_vehicle_registration', $pos, $not_found);
	}

	function getYearOfManufacture($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_year_of_manufacture', $pos, $not_found);
	}

	function getModel($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('users_model', $pos, $not_found);
	}
	
	
	/*
	 * @param csv_ids IDs of records to be deleted.
	 */
	function deleteUser($csv_ids){
		$q = "delete from users where users_id in ($csv_ids)";
		mysql_query($q);
		$q = "delete from authentications where users_id in ($csv_ids) ";
		mysql_query($q);
	}
}

?>