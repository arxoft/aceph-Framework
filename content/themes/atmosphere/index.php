<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Atmosphere 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20110923

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo (defined('MODULE_META_DESCRIPTION') && MODULE_META_DESCRIPTION) ? MODULE_META_DESCRIPTION : SITE_META_DESCRIPTION ?>" />
<meta name="keywords" content="<?php echo (defined('MODULE_META_KEYWORDS') && MODULE_META_KEYWORDS) ? MODULE_META_KEYWORDS : SITE_META_KEYWORDS ?>"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />






<title><?php echo (defined('MODULE_TITLE') && MODULE_TITLE) ? MODULE_TITLE.' :: '.SITE_TITLE : SITE_TITLE ?></title>









<!-- Include all stylesheets -->
<link href="<?php echo DIR_CURRENT_THEME_URL; ?>style.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>jquery.multiSelect.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>pagination.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>themeroller/jquery-ui-1.8.9.custom.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>jquery.autocomplete.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>flora.datepick.css" type="text/css" media="screen" title="no title" charset="utf-8" />







<!-- Include javaScripts -->
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery-1.3.2.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.validationEngine.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.MetaData.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.form.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.multiSelect.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>scripts.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jscript.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>ypSlideOutMenusC.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>ddaccordion.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.maskedinput-1.2.2.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.autocomplete.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.elastic.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>jquery.datepick.js"></script>




<!--initialize JS addons-->
<script type="text/javascript">
$(document).ready(function() {
                	<?php
                	/*
                	 * Every form having fields to be
                	 * validation, should have id='valForm'
                	 */
                	?>
                    $("#valForm").validationEngine();
                });
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
</head>
<body>
<div id="wrapper">
	<div id="header" class="container">
		<div id="logo">
			<h1><a href="#"><?php echo ucwords(SITE_TITLE); ?></a></h1>
			<p><?php echo SITE_SLOGAN; ?></p>
		</div>
		<div id="menu">
			 
<ul>
		<li><a href="<?php echo URL;?>">Homepage</a></li>
				<li><a href="index.php?action=about">About</a></li> 
				<li><a href="index.php?action=contact">Contact</a></li>
			</ul>
		</div>
	</div>
	<!-- end #header -->
	<div id="page">
		<div id="content">
			
			<?php echo $module; ?>
			
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
				<li>
					<?php
						echo implode('</li><li>',$widgets);
					?>
					
				</li>
				
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer-content">
	 
</div>
<div id="footer">
	<p>Copyright (c) 2011 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>
