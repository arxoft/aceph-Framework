<?php
class error {
	
	function __construct($error = '', $title = '') {
		
			$this->stack = array();
			$this->total =0;
			$this->lock = false;
			
			if($error) $this->add($error,$title);
			
	}
	
	function add($error, $title = '', $lock = false) {
		if(!$this->lock) {
				if($error){
					if($title) {
						
						 
						$this->stack[$title] = $error;	
						
						
					}
					else
						$this->stack[] = $error;	
				}
				$this->total ++;
				$this->lock = $lock;
		}
	
	}
	
	function remove($title) {
		
		if($title) {
			unset($this->stack[$title]);	
		}
	}
	
	
	function lockStack() {
		$this->lock = true;	
	}
	
	
	function blank() {
		
		$this->stack = array();
		
	}
	
	function jq_print_errors() {
	
		if(count($this->stack)) {
			 
			foreach($this->stack as $k=>$v) {

					?>
						<div class="ui-widget"  style="width:100%">
			
							<div style="padding: 0pt 0.7em; margin-top: 8px;" class="ui-state-error ui-corner-all"> 
								<span  style="float:left; "  class="ui-icon ui-icon-info"></span>
								<?php echo ((!is_numeric($k) && $k) ? "<b>".$k."</b>: " : "").$v; ?>
							</div>
							
						</div>
					<?php	
					
			}
		}
		
	}
	
	function jq_print_info() {
		
		if(count($this->stack)) {
			 
			foreach($this->stack as $k=>$v) {

					?>
					 <div class="ui-widget"  style="width:100%">
						<div style="padding: 0pt 0.7em; margin-top: 8px;" class="ui-state-highlight ui-corner-all">
						   <span  style="float:left; "  class="ui-icon ui-icon-info" ></span>
						   <?php echo ((!is_numeric($k) && $k) ? "<b>".$k."</b>: " : "").$v; ?>
						</div>
					  </div>
      				<?php	
					
			}
		}
	}

	
}

?>