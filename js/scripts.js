function doAction(newAction){
		
		if(newAction != ''){
			
			switch(newAction){
			
				case "Delete":
							if(confirm("Are you SURE you want to delete selected rows?")) {
								document.myForm.submitType.value = newAction;
								document.myForm.submit();
							}
									break;
									
				case "Publish":
									document.myForm.submitType.value = "changeStatus";
									document.myForm.newStatus.value = "1";		
									document.myForm.submit();
									break;
									
				case "UnPublish":
									document.myForm.submitType.value = "changeStatus";
									document.myForm.newStatus.value = "0";		
									document.myForm.submit();
									break;
									
				case "pending review":
									document.myForm.submitType.value = "changeStatus";
									document.myForm.newStatus.value = "-1";		
									document.myForm.submit();
									break;
									
				case "cloneProduct":
									if(confirm("Are you sure you want to add clone products?")) {
										document.myForm.submitType.value = newAction;
										document.myForm.bulk_list.value = 'true';		
										document.myForm.submit();			
									}
									break;
									
				case "changeApprove":
									document.myForm.submitType.value = newAction;
									document.myForm.newStatus.value = status;	
									document.myForm.submit();
									break;

				default:
									document.myForm.submitType.value = newAction;
									document.myForm.submit();
									break;
			}
		}else{
			
			alert("Please select any action from list");
			return false;
		}
		
}

function checkUncheckAll(theElement) {
	var theForm = theElement.form, z = 0;
	for(z=0; z<theForm.length;z++){
		if(theForm[z].type == 'checkbox' && theForm[z].name == 'recordID[]'){
			theForm[z].checked = theElement.checked;
		}
	}
}

function checkOne(toCheck){
 theForm = document.myForm;
 for(z=0; z<theForm.length;z++){
  if(theForm[z].type == 'checkbox' && theForm[z].name == 'id[]' || theForm[z].name == 'checkall'){
  theForm[z].checked = false;
  }
 }
 
 document.getElementById(toCheck).checked=true;
}

var goSearch = function(){
	$("#searchKeyword").val($("#keyword").val());
	document.myForm.submit();
}
//end of search auto complete
function doSubmit(addAnother){
	document.myForm.addMore.value=addAnother;
}

var updateSession = function(o){
		$("#update_session").val('1');
		if(o.type == 'sort'){
			$("#update_session_action_sortby").val(o.sortby);
			$("#update_session_action_orderby").val(o.orderby);
		}else if(o.type == 'limit'){
			$("#update_session_action_getlimit").val(o.limit);
		}
	document.myForm.submit();
}

function LTrim( value ) {		
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");		
}

function RTrim( value ) {		
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");		
}

function trim( value ) {		
	return LTrim(RTrim(value));		
}
	
function createSEOName(val) {
  val = val.replace("&","and");
  var re1 = /[^A-Za-z0-9]+/g
  var re2 = /[ ]+/g
  val = val.replace(re1," ");
  val = val.replace(re2," ");
  val = trim(val);
  val = val.replace(re2,"-");
  val = val.toLowerCase();
  document.myForm.seoName.value="";  
  document.myForm.seoName.value=val;
 }
 
 var buildPrompt = function(caller,promptText) {			// ERROR PROMPT CREATION AND DISPLAY WHEN AN ERROR OCCUR
	var divFormError = document.createElement('div')
	var formErrorContent = document.createElement('div')
	var arrow = document.createElement('div')
	
	
	$(divFormError).addClass("formError")
	$(divFormError).addClass($(caller).attr("name"))
	$(formErrorContent).addClass("formErrorContent")
	$(arrow).addClass("formErrorArrow")

	$("body").append(divFormError)
	$(divFormError).append(arrow)
	$(divFormError).append(formErrorContent)
	$(arrow).html('<div class="line10"></div><div class="line9"></div><div class="line8"></div><div class="line7"></div><div class="line6"></div><div class="line5"></div><div class="line4"></div><div class="line3"></div><div class="line2"></div><div class="line1"></div>')
	$(formErrorContent).html(promptText)

	callerTopPosition = $(caller).offset().top;
	callerleftPosition = $(caller).offset().left;
	callerWidth =  $(caller).width()
	callerHeight =  $(caller).height()
	inputHeight = $(divFormError).height()

	callerleftPosition = callerleftPosition + callerWidth -30
	callerTopPosition = callerTopPosition  -inputHeight -10

	$(divFormError).css({
		top:callerTopPosition,
		left:callerleftPosition,
		opacity:0
	})
	$(divFormError).fadeTo("fast",0.8);
};

var closePrompt = function(caller) {	// CLOSE PROMPT WHEN ERROR CORRECTED
	closingPrompt = $(caller).attr("name")

	$("."+closingPrompt).fadeTo("fast",0,function(){
		$("."+closingPrompt).remove()
	});
};

showHideRow = function(row,col,refData){			
	if($("#"+row).is(':hidden')){
		$("#"+row).fadeIn("slow");
		
		if(refData !='' || refData !=null){	
			$("#"+col).html($("#"+refData).html());
			$("#"+col).addClass("validate[required,custom[onlyLetter],length[0,40]] txtField");
		}
					
	 }else {
		
		if(col !='' || col !=null){
			$("#"+col).removeClass("validate[required,custom[onlyLetter],length[0,40]] txtField");
			$("#"+col).html("");				
		}
		
		$("#"+row).fadeOut("slow");			
	 }
}

 

