<?php

/*
Functions List:
===============
createSEOName
gotGet
formPosted
wrapTag
getDateRange
coalesce(a,b,c ... )
timeRemaining
stripslashes_deep
getFileSize
makeURL 
isEmail
list_array
assembleParams
removeLastChars
delayedRedirect
ifempty
validateURL
makeaddress
makecontact
bounceback
error
message
redirect
valueOrDash
dateConversion
makeTimeStamp
getDbDateFormat
printDate
getFormatedDate
dateFromString
getEmailID
checksession
curPageName
curPageURL
validate_admin
validate
explode_items
random_text


*/




// return a string of random text of a desired length
function random_text($count, $rm_similar = false)
{
	// create list of characters
	$chars = array_flip(array_merge(range(0, 9), range('A', 'Z')));
	// remove similar looking characters that might cause confusion
	if ($rm_similar)
	{
		unset($chars[0], $chars[1], $chars[2], $chars[5], $chars[8], $chars['B'], $chars['I'], $chars['O'], $chars['Q'], $chars['S'], $chars['U'], $chars['V'], $chars['Z']);
	}
	// generate the string of random text
	for ($i = 0, $text = ''; $i < $count; $i++)
	{
		$text .= array_rand($chars);
	}
	return $text;
}
?>
<?php
// convert a list of items (separated by newlines by default) into an array
// omitting blank lines and optionally duplicates



function explode_items($text, $separator = "\n", $preserve = true)
{
	$items = array();
	foreach (explode($separator, $text) as $value)
	{
		$tmp = trim($value);
		if ($preserve)
		{
			$items[] = $tmp;
		}
		else
		{
			if (!empty($tmp))
			{
					$items[$tmp] = true;
			}
		}
	}
	if ($preserve)
	{
		return $items;
	}
	else
	{
		return array_keys($items);
	}
}
?>
<?php

	function validate($id, $redirect, $method='get') {
			
		if($method=='get') {
			if(!$_GET[$id]) {
				$loc = $redirect.'&error='.$id;
				@header("Location: $loc");
				}
			else return $_GET[$id]; 
		} else {
			if(!$_POST[$id]) {
				$loc = $redirect.'&error='.$id;
				@header("Location: $loc"); }
			else echo $_POST[$id];
			
		
		}
		
		
	}
	
?>
<?php

function validate_admin($name, $pass) {
		$query = "select * from admin where user = '".$name."' and password = '".$pass."';";
	//	echo $query; die();
		$result = mysql_query($query, $GLOBALS['DB']);
		if (mysql_num_rows($result))
		{
			return 1;
		}
		else {
			return 0;
		}
}

?>
<?php

function curPageURL() {
	 $pageURL = 'http';
	 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}


