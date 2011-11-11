<?php
//include('../connect/db.php');
class utility  {
	
	function __construct() {
		 
		 
		 /* Info:[ 

			following are options for getRecordsDropDown();

 		 ] */
		 $this->table = '';
		 $this->field = '';
		 $this->name = '';
		 $this->multiple = false;
		 $this->css_class = "";
		 $this->style = "";
		 $this->attributes = "";
		 $this->null_option="";
		 $this->selected="";
		 $this->sortby = '';
		 $this->order='asc';
		 
		 
		 
		 
		 
		 /* Info:[ 

			 options for checkEmailExistance()
			 -following is an array of database tabels in which you wnat to check for email existance. keys are table name, and their 
			 values are emails fields
			 -for example: 
			 	$this->email['employees'] = 'e__email';
				
				
 		 ] */
		 $this->email = array();
		 
		 
		 
		 
		 
		 
		 
		 /* Info:[ 

			 options for getFCKeditor()
			 -options to put rich text editor for user input.
			 	
				
 		 ] */
		$this->fck_name = ''; 
		$this->fck_tool_bar_set = 'Basic';
		$this->fck_base_path = 'fckeditor/';
		$this->fck_value = '';
		
		
		
		
		
		/* Info:[ 

			 options for getCKeditor()
			 -options to put rich text editor for user input.
			 	
				
 		 ] */
		$this->ck_name = ''; 
		$this->ck_tool_bar_set = 'Basic';
		$this->ck_base_path = CKEDITOR_BASEPATH;
		$this->ck_value = '';
		
		
		
		
		
		
		 /* Info:[ 

			 options for getByValue()
			 -options to get by value from various database tables.
			 	
				
 		 ] */
		$this->by = ''; 
		$this->by_id= 'Basic';
		
		
		
		
		
		
		/* Info:[ 

		for NIH Dictionary Table
		getDictionaryList() & getMultiSelectDictionaryList()

		 ] */
		$this->dictionary_id = ''; 
		$this->dictionary_selected_option ="";
		
		
	}
	


