<?php
// Update Category Count

include('ajax_fire.php');

$obj_category = new category();
$obj_category->_executeDynamicQuery("select categories_id, categories_name from categories where categories_id <> '".$_POST['ci']."'; ");

$str_select = "<select name='users_categories_reserve' id='users_categories_reserve'>";

foreach($obj_category->arr_records as $pos => $row){
	$str_select .= "<option value='".$obj_category->getCategoryID($pos)."'>".$obj_category->getCategoryName($pos)."</option>";
}

$str_select .= "</select>";


$arr = array(
			 'data'=>$str_select
			 );

echo 'var d='.json_encode($arr);
?>