// Simple JavaScript Rotating Banner Using jQuery
// www.mclelun.com
var jqb_vCurrent = 0;
var jqb_vTotal = 0;
var jqb_vDuration = 6000;
var jqb_intInterval = 0;
var jqb_vGo = 1;
var jqb_vIsPause = false;
var jqb_tmp = 20;

jQuery(document).ready(function() {	
	jqb_vTotal = $(".jqb_slides").children().size() -1;
	$(".jqb_info").text($(".jqb_slide").attr("title"));	
	jqb_intInterval = setInterval(jqb_fnLoop, jqb_vDuration);
			
	$("#jqb_object").find(".jqb_slide").each(function(i) { 
		jqb_tmp = ((i - 1)*598) - ((jqb_vCurrent -1)*598);
		$(this).animate({"left": jqb_tmp+"px"}, 500);
	});
	
	$("#btn_pauseplay").click(function() {
		if(jqb_vIsPause){
			jqb_fnChange();
			jqb_vIsPause = false;
			$("#btn_pauseplay").removeClass("jqb_btn_play");
			$("#btn_pauseplay").addClass("jqb_btn_pause");
		} else {
			clearInterval(jqb_intInterval);
			jqb_vIsPause = true;
			$("#btn_pauseplay").removeClass("jqb_btn_pause");
			$("#btn_pauseplay").addClass("jqb_btn_play");
		}
	});
	$("#btn_prev").click(function() {
		jqb_vGo = -1;
		jqb_fnLoop();
	});
		
	$("#btn_next").click(function() {
		jqb_vGo = 1;
		jqb_fnLoop();
	});
});

function jqb_fnChange(){
	clearInterval(jqb_intInterval);
	jqb_intInterval = setInterval(jqb_fnLoop, jqb_vDuration);
	jqb_fnLoop();
}

function jqb_fnLoop(){
	if(jqb_vGo == 1){
		jqb_vCurrent == jqb_vTotal ? jqb_vCurrent = 0 : jqb_vCurrent++;
	} else {
		jqb_vCurrent == 0 ? jqb_vCurrent = jqb_vTotal : jqb_vCurrent--;
	}
	
	
		
	$("#jqb_object").find(".jqb_slide").each(function(i) { 
		if(i == jqb_vCurrent){
			$(".jqb_info").text($(this).attr("title"));
		}
		
		//Horizontal Scrolling
		jqb_tmp = ((i - 1)*598) - ((jqb_vCurrent -1)*598);
		$(this).animate({"left": jqb_tmp+"px"}, 500);
		
		/*
		//Fade In & Fade Out
		if(i == jqb_vCurrent){
			$(".jqb_info").text($(this).attr("title"));
			$(this).animate({ opacity: 'show', height: 'show' }, 500);
		} else {
			$(this).animate({ opacity: 'hide', height: 'hide' }, 500);
		}
		*/
		
	});


}





