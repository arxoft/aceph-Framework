<?php

class country extends db_operations {
	
	function __construct($id = 0, $columns = '*') {
		
		/*
		 * The DB table this class will use
		 */
		$this->str_table = 'countries';
		
		
		
		/*
		 * Make table calculations
		 */
		 $this->_getColumns();
		
		
		if($id){
			$this->_executeDynamicQuery("select $columns from $this->str_table where $this->str_primary_key = $id;");
		}
		
		
	}
	
	
	function getCountryName($pos=0){
		
		return $this->_getRecordValue('countries_name', $pos);
	}
	

	
	
	function getDropDown($str_csv_selected = '') {
		
		$arr_selected = array();
		
		if($str_csv_selected) {
			$arr_selected = explode(',',strtolower($str_csv_selected));
		}
		
		$str_options = '';
		for($i=0; $i<$this->int_total; $i++) {
			$tmp_s = '';
			$tmp = $this->getCountryName($i);
			
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
								$tmp
							.'</option>'."\n";
		}
		return $str_options;
	}
}

?>