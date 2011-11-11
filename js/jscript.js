// JavaScript Document
 
function changeUserStatus(s,i, u) {
			 $.post(
					"app/ajax/workorder_change_user_status.php",
					{st:s,id:i},
					function(data){
						if(data == '1') {
							alert('Requested action has been performed successfully.');	
							window.location = u;
						} else {
							alert(data);	
						}
					}
				);
				return false;
}

function getTopPosition(who){
    var T= 0,L= 0;
    while(who){
        L+= who.offsetLeft;
        T+= who.offsetTop;
        who= who.offsetParent;
    }
    //return [L,T];    
	return T;
}
function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.parentNode;
    }
    return { top: _y, left: _x };
}

function printPage(u) {
	//body-form-container-body
	myWin=window.open("","myWin","menubar,scrollbars,left=30px,top=40px,height=800px,width=800px"); 
	html =  $("#body-form-container-body").html();
	
	contentHeaders = '<html><head><style> .dont-show-in-print{display:none;}</style><link href="'+u+'css_styles.css" rel="stylesheet" type="text/css" /><link href="'+u+'css_containers.css" rel="stylesheet" type="text/css" />';
	contentHeaders += '<style>body{background-image:none; background-color:#fff;} input{background-image:none;} #form-borrower-container, #form-notary-container, #form-status-container {float:none; width:600px}</style></head><body>';
	contentHeaders +=  html;
	
	
	
	myWin.document.write(contentHeaders); 
	
	myWin.document.write('<script>window.document.close()</script>');
	
	contentHeaders = '</body></html>';
	
	myWin.document.write(contentHeaders);
	
	myWin.document.close();
	//setTimeout("myWin.document.alert()",5000);
	 
}
