<?php
/**
 * Initialization Block:
 * 
 * fire.php is responsible to pre-loading all
 * the shared code to be used in 
 * this page. This includes :
 * 
 * ~ DB Connections
 * ~ Classes
 * ~ Functions
 * ~ Addons
 * ~ and every other such stuff.
 * 
 */
include_once('app/fire.php');




/*
 * Autherntication Block
 * 
 * Set the user's account_type id in brackets which
 * you want to allow to access this group and its modules.
 * 
 * Use || operand to allow multiple users to access
 * this page.
 * 
 * Comment following condition to allow any person 
 * to access this page.
 */

if(isset($auth) && is_object($auth)){
	if(!($auth->checkSession('1'))) {
		
		//redirect(URL);
	
	}
}



/*
 * Global Parameters
 * 
 * The parameters you want to be auto generated
 * by assembleParams(). This function converts an array
 * to URL parameters.
 * 
 * So, we use following params in auto generating links.
 * 
 * For example: in pagination, page_no and order is a URL param, which
 * we can't hardcode. like in 'user.php? action=view & page_no=2 & order=asc'.
 * If we want order to be desc, we'll pass global parameters to
 * assemble params and tell it to skip order (because we don't 
 * want its value 'asc'). This will get all global parameters
 * except order and generate a URL like:
 * user.php ? action=view & page=2 & order=desc
 * 
 */
$GLOBALS['global_parameters']['action'] = (gotGet('action')) ? $_GET['action'] : '';
$GLOBALS['global_parameters']['page_no'] = (gotGet('page_no')) ? $_GET['page_no'] : '';
$GLOBALS['global_parameters']['letter'] = (gotGet('letter')) ? $_GET['letter'] : '';
$GLOBALS['global_parameters']['id'] = (gotGet('id')) ? $_GET['id'] : '';



/*
 * Based on global parameter 'action'
 * we write different conditions to load this group's 
 * respective modules and widgets
 * 
 */

$widgets = array();

/*
 * Default Widgets Here
 * */

$widgets[]=display::getWidgetContent(DIR_WIDGETS_ABS.'widget.php');


switch($GLOBALS['global_parameters']['action']) {
		case 'contact': 
				$module = display::getPageContent(DIR_MODULES_ABS.'static/contact.php');
				break;
		
				
				
		/*
		 * If no action param in URL,
		 * then load this modle.
		 */
		default:
			$module = display::getPageContent(DIR_MODULES_ABS.'static/index.php');
			break;
		
	
} 

$content = DIR_CURRENT_THEME_ABS.'index.php';
include($content);
 
?>