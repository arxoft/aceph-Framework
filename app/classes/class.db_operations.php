<?php
 

class db_operations {
	
	 
	
	function __construct($id='',$columns='*') {
		
		
		
		/* 
		 * To be defined in Inheriting Class.
		 * */
		$this->str_table = '';
		 
		
		
		
		
		
		
		//primary key of this table. leave it as it is if you want to get it detected automatically
		$this->str_primary_key = '';
		
		
		
		
		//array used to store detailed information on table columns
		$this->columns_detailed = array();
		
		
		
		
		//array used to store only column names as array keys. this array will be used to work with queries.
		//in this table, we have:
		//lists_id,list_class_id,value,sort_order 
		$this->columns = array();
		
		
		
		
		$this->str_query='';
		$this->rsc_result='';
		$this->int_total = 0;
		$this->arr_records=array();
		
		
		/*
		 * To be called in Inheriting Class
		 * below is automatic detection of all columns as well as primary key of table and bombard them into arrays.
		 * $this->_getColumns();
		 */
		
		
		
		
		//paging options
		//to use paging, classes/paging/class.paging.php file must be included
		$this->str_paging_url = '';
		$this->str_paging_params = '';
		$this->str_paging_links = '';
		$this->str_paging_records = 16;
		$this->str_paging_pages = 5;
		
		
		
		if($id) {
			$this->_getRecord($id,$columns);	
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	
	//magic functions (stating with _) which repeat in every class
	function _getColumns() {
			
		$this->columns=array();
		  
		$result = mysql_query("show tables like '".$this->str_table."'");
		if(!mysql_num_rows($result)) {
			return false;	
		}
		
		$this->columns = array();
		$result = mysql_query("SHOW COLUMNS FROM ".$this->str_table);
		if (!$result) {
			error(mysql_error());
			exit;
		}
		 
		if (mysql_num_rows($result) > 0) {
			$i = 0;
			while ($row = mysql_fetch_assoc($result)) {
				
					$this->columns_detailed[$i] = $row;
					$this->columns[$row['Field']] =  '';
					if($row['Key'] == 'PRI' && !$this->str_primary_key)  {
					
						$this->str_primary_key = $row['Field'];
						
						//initialize columns[str_primary_key] = 0
						$this->columns[$row['Field']] = 0;
						
						
					} //if
					
					$i++;
					
			}//while
			
		}//if
	
	}
	
	
	
	
	function _setQuery($str_query){
		
		$this->str_query = $str_query;
		
	}
	
	
	
	
	
	
	
	/*
	 * Same as this::_executeDynamicQuery();
	 * */
	function _executeSelectQuery() {
		$this->_executeDynamicQuery();
	}
	
	
	
	
	
	
	
	/*
	 * Dynamic Queries are the queries which return Data Sets
	 * and records as result.
	 * 
	 * Like: Select
	 */
	function _executeDynamicQuery($str_query = ''){
			
		if(DB_IS_CONNECTED){
				
				
			if($str_query){
				$this->str_query = $str_query;
			}
			
			
			/*
			 * Reset the Records.
			 * */
			$this->_reset();
			
			
			
			/*
			 * Check if there's pagination required 
			 * for Query results.
			 */
			if($this->str_paging_url) {
		 	 
						$PAGING=new PAGING($this->str_query, $this->str_paging_records, $this->str_paging_pages);
						$this->str_query=$PAGING->sql;
						$this->str_paging_links = $PAGING->show_paging($this->str_paging_url, $this->str_paging_params);
						 
		 	}
			
			if(!($this->rsc_result = mysql_query($this->str_query))) error('db_operations::_executeDynamicQuery - '.mysql_error().' - '.$this->str_query);
			
			$this->int_total = mysql_num_rows($this->rsc_result);
			
			if($this->int_total){
				
					$i = 0;
					while($w = mysql_fetch_assoc($this->rsc_result)) {
						
						//this loop takes each column and its value from table and saves it into columns array;	 
						foreach($w as $k=>$v) {
			
							$this->arr_records[$i][$k] = $w[$k];
							
						}	
						$i++;
					}
				
				
			}
		} else {
			
				error('db_operations::_executeDynamicQuery - Execution of Query, while DB not connected.');
			
		}
		
		
		
	}
	
	
	function _getRecord($id, $str_columns='*') {
		
		//get query result
		$q = 'select '.$str_columns.' from '.$this->str_table.' where '.$this->str_primary_key.' = '.$id.';';
		$this->_executeDynamicQuery($q);
		
	}
	
	
	
	
	
	
	
	
	
		
	function _getRecords($query = '', $ids = '',  $orderby='',$sort='', $clause_where='') {
		
		$q_where = array();
		
		if($clause_where) {
			
			$q_where[] = ' ('.$clause_where.') ';
			
		}  
		
		
		//check if we need to fetach all records or just a few one. 
		if(is_array($ids)) {
			
			$q_where[] = ' ('. $this->str_primary_key.' in ('.implode(',',$ids).')) ';
			
		} 
		
		
		if(count($q_where)) {
			$where = implode(' and ',$q_where);
		} else {
			$where = ''; 
		}
		
		
		//get query result
		$q = ($query) ? $query : 'select * from '.$this->table.' ';
		$q .= (($where) ? ' where '.$where : '' );
        
		if($orderby) {
				
				$q .= " order by $orderby $sort ";
		}
        
		
		$this->int_total = mysql_num_rows(mysql_query($q));
		
        //check if paging is required or not
    	if($this->str_paging_url) {
	 	 
				$PAGING=new PAGING($q, $this->str_paging_records, $this->str_paging_pages);
				$q=$PAGING->sql;
				$this->str_paging_links = $PAGING->show_paging($this->str_paging_url, $this->str_paging_params);
				 
	 	}
		 
		if(!($r = mysql_query($q))) error(mysql_error().' ~ '.$q);
		
		 
		//number of total records returned
		//$this->int_total = mysql_num_rows($r);
		 
		//if total > 0
		if($this->int_total) {
			
			$i = 0;
			while($w = mysql_fetch_assoc($r)) {
				
				//this loop takes each column and its value from table and saves it into columns array;	 
				foreach($w as $k=>$v) {
	
					$this->rec[$i][$k] = $w[$k];
					
				}	
				$i++;
			}
			
			return $this->rec;
				
			
		}
		
		return false;
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	function _delete($id) {
		
		//check if we are deleting more then one record, or just a single one.
		if(is_array($id)) {
			
			$where = ' where '. $this->str_primary_key.' in ('.implode(',',$id).') ';
			
		} else 
		
			$where = ' where '.$this->str_primary_key.' = '.$id;
			
			
		$q = 'delete from '.$this->table.$where;
		
		if(!mysql_query($q)) die(mysql_error());
		
	}
	
	
	
	
	
	
	
	
	
	
	function _save() {
		
		if($this->columns[$this->str_primary_key]) {
		//update
		
		 
			$q = "  UPDATE ".$this->table." SET ";
			
			foreach($this->columns as $k=>$v) {

	

				if($k!=$this->str_primary_key) 
					
						//concat every index except primery key
						
						if($v){
							$q .= $k." = '".mysql_real_escape_string($v)."', ";
						}
						

			

			}
			
			//remove comma at end of $q
			$q = substr($q,1,-2);

			
			$q .= " where ".$this->str_primary_key."=".$this->columns[$this->str_primary_key].";";

			if(mysql_query($q, $GLOBALS['DB'])) {

				return true;

			} else {

				error(mysql_error());

			}
		
		
		} else {
		//insert
		
					$q = "     insert into ".$this->table." (";
		
					foreach($this->columns as $k=>$v) {
		
						
		
						if($k!=$this->str_primary_key)
		
							$q.= $k.", ";
		
					
		
					}
		
					$q = substr($q,1,-2);
		
					$q .= ") values (";
		
					
		
					foreach($this->columns as $k=>$v) {
		
						
		
						if($k!=$this->str_primary_key)
		
							$q.= "'".mysql_real_escape_string($v, $GLOBALS['DB'])."', ";
		
					
		
					}
		
					$q = substr($q,1,-2);
		
					$q .=");";
		
				 
		
				  
		
					if (mysql_query($q, $GLOBALS['DB']))
		
					{
		
					 
		
						return  $this->columns[$this->str_primary_key]=mysql_insert_id();
		
						
		
					}
		
					else
		
					{
		
						error(mysql_error());
		
					}
		
				
		
				
		}
		
		
	}
	
	
	function _getField($id,$field) {
		
		$q = "select $field as field from ".$this->table." where ".$this->str_primary_key." = '$id'; ";
		if(!($r = mysql_query($q))) error(mysql_error().' ~ '.$q);
		if(mysql_num_rows($r)==1) {
			$row = mysql_fetch_assoc($r);
			return $row['field'];
		} else
			return 0;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function _getFieldValue($field, $pos = '', $not_found_text = '') {
 		
		
		$name = '';
		
		if(is_numeric($pos)) {
			$name = trim($this->rec[$pos][$field]);
		} else {
 
			$name = trim($this->columns[$field]);
		}
		if($name) return ($name); else return $not_found_text;
		
	}
	
	
	function _getRecordValue($field, $pos=0, $not_found_text = NOT_FOUND_TEXT){
		
		$str_value = trim($this->arr_records[$pos][$field]);
		if($str_value) return ($str_value); else return $not_found_text;
	}
	
	
	
	function _reset(){
		
		$this->columns = array();
		
		$this->rsc_result='';
		$this->int_total = 0;
		$this->arr_records=array();
		
		$this->_getColumns();
		
		
	}
	
	
	function _getTotal() {
		return $this->int_total;
	}
	
	
	
 
	
	
	
} //class ends

?>