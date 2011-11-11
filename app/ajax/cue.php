<?php
// Check User Existance

include('ajax_fire.php');

if($auth->alreadyExists(mres($_POST['e']))){
	$arr = array('e'=>1);
} else {
	$arr = array('e'=>0);
}



echo 'var d='.json_encode($arr);
?>