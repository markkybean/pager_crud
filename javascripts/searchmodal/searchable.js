
(function( $ )
{
	$.fn.searchable=function(options)
	{	
		//public functions
		$.fn.destroy=function()
		{	

		}

		var opts = $.extend( {}, $.fn.searchable.defaults, options );

		return this.each(function(index) 
		{
			var id=index;

			var requestor=$(this);

			var bol_add = false;
			if(!requestor.hasClass('searchable_text'))
			{
				requestor.attr('placeholder','Click button to search...');
				$(this).wrap('<div class=\"searchable_outer_container\"></div>');
				$(this).wrap('<div class=\"searchable_container\"></div>');
	        	requestor.addClass('searchable_text');
				
	        	var searchButton = $.fn.searchable.searchButton();
	        	var modalSearchButton = $.fn.searchable.modalSearchButton();
	        	var modalAddButton = $.fn.searchable.modalAddButton();
	        	var selBy=$.fn.searchable.searchBy(opts.searchByValue,opts.searchByDesc);
	        	var searchTxt=$.fn.searchable.searchTxt();

	        	var modalClearButton = $.fn.searchable.modalClearButton();
	        	modalClearButton.insertAfter(requestor);

	        	var filtererContainer=$.fn.searchable.div();
	        	filtererContainer.addClass('searchable_filterercontainer');

	        	var resultContainer=$.fn.searchable.div();
	        	resultContainer.addClass('searchable_resultcontainer');

				var dialog = $.fn.searchable.divDialog(opts.modalTitle,opts.modalHeight,opts.modalWidth,resultContainer,filtererContainer,opts.modalCloseOnEscape,opts.modalResizable);

				filtererContainer.append(searchTxt);
				filtererContainer.append(selBy);
				filtererContainer.append(modalSearchButton);
				if(opts.table.toLowerCase() == 'customerfile' && jQuery('#has_add_customer').val() == 1 && opts.hasadd)
				{
					bol_add = true;
				}
				else if (opts.table.toLowerCase() == 'supplierfile' && jQuery('#has_add_supplier').val() == 1 && opts.hasadd)
				{
					bol_add = true;
				}
				if(bol_add)
				{	
					filtererContainer.append(modalAddButton);
				}

				dialog.append(filtererContainer);
				dialog.append(resultContainer);

				requestor.data('target-dialog',dialog.attr('id'));

				searchButton.appendTo(requestor.parents('.searchable_container'));
				searchButton.html('<i class="fa fa-search" aria-hidden="true"></i>');

				searchTxt.on('keypress', function (event) 
	        	{
			        if(event.which === 13)
			        {
						// modalSearchButton.click();
						modalSearchButton.trigger("click");
			        }
				});

				searchButton.on('click',function(e)
				{
					setTimeout(() => {
						const xarr = new Array(); // array for storing possible 
						// xarr.push(requestor.closest("table").parent().parent());
						xarr.push(jQuery("div[aria-describedby='ui-id-1']"));
						xarr.push(jQuery("div[aria-describedby='ui-id-7'"));
						xarr.push(jQuery("div[aria-describedby='Outstanding_EWT']"));
						xarr.push(jQuery("div[aria-describedby='Outstanding_EVAT']"));
						xarr.push(jQuery("div[aria-describedby='searchable_add_dialog']"));
						// xarr.push(jQuery("div#ui-datepicker-div"));

						xarr.forEach(function(value, index) {
							if(value[0]) {
								const parent_zindex = parseInt(value[0].style.zIndex) ? parseInt(value[0].style.zIndex) + 2 : 2005;
								dialog.parent().attr('style', function(i,s) {
									return (s || '') + ` z-index: ${parent_zindex} !important; ` ;
								});
							}
							return;
						})
					}, 10)
					dialog.dialog('open');
				});

	        	modalClearButton.on('click',function(e)
				{
					// requestor.val('').change();
					requestor.val('').trigger("change");

					for(var i =0;i<opts.passValueTo.length;i++)
					{
						if(opts.passValueTo[i] instanceof jQuery) //if value is a jQuery Object
						{
							// opts.passValueTo[i].val('').change();
							opts.passValueTo[i].val('').trigger("change");
						}
						else
						{
							// jQuery('#'+opts.passValueTo[i]).val('').change();	
							if(opts.passValueTo[i] && opts.passValueTo[i].length > 0) {
								jQuery('#'+opts.passValueTo[i]).val('').trigger("change");	
							}
						}
					}
				});

				// requestor.closest('.searchable_container').hover(function()
				requestor.closest('.searchable_container')
					.on('mouseenter', function() {
						// if(requestor.val() != '' && (requestor.hasClass('disabled') == false || requestor.prop('disabled') == false) )
						if(requestor.val() != '' && requestor.hasClass('disabled') == false)
						{
							$(this).find('.searchable_clear_btn').css('visibility','visible');
						}
							
					})
					.on('mouseleave', function() {
						if(requestor.hasClass('disabled') == false)
						{
							$(this).find('.searchable_clear_btn').css('visibility','hidden');
						}
					})

				// (this).closest('td').find('.searchable_clear_btn').show();
				// })

				requestor.on('keydown',function(e)
				{				
					// if(opts.searchButtonshrtct_keys_f.indexOf(e.keyCode)!==-1)
					if(opts.modalselfilterer_codeshortcut==e.keyCode)
					{
						// alert(filtererContainer.find('.searchable_modalselfilterer').length);
						// alert(filtererContainer.find('.searchable_modalselfilterer').find('option[value$=dsc]').length);
						filtererContainer.find('.searchable_modalselfilterer').find('option[value$=cde],option[value$=code]').prop('selected',true);					
						// $(this).closest('.searchable_container').find('.searchable_calldialog').click();
						$(this).closest('.searchable_container').find('.searchable_calldialog').trigger("click");
					}

					if(opts.modalselfilterer_descshortcut==e.keyCode)
					{
						// alert(filtererContainer.find('.searchable_modalselfilterer').length);
						// alert(filtererContainer.find('.searchable_modalselfilterer').find('option[value$=dsc]').length);
						filtererContainer.find('.searchable_modalselfilterer').find('option[value$=dsc],option[value$=desc]').prop('selected',true);					
						// $(this).closest('.searchable_container').find('.searchable_calldialog').click();
						$(this).closest('.searchable_container').find('.searchable_calldialog').trigger("click");
					}
				})

				requestor.closest('.searchable_outer_container').show();
				if(opts.autoOpen==false)
				{
					requestor.closest('.searchable_outer_container').hide();
				}
			}

			var target_dialog=jQuery('#'+requestor.data('target-dialog'));
			
			jQuery(target_dialog).find('.searchable_modalbtnfilterer').off('click');
			// jQuery(target_dialog).find('.searchable_modalbtnfilterer').click(function()
			jQuery(target_dialog).find('.searchable_modalbtnfilterer').on("click", function()
			{	

				var data=target_dialog.find("*").serialize()+"&table="+opts.table+"&link_id="+opts.link_id+"&searchCol="+opts.searchCol+"&tblcol="+opts.tableCol+'&tblcolheaders='+opts.tableColHeader;
				data+="&sqlfilter="+opts.sqlfilter;
				data+="&sqlorderby="+opts.orderBy;
				// data+="&sqlorderby="+jQuery(target_dialog).find('[name="filter_col_name"]').val(); // remarks by dlan: 20201118
				data+="&sqlorder="+opts.order;
				data+="&sqlgroupBy="+opts.groupBy;
				data+="&"+opts.data;
				data+="&passValue=";
				var passValueData="";

				for(var i =0;i<opts.passValue.length;i++)
				{
					data+=opts.passValue[i];
					passValueData+="&passValueTo["+opts.passValueTo[i]+"]="+opts.passValue[i];
					if((i+1)<opts.passValue.length)
					{
						data+=",";
					}
				}

				data+=passValueData;
				console.log(opts.ajaxPage);
				console.log(data);

				$.ajax({
					type: "POST",
					url: opts.ajaxPage,
					data: data,
					dataType: "json",
					success: function(xret) 
					{
						target_dialog.find('.searchable_resultcontainer').html(xret.content);
						console.log(xret);

						target_dialog.find('.searchable_resultcontainer').find('.filter_select').on('click',function()
						{
							for(var i =0;i<opts.passValue.length;i++)
							{
								var input_passValue=jQuery(this).closest('td').find('.passValue.searchable_'+opts.passValue[i]).val();

								if(opts.passValueTo[i] instanceof jQuery) //if value is a jQuery Object
								{
									// opts.passValueTo[i].val(input_passValue).change();
									opts.passValueTo[i].val(input_passValue).trigger("change");
								}
								else
								{
									// jQuery('#'+opts.passValueTo[i]).val(input_passValue).change();	
									if(opts.passValueTo[i] && opts.passValueTo[i].length > 0) {
										jQuery('#'+opts.passValueTo[i]).val(input_passValue).trigger("change");	
									}
								}
							}

							// requestor.val(jQuery(this).val()).change();
							requestor.val(jQuery(this).val()).trigger("change");

							target_dialog.dialog('close');
						});
					}
				});
			});
			// jQuery(target_dialog).find('.searchable_modalbtnfilterer_add').click(function()
			jQuery(target_dialog).find('.searchable_modalbtnfilterer_add').on("click", function()
			{	
				var xparams  = "event_action=get_add_form";
					xparams += "&table="+opts.table;

				$.ajax({
					url:'./javascripts/searchmodal/searchable_modifed_ajax.php',
					type:'POST',
					dataType:'JSON',
					data:xparams,
					success:function(res)
					{
						var divDialogAdd = jQuery('<div />');
						divDialogAdd.addClass('searchable_add_dialog');
						divDialogAdd.attr('id','searchable_add_dialog');
						divDialogAdd.html(res.divcontent);
						divDialogAdd.dialog({
							autoOpen : false, 
							modal : true,
							height: 600,
							width:1000,
							resizable : false,
							title : res.divtitle,
							close : function()
							{	
								jQuery(this).remove();
							},
							closeOnEscape : false,
							open:function()
							{
								// divDialogAdd.parent().siblings('.ui-widget-overlay').css('z-index','2000');
								// jQuery(this).closest('.ui-dialog').css({ 'z-index': '2000'});

							},
							buttons:{
								'Cancel': function(){
									jQuery(this).dialog('close');
								}
							}

						});
						divDialogAdd.dialog('open');
						// jQuery('.class_srch_save_cus').click(function()
						jQuery('.class_srch_save_cus').on("click", function()
						{	
							$.fn.searchable.func_save_customer(requestor,divDialogAdd,target_dialog,opts);
						});
						// jQuery('.class_srch_save_sup').click(function()
						jQuery('.class_srch_save_sup').on("click", function()
						{
							$.fn.searchable.func_save_supplier(requestor,divDialogAdd,target_dialog,opts);
						});

						setTimeout(() => {
							const x_zindex = parseInt(jQuery(e.currentTarget).closest("[role='dialog']")[0].style.zIndex) + 2;
							divDialogAdd.parent().attr('style', function(i,s) {
								return (s || '') + ` z-index: ${x_zindex} !important; `;
							});
						}, 10)
						
						correctUIDialog();
					}
				});	




			});
    	});
	};

	$.fn.searchable.defaults = {
		// searchButtonCusClass:'',
		// searchButtonshrtct_keys_f: [114,115],
		modalselfilterer_codeshortcut:114,
		modalselfilterer_descshortcut:115,
		modalTitle:'',
	    modalWidth:"auto",
	    modalHeight:'auto',
	    modalCloseOnEscape:true,
	    modalResizable:false,
	    ajaxPage:'./javascripts/searchmodal/searchable_ajax.php',
	    order:'asc',
	    orderBy:'',
	    groupBy:'',
	    table:"",
	    link_id:"",
	    searchCol:'',
	    searchByDesc:[],
		searchByValue:[],
		tableCol:'',
		tableColHeader:'',
		sqlfilter:'',
		data:'',
		passValueTo:[],
		passValue:[],
		autoOpen:true,
		hasadd:false,
	};	

	$.fn.searchable.searchButton = function()
	{
		var btn =  $('<button />').attr(
			{
				type: 'button',
				class: 'searchable_calldialog other-search',
				//id:'searchable_calldialog-0',
				tabIndex:"-1"
			});
		// var btn =  $('<input />').attr(
		// 	{
		// 		type: 'button',
		// 		value: ''
		// 	});
		
		return btn;
	};

	$.fn.searchable.modalClearButton = function()
	{
		var btn =  $('<i />').attr(
			{
				class: 'fa fa-times searchable_clear_btn',
				title: 'Clear Field'
			});
		// var btn =  $('<input />').attr(
		// 	{
		// 		type: 'button',
		// 		value: ''
		// 	});

		return btn;
	};

	$.fn.searchable.modalSearchButton = function()
	{
		var btn =  $('<button />').attr(
			{
				value:'',
				class:'searchable_modalbtnfilterer save'
			}).text('Search');

		return btn;
	};

	$.fn.searchable.hiddenTblCols = function()
	{
		var txt =  $('<input />').attr(
			{
				type:'hidden',
				name:'filter_txthidden'
			});

		return txt;
	}

	$.fn.searchable.searchTxt = function()
	{
		var txt =  $('<input />').attr(
			{
				type:'text',
				value:'',
				placeholder:'Search through here...',
				name:'filter_txt',
				class:'searchable_modaltxtfilterer',
				autocomplete:'off'
			});

		return txt;
	};

	$.fn.searchable.searchBy = function(val,desc)
	{
		var sel =  $('<select />',
			{
				name:'filter_col_name',
				class:'searchable_modalselfilterer'
			});

		for (var key in val) 
		{
			var option=$("<option />",
				{
					value:val[key]
				}).text(desc[key]);

		   sel.append(option);

    	// console.log(desc[key]);
		}

		return sel;
	};

	$.fn.searchable.modalAddButton = function()
	{
		var btn =  $('<button />').attr(
			{
				value:'',
				class:'searchable_modalbtnfilterer_add save'
			}).text('Add');

		return btn;
	};

	$.fn.searchable.div = function()
	{
		return $('<div />');
	}

	$.fn.searchable.divDialog=function(title,height,width,resultContainer,filtererContainer,closeOnEscape,resizable)
	{
		title = title || '';
		height = height || 500;
		width = width || 500;
		resultContainer = resultContainer || $('<div/ >');
		filtererContainer = filtererContainer || $('<div/ >');
		closeOnEscape = closeOnEscape || true;
		resizable = resizable || false;

		var divDialog = jQuery('<div />').attr({
			// id:'searchable_div_0'
			//class:'searchable_dialog'
		});

		divDialog.addClass('searchable_dialog searchable_div_0');

		return divDialog.dialog({
				autoOpen : false, 
				modal : true,
				height: height,
				width:width,
				resizable : resizable,
				title : title,
				closeOnEscape : closeOnEscape,
				open:function()
				{
					jQuery(this).find('.ui-dialog-titlebar-close').hide();
					resultContainer.empty();

					jQuery('body').css('overflow-y','hidden');

					filtererContainer.find('input').val('');
					// filtererContainer.find('select').val(filtererContainer.find('select option:first').val());

					// jQuery(this).closest('.ui-dialog').css({ 'z-index': '2000'});

					// jQuery('.ui-dialog').css({ 'z-index': '2000'});
				},
				close:function()
				{
					jQuery('body').css('overflow-y','scroll');
				},
				buttons:[
					{
						text: "Cancel",
		                "class": 'searchable_dialog_cancel_btn exit',
		               	click: function() 
		               	{
		               		jQuery(this).dialog('close');
		                }
					}
				]
			});

	}

	$.fn.searchable.func_save_customer=function(requestor,div_add_dialog,searchable_dialog,opts)
	{
		var xcuscde = jQuery('.searchable_add_dialog').children("#txtcuscde").val();
		var xcusdsc = jQuery('.searchable_add_dialog').children("#txtcusdsc").val();

		var xdata =jQuery("#div_customer *").serialize()+"&event_action=save_customer&txtcuscde="+xcuscde;
		xdata+="&searchcol="+opts.searchCol;
		xdata+="&passValue=";
		var passValueData="";
		for(var i =0;i<opts.passValue.length;i++)
		{
			xdata+=opts.passValue[i];
			passValueData+="&passValueTo["+opts.passValueTo[i]+"]="+opts.passValue[i];
			if((i+1)<opts.passValue.length)
			{
				xdata+=",";
			}
		}

		xdata+=passValueData;
		jQuery.ajax({
			url:"./javascripts/searchmodal/customer_ajax.php",
			type:"post",
			dataType:"json",
			beforeSend : function (){
				blockui();
			},
			data:xdata,
			success:function(res)
			{  
				$.unblockUI();
				if(res.log=="failed")
				{
					alertify.alert(beautifyErr(res.msg),function()
					{
						div_add_dialog.find(".required2").each(function()
						{   
							if(jQuery(this).val() == "")
							{
								jQuery(this).css("border"," red 1px solid");
							}
							else
							{
								jQuery(this).css("border"," silver 1px solid"); 
							}
						});
					});
				}
				else
				{
					// alertify.alert(res.msg,function(){});
						// requestor.val(res.searchcol).change();
						requestor.val(res.searchcol).trigger("change");
						for(var i =0;i<opts.passValue.length;i++)
						{
							var input_passValue=res[opts.passValue[i]];
							if(opts.passValueTo[i] instanceof jQuery) //if value is a jQuery Object
							{
								// opts.passValueTo[i].val(input_passValue).change();
								opts.passValueTo[i].val(input_passValue).trigger("change");
							}
							else
							{
								// jQuery('#'+opts.passValueTo[i]).val(input_passValue).change();	
								if(opts.passValueTo[i] && opts.passValueTo[i].length > 0) {
									jQuery('#'+opts.passValueTo[i]).val(input_passValue).trigger("change");	
								}
							}
						}
						div_add_dialog.dialog('close');
						searchable_dialog.dialog('close');
					
				}
			}
		});     
	}
	$.fn.searchable.func_save_supplier=function(requestor,div_add_dialog,searchable_dialog,opts)
	{
		var xdata =jQuery("#div_supplier *").serialize()+"&event_action=save_supplier";
			xdata+="&searchcol="+opts.searchCol;
			xdata+="&passValue=";
		var passValueData="";
		for(var i =0;i<opts.passValue.length;i++)
		{
			xdata+=opts.passValue[i];
			passValueData+="&passValueTo["+opts.passValueTo[i]+"]="+opts.passValue[i];
			if((i+1)<opts.passValue.length)
			{
				xdata+=",";
			}
		}

		xdata+=passValueData;
		jQuery.ajax({
			url:"./javascripts/searchmodal/supplier_ajax.php",
			type:"post",
			dataType:"json",
			beforeSend : function (){
				blockui();
			},
			data:xdata,
			success:function(res)
			{  
				if(res.log=="failed")
				{
					alertify.alert(beautifyErr(res.msg),function()
					{
						div_add_dialog.find(".required2").each(function()
						{   
							if(jQuery(this).val() == "")
							{
								jQuery(this).css("border"," red 1px solid");
							}
							else
							{
								jQuery(this).css("border"," silver 1px solid"); 
							}
						});
					});
				}
				else
				{
					// alertify.alert(res.msg,function(){});
						// requestor.val(res.searchcol).change();
						requestor.val(res.searchcol).trigger("change");
						for(var i =0;i<opts.passValue.length;i++)
						{
							var input_passValue=res[opts.passValue[i]];
							if(opts.passValueTo[i] instanceof jQuery) //if value is a jQuery Object
							{
								// opts.passValueTo[i].val(input_passValue).change();
								opts.passValueTo[i].val(input_passValue).trigger("change");
							}
							else
							{
								// jQuery('#'+opts.passValueTo[i]).val(input_passValue).change();	
								if(opts.passValueTo[i] && opts.passValueTo[i].length > 0) {
									jQuery('#'+opts.passValueTo[i]).val(input_passValue).trigger("change");	
								}
							}
						}
						div_add_dialog.dialog('close');
						searchable_dialog.dialog('close');
					
				}
				$.unblockUI();

			}
		});     
	}

}( jQuery ));