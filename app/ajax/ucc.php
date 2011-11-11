<?php
// Update Category Count

include('ajax_fire.php');
 		//if editing profile

$obj_category = new category();
$obj_category->_executeDynamicQuery("select categories_date, categories_start_time, categories_start_day, categories_max_places, categories_places_available from categories where categories_id = '".$_POST['ci']."'; ");
//echo $obj_category->getPlacesAvailable() - 1;
$arr = array(
			 'cd'=>$obj_category->getDate(),
			 'cst'=>$obj_category->getStartTime(),
			 'csd'=>$obj_category->getStartDay(),
			 'cmp'=>$obj_category->getMaxPlaces(),
			 'cpa'=>($obj_category->getPlacesAvailable()-$_POST['d'])
			 
			 );

echo 'var d='.json_encode($arr);
?>