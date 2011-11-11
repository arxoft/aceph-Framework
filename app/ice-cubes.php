<?php
/*
 * This file holds settings in the form
 * of PHP Constants.
 * 
 * You are free to change values
 * according to your requirements.
 * 
 */

 
 
 
 
 
 
/*
 * Will thie project be HTTPS?
 * Possible Values: 1/0
 * */
define('IS_HTTPS',0);





/*
 * Sub Directory in which the framework resides.
 * It should NOT have / at end.
 */
define('SUB_DIRECTORY','/aceph-framework');




/*
 * Not Found Text.
 * Some functions use to return this, instead
 * of FALSE (i.e. when something not found).
 */
define('NOT_FOUND_TEXT','<i>none</i>');




/*
 * In case you want to use Authentication
 *  
 */
define('AUTH_DB_TABLE','');
define('AUTH_USERNAME_FIELD','');
define('AUTH_PASSWORD_FIELD','');


/*
 * Here you can chose which theme your 
 * site should wear.
 */
define('THEME','atmosphere');



/*
 * All email to be sent to admin will
 * be sent on thse addresses
 */
define('EMAIL_ADMIN','aceph_@hotmail.com');
?>