function curPageName() {
	 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
/*
Example:
echo "The current page name is ".curPageName();
echo "<br>".curPageURL();
*/
?>
<?php


function checksession($type) {

	switch($type) {
		case 'member': 
				if(isset($_SESSION['member_access']) && $_SESSION['member_access']) return 1;
				else return 0;
		
		case 'profile': 
				if(isset($_SESSION['profile_access']) && $_SESSION['profile_access']) return 1;
				else return 0;
				
		case 'admin': 
				if(isset($_SESSION['admin_access']) && $_SESSION['admin_access']) return 1;
				else return 0;
	}

}

?>
<?php


function getEmailID($email) {
	if(strpos($email,'@')) return substr($email,0,strpos($email,'@'));
	else return $email;
	
}

?>
<?php
function printDate($timestamp, $not_found = DEFAULT_NOT_FOUND_VALUE) {
	 
	if(is_numeric($timestamp)) {
		$time = getdate($timestamp);
		return $time['mday'].' '.$time['month'].', '.$time['year'];
	}
	return $not_found;
}

function getFormatedDate($val){
	 
	if($val!=''){
		$arr = explode("-", $val);
		return date("m/d/Y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
	}
}

function dateConversion($date, $split='/') {
	list($year, $month, $day) = split($split, $date);
    $date = date('M d, Y', mktime(0, 0, 0, $month, $day, $year)); //mktime, makes timestamp...
	return $date;

}
function makeTimeStamp($date,$split='/') {
	list($month,$day,$year) = split($split,$date);	
	return mktime(0,0,0,$month,$day,$year);
	
}
function getDbDateFormat($val){
	if($val!=''){
		$arr = explode("/", $val);
		return date("Y-m-d", mktime(0,0,0, $arr[0], $arr[1], $arr[2]));
	}
}
function dateFromString($str, $format='d M, Y', $not_found = DEFAULT_NOT_FOUND_VALUE) {
	if($str && $str != '0000-00-00 00:00:00' && $str  != '0000-00-00')
		return date($format, strtotime($str));
	else
		return $not_found;
}
?>
<?php


function valueOrDash($value, $dash=DEFAULT_NOT_FOUND_VALUE) {
		if(!isempty($value)) return $value; else return $dash;
	
	}

?>
<?php

function redirect($url) {

	echo "<script>window.location = '".$url."';</script>";
	die();
	
}

function message($url) {

	echo "<script>alert('".$url."');</script>";
}


function error($e) {
	if(DEBUG_MODE)
		echo "<hr/><pre style='background-color:yellow'>".$e."</pre><hr/>";
	die('<!-- Error: Enter Debug Mode -->');
}


function bounceback() {
	echo "<script>history.go(-1)</script>";
	die();
}


function validateURL($url) {
	if(!preg_match("/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i",$url)) {
		
		return "http://".$url;
	
	} else
	
		return $url;
}

function makeaddress($street = "", $city = "", $state = "", $zip = "", $country = "", $seperator = '', $labels = '', $empty_text = TEXT_NOT_FOUND) {
				
				if(!$seperator) $seperator = ", ";
				
				if($labels == 'small') 
						$L = array("st"=>"<u>Str:</u> ","c"=>"<u>Cit:</u> ","s"=>"<u>Sta:</u> " ,"z"=>"<u>Zip:</u> ","u"=>"<u>Con:</u> ");
				elseif($labels == 'large')
						$L = array("st"=>"<u>Street:</u> ","c"=>"<u>City:</u> ","s"=>"<u>State:</u> " ,"z"=>"<u>Zip Code:</u> ","u"=>"<u>Country:</u> ");
				elseif($labels == '')
						$L = array("st"=>"","c"=>"","s"=>"","z"=>"","u"=>"");
	
	
	
				$address1 = array();
				if(!empty($street)) $address1[] = $L['st'].$street ;
				if(!empty($city)) $address1[] = $L['c'].$city ;
				if(!empty($state)) $address1[] = $L['s'].$state;
				if(!empty($zip)) $address1[] = $L['z'].$zip;
				if(!empty($country)) $address1[] = $L['c'].$country;
				
				if(count($address1))
					return implode($seperator,$address1);
				else
					return $empty_text;
				

}
function makecontact($mobile, $phone, $fax, $email, $website, $labels='',$seperator = ", ", $empty_text = '') {
				 
				if($labels == 'small') 
						$L = array("m"=>"<u>Mo:</u> ","p"=>"<u>Ph:</u> ","f"=>"<u>Fax:</u> " ,"e"=>"<u>Email:</u> ","u"=>"<u>URL:</u> ");
				elseif($labels == 'large')
						$L = array("m"=>"<u>Mobile:</u> ","p"=>"<u>Phone:</u> ","f"=>"<u>Fax:</u> ","e"=>"<u>Email:</u> ","u"=>"<u>Website:</u> ");
				elseif($labels == '')
						$L = array("m"=>"","p"=>"","f"=>"","e"=>"","u"=>"");
						
						
				$contact = array();
				if(!isempty($mobile)) $contact[] = $L['m'].$mobile ;
				if(!isempty($phone)) $contact[] = $L['p'].$phone ;
				if(!isempty($fax)) $contact[] = $L['f'].$fax ;
				if(!isempty($email)) $contact[] = $L['e']."<a href='mailto:$email'>$email</a>" ;
				if(!isempty($website)) $contact[] = $L['u']."<a href='".validateURL($website)."'>$website</a>" ;
				
				if(count($contact))
					return implode("$seperator",$contact);
				else
					return $empty_text;
				
	
}
function isempty($s) {
	
	$a= trim($s);
	if(empty($a)) return 1; else return 0;

}

function delayedRedirect($url, $time) {
	
	$a = "<script type='text/javascript'>
	<!--
	function delayer(){
		window.location = '".$url."';
	}
	setTimeout('delayer()', ".$time.");
	//-->
	</script>

	";
	
	return $a;

}

function removeLastChars($string, $length) {
	if($length > 0) $length = (-1)*$length;
	return substr_replace($string ,"",$length);

}
function assembleParams($params, $lead_question_mark=true, $bypass = '') {
	
	$p = '';
	$b = array();
	 
	if($bypass) $b = explode(",",$bypass);
	foreach($params as $k=>$v) {
	
	
		if(!in_array($k, $b)) {
			
			if(trim($v)) {
		
				$p .= $k.'='.$v.'&';
		
			}
		}
	}
	
	if($p) {
		$p = removeLastChars($p,1);
		 if($lead_question_mark) 
		 	return '?'.$p;
		 else 
		 	return $p;
		
	}

}


function list_array($arrayname,$tab="&nbsp&nbsp&nbsp&nbsp",$indent=0)
{ 
	// recursively displays contents of the array and sub-arrays:
	// This function (c) Peter Kionga-Kamau (http://www.pmkmedia.com)
	// Free for unrestricted use, except sale - do not resell.
	// use: echo LIST_CONTENTS(array $arrayname, string $tab, int $indent);
	// $tab = string to use as a tab, $indent = number of tabs to indent result
	while(list($key, $value) = each($arrayname))
	{
		for($i=0; $i<$indent; $i++) $currenttab .= $tab;
		if (is_array($value))
		{
			$retval .= "$currenttab$key : Array: <BR>$currenttab{<BR>";
			$retval .= LIST_CONTENTS($value,$tab,$indent+1)."$currenttab}<BR>";
		}
		else $retval .= "$currenttab$key => $value<BR>";
		$currenttab = NULL;
	}
	echo '<pre>'.$retval.'</pre>';
}
?>
<?php

function isEmail($email) {
  if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
    return true;
  }
  return false;
}

?>
<?php

function makeURL($url, $text='', $attr='') {
	$url = trim($url);
	$text = ($text) ? $text : $url; 
	
	if($url) {
		if(strpos($url,"@")) {
			$u = 'mailto:'.$url;
		}else{
			if(!strpos($url,'://'))
				$u = validateURL(trim($url));
			else
				$u = $url;
		}
		
		return "<a href='$u' $attr >$text</a>";
	}else{
		return '';	
	}
		
	
}


function getFileSize($size){
	$i=0;
	$iec = array("b", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb");
	while (($size/1024)>1) {
		$size=$size/1024;
		$i++;
	}
	return substr($size,0,strpos($size,'.')+4).$iec[$i];
} 



function stripslashes_deep($value)
{
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);

    return $value;
}



//*
function timeRemaining($end, $exceeds = 'Time exceeded by: ', $start = '', $limit = 5) {
	
	/* Documentation: [
	$limit:
			it indicates the maximum level of time we want to display. It ranges from 0 to 5.
			
			values:
			0 for YEARS
			1 for MONTHS
			2 for DAYS
			3 for HOURS
			4 for MINUTES
			5 for SECONDS
			
			
			ex:
			if we set limit =3, then time returned will be like: 1 year, 2 months, 3 days, 1 hour
			(or)
			if we set limit =1, then time returned will be like: 1 year, 2 months
	
	] */
	
	if(!$start)
		$start = time();

	if($end > $start) {
			$diff = $end - $start;
	} else {
			$diff = $start - $end;
	}
			
			$secs = $diff;

			


			$years = floor($secs/(12*60*60*24*30));
			$rem = $secs%(12*60*60*24*30);
			
			$months = floor($rem/(60*60*24*30));
			$rem = $secs%(60*60*24*30);
			
			$days = floor($rem/(60*60*24));			
			$rem = $secs%(60*60*24);
			
			$hours = floor($rem/(60*60));
			$rem = $secs%(60*60);
			
			$minutes = floor($rem/60);
			$rem = $secs%(60);
			
			$secs = floor($rem/60);
			
			$cd = array();
			if($years) $cd[]=$years.' years';			// $limit = 0
			if($months) $cd[]=$months.' months';		// $limit = 1
			if($days) $cd[]=$days.' days';				// $limit = 2
			if($hours) $cd[]=$hours.' hours';			// $limit = 3
			if($minutes) $cd[]=$minutes.' minutes';		// $limit = 4
			if($secs) $cd[]=$secs.' seconds';			// $limit = 5
			
			
			//remove elements above the limit
			for($i = 5; $i>=0; $i--) {
				if($i > $limit)
					unset($cd[$i]);
			}
			
			//join all the times
			if(count($cd)) {
					$timeRemaining = ($start > $end) ? $exceeds : '';
					$timeRemaining .= implode(', ',$cd);
					return $timeRemaining;
			}	
			
			
			
	 
	 
	
}












function coalesce() {
  $args = func_get_args();
  foreach ($args as $arg) {
    if (!isempty($arg)) {
      return $arg;
    }
  }
  return NULL;
}



/**
 * filterEmpties()
 * 
 * This function gets inifinit number of arguments.
 * Then, filters out empty arguments and returns non-empty arguments/parameters
 * in form of an array
 *  
 * @return array of non-emtpy paramaters
 */
function filterEmpties() {
	
	$args = func_get_args();
	$tmp = array();
	foreach ($args as $arg) {
	    if (!isempty($arg)) {
	      $tmp[] = $arg;
	    }
 	}
 	return $tmp;
	
}










function getDateRange($date, $today = '') {
		
		if(!$today)
			$today = time();
		
																			//echo date('l/d/m/Y',$date).'<br />';
		
		//today's day number in week. ex; tuesday: 2, wed: 3 ...
		$day_number = date('N',$today);
		$next_sunday = 7-$day_number;

		
		
		//get time stamps
		$next_month = strtotime('+30 days',$today);
		$next_weekend = strtotime("+$next_sunday days",$today); 			//echo date('l/d/m/Y',$next_weekend).'<br />';
		$tomorrow = strtotime('+1 day',$today);
		$yesterday = strtotime('-1 day',$today);
		$previous_weekend  = strtotime("-$day_number days",$today); 		//echo date('l/d/m/Y',$previous_weekend).'<br />';
		$last_month = strtotime('-30 days',$today); 						//echo " - ".date('l/d/m/Y',$last_month).'<br />';
																			//echo $last_month.'-'.$date.'<br /><br />';
		
		//convert timestamps to date format
		$date_to_day = date('dmY',$date);
		$tomorrow_to_day = date('dmY',$tomorrow);
		$today_to_day = date('dmY',$today);
		$yesterday_to_day = date('dmY',$yesterday);
		
		
		if($date > $next_month)
			return 'after next month';
		
		if($date <= $next_month && $date>$next_weekend)
			return 'this month';
		
		if($date <= $next_weekend && $date>$tomorrow)
			return 'next weekend';
		
		
		if($date_to_day == $tomorrow_to_day)
			return 'tomorrow';
		
		if($date_to_day == $today_to_day)
			return 'today';
			
		if($date_to_day == $yesterday_to_day)
			return 'yesterday';
			
		if($date >= $previous_weekend && $date<$yesterday) 
			return 'previous weekend';
			
		if($date >= $last_month && $date<$previous_weekend) 
			return 'last month';
		
		if($date < $last_month) {											//echo 'here'.date('l/d/m/Y',$date).'<br />';
			return 'older than last month';	
		}
		
}










function wrapTag($text, $tag = 'b') {
    
    return '<'.$tag.'>'.$text.'</'.$tag.'>';
    
}




function formPosted($name, $array = false, $return_value = false) {
	
	if($array) {
		if(isset($_POST[$name]) && count($_POST[$name]))
			if($return_value)
				return $_POST[$name];
			else
				return true;
		 
	}else{
		if(isset($_POST[$name]) && $_POST[$name])
			if($return_value) 
				return $_POST[$name];
			else 
				return true;
		
	}  
	
	return false;
}



function gotGet($name, $return_value = false) {
	if(isset($_GET[$name])) {
		if($return_value)
			return $_GET[$name];
		else
			return true;
			
	}
	
	return false;
}



function createSEOName($string){
	 /*$seoString = preg_replace("[&]+","and",$str);
	 //$seoString = preg_replace("[^A-Za-z0-9 ]+","",$seoString);
	 $seoString = str_replace(" ","-",$seoString);
	 $seoString = str_replace("_","-",$seoString);
	 $seoString = preg_replace("[ ]+"," ",$seoString);
     $seoString = strtolower(trim($seoString));
     return $seoString;*/
	 
	 
	$string = trim($string);
    $string = strtolower($string);
    $string = str_replace('&', 'and', $string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', "-", $string);
    return $string;

}

function getFileName($fullFileName){
	$dotPosition = strrpos($fullFileName, '.');	
	//echo "File Name: ".strtolower(substr($fullFileName, 0,$dotPosition));
	return strtolower(substr($fullFileName, 0,$dotPosition));
}

function getFileExtension($fullFileName){
	//echo "File Extension: ".strtolower(substr($fullFileName, strrpos($fullFileName, '.')+1));
	return strtolower(substr($fullFileName, strrpos($fullFileName, '.')+1));
}


function mres($txt) {
	return mysql_real_escape_string($txt);	
}


function dbError($q) {
	error(mysql_error()."<br /><br /><strong>$q</strong>");	
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
 
function unformat($str_number){
	
	$tmp = str_replace('$','',$str_number);
	$tmp = str_replace(',','',$tmp);
	return $tmp;
	
	
}

function currency($int_number) {
	
	return '$'.number_format($int_number);
}



$htmlUL_output='';
function htmlUL ($ul, $ul_params='', $li_params=''){
	 global $htmlUL_output;
     $htmlUL_output .= '<ul>';
    foreach($ul as $item) {
        if (is_array($item)) {
        	
            htmlUL($item, $ul_params, $li_params);
        } else {
            $htmlUL_output .= '<li>'. $item. '</li>';
        }
    }
   $htmlUL_output .= '</ul>';
   return $htmlUL_output;
	
}
?>