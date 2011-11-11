<?php

class category extends db_operations {
	
	function __construct($id = 0, $columns = '*') {
		
		/*
		 * The DB table this class will use
		 */
		$this->str_table = 'categories';
		
		
		
		/*
		 * Make table calculations
		 */
		 $this->_getColumns();
		 
		 
		 if($id){
			$this->_executeDynamicQuery("select $columns from $this->str_table where $this->str_primary_key = $id;");
		}
		
		
		
	}
	
	
	function getCategoryName($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_name', $pos, $not_found);
	}
	
	function getCategoryID($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_id', $pos,0, $not_found);
	}
	

	function getPlacesAvailable($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_places_available', $pos, $not_found);
	}
	
	function getDate($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_date', $pos, $not_found);
	}
	
	function getStartTime($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_start_time', $pos, $not_found);
	}
	
	function getStartDay($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_start_day', $pos, $not_found);
	}
	
	function getMaxPlaces($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_max_places', $pos, $not_found);
	}
	
	function getMinPlaces($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_min_places', $pos, $not_found);
	}
	
	function getAlreadyRegistered($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_already_registered', $pos, $not_found);
	}
	
	function getReservePlace($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_reserve_place', $pos, $not_found);
	}
	
	function getTime($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_start_time', $pos, $not_found);
	}
	
	function getTextA($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_text_a', $pos, $not_found);
	}
	
	function getTextB($pos=0, $not_found = NOT_FOUND_TEXT){
		
		return $this->_getRecordValue('categories_text_b', $pos, $not_found);
	}
	
	
	
	function getDropDown($str_csv_selected = '') {
		
		$arr_selected = array();
		
		if($str_csv_selected) {
			$arr_selected = explode(',',strtolower($str_csv_selected));
		}
		
		$str_options = '';
		for($i=0; $i<$this->int_total; $i++) {
			$tmp_s = '';
			$tmp = $this->getCategoryID($i);
			$tmp_n = $this->getCategoryName($i);
			
			if(in_array(strtolower($tmp),$arr_selected)) {
				$tmp_s = 'selected="selected"';	
			} else {
				$tmp_s = '';	
			}
			
			$str_options .= '<option value="'.
								$tmp
								.'" '.
								$tmp_s
								.' >'.
								$tmp_n
							.'</option>'."\n";
		}
		return $str_options;
	}


	function deleteCategory($pos=0){
		$categories_id = $this->getCategoryID($pos);
		mysql_query("delete from categories where categories_id = '$categories_id'");
	}

	function decrementPlacesAvailable($pos=0, $decrement = 1) {
		
		$int_category_id = $this->getCategoryID($pos);
		mysql_query("update categories set categories_places_available = categories_places_available - $decrement where categories_id = '".$int_category_id."'; ");
	}
	
	
	function incrementPlacesAvailable($pos=0, $increment = 1) {
		$int_category_id = $this->getCategoryID($pos);
		mysql_query("update categories set categories_places_available = categories_places_available + $increment where categories_id = '".$int_category_id."'; ");
	}
	
	
	function decrementAlreadyRegistered($pos=0, $decrement = 1) {
		
		$int_category_id = $this->getCategoryID($pos);
		mysql_query("update categories set categories_already_registered  = categories_already_registered  - $decrement where categories_id = '".$int_category_id."'; ");
	}
	
	
	function incrementAlreadyRegistered($pos=0, $increment = 1) {
		$int_category_id = $this->getCategoryID($pos);
		mysql_query("update categories set categories_already_registered  = categories_already_registered  + $increment where categories_id = '".$int_category_id."'; ");
	}
}

?>