	function getRecordsDropDown() {
	
		if(!isempty($this->table) && !isempty($this->field) && !isempty($this->name) ) {
			  
			   if($this->selected) {
						 
						$comma = '';
						$comma = @strpos($this->selected,','); 
					 
						if($comma) {
							 
							$selected = explode(',',$this->selected);
							
							foreach($selected as $k=>$v) {
								$selected[$k] = trim(strtolower($v));	
								 
							}
							 
							
						}
						else {
							 
							$x = $this->selected;
							$selected = array();
							$selected[] = trim(strtolower($x));
							 
							 
						}
				}else{
					$selected = array();	
				}
	 
		echo '<select name="'.$this->name.'" id="'.$this->name.'"';
		
		echo (!empty($this->css_class)) ? ' class="'.$this->css_class.'" ' : '';
		
		echo (!empty($this->style)) ? ' style="'.$this->style.' "' : '';
		
		echo ($this->multiple) ? ' multiple="multiple" ' : '';
		
		echo (!empty($this->attributes)) ? ' '.$this->attributes.' ' : '';
		
		echo ' > ';
		
		if(!isempty($this->null_option))
			
			echo '<option value="">'.$this->null_option.'</option>';
			
			
		$q = "select ".$this->field." from ".$this->table."; ";
		
		$r = mysql_query($q,$GLOBALS['DB']);
		
		if(mysql_num_rows($r)) 
			
			while($row = mysql_fetch_assoc($r)) {
			
					echo '<option value="'. $row[$this->field].'" ' ;
					
					
					 
						
							if(in_array(trim(strtolower($row[$this->field])), $selected))
								echo ' selected="selected" ';
						 
					
					echo ' >'. $row[$this->field].' </option>';
					echo "\n";
           	
			}//while
									
			echo  '</select>'; 
				
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	/* Info:[ 
		checkEmailExistance();
		this function is used in registration forms to check if give
		email already exists in database or not.
		
		@email : it is email which we want to check for existance
		@old_email (optional): it is old email which we want to update to new email (ie; $email).
		
		@returns: 	true, if email exists
					false, if doesn't exist


 	] */
	function checkEmailExistance($email, $old_email = '') {
				
				foreach($this->email as $table=>$field) {
						$q = "select $field from $table where $field = '$email' ";
						if($old_email) {
							$q.=" and $field <> '$old_email' ";	
						}
						$r = mysql_query($q);
						if(mysql_num_rows($r)) {
							return 1;
						}
				}
				return 0;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function getDictionaryList(){
		$id = $this->dictionary_id;
		$selOption = $this->dictionary_selected_option;
		
		$query = "select * from `dictionary` where `status` = '1' and `cat_id` = '$id' order by `sort_order` ASC";
		$res = mysql_query($query);
		$titles = "";	
		while($row = mysql_fetch_array($res)){	
			$titles .= "<option value=\"$row[title]\"";
			
			if(is_array($selOption)) {
				 
				$titles .= (in_array($row['title'],$selOption))? " selected = 'selected'>" : ">";
			
			} else {
				
				$titles .= ($row['title']=="$selOption")? " selected = 'selected'>" : ">";
				
			}
			$displayValue = (is_numeric($row['title']))?number_format($row['title'],0,'',','):$row['title'];
			$titles .= "$displayValue</option>";
		}	
		return $titles;	
	}
	
	
	
	
	
	
	
	//( ! ) Warning: mysql_fetch_array() expects parameter 1 to be resource, boolean given in D:\wampserver\www\notarynearyou-inhouse\2\app\classes\class.utility.php on line 269
	
	
	
	
	
	function getMultiSelectDictionaryList(){
		$id = $this->dictionary_id;
		$selOption = $this->dictionary_selected_option;
		
		$titles = "";
		$query = "SELECT * ";
		if($selOption!=''){
			$query .= ", IF(`title` IN ($selOption),'1','0') sel";
		}
		
		$query .=" FROM `dictionary` WHERE `status` = '1' AND `cat_id` = '$id' ORDER BY `sort_order` ASC";
		 
		//echo $query;
	 
		$res = @mysql_query($query);	
		 
		while($row = @mysql_fetch_array($res)){
		
			$titles .= "<option value=\"$row[title]\"";
			$titles .= ($row['sel']=='1')? " selected = 'selected'" : "";
			$titles .= ">$row[title]</option>\n";
		}
		
		return $titles;	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function getFCKeditor() {
		
 
                $oFCKeditor = new FCKeditor($this->fck_name) ;
                $oFCKeditor->ToolbarSet = $this->fck_tool_bar_set;
                $oFCKeditor->BasePath	= $this->fck_base_path;;
                $oFCKeditor->Value =   $this->fck_value;  
                $oFCKeditor->Create();	
	}
	
	
	
	
	
	
	
	
	
	function getCKeditor() {
		
 
               
				
				
				
				// Create a class instance.
				$CKEditor = new CKEditor();
				
				// Path to the CKEditor directory.
				$CKEditor->basePath = $this->ck_base_path;;
				
				
				$config['toolbar'] =  $this->ck_tool_bar_set;
				
				// Create an editor instance.
				$CKEditor->editor($this->ck_name, $this->ck_value,  $config);
	}
	
	
	
	
	
	
	
	
	
	function getByValue() {
				
				$man = new manager();
				$cli = new client();
				$emp = new employee();
				
				
				switch($this->by) {
					
					case 'manager':
						$updated_by = 'Manager: '.$man->_getField($this->by_id,'m__fname');
						break;
					
					case 'client':
						$name = $cli->_getField($this->by_id,'CONCAT(c__fname," ",c__lname)');
						$updated_by = "Client: ".((session::check_session('manager'))?"<a style='text-decoration:underline' href=manager_clients.php?action=details&id=".$this->by_id.">".$name."</a>" : $name);
						break;
					
					case 'employee':
						$name = $emp->_getField($this->by_id,'CONCAT(e__fname," ",e__lname)');
						$updated_by ="Employee: ".((session::check_session('manager')) ? "<a  style='text-decoration:underline' href=manager_employees.php?action=details&id=".$this->by_id.">".$name."</a>" : $name );
						break;
						
					case 'project':
						$pro = new project($this->by_id);
						$name = $pro->getName();
						$updated_by ="Project: ".((session::check_session('manager')) ? "<a  style='text-decoration:underline' href=manager_projects.php?action=details&id=".$this->by_id.">".$name."</a>" : $name );
						break;
				}
				
				return $updated_by;
	}
	
	
	
	
	
	
	
	
	
	
	
	


}


?>