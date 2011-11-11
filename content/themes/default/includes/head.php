<?php 
	/*
	 * Title of page will be a constant
	 * that is defined in a module ~ MODULE_TITLE
	 * If not defined, make SITE_TITLE as <title> of page
	 * Also applies to <meta> description and <meta> keywords
	 * 
	 * */
?>

<title><?php echo (defined('MODULE_TITLE') && MODULE_TITLE) ? MODULE_TITLE.' :: '.SITE_TITLE : SITE_TITLE ?></title>


<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo (defined('MODULE_META_DESCRIPTION') && MODULE_META_DESCRIPTION) ? MODULE_META_DESCRIPTION : SITE_META_DESCRIPTION ?>" />
<meta name="keywords" content="<?php echo (defined('MODULE_META_KEYWORDS') && MODULE_META_KEYWORDS) ? MODULE_META_KEYWORDS : SITE_META_KEYWORDS ?>"/>



<!-- Include all stylesheets -->
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>jquery.multiSelect.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>pagination.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>themeroller/jquery-ui-1.8.9.custom.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>jquery.autocomplete.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CSS_URL;?>flora.datepick.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?php echo DIR_CURRENT_THEME_URL;?>css/style.css" type="text/css" media="screen" title="no title" charset="utf-8" />


<!-- Include Fav Icon -->
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<!-- Include all Javascripts -->

<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>arial.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_JS_URL;?>cuf_run.js"></script>
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
</script>