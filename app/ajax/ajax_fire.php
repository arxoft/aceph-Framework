<?php
session_start();



/*
 * Debug mode setup
 * */
if(!isset($_GET['debug'])) {
	error_reporting(0);
	define('DEBUG_MODE',false);
} else {
	define('DEBUG_MODE',true);	
}





/*
 * Settings 
 * */
include('../ice-cubes.php');	






//functions
include_once('../functions/functions.php');


 




/*
 * Define all paths as constants
 */
$_SESSION['CONSTANTS']['DIR_PROJECT']						= (SUB_DIRECTORY!='')?SUB_DIRECTORY."/":"/"; 
$_SESSION['CONSTANTS']['URL']								= ((IS_HTTPS=='1') ? "https://" : "http://" ).$_SERVER['HTTP_HOST']."/".$_SESSION['CONSTANTS']['DIR_PROJECT'];
$_SESSION['CONSTANTS']['ROOT_ABS'] 							= $_SERVER['DOCUMENT_ROOT'];
$_SESSION['CONSTANTS']['DIR_PROJECT_ABS'] 					= $_SESSION['CONSTANTS']['ROOT_ABS'].$_SESSION['CONSTANTS']['DIR_PROJECT'];
$_SESSION['CONSTANTS']['DIR_ADDONS_ABS'] 					= $_SESSION['CONSTANTS']['DIR_PROJECT_ABS'].'app/addons/';
$_SESSION['CONSTANTS']['DIR_APP_ABS'] 						= $_SESSION['CONSTANTS']['DIR_PROJECT_ABS'].'app/';
$_SESSION['CONSTANTS']['DIR_UPLAODS_ABS'] 					= $_SESSION['CONSTANTS']['DIR_PROJECT_ABS'].'uploads/';
$_SESSION['CONSTANTS']['DIR_INCLUDES_ABS'] 					= $_SESSION['CONSTANTS']['DIR_PROJECT_ABS'].'includes/';




$_SESSION['CONSTANTS']['DIR_CLASSES_ABS'] 					= $_SESSION['CONSTANTS']['DIR_APP_ABS'].'classes/';
$_SESSION['CONSTANTS']['DIR_MODULE_CLASSES_ABS'] 			= $_SESSION['CONSTANTS']['DIR_APP_ABS'].'classes/modules/';
$_SESSION['CONSTANTS']['DIR_GROUP_CLASSES_ABS'] 			= $_SESSION['CONSTANTS']['DIR_APP_ABS'].'classes/groups/';
$_SESSION['CONSTANTS']['DIR_AJAX_ABS'] 						= $_SESSION['CONSTANTS']['DIR_APP_ABS'].'ajax/';
$_SESSION['CONSTANTS']['DIR_FUNCTIONS_ABS'] 				= $_SESSION['CONSTANTS']['DIR_APP_ABS'].'functions/';


$_SESSION['CONSTANTS']['DIR_IMAGES_URL']					= $_SESSION['CONSTANTS']['URL'].'images'.'/';
$_SESSION['CONSTANTS']['DIR_CSS_URL']						= $_SESSION['CONSTANTS']['URL'].'css'.'/';
$_SESSION['CONSTANTS']['DIR_JS_URL']						= $_SESSION['CONSTANTS']['URL'].'js'.'/';
$_SESSION['CONSTANTS']['DIR_UPLOADS_URL']					= $_SESSION['CONSTANTS']['URL'].'uploads'.'/';


