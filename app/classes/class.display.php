<?php

class display {

	function __construct() {
		
		
	
	}
	
	static function simple_box($title = '', $content = '') {
	
			
			 ?>
			 	  <table width="100%" border="0" cellspacing="1" cellpadding="5">
				  	<?php if($title) { ?>
                      <tr>
                        <td bgcolor="#EDECE9" style="color:#333333; font-size:13px;height:33px;border:1px solid #CCCCCC;border-bottom:3px solid #666666"><div align="center"><strong><?php echo $title; ?></strong></div></td>
                      </tr>
					 <?php } ?>
                      <tr>
                        <td style="border:1px solid #CCCCCC;padding:5px"><?php echo $content; ?></td>
                      </tr>
</table>
			<?php
		
	} //simple_box
	
	static function getPageContent($url) {
				  
				if(file_exists($url)){
				   ob_start();
				   include($url);
				   $data = ob_get_contents();
				   ob_end_clean();
				   return $data;
				} else{
					$error = new error();
					$error->add('Sorry, the requested content does not exist.');
					ob_start();
					$error->jq_print_errors();
					$data = ob_get_contents();
				    ob_end_clean();
				    return '<div class="module-not-exists" >'.$data.'</div>';
					
				}
	
	}
	static function getWidgetContent($url) {
				  
				if(file_exists($url)){
				   ob_start();
				   include($url);
				   $data = ob_get_contents();
				   ob_end_clean();
				   return $data;
				} else{
					$error = new error();
					$error->add('Sorry, the requested widget does not exist.');
					ob_start();
					$error->jq_print_errors();
					$data = ob_get_contents();
				    ob_end_clean();
				    return '<div class="module-not-exists" >'.$data.'</div>';
					
				}
	
	}
}







?>