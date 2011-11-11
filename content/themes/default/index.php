<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php

include_once(DIR_CURRENT_THEME_ABS.'includes/head.php');

?>
</head>
<body>
	
<div class="main">
 <?php include_once(DIR_CURRENT_THEME_ABS.'includes/header.php'); ?>
  <div class="body">
    <div class="body_resize">
      <div class="left">
        <?php echo $module; ?>
      </div>
      <?php include_once(DIR_CURRENT_THEME_ABS.'includes/right.php'); ?>
      <div class="clr"></div>
    </div>
  </div>
  <?php include_once(DIR_CURRENT_THEME_ABS.'includes/fbg.php'); ?>
  <?php include_once(DIR_CURRENT_THEME_ABS.'includes/footer.php'); ?>
</div>
</body>
</html>