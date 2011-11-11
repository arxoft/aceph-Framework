<?php
 

class email {

	function __construct($id='') {
		
		
		
		//Enter the name of table this class is made for
		$this->table = 'users';
		
		
		
		
		//primary key of this table. leave it as it is if you want to get it detected automatically
		$this->primary_key = '';
		
		
		
		
		//array used to store detailed information on table columns
		$this->columns_detailed = array();
		
		
		
		
		//array used to store only column names as array keys. this array will be used to work with queries.
		//in this table, we have:
		//lists_id,list_class_id,value,sort_order 
		$this->columns = array();
		
		
		
		
		//holds the total number of rows returned as result of a query
		$this->total = 0;
		
		
		
		//below is automatic detection of all columns as well as primary key of table and bombard them into arrays.
		$this->_getColumns();
		
		
		
		
		//paging options
		//to use paging, classes/paging/class.paging.php file must be included
		$this->paging_url = '';
		$this->paging_params = '';
		$this->paging_links = '';
		$this->paging_records = 16;
		$this->paging_pages = 5;
		
		
		
		if($id) {
			$this->_getRecord($id);	
		}
		
		
		
		
		
		
		
		$this->to = $this->from = '';
		
		
	}
	
	
	
	
	
	
	
	
	
	
	//magic functions (stating with _) which repeat in every class
	function _getColumns() {
		$this->columns = array();
		$result = mysql_query("SHOW COLUMNS FROM ".$this->table);
		if (!$result) {
			error(mysql_error());
			exit;
		}
		
		if (mysql_num_rows($result) > 0) {
			$i = 0;
			while ($row = mysql_fetch_assoc($result)) {
				
					$this->columns_detailed[$i] = $row;
					$this->columns[$row['Field']] =  '';
					if($row['Key'] == 'PRI' && !$this->primary_key)  {
					
						$this->primary_key = $row['Field'];
						
						//initialize columns[primary_key] = 0
						$this->columns[$row['Field']] = 0;
						
						
					} //if
					
					$i++;
					
			}//while
			
		}//if
	
	}
	
	
	
	
	
	
	
	
	
	
	function _getRecord($id) {
		
		//get query result
		$q = 'select * from '.$this->table.' where '.$this->primary_key.' = '.$id.';';
		if(!($r = mysql_query($q))) error(mysql_error().' ~ '.$q);
		 
		//number of total records returned
		$this->total = mysql_num_rows($r);
		 
		//if total > 0
		if($this->total) {
		
			while($w = mysql_fetch_assoc($r)) {
				
				//this loop takes each column and its value from table and saves it into columns array;	 
				foreach($this->columns as $k=>$v) {
	
					$this->columns[$k] = $w[$k];
					
				}
				
			}
			
			return $this->columns;
				
			
		}
		
		return false;
		
		
	}
	
	
	
	
	
	
	
	
	
		
	function _getRecords($query = '', $ids = '',  $orderby='',$sort='', $clause_where='') {
		
		$q_where = array();
		
		if($clause_where) {
			
			$q_where[] = ' ('.$clause_where.') ';
			
		}  
		
		
		//check if we need to fetach all records or just a few one. 
		if(is_array($ids)) {
			
			$q_where[] = ' ('. $this->primary_key.' in ('.implode(',',$ids).')) ';
			
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
        
		
		$this->total = mysql_num_rows(mysql_query($q));
		
        //check if paging is required or not
    	if($this->paging_url) {
	 	 
				$PAGING=new PAGING($q, $this->paging_records, $this->paging_pages);
				$q=$PAGING->sql;
				$this->paging_links = $PAGING->show_paging($this->paging_url, $this->paging_params);
				 
	 	}
		 
		if(!($r = mysql_query($q))) error(mysql_error().' ~ '.$q);
		
		 
		//number of total records returned
		//$this->total = mysql_num_rows($r);
		 
		//if total > 0
		if($this->total) {
			
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
			
			$where = ' where '. $this->primary_key.' in ('.implode(',',$id).') ';
			
		} else 
		
			$where = ' where '.$this->primary_key.' = '.$id;
			
			
		$q = 'delete from '.$this->table.$where;
		
		if(!mysql_query($q)) die(mysql_error());
		
	}
	
	
	
	
	
	
	
	
	
	
	function _save() {
		
		if($this->columns[$this->primary_key]) {
		//update
		
		 
			$q = "  UPDATE ".$this->table." SET ";
			
			foreach($this->columns as $k=>$v) {

	

				if($k!=$this->primary_key) 
					
						//concat every index except primery key
						$q .= $k." = '".mysql_real_escape_string($v, $GLOBALS['DB'])."', ";

			

			}
			
			//remove comma at end of $q
			$q = substr($q,1,-2);

			
			$q .= " where ".$this->primary_key."=".$this->columns[$this->primary_key].";";

			if(mysql_query($q, $GLOBALS['DB'])) {

				return true;

			} else {

				error(mysql_error());

			}
		
		
		} else {
		//insert
		
					$q = "     insert into ".$this->table." (";
		
					foreach($this->columns as $k=>$v) {
		
						
		
						if($k!=$this->primary_key)
		
							$q.= $k.", ";
		
					
		
					}
		
					$q = substr($q,1,-2);
		
					$q .= ") values (";
		
					
		
					foreach($this->columns as $k=>$v) {
		
						
		
						if($k!=$this->primary_key)
		
							$q.= "'".mysql_real_escape_string($v, $GLOBALS['DB'])."', ";
		
					
		
					}
		
					$q = substr($q,1,-2);
		
					$q .=");";
		
				 
		
				  
		
					if (mysql_query($q, $GLOBALS['DB']))
		
					{
		
					 
		
						return  $this->columns[$this->primary_key]=mysql_insert_id();
		
						
		
					}
		
					else
		
					{
		
						error(mysql_error());
		
					}
		
				
		
				
		}
		
		
	}
	
	
	function _getField($id,$field) {
		
		$q = "select $field as field from ".$this->table." where ".$this->primary_key." = '$id'; ";
		if(!($r = mysql_query($q))) error(mysql_error().' ~ '.$q);
		if(mysql_num_rows($r)==1) {
			$row = mysql_fetch_assoc($r);
			return $row['field'];
		} else
			return 0;
	}
	
	
	
	
	
	 
	
	
	
	
	
	
	
	
	 
		
		
	function sendMail($to,$toName,$from,$fromName,$subject,$message) {
				
				if(strpos($from,',')) {
					$from_emails = explode(',',$from);
					$from = trim($from_emails[0]);
				}
				
				
				$from = "$fromName <$from>";
				
				$headers  = "From: $from\r\n";
				$headers .= "Content-type: text/html\r\n";
			
				//options to send to cc+bcc
				//$headers .= "Cc: [email]maa@p-i-s.cXom[/email]";
				//$headers .= "Bcc: [email]email@maaking.cXom[/email]";
				 
				if(PKG_KEEP_EMAIL_HISTORY) $this->addToHistory($to,$toName,$from,$fromName,$subject,$message);
				// now lets send the email.
				mail($to, $subject, $message, $headers);
	
	}
		
		
		
		
		function addToHistory($to, $toName, $from, $fromName, $subject, $message) {
			$q = "insert into email_history values (NULL, '".mres($subject)."', '".mres($to)."', '".mres($from)."', '".mres($toName)."', '".mres($fromName)."', NOW(), '".mres($message)."');";
			if(!mysql_query($q)) dbError($q);
		}
		
		
		
		
		
		
		
		
		
		
		
		function getEmails($for,$id, $return_array = false) {
			switch($for) {
				case 'user'	:
								$q = "select email_1, email_2 from user_signup where id = '$id' ";
								$r = mysql_query($q);
								$all_emails = array();
								while($row = mysql_fetch_assoc($r)) {
									
									if(trim($row['email_1']))
										$all_emails[]  = $row['email_1'];
									if(trim($row['email_2']))
										$all_emails[]  = $row['email_2'];
								}
								if(!$return_array)
									return implode(', ',$all_emails);
								else
									return $all_emails;
							break;
				
				
				case 'workorder_client':
								$q = "select client_id from work_order where id = '$id' ";
								$r = mysql_fetch_assoc(mysql_query($q));
								$cid = $r['client_id'];
								
								
								$profile_emails = $this->getEmails('user',$cid,$return_array);
								
								$all_emails = array();
								if(is_array($profile_emails)) {
									foreach($profile_emails as $email) {
										$all_emails[] = $email;
									}
								} else {
									$all_emails[] = $profile_emails;
								
								}
								
								$q = "select * from user_signup_additional_contacts where id in (select add_contact_id from work_orders_2_additional_contacts where wo_id = '$id' and cc = '1') ";
								$r = mysql_query($q);
								while($row = mysql_fetch_assoc($r)) {
									if(trim($row['email1']))
										$all_emails[] = $row['email1'];
										
									if(trim($row['email2']))
										$all_emails[] = $row['email2'];
								}
								
								if(!$return_array)
									return implode(', ',$all_emails);
								else
									return $all_emails;
				
				break;
				
				
				
				case 'super':
								$q = "select email_1, email_2 from user_signup where account_id = '3' ";
								$r = mysql_query($q);
								$all_emails = array();
								while($row = mysql_fetch_assoc($r)) {
									if(trim($row['email_1']))
										$all_emails[] = $row['email_1'];
										
									if(trim($row['email_2']))
										$all_emails[] = $row['email_2'];
								}
								if(!$return_array)
									return implode(', ',$all_emails);
								else
									return $all_emails;
				break;
				
				
				case 'assigned_notary':
								$q = "select notary_id from work_order where id = '$id' ";
								$r = mysql_fetch_assoc(mysql_query($q));
								$nid = $r['notary_id'];
								 
								
								if($nid)
									return $this->getEmails('user',$nid, $return_array);
				
				break;
				
				
				
				case 'admin':
								$q = "select email_1, email_2 from user_signup where account_id = '4' ";
								$r = mysql_query($q);
								$all_emails = array();
								while($row = mysql_fetch_assoc($r)) {
									if(trim($row['email_1']))
										$all_emails[] = $row['email_1'];
										
									if(trim($row['email_2']))
										$all_emails[] = $row['email_2'];
								}
								if(!$return_array)
									return implode(', ',$all_emails);
								else
									return $all_emails;
				
				break;
				
				
				
				  
				
				
				
			}
		}
	
	
	
	
	
	
	
	
	
	
	function prepareContent($keys,$template) {
			
			foreach($keys as $k=>$v) {
					  $template = str_replace($k, $v, $template);
			}
					 
			return $template;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function clearHistory() {
			$q = "delete from email_history where date < '".date('Y-m-d',strtotime('-30 days'))." 00:00:00' ";	
			if(!mysql_query($q)) dbError($q);															 
	}
	
	
	
	
	
	
	 
	function sendRegisterEmailToUser($str_email, $str_password) {
		
		 
		
		 
	 
		
		
		//prepare data
		$message = 
				"Hello."."\n\n".
				
				"Thank you for registering at ".URL."\n".
				"Following are your login details."."\n".
				
				"Email: ".$str_email."\n".
				"Password: ".$str_password."\n\n".
				
				"You can log in back to ".URL." if you want to change any details.";
		 
		
		
		 
		
		//set email 
		$to = $str_email;
		$subject = 'Thank you for registering at '.URL;
		
		mail($to,$subject,$message);
		
		
		
	}
	
	
	
	
	function sendRegisterEmailToAdmin($int_users_id) {
		
		 
		
		 
	 
		
		
		//prepare data
		$message = 
				"Hello."."\n\n".
				
				"A new user has registered at ".URL."\n\n".
				
				"Following is his/her profile link. "."\n".
				URL."user.php?action=details&id=".$int_users_id."\n\n";
		 
		
		
		 
		 
		//set email 
		$to = EMAIL_ADMIN;
		$subject = 'New registration at '.URL;
		
		@mail($to,$subject,$message);
		
		
		
	}
	
	
	

	
	
	
	function makeInfoContent($info, $mid_break = ':', $break = '<br/><br />') {
		$text = '';
		foreach($info as $k=>$v) {
			if(!$v) {
				$text .= "$break <strong><u>$k</u></strong>$mid_break $break \n";
			} else {
				$text .= ((trim($k)) ? "<strong>$k</strong>$mid_break " : "" )."$v $break \n";
			}
		}
		return valueOrDash($text);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function getEmailTemplate($code){
		$r = '';
		$query = "SELECT * FROM `email_template` WHERE `code`='".$code."'";
		$res = @mysql_query($query);
		
		if(!empty($res)){
			$r = mysql_fetch_object($res);
		}
		if(PKG_USE_PLAIN_TEXT_EMAILS) {
			$r->description = $r->description_plain_text;
		}
		return $r;	
	}
	
	
	
	
	
	
	
	
	
	 
	function sendNotaryUpdateAlertToSuper($id) {
		
		$code = 'GENERAL_EMAIL_TEMPLATE';
		$notary = new user($id);
		
		if($tmp=$notary->getCreatedBy('',0)) {
			$admin = new user($tmp);
		} else {
			$admin = new user(OWNER_USERID);
		}
		
		$super = new user(SUPER_USERID);
		 
	 	
		
		
		//prepare data
		$intro = "Your customer <a href='".HOST."'>".PKG_TITLE."</a> has updated a Notary. Following are the details of Notary Account.";
		$exit = " ";
		
		
		$info = array();
		$info['Personal Information'] = '';
		$info['Full Name'] = $notary->getName();
		$info['Date of Birth'] = $notary->getDOB();
        	$info['Login Username'] = $notary->getUsername();
        	
        	$info['Contact Information'] = '';
		$info['Phone'] = $notary->getPhone();
		$info['Mobile'] = $notary->getMobile();
		$info['Fax'] = $notary->getFax();
		$info['Email(s)'] = $notary->getEmails();
		
		$info['Mailing Address'] = '';
		$info['Address'] = $notary->getAddress();		
		$info['City'] = $notary->getCity();
		$info['State'] = $notary->getState();
		$info['Zipcode'] = $notary->getZipcode();
		
		$info['Document Shipping Address'] = '';
		$info['Address '] = 	$notary->getAddressShipping();			
		$info['City '] = $notary->getCityShipping(); 			
		$info['State '] = $notary->getStateShipping(); 	
		$info['Zipcode '] = $notary->getZipcodeShipping();  
		
		
		
		$info['Saturday Delivery Address'] = '';
		$info[' Address'] = $notary->getAddressSaturdayDelivery();				
		$info[' City'] = $notary->getCitySaturdayDelivery();			
		$info[' State'] = $notary->getStateSaturdayDelivery();	
		$info[' Zipcode'] = $notary->getZipcodeSaturdayDelivery(); 
		
		
		$info['General Information'] = '';
		$info['Languages Spoken'] = $notary->getLanguagesSpoken();	
		$info['Availability'] = $notary->getAvailabiliy(); 
		$info['Willing to travel (Max. Miles)'] = $notary->getTravelMiles(); 
		$info['Overnight Charge'] = $notary->getOvernightCharge(); 
		$info['Edoc Charge'] = $notary->getEdocCharge(); 
		$info['Esign'] = $notary->getEsign();
		$info['Notary Commission Name'] = $notary->getNotaryCommissionName(); 
		$info['Notary Commission Number'] = $notary->getNotaryCommissionNumber(); 
		$info['Copy of Certificate'] = $notary->getCertificate(); 
		$info['Notary Commission Expiration'] = $notary->getNotaryCommissionExpiration();
		$info['Multiple States'] = $notary->getMultipleStates();
		$info['Errors & Omission'] = $notary->getErrorAndOmissionAmount();		
		$info['Errors & Omissions Certificate'] = $notary->getErrorsAndOmissionsCertificate(); 
		$info['How many years as a notary?'] = $notary->getYearsAsNotary(); 
		$info['Associations and Certifications'] = $notary->getAssociationsAndCertifications();
		$info['Background Check'] = $notary->getBackgroundCheckFile(); 
		$info['Bond Certificate'] = $notary->getBondCertificate(); 
		$info['Signed W-9 Form'] = $notary->getSignedW9form();
		$info['Check payable to'] = $notary->getCheckPayableTo();
		$info['Have Dural Tray Printer?'] = $notary->getDualTrayPrinter();	
		$info['Have Scanner?'] = $notary->getScanner();	
		$info['How did you hear about us?'] = $notary->getHowFound();
		
		
		
		
		
		
		
		
		
		
		 
		//use data in place holders
		$keys['%%FULL_NAME%%'] = $super->getName();
		$keys['%%LINK%%'] = HOST;
		$keys['%%LOGO%%'] = HOST.'images/pkg/'.PKG_LOGO;
		$keys['%%INTRO%%'] = $intro;
		$keys['%%INFO%%'] = $this->makeInfoContent($info);
		$keys['%%EXIT%%'] = $exit;
		$keys['%%SIGNATURE%%'] = $super->getSignature();
		
		  
		
		
		//fetch & compile message
		$template = $this->getEmailTemplate($code);
		$template = $template->description;
		$message  = $this->prepareContent($keys,$template);
		
		
		  
		
		
		//set email 
		$to = $this->getEmails('user',SUPER_USERID,true);;
		$to_name = $super->getName();
		$from = $admin->getEmails();
		$from_name = $admin->getCompany();
		$subject = 'Notary Account Updated';
		
		 
		foreach($to as $e_mail) {
		  if($e_mail) { 
		  		/*echo "<h1>$e_mail</h1>
				      <h2>$from_name < $from ></h2>
				  
				  ".$message;		  	
				*/
				$this->sendMail($e_mail, $to_name , $from, $from_name , $subject, $message);
		  }
		}
		
		
		
	}
	function sendAccountCreateAlertToNotary($id, $password) {
		
		$code = 'GENERAL_EMAIL_TEMPLATE';
		$notary = new user($id);
		$admin = new user($notary->getCreatedBy());
		 
	 	
		
		
		//prepare data
		$intro = "You have been registered at <a href='".HOST."'>".PKG_TITLE."</a>.  Following are your account details.";
		$exit = " ";
		
		
		$info = array();
		$info['Personal Information'] = '';
		$info['Full Name'] = $notary->getName();
		$info['Date of Birth'] = $notary->getDOB();
        	$info['Login Username'] = $notary->getUsername();
        	$info['Login Password'] = $password;
        	
        	$info['Contact Information'] = '';
		$info['Phone'] = $notary->getPhone();
		$info['Mobile'] = $notary->getMobile();
		$info['Fax'] = $notary->getFax();
		$info['Email(s)'] = $notary->getEmails();
		
		$info['Mailing Address'] = '';
		$info['Address'] = $notary->getAddress();		
		$info['City'] = $notary->getCity();
		$info['State'] = $notary->getState();
		$info['Zipcode'] = $notary->getZipcode();
		
		$info['Document Shipping Address'] = '';
		$info['Address '] = 	$notary->getAddressShipping();			
		$info['City '] = $notary->getCityShipping(); 			
		$info['State '] = $notary->getStateShipping(); 	
		$info['Zipcode '] = $notary->getZipcodeShipping();  
		
		
		
		$info['Saturday Delivery Address'] = '';
		$info[' Address'] = $notary->getAddressSaturdayDelivery();				
		$info[' City'] = $notary->getCitySaturdayDelivery();			
		$info[' State'] = $notary->getStateSaturdayDelivery();	
		$info[' Zipcode'] = $notary->getZipcodeSaturdayDelivery(); 
		
		
		$info['General Information'] = '';
		$info['Languages Spoken'] = $notary->getLanguagesSpoken();	
		$info['Availability'] = $notary->getAvailabiliy(); 
		$info['Willing to travel (Max. Miles)'] = $notary->getTravelMiles(); 
		$info['Overnight Charge'] = $notary->getOvernightCharge(); 
		$info['Edoc Charge'] = $notary->getEdocCharge(); 
		$info['Esign'] = $notary->getEsign();
		$info['Notary Commission Name'] = $notary->getNotaryCommissionName(); 
		$info['Notary Commission Number'] = $notary->getNotaryCommissionNumber(); 
		$info['Copy of Certificate'] = $notary->getCertificate(); 
		$info['Notary Commission Expiration'] = $notary->getNotaryCommissionExpiration();
		$info['Multiple States'] = $notary->getMultipleStates();
		$info['Errors & Omission'] = $notary->getErrorAndOmissionAmount();		
		$info['Errors & Omissions Certificate'] = $notary->getErrorsAndOmissionsCertificate(); 
		$info['How many years as a notary?'] = $notary->getYearsAsNotary(); 
		$info['Associations and Certifications'] = $notary->getAssociationsAndCertifications();
		$info['Background Check'] = $notary->getBackgroundCheckFile(); 
		$info['Bond Certificate'] = $notary->getBondCertificate(); 
		$info['Signed W-9 Form'] = $notary->getSignedW9form();
		$info['Check payable to'] = $notary->getCheckPayableTo();
		$info['Have Dural Tray Printer?'] = $notary->getDualTrayPrinter();	
		$info['Have Scanner?'] = $notary->getScanner();	
		$info['How did you hear about us?'] = $notary->getHowFound();
		
		
		
		
		
		
		
		
		
		
		 
		//use data in place holders
		$keys['%%FULL_NAME%%'] = $notary->getName();
		$keys['%%LINK%%'] = HOST;
		$keys['%%LOGO%%'] = HOST.'images/pkg/'.PKG_LOGO;
		$keys['%%INTRO%%'] = $intro;
		$keys['%%INFO%%'] = $this->makeInfoContent($info);
		$keys['%%EXIT%%'] = $exit;
		$keys['%%SIGNATURE%%'] = $admin->getSignature();
		
		  
		
		
		//fetch & compile message
		$template = $this->getEmailTemplate($code);
		$template = $template->description;
		$message  = $this->prepareContent($keys,$template);
		
		
		  
		
		
		//set email 
		$to = $this->getEmails('user',$notary->getID(),true);;
		$to_name = $notary->getName();
		$from = $admin->getEmails();
		$from_name = $admin->getCompany();
		$subject = 'Notary Account Created';
		
		 
		foreach($to as $e_mail) {
		  if($e_mail) { 
		  		/*
		  		echo "<h1>$e_mail</h1>
				      <h2>$from_name < $from ></h2>
				  
				  ".$message;		  	
				 */
				$this->sendMail($e_mail, $to_name , $from, $from_name , $subject, $message);
		  }
		}
		
		
		
	}
	function sendNewNotaryAlertToSuper($id) {
		
		$code = 'GENERAL_EMAIL_TEMPLATE';
		$notary = new user($id);
		$admin = new user($notary->getCreatedBy());
		$super = new user(SUPER_USERID); 
	 	
		
		
		//prepare data
		$intro = "Your customer <a href='".HOST."'>".PKG_TITLE."</a> has registered a new Notary. Following are the details of Notary Account.";
		$exit = " ";
		
		
		$info = array();
		$info['Personal Information'] = '';
		$info['Full Name'] = $notary->getName();
		$info['Date of Birth'] = $notary->getDOB();
        	$info['Login Username'] = $notary->getUsername();
        	
        	$info['Contact Information'] = '';
		$info['Phone'] = $notary->getPhone();
		$info['Mobile'] = $notary->getMobile();
		$info['Fax'] = $notary->getFax();
		$info['Email(s)'] = $notary->getEmails();
		
		$info['Mailing Address'] = '';
		$info['Address'] = $notary->getAddress();		
		$info['City'] = $notary->getCity();
		$info['State'] = $notary->getState();
		$info['Zipcode'] = $notary->getZipcode();
		
		$info['Document Shipping Address'] = '';
		$info['Address '] = 	$notary->getAddressShipping();			
		$info['City '] = $notary->getCityShipping(); 			
		$info['State '] = $notary->getStateShipping(); 	
		$info['Zipcode '] = $notary->getZipcodeShipping();  
		
		
		
		$info['Saturday Delivery Address'] = '';
		$info[' Address'] = $notary->getAddressSaturdayDelivery();				
		$info[' City'] = $notary->getCitySaturdayDelivery();			
		$info[' State'] = $notary->getStateSaturdayDelivery();	
		$info[' Zipcode'] = $notary->getZipcodeSaturdayDelivery(); 
		
		
		$info['General Information'] = '';
		$info['Languages Spoken'] = $notary->getLanguagesSpoken();	
		$info['Availability'] = $notary->getAvailabiliy(); 
		$info['Willing to travel (Max. Miles)'] = $notary->getTravelMiles(); 
		$info['Overnight Charge'] = $notary->getOvernightCharge(); 
		$info['Edoc Charge'] = $notary->getEdocCharge(); 
		$info['Esign'] = $notary->getEsign();
		$info['Notary Commission Name'] = $notary->getNotaryCommissionName(); 
		$info['Notary Commission Number'] = $notary->getNotaryCommissionNumber(); 
		$info['Copy of Certificate'] = $notary->getCertificate(); 
		$info['Notary Commission Expiration'] = $notary->getNotaryCommissionExpiration();
		$info['Multiple States'] = $notary->getMultipleStates();
		$info['Errors & Omission'] = $notary->getErrorAndOmissionAmount();		
		$info['Errors & Omissions Certificate'] = $notary->getErrorsAndOmissionsCertificate(); 
		$info['How many years as a notary?'] = $notary->getYearsAsNotary(); 
		$info['Associations and Certifications'] = $notary->getAssociationsAndCertifications();
		$info['Background Check'] = $notary->getBackgroundCheckFile(); 
		$info['Bond Certificate'] = $notary->getBondCertificate(); 
		$info['Signed W-9 Form'] = $notary->getSignedW9form();
		$info['Check payable to'] = $notary->getCheckPayableTo();
		$info['Have Dural Tray Printer?'] = $notary->getDualTrayPrinter();	
		$info['Have Scanner?'] = $notary->getScanner();	
		$info['How did you hear about us?'] = $notary->getHowFound();
		
		
		
		
		
		
		
		
		
		
		 
		//use data in place holders
		$keys['%%FULL_NAME%%'] = $super->getName();
		$keys['%%LINK%%'] = HOST;
		$keys['%%LOGO%%'] = HOST.'images/pkg/'.PKG_LOGO;
		$keys['%%INTRO%%'] = $intro;
		$keys['%%INFO%%'] = $this->makeInfoContent($info);
		$keys['%%EXIT%%'] = $exit;
		$keys['%%SIGNATURE%%'] = $admin->getSignature();
		
		  
		
		
		//fetch & compile message
		$template = $this->getEmailTemplate($code);
		$template = $template->description;
		$message  = $this->prepareContent($keys,$template);
		
		
		  
		
		
		//set email 
		$to = $this->getEmails('user',SUPER_USERID,true);;
		$to_name = $super->getName();
		$from = $admin->getEmails();
		$from_name = $admin->getCompany();
		$subject = 'New Notary Account';
		
		 
		foreach($to as $e_mail) {
		  if($e_mail) { 
		  		/*echo "<h1>$e_mail</h1>
				      <h2>$from_name < $from ></h2>
				  
				  ".$message;		  	
				*/
				$this->sendMail($e_mail, $to_name , $from, $from_name , $subject, $message);
		  }
		}
		
		
		
	}
	
} //class ends

?>