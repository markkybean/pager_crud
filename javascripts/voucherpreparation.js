window.onload = initForms;

var xglo_glent;

function initForms()
{
	document.getElementById('tr_import').style.display = 'none';

	document.getElementById('btn_fakeimport').onclick = function(){
		document.getElementById('tr_import').style.display = '';
	};
	document.getElementById('btn_fcancel').onclick = function(){
		document.getElementById('tr_import').style.display = 'none';
	};

	document.getElementById('btn_fupload').onclick = importGLEntry;


	document.getElementById('fileupload').onchange = function(){

		readFileExtension(this);

		var file = this.files[0];

		// console.log(file);
		// console.log(this);
		// console.log(document.getElementById('fileupload'));

		var reader = new FileReader();
		reader.onload = function(progressEvent) {
			// Entire file
			// console.log(this.result);

			xglo_glent = this.result;

			// By lines
			// var lines = this.result.split('\n');
			// for(var line = 0; line < lines.length; line++)
			// {
			// 	console.log(lines[line]);
			// }
		};
		reader.readAsText(file);
	};

}

jQuery(document).ready( function() {

	compute_totewt()
		
	jQuery(".amtfld").autoNumeric();
	var xpaytyp	= jQuery('#txtdirpaytyp').val().toUpperCase();
		
	jQuery("#div_addinvoice").dialog({
		width: 800,
		height: 540,
		autoOpen: false,
		title: 'Add Invoice',
		resizable: false,
		modal: true,
		buttons : {
			'Cancel' : function (){ jQuery("#div_addinvoice").dialog('close') } ,
			'Save' : function (){ save_invoice(); }
		}
	});

	jQuery("#div_glent_err").dialog({
		width: 800,
		height: 540,
		autoOpen: false,
		title: 'Import GL Entry Error List',
		resizable: false,
		modal: true,
		buttons : {
			'Ok' : function (){ jQuery(this).dialog('close') }
		}
	});

	jQuery("#div_glent_loading").dialog({
		width: 200,
		height: 180,
		autoOpen: false,
		title: 'Importing GL Entry...',
		resizable: false,
		modal: true
	});

	validate_dirpaytyp()

	// if ( xpaytyp == 'CASH' || xpaytyp == 'CHECK' )
	// {
	// 	jQuery('.pay_cash').show();
		
	// 	if ( xpaytyp == 'CASH' )
	// 	{
	// 		jQuery('.pay_cash').hide();
	// 	}
		
	// 	jQuery('.auto_debit').hide();
	// 	jQuery('.memtypcde').hide();
	// }
	// else if(xpaytyp == 'AUTO DEBIT')
	// {
	// 	jQuery('#txtdirbnkcde').val('');
	// 	jQuery('.pay_cash').hide();
	// 	jQuery('.auto_debit').show();
	// }
	// else
	// {
	// 	jQuery('.memtypcde').show();
	// }
		
	jQuery("input[type='text']").attr("autocomplete", "off");
	// jQuery("input.auto").autoNumeric();
		
	jQuery("#txttrndte").datepicker({
		 dateFormat : "mm-dd-yy"
	});

	jQuery("#txtreceivedte").datepicker({
		 dateFormat : "mm-dd-yy"
	});

	jQuery("#txt_duedte").datepicker({
		 dateFormat : "mm-dd-yy"
	});
		
	jQuery('#tabs').tabs();
		
	if ( jQuery("#par").val() == 'Add' )
	{ 
		jQuery('#tabs').hide();
		
		jQuery(".td_dir_col").css('display', 'none');
		
		display_dirpay_par();			
	}
	else
	{
		jQuery(".td_dir_col_add").css("visibility", "hidden" )
		if ( jQuery("#txtdirpay").attr("checked") )
		{
			jQuery(".td_dir_col").css("display", "" )
		}			
	
		var xdirpay	= jQuery('#txtdirpay').attr('checked');	
		
		if ( xdirpay )
		{
			jQuery(".dir_pay").css('display', '');
			
			jQuery('.in_dir_pay').css('display', 'none');
			
			jQuery('#txtamount').attr('readonly',true);
			jQuery('.dir_pay_hide').css('display','none');
			jQuery(".ewtgroamt").attr("readonly", false);
			jQuery(".td_dir_col").css('display', '');
			jQuery('.multipay_hide').css('display','');
		}		
		else
		{
			jQuery(".dir_pay").css('display', 'none');
			jQuery(".td_dir_col").css('display', 'none');
			jQuery('#txtamount').attr('readonly',true);
			jQuery('.multipay_hide').css('display','none');
		}
	}
		
	// jQuery("#txtreqptcde").autocomplete({
	   // minLength: 1,
	   // source: "ajax_search_requestparty.php",
	   // select: function(event, ui){
				// jQuery('#txtreqptcde<?php echo $idx; ?>').val(ui.item.label);
				// return false;
		// }
	// });
	
	// jQuery("#txttieupcde").autocomplete({
	   // minLength: 1,
	   // source: "ajax_search_tieup.php",
	   // select: function(event, ui){
				// jQuery('#txttieupcde<?php echo $idx; ?>').val(ui.item.label);
				// return false;
		// }
	// });

	jQuery("#txttrmcde").autocomplete({
	   minLength: 1,
	   source: "ajax_search_terms.php?field=trmcde&xcash=CASH",
	   select: function(event, ui){
				jQuery('#txttrmcde<?php echo $idx; ?>').val(ui.item.label);
				return false;
		}
	});
		
	manualgl_click();

	var gl_limit = 100;

	for(var y=1; y<=gl_limit; y++)
	{
		jQuery("#txtactdsc"+y).attr("readonly", true);
	}
		
});
function cleartbl(id)
{
	// jQuery("tr[rowvat"+id+"] input[type=text]").val('');
	// jQuery("tr[rowvat"+id+"] select").children().removeAttr("selected");
	  jQuery("#gl"+id +" input[type='text']").val("");
}
function loading()
{	
	jQuery("#loading").dialog({
		resizable: false,
		width: 250,
		height: 150,
		zIndex: 3999
	});
	
}
function hide_loading()
{
	jQuery("#loading").dialog('close');
}
function validate_dirpaytyp()
{
	// alert("pass");
	
	var xpaytyp	= jQuery('#txtdirpaytyp').val().toUpperCase();
	console.log(xpaytyp);
	if ( xpaytyp == 'CASH' || xpaytyp == 'CHECK' )
	{
		jQuery('.pay_cash').show();
		
		if ( xpaytyp == 'CASH' )
		{
			jQuery('.pay_cash').hide();
		}

		jQuery('.auto_debit').hide();
		jQuery('.memtypcde').hide();
	}
	else if(xpaytyp == 'AUTO DEBIT')
	{
		jQuery('#txtdirbnkcde').val('');
		jQuery('.pay_cash').hide();
		jQuery('.auto_debit').show();
	}
	else if(xpaytyp == 'BANK DEBIT MEMO')
	{
		jQuery('.bnk_deb_memo').hide();
		jQuery('.bnk_deb_memo_show').show();
	}
	else
	{
		jQuery('.memtypcde').show();
	}
}
function saveexit_click()
{
	console.log('saveexit');

	var xbool=validte_chk_manual_gl();

	if(xbool)
	{
		xtrndte = jQuery('#txttrndte').val();
		dtelockfrm = jQuery('#hid_dtelockfrom').val();;
		dtelockto = jQuery('#hid_dtelockto').val();;
		
		var xchkdte_from = check_greaterdate( xtrndte , dtelockfrm );
		var xchkdte_to = check_greaterdate( dtelockto , xtrndte  );
		
		if (xtrndte == dtelockfrm)
		{
			xchkdte_from = true;
		}
		if (xtrndte == dtelockto)
		{
			xchkdte_to = true;
		}
		// console.log(xchkdte_from);
		// console.log(xchkdte_to);
		if (xchkdte_from  &&  xchkdte_to)
		{
			jQuery("#par").attr("value", "exit");
			
			var xdebit  = 0;
			var xcredit = 0;
			var num;
			var xtable_deamt_creamt = true;
			var maxcount   = parseInt(document.getElementById('maxcount').value);
			if(maxcount == 20)
			{
				maxcount = 100;
			}
			// console.log(maxcount);
			var xchkmanualgl	= jQuery("#chkmanualgl").attr("checked");

			console.log("xchkmanualgl "+xchkmanualgl);
			
			if ( xchkmanualgl )
			{
				for( var i=1; i<= maxcount; i++ )
				{
					 xdebamt = document.getElementById('txtdebamt'+i).value.replace(/,/g,'');
					 xcreamt = document.getElementById('txtcreamt'+i).value.replace(/,/g,'');

					 
					 
					 if ( xdebamt != '' )
					 {
						xdebit = xdebit + parseFloat( xdebamt );  			
					 }
					 if ( xcreamt != '' )
					 {
						xcredit = xcredit + parseFloat( xcreamt ); 
					 }	

					 console.log("Number "+i+" Debamt: "+xdebamt+" Running Bal: "+xdebit);
					 console.log("Number "+i+" Creamt: "+xcreamt+" Running Bal: "+xcredit);
				}
				
				xdebit = math.round(xdebit,2);
				xcredit = math.round(xcredit,2);

				console.log("Total Debit: "+xdebit);
				console.log("Total Credit: "+xcredit);
				
				if ( xdebit.toFixed(2) != xcredit.toFixed(2) )
				{
					alert( "Debit and Credit amount must be equal" );
					
					 return false;
				}
			}
			
			// if (jQuery.trim(jQuery('#txtdirpaytyp').val()) == 'BANK TRANSFER' )
			if (String(jQuery('#txtdirpaytyp').val()).trim() == 'BANK TRANSFER' )
			{
				// if (jQuery.trim(jQuery('#txtrefnum').val()) == '')
				if (String(jQuery('#txtrefnum').val()).trim() == '')
				{
					alert('Plese fill-up reference no.');
					// jQuery('#txtrefnum').focus()
					jQuery('#txtrefnum').trigger('focus')
					return false;
				}
			}
			// if(jQuery.trim(jQuery('#txtdirpaytyp').val()) == 'CHECK'  ||  jQuery.trim(jQuery('#txtdirpaytyp').val()) == 'check' )
			if(String(jQuery('#txtdirpaytyp').val()).trim() == 'CHECK'  ||  String(jQuery('#txtdirpaytyp').val()).trim() == 'check' )
			{
				if(jQuery("#txtdirchknum").val() == '' )
				{
					
					alert('Plese fill-up Bank Account! .');
					// jQuery('#txtdirchknum').focus()
					jQuery('#txtdirchknum').trigger('focus')
					return false;
				}
				// else
				// {
					// var count = jQuery("#txtdirchknum").val().length;
					// if(count <10)
					// {
						// alert("Invalid Check Number!!")
						// jQuery('#txtdirchknum').focus();
						// return false;
					// }
				// }
				if(jQuery("#txtdirchknum").val() == '' )
				{
					
					alert('Plese fill-up Check Number! .');
					// jQuery('#txtdirchknum').focus()
					jQuery('#txtdirchknum').trigger('focus')
					return false;
				}
				if(jQuery("#txtdirchkdte").val() == '' )
				{
					
					alert('Plese fill-up Check Date! .');
					// jQuery('#txtdirchkdte').focus()
					jQuery('#txtdirchkdte').trigger('focus')
					return false;
				}
				
			}
			var xform_data	= jQuery("#myform *").serialize();	

			jQuery.ajax({
				url : 'trn_voucherpreparation_inc.php',
				type: 'post',
				dataType: 'json',
				data: xform_data,
				success: function ( xresult )
				{
					if ( xresult.length == 0 )
					{
						alert("Data Saved!");
						window.location	= "view_voucherpreparation.php";
					}
					else
					{
						alert( xresult.msg ); 
					}
				}
			});
		}
		else
		{
			alert("Date Lock Out From " + dtelockfrm  + " to " + dtelockto)
			return;
		}
	}//endif validate
	else
	{
		alert("GL Entry is empty.");
	}

}
function display_dirpay_par()
{
	var xchk_dirpay	= jQuery("#txtdirpay").attr("checked");
	
	if ( xchk_dirpay )
	{
		jQuery('#txtamount').attr('readonly',false);
		
		jQuery(".dir_pay_upon_add").show();
		jQuery("#btnsave1").attr("innerHTML", "Save");
		jQuery("#btnsave").attr("value", "Save");
	}
	else
	{
		jQuery('#txtamount').attr('readonly','readonly');
		jQuery(".dir_pay_upon_add").hide();
		jQuery("#btnsave1").attr("innerHTML", "Display outstanding");
		jQuery("#btnsave").attr("value", "Display outstanding");
	}
}
function add_payment( xdocnum, xpar )
{	
	jQuery('#tabs').tabs('select', 1);
	
	jQuery("#payment-application input[type='text']").attr("value", "");

	jQuery('.pay_cash .memtypcde').show();
	jQuery('.pay_cash').show();

	jQuery('#payment-details').hide();
	jQuery('#payment-application').show();
}
function cancelclick()
{
	jQuery('#payment-application').hide();
	jQuery('#payment-details').show();
}
function validate_paytyp()
{
	// alert("pass");
	var xpaytyp	= jQuery('#txtpaytyp').val().toUpperCase();
	
	if ( xpaytyp == 'CASH' || xpaytyp == 'CHECK' )
	{
		
		jQuery('.pay_cash').show();
		
		if ( xpaytyp == 'CASH' )
		{
			jQuery('.pay_cash').hide();
		}
	
		jQuery('.memtypcde').hide();
		jQuery('.auto_debit').hide();
	}
	else if(xpaytyp == 'AUTO DEBIT')
	{
		jQuery('#txtdirbnkcde').val('');
		jQuery('.pay_cash').hide();
		jQuery('.auto_debit').show();
	}
	else
	{
		jQuery('.memtypcde').show();
	}
}
function applyallclick( x )
	{
		var idx	= jQuery( x ).parent().parent().attr( 'id' );
		
		jQuery(x).hide();
		jQuery( x ).parent().parent().css( 'opacity', 0.60 );
		jQuery( "#"+idx +" .payment-img " ).show();
		jQuery( x ).parent().children("input").attr('disabled', true);
		
		var xdata	= [
		
						{name:'txtrecid', value: idx},
						{name:'event_action', value: 'apply_all'}
		
					  ];
		

		jQuery.ajax({
			url : 'proc_save_voucherprep.php',
			type : 'post',
			dataType : 'json',
			data : xdata,
			
			success : function(xresult){
						
						jQuery( "#"+idx ).find('.spn-bal').text( xresult.docbal );	
						jQuery(x).show();
						jQuery( x ).parent().parent().css( 'opacity', 1.0 );
						jQuery( "#"+idx +" .payment-img " ).hide();
						jQuery( x ).parent().children("input").attr('disabled', false);	
					  }
		});
	}
	
	function paydelclick( x )
	{	
		var idx	= jQuery( x ).parent().parent().attr( 'id' );
		jQuery( x ).parent().parent().css( 'opacity', 0.60 );
		jQuery( x ).parent().children("input").attr('disabled', true);
		
		var xdata	= [
		
						{name:'txtrecid', value: idx},
						{name:'event_action', value: 'delete'}
		
					  ];
		

		jQuery.ajax({
			url : 'proc_save_voucherprep.php',
			type : 'post',
			dataType : 'json',
			data : xdata,
			
			success : function( xresult ){
						
						jQuery('#txtamount').val(xresult.amttot);
						jQuery( x ).parent().parent().fadeOut( 399, function (){ 
								jQuery( this ).remove();
							});
					  }
		});
	}
	
	function applyclick( x )
	{
		var xcuscde	= jQuery("#txtsupcde").val();
		
		var idx	= jQuery( x ).parent().parent().attr( 'id' );
		
		var xdata	= [
						{name: 'txtrecid', value: idx}, 
						{name: 'txtcuscde', value: xcuscde},
						{name: 'event_action', value: 'retrieve_ar_data'}
					  ];
		
		jQuery.ajax({
				url : 'proc_apply_voucher.php',
				type: 'post',
				dataType: 'json',
				data: xdata,
				
				success : function ( x_ar_data )
						  {
							jQuery("#payment_application").empty();
							jQuery("#payment_application").append( x_ar_data.return );
							
							jQuery("#payment_application").dialog({
									width: 800,
									height: 540,
									title: 'AP Payment Application',
									resizable: false,
									modal: true,
									buttons : {
												'Cancel' : function (){ jQuery("#payment_application").dialog('close') } ,
												'Apply' : save_amt_apply_click
											}
								});
								
								jQuery("input.auto").autoNumeric();
						  }
			})
	}
	
	function save_amt_apply_click()
	{
		// var xparams	= jQuery("#payment_application * ").serialize().replace(new RegExp(',','g'),"");
		var xparams	= jQuery("#payment_application * ").serialize();
		// alert(xparams);
		jQuery.ajax({
				url : 'proc_apply_voucher.php',
				type: 'post',
				dataType :'json',
				data: xparams,
				
				success : function ( xresult )
							{
								jQuery("#"+xresult.recid+" .spn-bal").text( xresult.balance ); 
								
								 jQuery("#payment_application").dialog("close");
							}
			});
	}
	
	function validate_amountamtapp(par_id)
	{
		jQuery("#txtsal"+par_id).val(jQuery("#txttmpsal"+par_id).val().replace(new RegExp(',','g'),""))
		var	xamtapp	= jQuery("#txtsal"+par_id).val();	
		
		var xbalance= jQuery("#txtsaldocbal"+par_id).val()
		var xcuramtapp= jQuery("#"+par_id+" .curamtapp").html()		
		var xarbalance	= jQuery("#txtarbalance").val();
		console.log(xbalance);
		console.log(xamtapp);
		console.log(xarbalance);
		console.log(xcuramtapp);
		xamtapp	= parseFloat(xamtapp,10);
		xcuramtapp	= parseFloat(xcuramtapp,10);
		xbalance	= parseFloat(xbalance,10);
		xarbalance	= parseFloat(xarbalance,10);
		console.log(xbalance);
		console.log(xamtapp);
		console.log(xarbalance);
		console.log(xcuramtapp);
		xupdate	= true
				
		if ( xamtapp > ( xcuramtapp+xbalance ) )
		{
			jQuery("#txttmpsal"+par_id).attr("value", "0.00");
			jQuery("#txtsal"+par_id).attr("value", "0.00");
			// jQuery("#txtsal"+par_id).focus();
			// jQuery("#txttmpsal"+par_id).focus();
			jQuery("#txttmpsal"+par_id).trigger('focus');
			
			xupdate	= false;
			
			return false
		}
		
		if ( xamtapp > ( xarbalance + xcuramtapp ) )
		{
			jQuery("#txtsal"+par_id).attr("value", "0.00");
			jQuery("#txttmpsal"+par_id).attr("value", "0.00");
			// jQuery("#txtsal"+par_id).focus();
			// jQuery("#txtsal"+par_id).focus();
			jQuery("#txtsal"+par_id).trigger('focus');
			
			xupdate	= false;
			return false
		}		
		
		if ( xupdate )
		{
			var xnewarbalance	= xarbalance + xcuramtapp - xamtapp;
			var xnewbalance		= xbalance + xcuramtapp - xamtapp;
			var xnewcuramtapp	= xamtapp
			console.log(xnewbalance);
			jQuery("#txtarbalance").val( xnewarbalance.toFixed(2) == 'NaN' ? '0.00' : xnewarbalance.toFixed(2) );
			jQuery("#txtsaldocbal"+par_id).val(xnewbalance.toFixed(2) == 'NaN' ? '0.00' :  xnewbalance.toFixed(2) );
			jQuery("#"+par_id+" .curamtapp").html( xnewcuramtapp.toFixed(2) == 'NaN' ? '0.00' : xnewcuramtapp.toFixed(2));
		}
	}
	
    function func_exit()
    {
		document.forms.myform.action	= "view_voucherpreparation.php";
		document.forms.myform.target	="_self";
		document.forms.myform.submit();
    } 
    
	function manualgl_click()
	{		
		if ( jQuery("#chkmanualgl").attr("checked") )
		{
			jQuery("#actsubsidiary input[type='text']").attr("readonly", false);
			jQuery("#btngenerategl").attr("disabled", true)
			jQuery("#actsubsidiary img").show();
			
			
			var xchk_dirpay	= jQuery("#txtdirpay").attr("checked");
		
			if ( xchk_dirpay )
			{
				jQuery(".td_manual_gl").hide();
				jQuery("#txtdirmemtypcde").val('');
			}
		}
		else
		{
			jQuery("#actsubsidiary input[type='text']").attr("readonly", true);
			jQuery("#btngenerategl").attr("disabled", false)
			jQuery("#actsubsidiary img").hide();
			
			
			var xchk_dirpay	= jQuery("#txtdirpay").attr("checked");
		
			if ( xchk_dirpay )
			{
				jQuery(".td_manual_gl").show();
			}
		}
	}
		
	function to_decimal_place()
	{
		var xmaxcount = document.getElementById('maxcount').value;
		for( a = 1; a <= xmaxcount; a++)
		{
			xdebit = document.getElementById('txtdebamt'+a).value.replace(/,/g,'');
			xcredit = document.getElementById('txtcreamt'+a).value.replace(/,/g,'');
			
			if(xdebit!='')
			{
				document.getElementById('txtdebamt'+a).value = parseFloat(xdebit).toFixed(2);
			}
			if(xcredit!='')
			{
				document.getElementById('txtcreamt'+a).value = parseFloat(xcredit).toFixed(2);
			
			}		
		}	
	}
	
	function total_debitcredit()
	{
		
		var totaldebit = 0;
		var totalcredit = 0;
		var xdebit = 0;
		var xcredit = 0;
		var xmaxcount = document.getElementById('maxcount').value;
		if(xmaxcount == 20)
		{
			xmaxcount = 100;
			}
		console.log(xmaxcount+"pass");
		for(a = 1; a <= xmaxcount; a++)
		{
			 xdebit = document.getElementById('txtdebamt'+a).value;
			 xcredit = document.getElementById('txtcreamt'+a).value;
			 
			 xdebit = xdebit.replace(new RegExp(',','g'),"");
			 // xdebit = xdebit.replace(' ','');
			 
			 xcredit = xcredit.replace(new RegExp(',','g'),"");
			 // xcredit = xcredit.replace(' ','');
			
			if(xdebit!='')
			{
				totaldebit += parseFloat(xdebit);
				// document.getElementById('txtdebit').value = totaldebit.toFixed(2);
				jQuery("#txtdebit").html(totaldebit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			}
			if( xcredit!='')
			{
				totalcredit += parseFloat(xcredit);
				// document.getElementById('txtcredit').value = totalcredit.toFixed(2);
				jQuery("#txtcredit").html(totalcredit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			}
		}
	}	

	function paysaveclick()
	{
		var xdata	= jQuery('#payment-application :input').serialize();
		
		jQuery.ajax({
			url : 'proc_save_voucherprep.php',
			type : 'POST',
			dataType : 'json',
			data : xdata,
			
			success : function ( xresult )
					  {
						jQuery('#txtamount').val(xresult.amount_tot);
						var xnewrow	= jQuery('#tbl-payment-details .template').clone().removeClass('template').show();
					
                        xnewrow.attr('id', xresult.spnrecid);  
                    
						if ( xresult.spnmsg == '' )
						{
							for( var xdata in xresult )
							{
								xnewrow.find( '.'+xdata ).text( xresult[xdata] );
							}
						
							xnewrow.appendTo('#tbl-payment-details');
						
							// cancelclick();
							document.forms.myform.action	= "";
							document.forms.myform.target	= "_self";
							document.forms.myform.submit();
						}
						else
						{
							alert( xresult.spnmsg );
						}
					  }
		});
	}	
	
	function  search_receivables( par_ctrl_idx )
	{
		var xparams	= "txtdocnum="+jQuery('#txtdocnum').val();
	
        jQuery("#txtewtdocnum" + par_ctrl_idx).autocomplete( 
															"option", 
															"source", 
															"ajax_search_payment.php?" + xparams );
    }
   	

	function compute_tax_amount( par_ctrl_idx )
	{
		// console.log("pass");
		var xdata	= jQuery("#row"+par_ctrl_idx+" :input ").serialize();
		
		if ( jQuery('#chkdirpay').attr('checked') )
		{
			jQuery('#txttype'+par_ctrl_idx).html('DIR');
		}
		var compute_net = 0;
		var xtaxbase	= 0;
		var xtaxamt		= 0;
		// var xgroamt	= jQuery("#txtgroamt"+par_ctrl_idx).val();
		var xtaxamt = jQuery("#txttaxamt"+par_ctrl_idx).val();
		// xgroamt = xgroamt.replace("")
		// console.log(xgroamt.replace(",", ""));
		var xgroamt	= jQuery("#txtgroamt"+par_ctrl_idx).val().replace(new RegExp(',','g'),"");
		var xewtrte	= jQuery("#txtewtrte"+par_ctrl_idx).val().replace(new RegExp(',','g'),"");
		var xvatrte	= jQuery("#txtvatrte"+par_ctrl_idx).val().replace(new RegExp(',','g'),"");
		var xdocnum	= jQuery("#txtewtdocnum"+par_ctrl_idx).val();
		// xgroamt = parseFloat(xgroamt.replace(",", ""));
		// xewtrte = parseFloat(xewtrte.replace(",", ""));
		// xvatrte = parseFloat(xvatrte.replace(",", ""));
		if( isNaN(xgroamt) )
		{
			xgroamt	= 0;
		}	
		
		if( isNaN(xvatrte) )
		{
			xvatrte	= 0;
		}		
		 
		
		 xtaxbase	= xgroamt / ( 1 + (xvatrte/100) );
		 		 
		if ( isNaN(xewtrte) )
		{		
			xewtrte	= 0;
		}
		
		xtaxamt	= xtaxbase * ( xewtrte / 100 );
		compute_net=xgroamt-xtaxamt;

		xtaxbase = Math.round(parseFloat(xtaxbase).toFixed(3) * 100)/100 ;
		xtaxamt = Math.round(parseFloat(xtaxamt).toFixed(3) * 100)/100 ;
		compute_net = Math.round(parseFloat(compute_net).toFixed(3) * 100)/100 ;
		
		console.log(xtaxamt);
		
		// xtaxbase	= Math.round(parseFloat(xtaxbase).toFixed(2));
		// xtaxamt		= Math.round(parseFloat(xtaxamt).toFixed(2));
		// compute_net	= Math.round(parseFloat(compute_net).toFixed(2));
		// console.log(compute_net.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		if ( xdocnum !=''  )
		{
			var xdatarow	= parseFloat(jQuery('.'+xdocnum).find('.appdocbal2').val());
			var	newdocbal	= xdatarow - xtaxamt;
			
			newdocbal	= newdocbal.toFixed(2);
			
			jQuery('.'+xdocnum).find('.appdocbal').attr('value', newdocbal);
		}
		compute_net = compute_net.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		jQuery("#txtnetamt"+par_ctrl_idx).val(compute_net);
		jQuery("#txttaxbase"+par_ctrl_idx).val(xtaxbase.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		jQuery("#txttaxamt"+par_ctrl_idx).val(xtaxamt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		
		// jQuery("#txtnetamt"+par_ctrl_idx).val(compute_net);
		// jQuery("#txttaxbase"+par_ctrl_idx).val(xtaxbase);
		// jQuery("#txttaxamt"+par_ctrl_idx).val(xtaxamt);
			
		compute_totewt();
	}
	
	function compute_evat_amount( par_ctrl_idx )
	{

		var xdata	= jQuery("#rowvat"+par_ctrl_idx+" :input ").serialize();
		
		if ( jQuery('#chkdirpay').attr('checked') )
		{
			jQuery('#txttype'+par_ctrl_idx).attr('innerHTML', 'DIR');
		}
		
		var xtaxbase	= 0;
		var xtaxamt		= 0;
		
		var xgroamt	= parseFloat(jQuery("#txtevatgroamt"+par_ctrl_idx).val());
		var xewtrte	= parseFloat(jQuery("#txtevatrte"+par_ctrl_idx).val());
		// console.log(xgroamt);
		// console.log(xewtrte);
		// var xvatrte	= parseFloat(jQuery("#txtvatrte"+par_ctrl_idx).val());
		var xdocnum	= jQuery("#txtewtdocnum"+par_ctrl_idx).val();
				
		if( isNaN(xgroamt) )
		{
			xgroamt	= 0;
		}	
		
		// if( isNaN(xvatrte) )
		// {
			// xvatrte	= 0;
		// }		
		
		 xtaxbase	= xgroamt;
		 		 
		if ( isNaN(xewtrte) )
		{		
			xewtrte	= 0;
		}
		
		xtaxamt	= (xtaxbase / 1.12) * ( xewtrte / 100 );
		
		xtaxbase	= parseFloat(xtaxbase).toFixed(4);
		xtaxamt		= parseFloat(xtaxamt).toFixed(4);
		
		if ( xdocnum !=''  )
		{
			var xdatarow	= parseFloat(jQuery('.'+xdocnum).find('.appdocbal2').val());
			var	newdocbal	= xdatarow - xtaxamt;
			
			newdocbal	= newdocbal.toFixed(4);
			
			jQuery('.'+xdocnum).find('.appdocbal').attr('value', newdocbal);
		}
		
		jQuery("#txtevattaxbase"+par_ctrl_idx).attr('value', xtaxbase);
		jQuery("#txtevattaxamt"+par_ctrl_idx).attr('value', xtaxamt);
	}
	
    function saveclick()
    {
		var xpar	= jQuery("#par").val();
		
		if ( xpar == 'Add' )
		{
			var xparams	= jQuery("#myform input[type='text'], input[type='hidden'], input[type='checkbox']").serialize();
			
			jQuery.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'ajax_save_voucherspreparation.php',
				data: xparams,
				
				success : function ( xresult )
						  {
								if ( xresult.msg != '' )
								{
									alert( xresult.msg );
								}
								else
								{									
									jQuery('#par_docnum').attr('value', xresult.docnum);
									jQuery('#txtdocnum').attr('value', xresult.docnum);
									jQuery('#txtid').attr('value', xresult.recid);
									jQuery('#par').attr('value', 'Edit');
									
									jQuery("btnsave").attr("value", "Save");
									
									document.forms.myform.action	= "";
									document.forms.myform.target	= "_self";
									document.forms.myform.submit();
								}
						  }
			});
		}
		else
		{
			document.forms.myform.event_action.value = 'save';
			document.forms.myform.target = '_self';
			document.forms.myform.action = 'trn_voucherpreparation.php';
			document.forms.myform.submit();
		}
    }
	
	function gengl_click()
	{	
		var xparams	= jQuery("#myform input").serialize();
		
		var par_maxcount	= 0;
		
		loading();
		
		jQuery.ajax({
				url : "gengl_array.php",
				type: 'post',
				dataType: 'json',
				data: xparams,
				
				success : function ( xresult )
							{
								jQuery("#actsubsidiary input[type='text']").attr("value", "");
								
								var x	= 1;
								
								for ( var par_key in xresult )
								{
									
									
									if ( !isNaN(par_key) )
									{ 
										xdata	= xresult[par_key];
										
										if ( xdata != null )
										{
											if ( typeof xdata.actcde !== 'undefined' )
											{
												jQuery("#txtactcde"+x).attr("value", xdata.actcde);
												jQuery("#txtactdsc"+x).attr("value", xdata.actdsc);
												jQuery("#txtdebamt"+x).attr("value", xdata.debamt);
												jQuery("#txtcreamt"+x).attr("value", xdata.creamt);
												
												x = x +1;
											}
										}
									}
								}
								
								total_debitcredit();
								hide_loading(); 
							}
			})
	}
	function func_clear(xid)
	{
		jQuery('#'+xid+' input[type=text]').val('');
	}
	function copybalance_click( xctr )
	{
		var xdocbal	= jQuery("#txtsaldocbal"+xctr).val();
		var xdocbalpayment	= jQuery("#txtarbalance").val();

		if ( parseFloat(xdocbal,10) > parseFloat(xdocbalpayment,10))
		{
			jQuery("#txttmpsal"+xctr).attr("value", xdocbalpayment);
			
		}
		else
		{
			jQuery("#txttmpsal"+xctr).attr("value", xdocbal);
		}
		validate_amountamtapp(xctr);
	}
	
	function set_subsidiary(par_id) 
	{
        jQuery("#txtsubsidiary" + par_id).autocomplete("option", "source", "ajax_search_subsidiary.php?actcde=" + encodeURIComponent(jQuery('#txtactcde' + par_id).attr("value")));
    	jQuery("#hid_sel_subsidiary").val('sub1');
    }

    function set_subsidiary2(par_id) 
	{
        jQuery("#txtsubsidiarytwo" + par_id).autocomplete("option", "source", "ajax_search_subsidiary2.php?actcde=" + encodeURIComponent(jQuery('#txtactcde' + par_id).attr("value")));
    	jQuery("#hid_sel_subsidiary").val('sub2');
    }
	
	function set_subsidiary3(par_id) 
	{
        jQuery("#txtsubsidiarythree" + par_id).autocomplete("option", "source", "ajax_search_subsidiary3.php?actcde=" + encodeURIComponent(jQuery('#txtactcde' + par_id).attr("value")));
    	jQuery("#hid_sel_subsidiary").val('sub3');
    }
	function add_payables_click()
	{
		
		jQuery.ajax({
			url:"ajax_add_invoice.php",
			data:{event_action:'get_invoice', xdocnum:jQuery("#txtdocnum").val()},
			dataType:'json',
			type:'post',
			success:function(xretobj){
				jQuery("#div_addinvoice").html(xretobj);
				jQuery(".class_date").datepicker({
					 dateFormat : "mm-dd-yy"
				});
				// jQuery(".autocom_inv").autocompltet
				jQuery(".autocom_inv").autocomplete({
								minLength: 1,
							   width: 600,
								maxItemsToShow:10, autoFill:true,
								source: "ajax_search_terms.php?field=trmcde&xcash=CASH",
								select: function(event, ui){
									console.log(jQuery(this));
															jQuery(this).val(ui.item.label);
															
							}
							})
							.data( "autocomplete" )._renderItem = function( ul, item ) {
											return jQuery( "<li><hr /></li>" )
											.data( "item.autocomplete", item )
											.append( "<a><b>" + item.value + "</b><br>" + item.label + "</a>" )
											.appendTo( ul );
								  };
				jQuery("#div_addinvoice").dialog('open');
				compute_amount("<?php echo $xlimit;?>");
			}
		});
		
	}

	function save_invoice()
	{

		// 20150915 -jep
		// count number of rows of the table
		var tbl_rows = document.getElementById('inv_table').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;

		console.log("Table Rows: "+tbl_rows);

		// added 20160308 -jep
			// validation of total invoice is equal to voucher amount
			var xtotamt 	= jQuery("#txtamount").val().replace(new RegExp(',','g'),"");
			var xtotinvamt 	= jQuery("#txttot").val().replace(new RegExp(',','g'),"");
			if(xtotamt != xtotinvamt)
			{
				alert("Amount should be equal!");
				return false;
			}



		//06112015 -jep
		//validate sales invoice if has a value
		var xbool = new Array();
		for(i=0; i<=(tbl_rows-1); i++)
		{
			xval = jQuery('#txtinvnum'+i).val();
			// xval = xval.trim();
			// if(jQuery.trim(xval)!='')
			if(String(xval).trim()!='' && xval)
			{
				xbool[i]=true;
			}
			else
			{
				xbool[i]=false;
			}
		}

		var xbool_invoice=false;
		if(xbool[0]===true || xbool[1]===true || xbool[2]===true || xbool[3]===true || xbool[4]===true || xbool[5]===true || xbool[6]===true || xbool[7]===true || xbool[8]===true || xbool[9]===true)
		{
			xbool_invoice=true;
		}
		//end validation

		// validate if voucher has an existing invoice
		var xdocnum=jQuery("#txtdocnum").val();

		var xbool_invoice_exist = false;
		var xdata = "&event_action=validate_invoice_exist&docnum="+xdocnum;
		jQuery.ajax({
			url:"trn_voucherpreparation_ajax.php",
			data:xdata,
			dataType:'json',
			type:'post',
			success:function(xretobj)
			{
				var xdata2 = jQuery("#div_addinvoice *").serialize()+"&event_action=save_invoice&xdocnum="+jQuery("#txtdocnum").val()+"&payeecde="+jQuery("#txtsupcde").val();
				var xtotamt = jQuery("#txtamount").val().replace(new RegExp(',','g'),"");
				var xtotinvamt = jQuery("#txttot").val().replace(new RegExp(',','g'),"");

				console.log(xtotamt,xtotinvamt );

				// saving of invoice

				// validate if voucher has existing invoice
				// no validation if the user inputed invoice or not
				// else
				// validate if user inputed invoice
				if(xretobj.bool)
				{
					xbool_invoice_exist = true;

					// if(xtotamt != xtotinvamt)
					// {
					// 	alert("Amount should be equal!");
					// }
					// else
					// {
						jQuery.ajax({
							url:"ajax_add_invoice.php",
							data:xdata2,
							dataType:'json',
							type:'post',
							success:function(xretobj2)
							{
								console.log(xretobj2);
								alert(xretobj2);
								if(xretobj2=='Data Saved!')
								{
									jQuery("#div_addinvoice").dialog("close");
								}
							}
							
						});
					// }
				}
				else
				{
					if(xbool_invoice)
					{
						if(xtotamt != xtotinvamt)
						{
							alert("Amount should be equal!");
						}
						else
						{
							jQuery.ajax({
								url:"ajax_add_invoice.php",
								data:xdata2,
								dataType:'json',
								type:'post',
								success:function(xretobj2)
								{
									console.log(xretobj2);
									alert(xretobj2);
									if(xretobj2=='Data Saved!')
									{
										jQuery("#div_addinvoice").dialog("close");
									}
								}
								
							});
						}
					}
					else
					{
						alert("No invoice data.");
						// jQuery("#txtinvnum0").focus();
						jQuery("#txtinvnum0").trigger('focus');
					}
				}


				console.log(xretobj.msg);
			}
			
		});
		//end validation
	}
	
	function compute_amount(xlimit)
	{
		var txttot = parseInt(0);
		for(x = 0; x < xlimit; x++)
		{		
			
			var  amt = jQuery("#txtamt"+x).val().replace(new RegExp(',','g'),"");
			if(amt !='')
			{
				txttot += parseFloat(amt);
			}
			jQuery("#txtamt"+x).val(amt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		}
		jQuery("#txttot").val(txttot.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		
	}
	function check_term(sender)
	{
		//alert("try");
		// var xlimit = <?php echo $xlimit; ?>;
		// console.log(xlimit);
			// var  term = jQuery("#txtinv"+id).val().replace(new RegExp(',','g'),"");
			
			// if(term!='' && term > xlimit)
			// {
				// alert("Not Accepted Term");
				// jQuery("#txtinv"+id).val(xlimit);
			// }
			if(sender.value !="")
			{
				var xterm = "xterm="+jQuery("#"+sender.id).val()+"&event_action=checkterm";
				jQuery.ajax({
					url : "ajax_add_invoice.php",
					data : xterm,
					dataType:"json",
					type:'post',
					success:function(xretobj)
					{
						console.log(xretobj);
						if(!xretobj)
						{
							alert("Not Accepted Term");
							 jQuery("#"+sender.id).val("");
						}
						
					}
				
				})
			}
			
			
		
		
	}
	// function set_comas(sender)
	// {
		// var xvalue = sender.value.replace(new RegExp(',','g'),"");
		// console.log(xvalue);
		// xvalue = parseFloat(xvalue);
		// console.log(xvalue);
		// jQuery("#"+sender.id).attr("value", xvalue.toFixed(2));
	// }
	function generate_credit()
	{
		var xbnkcde = jQuery("#txtdirbnkcde").val();
		var xamount = jQuery('#txtdirchkamt').val();
		var xewttot = jQuery("#txtewttot").val();
		var xdata = jQuery("#actsubsidiary * ").serialize()+'&bnkcde='+xbnkcde+"&bnkamt="+xamount+"&ewtamt="+xewttot+"&event_action=generate_credit&payee="+jQuery('#txtsupcde').val()+"&paytyp="+jQuery('#txtdirpaytyp').val();
		jQuery.ajax({
				url:"ajax_generate_credit.php",
				data:xdata,
				dataType:'json',
				type:'post',
				success:function(xretobj)
				{
					jQuery("#actsubsidiary input[type='text']").val('');
					var xcounter = 1;
					var xdebtot = 0;
					var xcretot = 0;
					jQuery(xretobj).each(function(key, val)
					{
						// xsubdeb = val.debit.replace(',', '');
						// xsubcre = val.creamt.replace(',', '');
						
						// xsubdeb = parseFloat(xsubdeb.replace(',', ''));
						// xsubcre = parseFloat(xsubcre.replace(',', ''));
						console.log(key, val.actcde);
						jQuery("#txtactcde"+xcounter).val(val.actcde);
						jQuery("#txtactdsc"+xcounter).val(val.actdesc);
						jQuery("#txtsubsidiary"+xcounter).val(val.subsidiary);
						jQuery("#txtsubsidiarytwo"+xcounter).val(val.subsidiary2);
						jQuery("#txtsubsidiarythree"+xcounter).val(val.subsidiary3);
						jQuery("#txtdebamt"+xcounter).val(val.debit);
						jQuery("#txtcreamt"+xcounter).val(val.credit);
						// xdebtot +=xsubdeb;
						// xcretot +=xsubcre;
						xcounter++
					});
					// console.log(xdebtot);
					// console.log(xcretot);
					total_debitcredit();
				}
		});
		
	}
	function compute_totewt()
	{
		
		var compute_net = 0;
		var maxcount = jQuery("#maxcount").val();
		var tottax = 0;
		var tottaxbase =0;
		var totnet = 0;
		for(i=1;i<= maxcount; i++)
		{
			var xval = jQuery("#txttaxamt"+i).val().replace(new RegExp(',','g'),"");
			var xtaxbase = jQuery("#txttaxbase"+i).val().replace(new RegExp(',','g'),"");
			var compute_net = jQuery("#txtnetamt"+i).val().replace(new RegExp(',','g'),"");
			var xgroamt	= jQuery("#txtgroamt"+i).val().replace(new RegExp(',','g'),"");
			var xtaxamt = jQuery("#txttaxamt"+i).val().replace(new RegExp(',','g'),"");
			//console.log(xtaxbase,xval , "==>"+i);
			if(xval !='')
			{
				tottax = parseFloat(tottax)+parseFloat(xval);
			}
			if(xtaxbase !='')
			{
				tottaxbase = parseFloat(tottaxbase)+parseFloat(xtaxbase);
			}
			if(xtaxamt!='' && xgroamt!='' && compute_net ==''){
				compute_net = parseFloat(xgroamt)-parseFloat(xtaxamt);
				compute_net	= parseFloat(compute_net).toFixed(2);
				jQuery("#txtnetamt"+i).val(compute_net.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				// console.log(compute_net);
			}
			if(compute_net !='')
			{
				totnet = parseFloat(totnet)+parseFloat(compute_net);
			}
			
		
		}
		//compute netamt
		if(tottax > 0)
		{
			tottax = tottax.toFixed(2);
			totnet = totnet.toFixed(2);
			tottaxbase = tottaxbase.toFixed(2);
			// console.log(totnet, "totnet");
			jQuery("#txtewttot").val(tottax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			jQuery("#txtnettot").val(totnet.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			jQuery("#txtbasetot").val(tottaxbase.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
			

		}
		
	}
	function compute_netamt()
	{
		// var txtnettot = 0;
		for(i = 1;i<=20;i++)
		{
			/*if( jQuery("#txttaxbase"+i).val() !="")
			{
				// console.log(jQuery("#txttaxbase"+i).val() );
				var  xgramt = jQuery("#txttaxbase"+i).val().replace(", ", "");
				var  xtaxamt = jQuery("#txttaxamt"+i).val().replace(", ", "");
						console.log(xgramt, xtaxamt);
				xgramt =xgramt.replace(",", "");
				xtaxamt = xtaxamt.replace(",","");
				xgramt =xgramt.replace(",", "");
				xtaxamt = xtaxamt.replace(",","");
				xtaxamt = xtaxamt.replace(",","");
				var xnewamt = parseFloat(xgramt) - parseFloat(xtaxamt );
				// console.log( xnewamt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				if(xnewamt > 0 )
				{
					xtotpercol = xnewamt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					console.log(xtotpercol);
					jQuery("#txtnetamt"+i).val( xtotpercol);
					txtnettot+=xnewamt;
				}
			}
			
		}
		// console.log(txtnettot);
		if(txtnettot !=0 && txtnettot !="NaN")
		{
			jQuery("#txtnettot").val(txtnettot.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))*/
			compute_tax_amount(i);
		}
	}
	
	function authvoucher_click()
	{
		var con = confirm("Are you sure to authorize this voucher?");
		if(con)
		{
			var xparams = jQuery('#xfield *').serialize()+"&action=autofill&usrnam=<?php echo $xgusrcde; ?>";
			jQuery.ajax({
				url:'proc_save_voucherauthorize.php',
				type:'post',
				dataType:'json',
				data: xparams,
				success:function(res)
				{
					alert('Document successfully authorized..');
					document.forms.myform.action='trn_voucherpreparation.php';
					document.forms.myform.method='post';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				}
			});
		}
	}


	//added 07252015 -jep
	//validate GL Entry if emtpy or not
	function validte_chk_manual_gl()
	{
		var xbool = jQuery('#chkmanualgl').attr('checked');
		var xval='';

		if(xbool)
		{
			var xcurr_bool=false;
			var xret_bool=false;

			jQuery(".gl_entry").each(function() {

				xval = jQuery(this).val();

			    // if(jQuery.trim(xval) != '' )
			    if(String(xval).trim() != '' && xval)
				{
					xcurr_bool=true;
				}

				if(xcurr_bool)
				{
					xret_bool=true;
				}
			});

			return xret_bool;
		}
		else
		{
			return true;
		}

	}
	function validate_date(xthis,xindex)
	{
		var xdate='';
		var xdaterec='';

		xdate = jQuery('#txtinvdte'+xindex).val();
		xdaterec = jQuery('#'+xthis.id).val();


		var firstValue = "2012-05-12".split('-');   0,1,2(y-m-d)
		var secondValue = "2014-07-12".split('-');
		// var firstValue = xdate.split('-');			// 0,1,2(m-d-y)
		// var secondValue = xdaterec.split('-');

		

		 var firstDate=new Date();
		 // firstDate.setFullYear(firstValue[0],(firstValue[1] - 1 ),firstValue[2]);
		 firstDate.setFullYear(firstValue[2],(firstValue[0] - 1 ),firstValue[1]);

		 var secondDate=new Date();


		 secondDate.setFullYear(secondValue[2],(secondValue[0] - 1 ),secondValue[1]);  


		 console.log("First Value: "+firstValue);
		// console.log("Second Value: "+secondValue);
		// return false;

		if(firstValue=='')
		{
			alert('Please select Invoice Date first.');
			jQuery('#'+xthis.id).val('');
			// jQuery('#txtinvdte'+xindex).focus();
			jQuery('#txtinvdte'+xindex).trigger('focus');
			return false;
		}
		else
		{
			if (firstDate > secondDate)
			{
				// alert("First Date  is greater than Second Date");
				alert('Date invalid.\nSelected date is less than the Invoice Date.');
				jQuery('#'+xthis.id).val('');
				// jQuery('#'+xthis.id).focus();
				jQuery('#'+xthis.id).trigger('focus');
				return false;
			}
			else
			{
				//alert("Second Date  is greater than First Date");

				//do nothing
			}
		}

		  
	}

	// function validate_subs(xindex,xsubsidiary)
	// added 20151001 -jep
	function validate_subs(xindex)
	{
		var xsubcode = '';
		var xgl_code = jQuery('#txtactcde'+xindex).val();
		var xsubsidiary = jQuery("#hid_sel_subsidiary").val();
		var xsub_id = '';

		if(xsubsidiary == 'sub1')
		{
			xsubcode = jQuery('#txtsubsidiary'+xindex).val();
			xsub_id = 'txtsubsidiary'+xindex;
		}
		if(xsubsidiary == 'sub2')
		{
			xsubcode = jQuery('#txtsubsidiarytwo'+xindex).val();
			xsub_id = 'txtsubsidiarytwo'+xindex;
		}
		if(xsubsidiary == 'sub3')
		{
			xsubcode = jQuery('#txtsubsidiarythree'+xindex).val();
			xsub_id = 'txtsubsidiarythree'+xindex;
		}

		if(xsubcode!='')
		{
			var xparams = "&event_action=validate_subsidiary&xgl_code="+xgl_code+"&xsubcode="+xsubcode+"&xsubsidiary="+xsubsidiary;
			jQuery.ajax({
				url:'trn_voucherpreparation_ajax.php',
				type:'post',
				dataType:'json',
				data: xparams,
				success:function(res)
				{
					if(res.bool)
					{
						// do nothing
					}
					else
					{
						alert(res.msg);
						jQuery('#'+xsub_id).val('');
						// jQuery('#txtsubsidiary'+xindex).focus();
					}
				}
			});
		}
	}
	function disableEnterKey(e)
	{
		var key;
		if(window.event)
		{
			key = window.event.keyCode;
		}
		else
		{
			e.which;
		}

		if(key == '13')
		{
			return false;
		}
	}

	function computeTerms()
	{
		var xreceivedte, xduedte;

		xreceivedte = jQuery('#txtreceivedte').val();
		xduedte = jQuery('#txt_duedte').val();

		console.log(xreceivedte);
		console.log(xduedte);

		var xparams = "&action=compute_terms2&receivedte="+xreceivedte+"&duedte="+xduedte;

		jQuery.ajax({
			url:'ajax_get_voucher_info.php',
			type:'post',
			dataType:'json',
			data: xparams,
			success:function(res)
			{
				if( res.bool )
				{
					// terms
					jQuery('#txttrmcde').val(res.trmcde);
				}
			}
		});
	}
	function generateTemplate(xtab_params)
	{
		document.forms.myform.tab_params.value = xtab_params;
		document.forms.myform.action = 'tab_template.php';
		document.forms.myform.target = '_blank';
		document.forms.myform.method = 'post';
	    document.forms.myform.enctype= 'multipart/form-data';
		document.forms.myform.submit();
		
	}

	// function importGLEntry()
	// {
	// 	if( document.getElementById('fileupload').value == '' )
	// 	{
	// 		alert('Select a file to be imported.');
	// 	}
	// 	else
	// 	{

	// 		document.forms.myform.import_event.value = 'import_glentry';
	// 		document.forms.myform.action = 'trn_voucherpreparation.php';
	// 		document.forms.myform.target = '_self';
	// 		document.forms.myform.method = 'post';
	// 		document.forms.myform.submit();

	// 	}
	// }

	function readFileExtension(xthis)
    {
    	var xext = new Array('.txt')

        if (!hasExtension(xthis.id, xext))
        {
            // ... block upload
            alert('Invalid file extension.');
            document.getElementById(xthis.id).value = '';
            return false;
        }
        
    }

    function hasExtension(inputID, exts) 
    {
        var fileName = document.getElementById(inputID).value;
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
    }

	function importGLEntry()
	{

		if( document.getElementById('fileupload').value == '' )
		{
			alert('Select a file to be imported.');
		}
		else
		{
			if(confirm('Import file?'))
			{
				// put preloader here
				
				var xctr 		= 0;
				var xctr_err 	= 0;

				var xarr_glent = new Array();
				var xarr_glent_err = new Array();

				// by lines
				var lines = xglo_glent.split('\n');

				var xline_limit = 100;

				console.log("line lenght: "+parseInt(lines.length));
				console.log("line limit:"+xline_limit);

				if( parseInt(lines.length) - 2 > xline_limit ) 
				{
					alert("You are trying to upload a file that has above " + xline_limit + " GL Entry.");
					return false
				}

				for(var line = 0; line < lines.length; line++)
				{
					// console.log(line+". "+lines[line]);
					var tabs = lines[line].split('\t');

					var num;

					var xcp_status;

					if( line > 1 && tabs[0] != "" )
					{
						xcp_status = checkPoint(tabs);
						
						tabs[4] = tabs[4].replace(/"| |,/g,'');
						tabs[5] = tabs[5].replace(/"| |,/g,'');

						tabs[4] = tabs[4] != "" && tabs[4] != "-" ? tabs[4] : 0;
						tabs[5] = tabs[5] != "" && tabs[5] != "-" ? tabs[5] : 0;

						if( xcp_status.bool !== true )
						{
							xarr_glent_err[xctr_err] = new Array();

							xarr_glent_err[xctr_err]['row'] = parseInt(line) + 1;
							xarr_glent_err[xctr_err]['actcde'] 		= xcp_status.actcde;
							xarr_glent_err[xctr_err]['sub1'] 		= xcp_status.sub1;
							xarr_glent_err[xctr_err]['sub2'] 		= xcp_status.sub2;
							xarr_glent_err[xctr_err]['sub3'] 		= xcp_status.sub3;

							xctr_err++;
						}
						else
						{
							xarr_glent[xctr] = new Array();

							xarr_glent[xctr]['actcde'] 		= tabs[0];
							xarr_glent[xctr]['actdsc'] 		= xcp_status.dsc;
							xarr_glent[xctr]['sub1'] 		= tabs[1];
							xarr_glent[xctr]['sub2'] 		= tabs[2];
							xarr_glent[xctr]['sub3'] 		= tabs[3];
							xarr_glent[xctr]['debamt'] 		= parseFloat(tabs[4]);
							xarr_glent[xctr]['creamt'] 		= parseFloat(tabs[5]);
							xarr_glent[xctr]['remarks'] 	= tabs[6];

							xctr++;
						}

					}
				}

				if( xarr_glent_err.length > 0 )
				{
					alert('There is an error.');

					jQuery('#tbl_glent_err').empty('');

					var xerr_list = '';

					for(var i=0; i<xarr_glent_err.length; i++)
					{
						xerr_list += "<tr><td colspan=2>Error on Line: " + xarr_glent_err[i]['row'] + "</td></tr>";

						if( xarr_glent_err[i]['actcde'] != "" )
						{
							xerr_list += "<tr><td></td><td>" + xarr_glent_err[i]['actcde'] + "</td></td></tr>";
						}
						if( xarr_glent_err[i]['sub1'] != "" )
						{
							xerr_list += "<tr><td></td><td>" + xarr_glent_err[i]['sub1'] + "</td></td></tr>";
						}
						if( xarr_glent_err[i]['sub2'] != "" )
						{
							xerr_list += "<tr><td></td><td>" + xarr_glent_err[i]['sub2'] + "</td></td></tr>";
						}
						if( xarr_glent_err[i]['sub3'] != "" )
						{
							xerr_list += "<tr><td></td><td>" + xarr_glent_err[i]['sub3'] + "</td></td></tr>";
						}

					}

					jQuery('#tbl_glent_err').append(xerr_list);

					jQuery('#div_glent_err').dialog('open');

				}
				else
				{
					for( var i=0; i<xarr_glent.length; i++ )
					{
						num = parseInt(i) + 1;

						document.getElementById('txtactcde'+num).value 			= xarr_glent[i]['actcde'];
						document.getElementById('txtactdsc'+num).value 			= xarr_glent[i]['actdsc'];
						document.getElementById('txtsubsidiary'+num).value 		= xarr_glent[i]['sub1'];
						document.getElementById('txtsubsidiarytwo'+num).value 	= xarr_glent[i]['sub2'];
						document.getElementById('txtsubsidiarythree'+num).value = xarr_glent[i]['sub3'];
						document.getElementById('txtdebamt'+num).value 			= formatCurrency(xarr_glent[i]['debamt']);
						document.getElementById('txtcreamt'+num).value 			= formatCurrency(xarr_glent[i]['creamt']);
						document.getElementById('txtremarks'+num).value 		= xarr_glent[i]['remarks'];
					}

					total_debitcredit();
				}

				

			}

		}

		function formatCurrency(num)
		{
			return num.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		
		function checkPoint(data)
		{
			var xparams = "&event_action=validate_entry&data="+data;

			var xretval;

			jQuery.ajax({
				url:'trn_voucherpreparation_ajax.php',
				type:'post',
				dataType:'json',
				data: xparams,
				async: false,
				success:function(response)
				{
					xretval = response;
				}
			});

			return xretval;
		}
	}

	


