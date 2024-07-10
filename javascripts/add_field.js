var ajaxFunc = '';

function add_field() {
    
    new Insertion.Bottom('tbl',"<tr id='school" + par_itemkey +
    "'><td><input type='text' name='borrowed[" + par_itemkey + "][acsnum]' readonly='readonly'  value='"+ par_acsnum + 
    "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][title]' value='" + par_title +
    "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][trndte]' value='" + par_trndte +
    "' /></td><td><input type='text' name='borrowed[" + par_itemkey + "][duedte]' value='" + par_duedte +
    "' /></td><td><input type='text' readonly='readonly' name='borrowed[" + par_itemkey + "][mtype]' value='" + par_tom +
    "' /></td><td width='136px' align='center' style='text-align:center;'> " + par_rsv + "</td><td width='100px' align='center'><input type='button' value='Delete' onclick=\"delete_borrow_item('" + par_itemkey +
    "');\" /></td></tr>");
    document.getElementById('txt_code').value = '';

}

function add_field_click(){
    
    ajaxFunc = "eval(req.responseText)";
    DoCallback('ajax_borrow.php',params);
}