$_SESSION['CONSTANTS']['DIR_CONTENT_ABS'] 					= $_SESSION['CONSTANTS']['DIR_PROJECT_ABS'].'content/';
$_SESSION['CONSTANTS']['DIR_MODULES_ABS'] 					= $_SESSION['CONSTANTS']['DIR_CONTENT_ABS'].'modules/';
$_SESSION['CONSTANTS']['DIR_WIDGETS_ABS'] 					= $_SESSION['CONSTANTS']['DIR_CONTENT_ABS'].'widgets/';
$_SESSION['CONSTANTS']['DIR_LANGUAGES_ABS'] 				= $_SESSION['CONSTANTS']['DIR_CONTENT_ABS'].'languages/';
$_SESSION['CONSTANTS']['DIR_THEMES_ABS'] 					= $_SESSION['CONSTANTS']['DIR_CONTENT_ABS'].'themes/';
$_SESSION['CONSTANTS']['DIR_CURRENT_THEME_ABS'] 			= $_SESSION['CONSTANTS']['DIR_THEMES_ABS'].THEME.'/';
//$_SESSION['CONSTANTS']['DIR_THEME_INCLUDES_ABS'] 			= $_SESSION['CONSTANTS']['DIR_CURRENT_THEME_ABS'].'includes/';

$_SESSION['CONSTANTS']['DIR_CURRENT_THEME_URL'] 			= $_SESSION['CONSTANTS']['URL'].'content/themes/'.THEME.'/';
//$_SESSION['CONSTANTS']['DIR_THEME_IMAGES_URL']				= $_SESSION['CONSTANTS']['DIR_CURRENT_THEME_URL'].'images/';
//$_SESSION['CONSTANTS']['DIR_THEME_CSS_URL']					= $_SESSION['CONSTANTS']['DIR_CURRENT_THEME_URL'].'css/';


foreach($_SESSION['CONSTANTS'] as $key => $value){
	
	define($key,$value);
}




/*
 * Active Language
 * */
if(!isset($_SESSION['LANGUAGE'])){
	$_SESSION['LANGUAGE'] = 'en';
} elseif ($tmp = gotGet('set_language',true)	){
	$_SESSION['LANGUAGE'] = $tmp;
}

define("LANGUAGE", $_SESSION['LANGUAGE']);
define('DIR_CURRENT_LANGUAGE_ABS',DIR_LANGUAGES_ABS.LANGUAGE.'/');
include(DIR_CURRENT_LANGUAGE_ABS.'/dictionary.php');



/*
 * Include Addons
 * */
include( DIR_ADDONS_ABS."ckeditor/ckeditor.php");
$CKEditor = new CKEditor();
$CKEditor->basePath = 'app/addons/ckeditor/';
$CKEditor->config['width'] = 600;
$CKEditor->config['language'] = (LANGUAGE=='en')?'en':'ar';
$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);


/*
 * IP OF THE USER
 * */
define("IP",$_SERVER['REMOTE_ADDR']);




/*
 * Current Page Info Constants
 */
$path = $_SERVER['PHP_SELF'];
$pageName = basename($path, ".php").".php";	

define('CUR_PAGE_NAME',curPageName());
define('CUR_PAGE_URL',curPageURL());
define('CUR_PAGE_ABS_PATH',$path);






/**
 * Auto Load Classes
 */

function __autoload($class){
	
	if(substr($class, 0, 7) ==  'module_') {
		$class = DIR_MODULE_CLASSES_ABS.'class.'.strtolower($class).'.php';
	} elseif(substr($class, 0, 6) ==  'group_') {
		$class = DIR_GROUP_CLASSES_ABS.'class.'.strtolower($class).'.php';
	} else {
		
		$class = DIR_CLASSES_ABS.'class.'.strtolower($class).'.php';
		
	}
	 
	if(file_exists($class)) {
			include_once($class);
	} 

}





//database
include_once('../db/db.php');
$db = new db_connection();
define('DB_IS_CONNECTED',$db->isDBconnected()); 
if(DB_IS_CONNECTED){
	define('DB_CONNECTION',$db->getDBconnection());
}



//initialize browser class
$browser = new browser();



/*
 * If there is authentication table setup in ice-cubes.php
 * Do start authentication system
 */
if(AUTH_DB_TABLE && AUTH_USERNAME_FIELD && AUTH_PASSWORD_FIELD){
	$auth = new authenticate(AUTH_DB_TABLE,AUTH_USERNAME_FIELD,AUTH_PASSWORD_FIELD);
}


//die('<br/>--end of fire--');

?>