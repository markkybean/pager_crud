var ajaxFunc = '';
var borrowed_item_count = 0;

	

function delete_borrow_item(par_itemkey){            
    $('lib' + par_itemkey).remove();
	borrowed_item_count-=1;
	
}

function summary_click (par_reckey){
    day = new Date();
    id = day.getTime();
    window.open('trn_circulation_for_reservation.php', 'trn_circulation_for_reservation', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=815,height=400,left = 112,top = 134');    
    document.forms.myform.reckey.value = par_reckey;
    document.forms.myform.action = "trn_circulation_for_reservation.php";
    document.forms.myform.target = "trn_circulation_for_reservation";
    
    document.forms.myform.submit();
}

function add_borrow_item(par_itemkey,par_acsnum,par_title,par_trndte,par_duedte,par_tom,par_rsv,par_acqkey) {
    
    try{
        var item = document.getElementById('lib' + par_itemkey);
               
        if (!item){
		
		borrowed_item_count+=1;
			var max_brw=parseInt(document.getElementById('req_count').value);
		var bor_tot = parseInt(document.getElementById('bor_count').value) + borrowed_item_count;
		if((bor_tot>max_brw)&&(max_brw!=0))
			{
			
			alert("The maximum number of allowed items on \n borrowing for this level is " + max_brw );
			
			}
		else{
				new Insertion.Bottom('tbl',"<tr id='lib" + par_itemkey +
            "'><td><input type='text' name='borrowed[" + par_itemkey + "][acsnum]' readonly='readonly'  value='"+ par_acsnum + 
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][title]' value='" + par_title +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][trndte]' value='" + par_trndte +
            "' /></td><td><input type='text' name='borrowed[" + par_itemkey + "][duedte]' value='" + par_duedte +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][mtype]' value='" + par_tom +
            "' /></td><td width='136px' align='center' style='text-align:center;'> " + par_rsv + 
			"</td><td width='100px' align='center'><input type='hidden' name='borrowed[" + par_itemkey + "][acqkey]' value='" + par_acqkey + "' />" + 
			"<input type='button' value='Delete' onclick=\"delete_borrow_item('" + par_itemkey +
            "');\" /></td></tr>");
            document.getElementById('txt_code').value = '';
			}
			
			
        }else{
            alert('Item already exists!');                    
        }



        
    }catch(e){            
        
    }
}
function add_others_borrow_item(par_itemkey,par_acsnum,par_title,par_trndte,par_duedte,par_tom,par_rsv,par_acqkey) {

    try{
        var item = document.getElementById('lib' + par_itemkey);

        if (!item){

	new Insertion.Bottom('tbl',"<tr id='lib" + par_itemkey +
            "'><td><input type='text' name='borrowed[" + par_itemkey + "][acsnum]' readonly='readonly'  value='"+ par_acsnum +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][title]' value='" + par_title +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][trndte]' value='" + par_trndte +
            "' /></td><td><input type='text' name='borrowed[" + par_itemkey + "][duedte]' value='" + par_duedte +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][mtype]' value='" + par_tom +
            "' /></td><td width='136px' align='center' style='text-align:center;'> " + par_rsv +
			"</td><td width='100px' align='center'><input type='hidden' name='borrowed[" + par_itemkey + "][acqkey]' value='" + par_acqkey + "' />" +
			"<input type='button' value='Delete' onclick=\"delete_borrow_item('" + par_itemkey +
            "');\" /></td></tr>");
            document.getElementById('txt_code').value = '';
			


        }else{
            alert('Item already exists!');
        }




    }catch(e){

    }
}


function add_return_item(par_itemkey,par_acsnum,par_title,par_trndte,par_duedte,par_tom,par_rsv,par_acqkey) {

    try{
        var item = document.getElementById('lib' + par_itemkey);

        if (!item){
			new Insertion.Bottom('tbl',"<tr id='lib" + par_itemkey +
            "'><td><input type='text' name='borrowed[" + par_itemkey + "][acsnum]' readonly='readonly'  value='"+ par_acsnum +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][title]' value='" + par_title +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][trndte]' value='" + par_trndte +
            "' /></td><td><input type='text' name='borrowed[" + par_itemkey + "][duedte]' value='" + par_duedte +
            "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][mtype]' value='" + par_tom +
            "' /></td><td width='136px' align='center' style='text-align:center;'> " + par_rsv +
			"</td><td width='100px' align='center'><input type='hidden' name='borrowed[" + par_itemkey + "][acqkey]' value='" + par_acqkey + "' />" +
			"<input type='button' value='Delete' onclick=\"delete_borrow_item('" + par_itemkey +
            "');\" /></td></tr>");
            document.getElementById('txt_code').value = '';

        }else{
            alert('Item already exists!');
        }




    }catch(e){

    }
}


function insert_borrow_item(){
    
    
    var params = "acsnum=" + encodeURIComponent(document.getElementById('txt_code').value);
    ajaxFunc = "eval(req.responseText)";
    DoCallback('ajax_borrow.php',params);	
}
function others_borrow_item(){

    
    var params = "acsnum=" + encodeURIComponent(document.getElementById('txt_code').value);
    ajaxFunc = "eval(req.responseText)";
    DoCallback('ajax_others_borrow_item.php',params);
}



function insert_returns_item(){
    
    
    var params = "acsnum=" + encodeURIComponent(document.getElementById('txt_code').value);
    ajaxFunc = "eval(req.responseText)";
    DoCallback('ajax_returns.php',params);
}

function save_borrow_circulation(){
    document.getElementById('event_action').value = 'borrow';
    document.forms.myform.action = 'trn_circulation_borrow.php';
    document.forms.myform.target='_self';
    document.forms.myform.submit();
}
function save_borrow_circulation_others(){
    document.getElementById('event_action').value = 'borrow';
    document.forms.myform.action = 'trn_circulation_others_borrow.php';
    document.forms.myform.target='_self';
    document.forms.myform.submit();
}

function confirmation(){
    var answer = confirm("Do you wish to exit?")
    if (answer){
        document.forms.myform.target = '_self';
        document.forms.myform.action = 'view_circulation.php';
        document.forms.myform.submit();
    }
}

function popUp() {
    day = new Date();
    id = day.getTime();
    window.open('view_reservation.php', 'view_reservation', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=815,height=400,left = 112,top = 134');
    document.forms.myform.action = "view_reservation.php";
    document.forms.myform.target = "view_reservation";
    document.forms.myform.submit();
}

function popUp_View() {
    day = new Date();
    id = day.getTime();
    window.open('view_details.php', 'view_details', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=815,height=400,left = 112,top = 134');
    document.forms.myform.action = "view_details.php";
    document.forms.myform.target = "view_details";
    document.forms.myform.submit();
}