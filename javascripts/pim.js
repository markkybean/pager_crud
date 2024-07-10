function refresh_educational_background_list(par_fld,par_refcde,par_tablename){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde + '&tablename=' + par_tablename;
    var loadUrl = "list_educational_background.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('eduback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_educational_background(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    var loadUrl = "list_educational_background.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_educational_background_list(par_fld,par_refcde,par_tablename);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_educational_background(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    lightbox.prototype.open_lbox3('proc_educational_background.php?' + params);  
}

function refresh_skills_information_list(par_fld,par_refcde,par_tablename){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde + '&tablename=' + par_tablename;
    var loadUrl = "list_skills_information.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('skiback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_skills_information(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    var loadUrl = "list_skills_information.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_skills_information_list(par_fld,par_refcde,par_tablename);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_skills_information(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    lightbox.prototype.open_lbox3('proc_skills_information.php?' + params);  
}

function refresh_exams_taken_list(par_fld,par_refcde,par_tablename){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde + '&tablename=' + par_tablename;
    var loadUrl = "list_exams_taken.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('extback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_exams_taken(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    var loadUrl = "list_exams_taken.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_exams_taken_list(par_fld,par_refcde,par_tablename);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_exams_taken(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    lightbox.prototype.open_lbox3('proc_exams_taken.php?' + params);  
}

function refresh_past_employment_list(par_fld,par_refcde,par_tablename){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde + '&tablename=' + par_tablename;
    var loadUrl = "list_past_employment.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('pseback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_past_employment(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    var loadUrl = "list_past_employment.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_past_employment_list(par_fld,par_refcde,par_tablename);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_past_employment(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    lightbox.prototype.open_lbox3('proc_past_employment.php?' + params);  
}

function refresh_requirements_list(par_fld,par_refcde,par_tablename){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde + '&tablename=' + par_tablename;
    var loadUrl = "list_requirements.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('reqback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_requirements(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    var loadUrl = "list_requirements.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_requirements_list(par_fld,par_refcde,par_tablename);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_requirements(par_fld,par_refcde,par_itmcde,par_tablename)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde + '&tablename=' + par_tablename;
    lightbox.prototype.open_lbox3('proc_requirements.php?' + params);  
}


function refresh_app_siblings_list(par_fld,par_refcde){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_app_siblings.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('osback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function refresh_emp_siblings_list(par_fld,par_refcde){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_emp_siblings.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('osback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}



function refresh_other_earnings_list(par_fld,par_refcde){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_other_earnings.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('oeback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_app_siblings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_app_siblings.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_app_siblings_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}


function delete_emp_siblings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_emp_siblings.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_emp_siblings_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}


function delete_other_earnings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_other_earnings.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_other_earnings_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}
function edit_app_siblings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_app_siblings.php?' + params);  
}

function edit_emp_siblings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_emp_siblings.php?' + params);  
}

function edit_other_earnings(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_other_earnings.php?' + params);  
}

function refresh_allowance_list(par_fld,par_refcde){

//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_allowance.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('awback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_allowance(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_allowance.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_allowance_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_allowance(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_allowance.php?' + params);  
}

function refresh_other_deductions_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_other_deductions.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('odback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_other_deductions(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_other_deductions.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_other_deductions_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_other_deduction(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_other_deductions.php?' + params);  
}

function refresh_leaves_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_leaves.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('leback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;   
}

function delete_leaves(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_leaves.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_leaves_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_leaves(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_leaves.php?' + params);  
}





function refresh_loans_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_loans.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('loback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_loans(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_loans.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_loans_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_loans(par_fld,par_refcde,par_itmcde)
{
 	var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_loans.php?' + params);  
}


function refresh_ranklevel_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_ranklevel.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('rnklvlback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}
function delete_ranklevel(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_ranklevel.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_ranklevel_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_ranklevel(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_ranklevel.php?' + params);  
}

function refresh_emp_retirees_list(par_fld,par_refcde){
//alert('heelo');

    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_emp_retirees_history.php?" + params;

    var callback = {

        success: function(o) {
           document.getElementById('retback').innerHTML =  o.responseText;
        },

        failure: function(o) {
            //alert("AJAX doesn't work"); //FAILURE
        }

    }

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;


}

function delete_emp_retirees(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_emp_retirees_history.php?" + params;
    var callback = {

        success: function(o) {
            refresh_emp_retirees_list(par_fld,par_refcde);
        },

        failure: function(o) {
            //alert("AJAX doesn't work"); //FAILURE
        }

    }
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_emp_retirees(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_emp_retirees.php?' + params);
}





function refresh_position_history_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_position_history.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('posback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_position_history(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_position_history.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_position_history_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_position_history(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_position_history.php?' + params);  
}

function refresh_salary_history_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_salary_history.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('salback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_salary_history(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_salary_history.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_salary_history_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_salary_history(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_salary_history.php?' + params);  
}

function refresh_awards_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_awards.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('awdback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_awards(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_awards.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_awards_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_awards(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_awards.php?' + params);  
}

function refresh_seminars_attended_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_seminars_attended.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('semback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_seminars_attended(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_seminars_attended.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_seminars_attended_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_seminars_attended(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_seminars_attended.php?' + params);  
}

function refresh_violations_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_violations.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('vioback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_violations(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_violations.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_violations_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_violations(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_violations.php?' + params);  
}

function refresh_licenses_list_app(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_licenses_app.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('licback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function refresh_licenses_list(par_fld,par_refcde){
//alert('heelo');
 
    var params = 'fld=' + par_fld + '&refcde=' + par_refcde;
    var loadUrl = "list_licenses.php?" + params;

    var callback = { 

        success: function(o) {    
           document.getElementById('licback').innerHTML =  o.responseText;          
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   

    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

   
}

function delete_licenses_app(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_licenses_app.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_licenses_list_app(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function delete_licenses(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=delete&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    var loadUrl = "list_licenses.php?" + params;
    var callback = { 

        success: function(o) {    
            refresh_licenses_list(par_fld,par_refcde);
        },  

        failure: function(o) {    
            //alert("AJAX doesn't work"); //FAILURE            
        }   

    }   
    var transaction = YAHOO.util.Connect.asyncRequest('POST', loadUrl, callback, null);

    return false;

}

function edit_licenses(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_licenses.php?' + params);  
}
function edit_licenses_app(par_fld,par_refcde,par_itmcde)
{
    var params = 'event_action=edit&fld=' + par_fld + '&refcde=' + par_refcde + '&item_id=' + par_itmcde;
    lightbox.prototype.open_lbox3('proc_licenses_app.php?' + params);  
}