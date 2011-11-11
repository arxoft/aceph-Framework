<?php

class authentication {
	
	function __construct() {
		
		if (!isset($_SESSION['loggedInUserID'])) { 
			$this->setSessionDefaults();
		}
		
		
		$this->userHomePages = array(
									 1=>'notary.php',
									 2=>'client.php',
									 3=>'admin.php',
									 4=>'admin.php',
									 5=>'admin.php',
									 99=>'webmaster.php'
									 );
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function setSessionDefaults() { 
		$_SESSION['userLogged'] = false; 
		$_SESSION['loggedInUserID'] = 0; 
		$_SESSION['loggedUsername'] = ''; 
		$_SESSION['userCookie'] = 0; 
		$_SESSION['remembered'] = false;
		$_SESSION['loggedInUserName'] = "";
		$_SESSION['loggedInUserRole'] = "";
		$_SESSION['userAccountTypeID'] = 0; 
		$_SESSION['lastVisit'] = 0;
	}
	
	
	
	function getSessionData($index) {
		if(isset($_SESSION[$index]) && $_SESSION[$index])
			return $_SESSION[$index];
	}
 
 
 
 	function checkSession($loggedInUserID=0) { 
	
		if(isset($_SESSION['userLogged'])  && $_SESSION['userLogged']) {
			if(isset($_SESSION['loggedInUserID'])  && $_SESSION['loggedInUserID']) {
				if($loggedInUserID) {
					if(isset($_SESSION['userAccountTypeID']) && ($_SESSION['userAccountTypeID'] == $loggedInUserID)) {
							 
						return true;
					} else return false;
				} else {
					if(isset($_SESSION['userAccountTypeID']) && $_SESSION['userAccountTypeID']) {
						return $_SESSION['userAccountTypeID'];
					} else return false;
				}
			} else return false;
		} else return false;
		
	}
 
 
 
 
	function checkLoggedInUser($username, $password, $remember='') {
		
		
		
		
		
		if(PKG_ALLOW_CLIENT_END) {
			$sql = "SELECT * FROM `user_signup` WHERE `username` = '$username' AND `password`= '".md5($password)."'  "; 
		}else{
			//client (account id 2) can't login to system.
			$sql = "SELECT * FROM `user_signup` WHERE `username` = '$username' AND `password`= '".md5($password)."' and account_id <> '2' "; 
		}
		//
		
		
		$result = mysql_query($sql);
		$totalRows = mysql_num_rows($result); 
		if ($totalRows > 0) 
		{ 
				
				$row = mysql_fetch_array($result);
				
				
				//check if this user is already logged in other place... 
				if($row['account_id'] == '4' || $row['account_id'] == '5') {
					
					
					
					$q_op = "select * from login_user where user_id = '".$row['id']."' ";
					$r_op = mysql_query($q_op);
					if(mysql_num_rows($r_op)) {
								$row_op = mysql_fetch_assoc($r_op);
								$last_active = $this->getLastActive($row_op['user_id']);
								$now = time();
								$difference = $now - $last_active;
								
								//convert into seconds
								if(is_numeric(PKG_AUTO_LOGOUT_TIME)) {
									$logout_time = PKG_AUTO_LOGOUT_TIME * 60;
								} else {
								
									$logout_time = 30 * 60;
								}
								
								
								
								if($difference < $logout_time) {
										return false;
								} else {
									$q = "delete from login_user where user_id = '".$row['id']."' ";
									if(!mysql_query($q)) dbError($q);
								}
					} 
				}
				
				
				
				
				if($row['status']=="1" || $row['status']=="2"){
					 
					$qRole = "select title from account_type where id='{$row['account_id']}' ";
					$rRole = mysql_query($qRole);
					$rowRole = mysql_fetch_array($rRole);
					 
					$this->setUserSessionData($row['id'], $row['title'].' '.$row['first_name'].' '.$row['middle_name'].' '.$row['last_name'], $row['username'],$rowRole['title'], $row['username'], $remember, $row['account_id'], $row['last_visit']);
					return $_SESSION['userAccountTypeID']; 
				}else{
					$this->logOutLoggedUser();
				} 
			}else {		
				 
				$this->logOutLoggedUser(); 
			} 
	} 
	
	
	
	
	
	
	
	
	
	

	function setUserSessionData($ID, $name, $username, $role, $cookie, $remember, $accountID, $last_visit, $init = true ) { 
			//echo 'ID: '.$ID.' Name:'.$name.' User Name:'.$username.' Cookie: '.$cookie.' Remember: '.$remember.' AccountID: '.$accountID.' Initialized: '.$init;
			
			$_SESSION['loggedInUserID'] = $ID; 
			$_SESSION['loggedInUserName'] = $name;
			$_SESSION['loggedInUserRole'] = str_replace(' ','_',strtolower($role));
			$_SESSION['loggedUsername'] = htmlspecialchars($username); 
			$_SESSION['userCookie'] = $cookie;
			$_SESSION['userAccountTypeID'] = $accountID; 
			$_SESSION['userLogged'] = true; 
			$_SESSION['lastVisit'] = $last_visit; 
			$_SESSION['lastActive'] = time();
			$session = session_id(); 
			$ip = $_SERVER['REMOTE_ADDR']; 
			
			//$delQry = "DELETE FROM `login_user` WHERE `user_id`='$ID'";	
			$insertSql = "INSERT INTO `login_user` VALUES('$ID','$ip',Now(),$accountID, '".time()."')";
			$d=mysql_query($delQry);
			$ins=mysql_query($insertSql);
							
			if ($remember == "1") { 
				$this->updateSiteCookie($cookie, true); 
			}
			if ($init) { 
				$session = session_id(); 
				$sql = "UPDATE `user_signup` SET `session`='$session', `last_ip`='$ip', `last_visit`=Now() WHERE `id` = '$ID'";
				$result = mysql_query($sql);
				return $_SESSION['userAccountTypeID'];
				exit();  		
			} 	
	} 













	function updateSiteCookie($cookie, $save) { 
		$_SESSION['userCookie'] = $cookie; 
		if ($save == "true") 
		{ 
			$_SESSION['setCookie'] = true;
			setcookie(SITE_COOKIE_NAME, $cookie, time() + 31104000,''); 
		} 
	} 










	
	function checkRemembered() { 
		if(isset($_COOKIE[SITE_COOKIE_NAME])){
			$sql = "SELECT * FROM user_signup WHERE username = '".$_COOKIE[SITE_COOKIE_NAME]."'"; 
			$result = mysql_query($sql);
			$totalRows = mysql_num_rows($result);
		
			if ($totalRows > 0) 
			{ 
				$row = mysql_fetch_array($result);
				$this->setUserSessionData($row['id'], $row['title'].' '.$row['first_name'].' '.$row['middle_name'].' '.$row['last_name'], $row['username'], $row['username'], false, $row['account_id'], $row['last_visit'], true);
			} else { 
				$this->logOutLoggedUser(); 
			} 
		}	
	} 












	
	function checkUserSessionData() { 

		$username = $_SESSION['loggedUsername']; 
		$cookie = $_SESSION['userCookie']; 
		$session = session_id(); 
		$ip = $_SERVER['REMOTE_ADDR']; 
		
		$sql = "SELECT * FROM `user_signup` WHERE `username`='$username' AND `session`='$session' AND `last_ip`='$ip'"; 
		$result = mysql_query($sql); 
		$totalRows = mysql_num_rows($result);
		if ($totalRows > 0){ 	
			$row = mysql_fetch_array($result);
			$this->setUserSessionData($row['id'], $row['title'].' '.$row['first_name'].' '.$row['middle_name'].' '.$row['last_name'], $row['username'], false, false, $row['account_id'], $row['last_visit']);
		} else {  
			$this->logOutLoggedUser(); 
		} 
	} 











	function logOutLoggedUser($type='fail', $arg='loggedOut') {
		$delQry = "DELETE FROM `login_user` WHERE `user_id`='".$_SESSION['loggedInUserID']."'";
		mysql_query($delQry);
		
		if($_SESSION['userAccountTypeID']=='1'){
			$loc = 'notary-login';
		}else{
			$loc = 'client-login';
		}
		if(isset($_POST['from']) && $_POST['from'] ) {
			 
			$loc = $_POST['from'];
		}
		$this->setSessionDefaults();
		
		
		redirect(HOST.'?'.$type);
		return false;
	 
	}
	
	
	

	
	
	
	
	
	
	
	
	function getUserTypeName($type_id) {
		$r = mysql_fetch_assoc(mysql_query("select title from account_type where id = '$type_id' "));
		return $r['title'];
	}
	
	
	
	
	
	
	
	
	
	function resetPassword($key) {
	
		$q = "select * from user_signup_forget_password where `key` like '$key' and Now() < ( `date` + INTERVAL 1 DAY )  ";
		if(!($r = mysql_query($q))) dbError($q);
		if(mysql_num_rows($r)) {
			
			//return true;
			$row = mysql_fetch_assoc($r);
			$q2 = "update user_signup set password = MD5('".$row['password']."') where id = '".$row['user_id']."' ";
			if(!(mysql_query($q2))) dbError($q2);
			
			return array('id'=>$row['user_id'], 'password'=>$row['password']);
			
		} else {
		
			return false;
			
		}
		
	}
	
	
	
	
	
	function getLastActive($user_id) {
	
		$q = "select last_active from login_user where user_id = '$user_id' ";
		$r = mysql_query($q);
		$row = mysql_fetch_assoc($r);
		return $row['last_active'];
		
	}
	function getUserIP($user_id) {
	
		$q = "select user_ip from login_user where user_id = '$user_id' ";
		$r = mysql_query($q);
		$row = mysql_fetch_assoc($r);
		return $row['user_ip'];
		
	}
	function setLastActive($user_id) {
	
		$q = " update login_user set last_active = '".time()."' where user_id = '$user_id' ";
		if(!($r = mysql_query($q))) dbError($q);
		
		
	}
	 

}


 
?>