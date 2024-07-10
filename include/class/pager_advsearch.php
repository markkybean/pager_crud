
<?php
	class PagerAdvSearch extends Pager {
		public $search_option = true;
		public $quick_search = true;
		public $adv_search = true;
		public $gen_advsearch = array();


		private $has_add 	= true;
		private $has_edit 	= true;
		private $has_delete = true;
		private $has_print 	= true;
		private $has_cancel = true;
		private $has_export = true;
		private $has_inquiry = true;

		public function __construct($page,$limit,$showInfo = false)
		{
			$this->page = $page == '' ? intval(1) : intval($page);
			$this->limit = $limit == '' ? 10 : $limit;
			$this->showInfo = $showInfo;
			$this->pager_keys[0] = $this->table_id;
		}

		public function generateFieldHandler($field,$val)
		{

			$xtbl = $field->field_handler_table;
			$xcol = $field->field_handler_col;
			$xfilter = $field->field_handler_qry;

			$sql = "SELECT $xcol FROM $xtbl WHERE recid > 0 AND $xfilter";
			$stmt = $this->link_id->prepare($sql);
			$stmt->execute(array($val));
			$rs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $rs[$xcol];
		}

		private function generateHtmlObjects()
		{
			$this->gen_advsearch[0] = "
				<td colspan='2'>
					<input type='hidden' id='modalField_".$this->table_id."' name='modalField[".$this->table_id."]' />
				</td>";

			for($i=0;$i<count($this->fields);$i++){

				$xreadonly = '';
				$xdisable = '';
				if($this->fields[$i]->readonly)
				{
					$xreadonly = 'readonly';
					$xdisable = 'disable';
				}


				switch($this->fields[$i]->type){
					case 'text' :

						if($this->fields[$i]->modal_display){

							$text_str = "<td>".$this->headers[$i]." </td><td><input type='text' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' class='".$this->fields[$i]->class."' style='width:250px;".$this->fields[$i]->css."' ".$xreadonly."/></td>";
							$this->genfields[count($this->genfields)] = $text_str;
						}
						break;

					case 'select' :

						if($this->fields[$i]->modal_display){


							$select_str =  "<td>".$this->headers[$i]." </td><td><select id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' ".$this->fields[$i]->event_action." ".$xreadonly.">";

								$select_sql = "SELECT ".$this->fields[$i]->value.", ".$this->fields[$i]->desc." FROM ".$this->fields[$i]->select_table." ".$this->fields[$i]->filter." ".$this->fields[$i]->groupby." ".$this->fields[$i]->orderby;
								$select_stmt = $this->fields[$i]->link->prepare($select_sql);

								if($this->fields[$i]->params){
									$select_stmt->execute($this->fields[$i]->params);
								}
								else{
									$select_stmt->execute();
								}
								while($select_rs = $select_stmt->fetch()){

									$select_str .= "<option value='".$select_rs[$this->fields[$i]->value]."'>".$select_rs[$this->fields[$i]->desc]."</option>";
								}

							$select_str .= "</select></td>";
							$this->genfields[count($this->genfields)] = $select_str;
						}
						break;
					case 'date' :
						if($this->fields[$i]->modal_display)
						{
							// $text_str = "<td>".$this->headers[$i]." </td><td><input type='text' class='datepicker' placeholder='mm/dd/yyyy' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='width:250px;".$this->fields[$i]->css."' ".$xreadonly."/></td>";
							$text_str = "<td>".$this->headers[$i]." </td><td><input type='text' class='datepicker' placeholder='yyyy/mm/dd' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='width:250px;".$this->fields[$i]->css."' ".$xreadonly."/></td>";
							$this->genfields[count($this->genfields)] = $text_str;
						}
						break;
					case 'autocomplete' :
						if($this->fields[$i]->modal_display)
						{
							$text_str = "<td>".$this->headers[$i]." </td><td><input type='text' class='xautocomplete' searchkey='{$this->fields[$i]->value}' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='width:250px;".$this->fields[$i]->css."' ".$xreadonly."/></td>";
							$this->genfields[count($this->genfields)] = $text_str;
						}
						break;
					case 'hidden' :

						if($this->fields[$i]->modal_display){

							if($this->fields[$i]->hidden_type == 'select'){

								$hidden_str =  "<td>".$this->fields[$i]->hidden_desc." </td><td><select id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='".$this->fields[$i]->hidden_css."'>";

									$select_sql = "SELECT ".$this->fields[$i]->value.", ".$this->fields[$i]->desc." FROM ".$this->fields[$i]->select_table." ".$this->fields[$i]->filter." ".$this->fields[$i]->groupby." ".$this->fields[$i]->orderby;
									$select_stmt = $this->fields[$i]->link->prepare($select_sql);

									if($this->fields[$i]->params){
										$select_stmt->execute($this->fields[$i]->params);
									}
									else{
										$select_stmt->execute();
									}

									while($select_rs = $select_stmt->fetch()){

										$hidden_str .= "<option value='".$select_rs[$this->fields[$i]->value]."'>".$select_rs[$this->fields[$i]->desc]."</option>";
									}

								$hidden_str .= "</select></td>";
							}
							else if ($this->fields[$i]->hidden_type == 'checkbox'){

								$hidden_str = "<td>".$this->fields[$i]->hidden_desc." </td><td><input type='checkbox' id='modalField_".$this->fields[$i]->fieldname."'/><input type='hidden' id='checkbox_modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' /></td>";
							}
							else if ($this->fields[$i]->hidden_type == 'text'){

								$hidden_str = "<td>".$this->fields[$i]->hidden_desc." </td><td><input type='text' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='".$this->fields[$i]->hidden_css."'/></td>";
							}
							else if ($this->fields[$i]->hidden_type == 'textarea'){

								$hidden_str = "<td>".$this->fields[$i]->hidden_desc." </td><td><textarea id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' style='".$this->fields[$i]->hidden_css."'></textarea></td>";
							}
							$this->genfields[count($this->genfields)] = $hidden_str;
						}
						break;

					case 'checkbox' :

						if($this->fields[$i]->modal_display){

							$checkbox_str = "<td>".$this->headers[$i]." </td><td><input type='checkbox' id='modalField_".$this->fields[$i]->fieldname."' name='modalField[".$this->fields[$i]->fieldname."]' /></td>";
							$this->genfields[count($this->genfields)] = $checkbox_str;
						}
						break;


				}
			}
		}

		private function loadJavascript(){
			// $javascript = "<script type='text/javascript' src='searchbox.js'></script>";
			$javascript = "<script type='text/javascript'>

				jQuery(document).ready(function(){
					// jQuery('input[type=text]').blur();
					jQuery('input[type=text]').trigger('blur');

					// jQuery('#txtchk').click(function(){

					//    document.forms.myform.pager_page.value=page;
					  //          document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					  //          document.forms.myform.method='post';
					  //          document.forms.myform.target='_self';
					  //          document.forms.myform.submit();
					  //       });
					  
					  
					  ";
					  


				$javascript .= "

					// jQuery('.datepicker').datepicker();
					// AddAutoCompleteHandlers();

					// jQuery('ul.ui-autocomplete').css('z-index',1100);
					$('th.header').each(function(index)
					{
						$(this).css(
						{
							'position':'relative',
							'border-right':'1px solid #fff'
						})
						if($(this).text().toLowerCase()!='action')
						{
							var arrow_triangle=$('<div/>');
							arrow_triangle.css(
							{
								'width': 'auto',
								'height': 'auto',
								'background': 'transparent',
								'position':'absolute',
								'top':'0',
								'right':'0',
								'left':'5',
								'bottom':'0',
								'z-index':'10'
							});

							var arrow_ctn=arrow_triangle;

							var arrow=$('<div />');
							if(index==parseInt($('#header_th_index').val()))
							{	
								if($('#sort_order').val()=='DESC')
								{
									arrow.css
									({
										'position':'absolute',
										'top':'15',
										'right':'5px',
										'width':'1px',
										'height':'1px',
										'border-bottom': '5px solid #fff',
										'border-left': '5px solid transparent',
										'border-right': '5px solid transparent'
									});
								}
								else
								{
									arrow.css
									({
										'position':'absolute',
										'top':'15',
										'right':'5px',
										'width':'1px',
										'height':'1px',
										'border-top': '5px solid #fff',
										'border-left': '5px solid transparent',
										'border-right': '5px solid transparent'
									});
								}
							}
							else
							{
								arrow.css
									({
										'position':'absolute',
										'top':'15',
										'right':'5px',
										'width':'1px',
										'height':'1px',
										'border-top': '5px solid #fff',
										'border-left': '5px solid transparent',
										'border-right': '5px solid transparent'
									});
							}


							arrow_ctn.append($(this).text());
							// arrow_ctn.append(arrow);

							$(this).text('');

							$(this).append(arrow_ctn);
							$(this).append(arrow);
						}

					});

					var index=(parseInt($('#header_th_index').val())+1);
					if($('#sort_order').val()=='DESC')
					{
						// add icon container here
						// $('th.header:nth-child('+index+')').text('desc');
					}";

			$javascript .= "
				});

				var fields = jQuery('#pager_fields').val().split(',');
				var headers = jQuery('#pager_headers').val().split(',');
				var table = jQuery('#pager_table').val();
				var table_id = jQuery('#pager_table_id').val();
				var xlink_id = jQuery('#pager_xlink').val();
				var xfield = jQuery('#pager_xfield').val();
				var xtitle = jQuery('#pager_title').val();


				var totalpages = jQuery('#pager_totalpages').val();
				var page = jQuery('#pager_page').val();

				var pager_keys = new Array();

				// jQuery('#div_pager #datatable tbody tr:eq(0) td').each(function(key,val){
				jQuery('#div_pager #datatable tbody tr').eq(0).find('td').each(function(key,val){

					pager_keys[pager_keys.length] = jQuery(val).attr('id');

				});

				jQuery('#pager_modal input[type=checkbox]').each(function(key,val){

					// jQuery(this).click(function(){
					jQuery(this).on('click', function(){
						var xbool = jQuery(this).prop('checked');
						var xid = jQuery(this).attr('id');
						if(xbool == true){
							jQuery('#checkbox_'+xid).val('1');
						}
						else{
							jQuery('#checkbox_'+xid).val('0');
						}
					});
				});

				function addRow()
				{
					var xtitle = jQuery('#pager_title').val();
					var x = '".$this->validate_fields."';
					var y = ".json_encode($this->filter_fields).";

					blockui();
					var addParams = jQuery('#pager_modal *').serialize()+\"&table=\"+table+\"&table_id=\"+table_id+\"&title=\"+xtitle+\"&head=\"+headers+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=add&validate=\"+x+\"&filterflds=\"+y;

					jQuery.ajax({
						url:'class/pager_handler.php',
						type:'post',
						dataType:'json',
						data: addParams,
						success:function(response){
							$.unblockUI();
							if(response.msg == 'failed')
							{
								alertify.alert(response.log);
							}
							else
							{
								alertify.alert(response.log, function() {
									jQuery('#pager_modal table input').val('');
									jQuery('#pager_modal').dialog('close');
									pager_reload();
								});
							}
							jQuery(x).prop('disabled','');

						}
					});
				}

				function updateRow()
				{
					var xtitle = jQuery('#pager_title').val();
					var x = '".$this->validate_fields."';
					var y = ".json_encode($this->filter_fields).";

					blockui();
					var updateParams = jQuery(\"#pager_modal *\").serialize()+\"&table=\"+table+\"&table_id=\"+table_id+\"&head=\"+headers+\"&title=\"+xtitle+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=update&fieldname=\"+xfield+\"&validate=\"+x+\"&filterflds=\"+y;

					jQuery.ajax({
						url:'class/pager_handler.php',
						type:'post',
						dataType:'json',
						data: updateParams,
						beforeSend : function (){
			                blockui();
			            },
						success:function(response){

							$.unblockUI();
							if(response.msg == 'failed')
							{
								alertify.alert(response.log);
							}
							else
							{
								alertify.alert(response.log, function() {
									jQuery('#pager_modal table input').val('');
									jQuery('#pager_modal').dialog('close');
									pager_reload();
								});
							}
							jQuery(x).prop('disabled','');
						}
					});
				}

				function modPagerBtn(par)
				{
					if(par=='add')
					{
						var xfunc = 'addRow()';
						var xtitle = 'Save';
					}
					else if(par=='edit')
					{
						var xfunc = 'updateRow()';
						var xtitle = 'Save';
					}

					jQuery('.ui-dialog .ui-dialog-buttonpane .ui-button').remove();

					var btn = \"<button role='button' class='save' style='height:30px;' onclick='\"+xfunc+\"'>\"+xtitle+\"</button>\";
					btn += \"<button role='button' class='exit' style='height:30px;' onclick='closePagerModal()'>Close</button>\";

					jQuery('.ui-dialog-buttonset').append(btn);
				}

				function closePagerModal()
				{
					jQuery('#pager_modal').dialog('close');
				}

				function add_item(){

					jQuery('#pager_modal input').val('');

					jQuery('#pager_modal').dialog({
						title:'Add',
						width: '".$this->modal_width."',
						height: '".$this->modal_height."',
						draggable:false,
						resizable:false,
						autoOpen:false,
						modal : true,
						closeOnEscape:false,
						open:function(){jQuery('.ui-dialog-titlebar-close').hide();},
						buttons:{

							\"Save\":function(){},
							\"Close\":function(){}
						}
					}).dialog('open');

					modPagerBtn('add');
				}

				function edit_item(){

					var active_id = jQuery('#pager_active_id').val();

					jQuery(\"#modalField_".$this->table_id."\").val(active_id);

					if(active_id == ''){

						alert('No row selected!');

						return false;
					}

					jQuery('#div_pager #datatable tbody tr').each(function(key,val){

						var xactive = jQuery(val).attr('id');

						if(active_id == xactive){

							for(var i=0;i<pager_keys.length;i++){

								var hidden_type = jQuery(val).find('#'+pager_keys[i]).attr('hidden_type');

								if(hidden_type == 'checkbox'){
									var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
									if(dataval == 'Y' || dataval == '1'){
										jQuery('#modalField_'+pager_keys[i]).prop('checked',true);
										jQuery('#checkbox_modalField_'+pager_keys[i]).val('1');
									}
									else{
										jQuery('#checkbox_modalField_'+pager_keys[i]).val('0');
									}
								}
								else{
									var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
									jQuery('#modalField_'+pager_keys[i]).val(dataval);
								}
							}
						}

					});

					jQuery('#pager_modal').dialog({
						title:'Edit',
						width: '".$this->modal_width."',
						height: '".$this->modal_height."',
						draggable:false,
						resizable:false,
						autoOpen:false,
						modal:true,
						closeOnEscape:false,
						open:function(){jQuery('.ui-dialog-titlebar-close').hide();},
						buttons:{

							\"Save\":function(){},
							\"Close\":function(){}
						}
					}).dialog('open');

					modPagerBtn('edit');
				}

				function delete_item(){

					var active_id = jQuery('#pager_active_id').val();

					jQuery(\"#modalField_".$this->table_id."\").val(active_id);

					if(active_id == ''){

						alert('No row selected!');

						return false;
					}

					jQuery('#div_pager #datatable tbody tr').each(function(key,val){

						var xactive = jQuery(val).attr('id');

						if(active_id == xactive){

							for(var i=0;i<pager_keys.length;i++){
								var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
								jQuery('#modalField_'+pager_keys[i]).val(dataval);
							}
						}

					});

					jQuery('#pager_modal').hide();

					alertify.confirm('Delete item?',
					function(){
						blockui();
						var deleteParams = jQuery('#pager_modal *').serialize()+\"&table=\"+table+\"&table_id=\"+table_id+\"&head=\"+headers+\"&title=\"+xtitle+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=delete&fieldname=\"+xfield;

						jQuery.ajax({
							url:'class/pager_handler.php',
							type:'post',
							dataType:'json',
							data: deleteParams,
							success:function(response){
								alertify.alert(response);
								$.unblockUI();
				                $( '.blockUI' ).fadeIn( 'slow', function() {
									jQuery('#pager_modal table input').val('');
									pager_reload();
								});
							}
						});
					},
					function(closeEvent){
					}
					);
				}

				// jQuery('#pager_selector').change(function(){
				jQuery('#pager_selector').on('change', function(){
					document.forms.myform.pager_page.value=this.value;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				});

				// jQuery('#pager_limiter').change(function(){
				jQuery('#pager_limiter').on('change', function(){

					document.forms.myform.pager_page.value='1';
					document.forms.myform.pager_limit.value=this.value;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				});

				// jQuery('#pager_next').click(function(){
				jQuery('#pager_next').on('click', function(){

					if(page == totalpages){
						page = totalpages;
					}
					else{
						page = parseInt(jQuery('#pager_page').val()) + 1;
					}

					document.forms.myform.pager_page.value=page;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();

				});

				// jQuery('#pager_prev').click(function(){
				jQuery('#pager_prev').on('click', function(){

					if(page == 1){
						page = 1;
					}
					else{
						page = parseInt(jQuery('#pager_page').val()) - 1;
					}

					document.forms.myform.pager_page.value=page;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				});

				// jQuery('#pager_first').click(function(){
				jQuery('#pager_first').on('click', function(){

					document.forms.myform.pager_page.value=1;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				});

				// jQuery('#pager_last').click(function(){
				jQuery('#pager_last').on('click', function(){

					document.forms.myform.pager_page.value=totalpages;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				});

				// jQuery('#pager_search_btn').click(function(){
				jQuery('#pager_search_btn').on('click', function(){
					document.forms.myform.pager_page.value=1;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();

				})

				function pager_reload()
				{
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				}

				function print_item()
				{
					document.forms.myform.action='mf_print.php';
					document.forms.myform.method='POST';
					document.forms.myform.target='_blank';
					document.forms.myform.submit();

				}

				function export_item()
				{
					document.forms.myform.action='mf_export.php';
					document.forms.myform.method='POST';
					document.forms.myform.target='_blank';
					document.forms.myform.submit();

				}
				function clear_searchbox()
				{
					jQuery('#pager_search_input').val('');
				}

				function pager_check_all(obj)
				{
					var ischecked = jQuery(obj).prop('checked');
					if(ischecked)
					{
						jQuery('.pager_checker_body').prop('checked',true);
					}
					else
					{
						jQuery('.pager_checker_body').prop('checked',false);
					}
				}

				function header_click(fieldname,input){
					var xsort = jQuery('#sort_order').val();

					if( xsort == 'ASC' )
					{
						jQuery('#sort_order').val('DESC');
					}
					else
					{
						jQuery('#sort_order').val('ASC');
					}
					document.forms.myform.header_sort_order.value=fieldname;

					document.forms.myform.header_th_index.value=$(input).index('th');

					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
				}

				// jQuery('#btn_pager_qsearch').click(function()
				jQuery('#btn_pager_qsearch').on('click', function()
				{
					document.forms.myform.pager_page.value = 1;
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
                    // formSubmit('".$_SERVER['PHP_SELF']."');
				})

				// jQuery('#btn_pager_advsearch').click(function()
				jQuery('#btn_pager_advsearch').on('click', function()
				{
					jQuery('#div_pager_advsearch').dialog({
						title:'Advance Search',
						appendTo: '#myform',
						width: 'auto',
						height: 'auto',
						draggable:true,
						resizable:false,
						autoOpen:false,
						modal : true,
						closeOnEscape:false,
						open:function(){
							jQuery('.ui-dialog-titlebar-close').hide();
							$(this).parent().css({'top':0});
						},
						buttons:{

							\"Search\":function(){
								advSearchClicked();
							},
							\"Cancel\":function(){
								jQuery(this).dialog('close')
							}
						}
					}).dialog('open');
					jQuery('.datepicker').datepicker({
						// showOn: 'button',
				      	// buttonImage: '../images/if_calendar_285670.png',
				      	// buttonImageOnly: true,
						//   buttonText: 'Select date',
						// dateFormat:  'mm/dd/yy',
						dateFormat:  'yy/mm/dd',
						// changeMonth: true,
						// changeYear: true,
					});
					// jQuery('.datepicker').blur();
					jQuery('.datepicker').trigger('blur');
					datepickerDteToday();
					initRegexLoader();
					// correctUIDialog();
				})

				function advSearchClicked()
				{
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					console.log(document.forms.myform);
					document.forms.myform.pager_event_action.value='advance_search';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
                    // formSubmit('".$_SERVER['PHP_SELF']."');
				}

				";

				if($this->hovering_type == 'click'){

					$javascript .= "

					jQuery('#div_pager #datatable tbody tr td').each(function(key,val){

						// jQuery(val).click(function(){
						jQuery(val).on('click', function(){
					

							jQuery('#div_pager #datatable tbody tr').removeClass('pager-active');

							jQuery(this).addClass('pager-active');

							var active_id = jQuery(this).attr('id');

							jQuery('#pager_active_id').val(active_id);

							for(var i=0;i<pager_keys.length;i++){
								var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
								jQuery('#pager_'+pager_keys[i]).val(dataval);
							}

						});
					});";
				}
				else{

					$javascript .= "
					jQuery(window).on('load', function(){
						let xselector = '';
						if(jQuery('.tbody').length > 0){
							xselector = '#div_pager #datatable .tbody .tr ';
						}else{
							xselector = '#div_pager #datatable tbody tr';
						}

						jQuery(xselector).each(function(key,val){
							// jQuery(val).hover(function(){	
							jQuery(val).on('mouseenter', function(){	
								var active_id = jQuery(this).attr('id');
	
								jQuery('#pager_active_id').val(active_id);
	
								for(var i=0;i<pager_keys.length;i++){
									var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
									jQuery('#pager_'+pager_keys[i]).val(dataval);
								}
							});
						});
					});					
					";
				}

			$javascript .= "</script>";

			echo $javascript;
		}

		private function generateAdvSearch()
		{
			$xarr_advsearch = array();

			$this->gen_advsearch[0] = "
				<td colspan='2'>
					<input type='hidden' id='modalField_".$this->table_id."' name='modalField[".$this->table_id."]' />
				</td>";

			// echo "<pre>";
			// var_dump($this->fields[0]->fieldname);
			// var_dump($this->fields[1]->fieldname);
			// var_dump($this->fields[2]->fieldname);
			// var_dump($this->fields[3]->fieldname);
			// var_dump($this->fields[4]->fieldname);
			// var_dump($this->fields[5]->fieldname);
			// var_dump($this->fields[6]->fieldname);
			// var_dump($this->fields[7]->fieldname);

			for( $i=0; $i<count($this->fields); $i++ )
			{
				$xreadonly = '';
				$xdisable = '';
				if($this->fields[$i]->readonly)
				{
					$xreadonly = 'readonly';
					$xdisable = 'disable';
				}

				if( $this->fields[$i]->type == 'hidden' ) continue;
				
				if( !$this->fields[$i]->qs_fromto )
				{
					$xclass_num = $this->fields[$i]->type =='number' ? 'number regex-numericwithcommaperiod' : '';
					if( $this->fields[$i]->type != 'boolean')
					{
						// var_dump($_POST['txtfld']['advs'][$this->fields[$i]->fieldname]);
						$this->gen_advsearch[] = "
						<tr>
		    				<td>
		    					<label id='lbl_pager_adv_srch_".$this->fields[$i]->fieldname."' style='font-weight: bold;'>" . $this->headers[$i] . " : </label>
		    				</td>
		    				<td colspan=2>
							<input class='input ".$xclass_num."' name='txtfld[advs][". $this->fields[$i]->fieldname ."]' type='text' value='".$_POST['txtfld']['advs'][$this->fields[$i]->fieldname]."' id = 'txt_advs_". $this->fields[$i]->fieldname ."'  />
		    				</td>
		    			</tr>";
					}
					
		    		else {
						$xselecty = $_POST['txtfld']['advs'][$this->fields[$i]->fieldname] == 'Y' ? 'selected' : '';  
						$xselectn = $_POST['txtfld']['advs'][$this->fields[$i]->fieldname] == 'N' ? 'selected' : '';  

						$this->gen_advsearch[] = "
						<tr>
		    				<td>
		    					<label id='lbl_pager_adv_srch_".$this->fields[$i]->fieldname."' style='font-weight: bold;'>" . $this->headers[$i] . " : </label>
		    				</td>
		    				<td colspan=2>
		    					<select name='txtfld[advs][". $this->fields[$i]->fieldname ."]' class='input'>
									<option></option>
									<option value='Y' ".$xselecty.">Y</option>
									<option value='N' ".$xselectn.">N</option>
		    					</select>
		    					
		    				</td>
		    			</tr>";
		    			// <select name='txtfld[advs][". $this->fields[$i]->fieldname ."]' class='input'>
									// <option></option>
									// <option value='Y'>Y</option>
									// <option value='N'>N</option>
		    			// 		</select>
		    			//<input class='input' name='txtfld[advs][". $this->fields[$i]->fieldname ."]' type='text' style='text-transform:uppercase;' placeholder='Y/N'/>
		    			
		    		}
				}
				if( $this->fields[$i]->qs_fromto )
				{
					switch ($this->fields[$i]->type) {
						case 'date':
							$xclass = 'datepicker hasDatepickerDteToday';
							// $xplaceholder = 'mm/dd/yyyy';
							$xplaceholder = 'yyyy/mm/dd';
							$xreadonly = 'readonly';
							$xstyle = 'width:150px;';
							$xtd_align = "valign='center'";
							break;

						default:
							$xclass = '';
							$xplaceholder = '';
							$xreadonly = '';
							$xstyle = '';
							$xtd_align = '';
							break;
					}

					$this->gen_advsearch[] = "
					<tr>
	    				<td>
	    					<label id='lbl_pager_adv_srch_".$this->fields[$i]->fieldname."' style='font-weight: bold;'>" . $this->headers[$i] . "</label>
	    				</td>
	    			</tr>
	    			<tr>
	    				<td></td>
	    				<td id='td_pager_adv_srch_".$this->fields[$i]->fieldname."_frm'>
	    					From :
	    				</td>
	    				<td $xtd_align>
	    					<input class='input $xclass' name='txtfld[advs][". $this->fields[$i]->fieldname ."][from]' placeholder='$xplaceholder' $xreadonly type='text' style='$xstyle' value='".$_POST['txtfld']['advs'][$this->fields[$i]->fieldname]['from']."'/>
	    				</td>
	    			</tr>
	    			<tr>
	    				<td></td>
	    				<td $xtd_align id='td_pager_adv_srch_".$this->fields[$i]->fieldname."_to'>
	    					To :
	    				</td>
	    				<td>
	    					<input class='input $xclass' name='txtfld[advs][". $this->fields[$i]->fieldname ."][to]' placeholder='$xplaceholder' $xreadonly type='text' style='$xstyle' value='".$_POST['txtfld']['advs'][$this->fields[$i]->fieldname]['to']."'/>
	    				</td>
	    			</tr>
	    			";
				}

			}
		}

		public function render()
		{
			global $xg_appkey;
			$pager_container = "";
			// $page_recid=$_SESSION[$xg_appkey]['current_menrecid'];

			#region search option
			if( $this->search_option )
			{
				$pager_container .= "
					<div id='div_pager_searchopt' style='width:".$this->pager_width."'>
						<article class='module' style='border:1px solid #d8d8db;background:#f6f6f8'>
							<header style='background:#d8d8db'>
	    						<h3 style='font-weight:bold;'>Search Option</h3>
	    					</header>
	    					<br>
	    					<table style='width:70%' id='tbl_pager_qsearch'>
					    		<thead>
					    			<tr>
					    				<td style='width:15%'></td>
					    				<td style='width:75%'></td>
					    				<td style='width:10%'></td>
					    			</tr>
					    		</thead>
					    		<tbody>
					    			<tr>
					    				<td  id='td_pager_qsearch_title'>Quick Search</td>
					    				<td>
					    					<input type='text' class='input' id = 'txt_pager_qsearch'  name='txt_pager_qsearch' value='".$_POST['txt_pager_qsearch']."'/>
					    				</td>
					    				<td>
					    					<input type='button' class='save' value='Search' id='btn_pager_qsearch' />
					    				</td>
					    			</tr>
					    			<tr>
					    				<td colspan=3 align='right'>
					    					<input type='button' class='link' value='Advanced Search' id='btn_pager_advsearch' />
					    				</td>
					    			</tr>
					    		</tbody>
					    	</table>
							<br />
						</article>
					</div>
				";
			}

			#end


			$pager_container .= "<article id='pager_article' class='pager-module' style='min-width:820px;max-width:1280px;width:".$this->pager_width."'>
							<header>
								<h3 class='tabs_involved'>".$this->title."</h3>
							</header>
							<div class='tab_container'><div id='div_pager' class='pager tab_content'>";

			$view_pager=true;

			// if( strtolower($_SESSION[$xg_appkey]['usrlvl']) == "user" ) //usrlvl == user
			// {
			// 	$stmt_um = $this->link_id->prepare("SELECT * FROM user_menus WHERE usrcde=? AND menid=?");
			// 	$stmt_um->execute(array( $_SESSION[$xg_appkey]['usrcde'], $page_recid ));
			// 	$rs_um = $stmt_um->fetch(PDO::FETCH_ASSOC);

			// 	if($rs_um)
			// 	{
			// 		$this->has_add 	= empty($rs_um['has_add']) ? false : true;
			// 		$this->has_edit 	= empty($rs_um['has_edit']) ? false : true;
			// 		$this->has_delete = empty($rs_um['has_delete']) ? false : true;
			// 		$this->has_cancel = empty($rs_um['has_cancel']) ? false : true;
			// 		$this->has_print 	= empty($rs_um['has_print']) ? false : true;
			// 		$this->has_export = empty($rs_um['has_export']) ? false : true;
			// 		$this->has_inquiry = empty($rs_um['has_inquiry']) ? false : true;
			// 	}
			// 	else
			// 	{
			// 		$view_pager=false;
			// 	}
			// }

			if(!$view_pager)
			{
				// echo "Contact Your System Administrator for your User Access.";
				include 'useraccesswarning.php';
			}
			else
			{
				#region header buttons
				if(!$this->no_header_buttons)
				{
					$h_button = "";

					if(count($this->header_buttons) == 0)
					{
						if($this->show_add && $this->has_add==true)
						{
							$h_button .= "<input type='button' id='pager_default_add' class='add' value='Add' onclick='add_item()'>";
						}
							// $h_button .= "<input type='button' id='pager_default_add' class='add' value='Add' onclick='add_item()'/>";
					}
					else
					{

						if($this->show_checkbox)
						{

							$xapply = "<select style='align:left' id='".$this->fields[0]->select_id."' name='".$this->fields[0]->select_name."' class='".$this->fields[0]->select_class."' >";

							for($chk1=0;$chk1<count($this->fields[0]->select_optionval);$chk1++)
							{
								// $xapply .= "<option value='".$this->fields[0]->select_optionval[$chk1]."'>".$this->fields[0]->select_optiondesc[$chk1]."</option>";
								$xapply .= "<option value='".$this->fields[0]->select_optionval[$chk1]."'>";
								$xapply .= $this->fields[0]->select_optiondesc[$chk1];
								$xapply .= "</option>";
							}

							$xbtn_event = $this->fields[0]->apply_btn_event."()";

							$xapply .= "</select> <input type='button' id='".$this->fields[0]->apply_btn_id."' style='margin-right:5px' name='".$this->fields[0]->apply_btn_name."' class='".$this->fields[0]->apply_btn_class."' value='".$this->fields[0]->apply_btn_value."' onclick=\"$xbtn_event\"/>";

							$h_button .= $xapply;
						}


						for($hb1=0;$hb1<count($this->header_buttons);$hb1++)
						{
							//gian karlo - 20180820 remove add buttons from header_buttons via strpos
							if($this->has_add==false && strpos(strtolower($this->header_buttons[$hb1]->value), 'add')!==false) continue;
							if($this->has_print==false && strpos(strtolower($this->header_buttons[$hb1]->value), 'print')!==false) continue;
							if($this->has_export==false && strpos(strtolower($this->header_buttons[$hb1]->value), 'export')!==false) continue;
							if(count($this->header_buttons[$hb1]->fields) > 0)
							{
								$h_button_fields = $this->header_buttons[$hb1]->event."()";

								$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' onclick=\"$h_button_fields\" > ";
							}
							else{

								if($this->header_buttons[$hb1]->event == '')
								{
									$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' > ";
								}
								else{
									$event = $this->header_buttons[$hb1]->event."()";
									$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' onclick=\"$event\" > ";
								}

							}
						}
					}
					if($this->show_print && $this->has_print)
					{
						$h_button .= "&nbsp;<input type='button' id= 'pager_def_print' class='print' value='Print Preview' onclick='print_item()'>";

					}
					if($this->show_export && $this->has_export)
					{

						$h_button .= "&nbsp;<input type='button' id= 'pager_def_export' class='print' value='Export' onclick='export_item()'>";
					}
					// if($this->show_print && $this->has_print)
					// {
					// 	$h_button .= "&nbsp;<input type='button' class='print' value='Print Preview' onclick='print_item()'>";
					// 	// if($this->title == "Employee File")
					// 	// {
					// 	// 	$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:80px' value='Print' onclick='print_item()'/>";

					// 	// }
					// 	// else
					// 	// {
					// 	// 	$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:80px' value='Print' onclick='print_item()'/>";

					// 	// }
					// }
					// if($this->show_export && $this->has_export)
					// {

					// 	$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:122px' value='Export Masterfile' onclick='export_item()'>";
					// }
					// $h_button .= "</td>";
				}

				$h_button .= "";

				$searchfield = "<select name='pager_search' id='pager_search' onchange='clear_searchbox();'>";
				 //karlo 20170911
				 $this->searchfield=(isset($_POST['pager_search']) and trim($_POST['pager_search'])!="" and $this->same_url!==false) ? $_POST['pager_search']:"";
				 $this->searchvalue=(isset($_POST['pager_search_input']) and trim($_POST['pager_search_input'])!="" and $this->same_url!==false) ? $_POST['pager_search_input']:"";
				for($i=0;$i<count($this->fields);$i++){

					if($this->headers[$i] != ''){

						$xsel = '';

						if($this->searchfield == $this->fields[$i]->fieldname)
						{
							$xsel = 'selected';
						}

						if($this->headers[$i] != 'chk')
						{
							if($this->fields[$i]->type=='date')
							{
								// $searchfield .= "<option $xsel value='".$this->fields[$i]->fieldname."'>".$this->headers[$i]."(mm-dd-yyyy)</option>";
								$searchfield .= "<option $xsel value='".$this->fields[$i]->fieldname."'>".$this->headers[$i]."(yyyy/mm/dd)</option>";
							}
							else
							{
								$searchfield .= "<option $xsel value='".$this->fields[$i]->fieldname."'>".$this->headers[$i]."</option>";
							}

						}
					}
				}

				// $searchfield .= "</select><input type='text' name='pager_search_input' id='pager_search_input' value='".$this->searchvalue."'/><input type='button' id='pager_search_btn' class='search' value='Search' onclick='pager_search()'>";
				$searchfield .= "</select><input type='text' name='pager_search_input' id='pager_search_input' value=''/><input type='button' id='pager_search_btn' class='search' value='Search' onclick='pager_search()'>";

				$hidden_search = '';

				if($this->search == false){
					$hidden_search = 'display:none';
				}
				$chkstyle='';
				$chkdesc='';

				if($this->show_checked == false){ // for asset assignment module
					$chkstyle = 'display:none';
				}
				$chkbox =$this->chk_desc."<input type='checkbox' style='".$chkstyle."' id='txtchk' name='txtchk' ".$this->post_val.">"; //for asset assignment module

				$pager_container1 = "<table id='pager_container1' class='pager_container1'>
				<tr>
				<td class='container1-left' >".$h_button."</td>
				<td class='container1-right' style='".$hidden_search."'>".$chkbox."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$searchfield."</td>
				</tr>
				</table>";

				$pager_container .= $pager_container1;

				$datatable = "<table id='datatable' class='hoverTable june292018'><thead style='font-size:".$this->header_font_size."'><tr>";

				// for($i=0;$i<count($this->headers);$i++){

				// 	if($this->headers[$i] != ''){

				// 		if($this->headers[$i] == "checker")
				// 		{
				// 			$datatable .= "<th class='header'><input type='checkbox' id='pager_checker_head' onclick='pager_check_all(this)'/></th>";
				// 		}
				// 		else
				// 		{
				// 			$datatable .= "<th class='header' onclick=\"header_click('". $this->fields[$i]->fieldname ."',this)\" style='width:".$this->header_width[$i]."%;cursor:hand;'>".$this->headers[$i]."</th>";
				// 		}
				// 	}
				// }
				for($i=0;$i<count($this->headers);$i++)
		    	{
		        	if($this->headers[$i] != '')
		        	{
						$header_field_column = $this->fields[$i]->fieldname;
						if($this->fields[$i]->type == "checker")
						{
							$datatable .= "<th class='header'>";
							$datatable .= "<input type='checkbox' id='pager_checker_head' onclick='pager_check_all(this)'>";
							$datatable .= "</th>";
						}
						else
						{
							$hidden = '';
							if(($this->fields[$i]->fieldname == $this->table_code && $this->usedscascde == 1) ||
								($this->fields[$i]->fieldname == "subdrycde" && ($this->hide_all_subsidiary ==0 || $this->modsubdry == 0 )))
							{
								$hidden = 'hidden';
								$header_field_column="";
							}

							$datatable .= "<th ".$hidden;
							$datatable .= " class='header' ";
							$datatable .= " data-header_field_column=\"{$header_field_column}\"";
							$datatable .= " data-header_field_label=\"{$this->headers[$i]}\"";
							$datatable .= " onclick=\"header_click('". $this->fields[$i]->fieldname ."',this)\"";
							$datatable .= " style='width:".$this->header_width[$i]."%'>";
							$datatable .= $this->headers[$i];
							$datatable .= "</th>";
						}
		        	}
				}

				// if($this->button_handler != '')
				// {
				// 	$datatable .= "<th class='header' style='width:".$this->action_width."'>Action</th>";
				// }
				// else
				// {
				// 	if(!$this->no_side_buttons){
				// 		$datatable .= "<th class='header' style='width:".$this->action_width."'>Action</th>";
				// 	}
				// }

				// $datatable .= "</tr>";

				// $datatable .= "</thead><tbody style='font-size:".$this->body_font_size."'>";
				// $datatable .= "</thead><tbody style='font-size: 12px'>";

				//20180820 gian - consolidate all buttons first
				if(count($this->dataset) != 0)
				{
					$btn_container=array();

					for($i=0;$i<count($this->dataset);$i++)
					{
						if($this->button_handler != '')
						{
							$button = '';

							include ($this->main_dir."/".$this->button_handler);

							// $datatable .= $button;

							$btn_container[$i] .= $button;
						}
						else
						{
							#region side buttons
							if(!$this->no_side_buttons)
							{
								$xid = $this->dataset[$i][$this->table_id];
								$tmp_btn_container="";

								if(count($this->side_buttons) == 0)
								{
									if($this->has_inquiry)
									{
										$tmp_btn_container .= "<input type='button' id='pager_default_view' class='print' value='View' onclick='view_item_click(\"$xid\")'> ";
									}
									if($this->has_edit)
									{
										$tmp_btn_container .= "<input type='button' id='pager_default_edit' class='edit' value='Edit' onclick='edit_item(\"$xid\")'> ";
									}
									if($this->has_delete)
									{
										$tmp_btn_container .= "<input type='button' id='pager_default_delete' class='delete' value='Delete' onclick='delete_item(\"$xid\")'>";
									}
								}
								else
								{
									for($sb1=0;$sb1<count($this->side_buttons);$sb1++)
									{
										if((strpos(strtolower($this->side_buttons[$sb1]->value), "view")!==false ||
											strpos(strtolower($this->side_buttons[$sb1]->value), "inquire")!==false ||
										   	strpos(strtolower($this->side_buttons[$sb1]->value), "inquiry")!==false) && $this->has_inquiry==false)
										{
											continue;
										}
										elseif((strpos(strtolower($this->side_buttons[$sb1]->value), "edit")!==false ||
											strpos(strtolower($this->side_buttons[$sb1]->value), "apply to receivable")!==false ||
											strpos(strtolower($this->side_buttons[$sb1]->value), "apply to payable")!==false ||
											strtolower($this->side_buttons[$sb1]->value) == "unpost" ||
											strpos(strtolower($this->side_buttons[$sb1]->value), "force close")!==false)
											&& $this->has_edit==false)
										{
											continue;
										}
										elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "delete")!==false && $this->has_delete==false)
										{
											continue;
										}
										elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "cancel")!==false && $this->has_cancel==false)
										{
											continue;
										}
										elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "print")!==false && $this->has_print==false)
										{
											continue;
										}
										elseif(strtolower($this->side_buttons[$sb1]->value) == "post" && $this->has_add==false)
										{
											continue;
										}
										else {

										}
										if(count($this->side_buttons[$sb1]->fields) > 0)
										{

											$s_button_fields = "";
											$button_fields = "";

											for($sb2=0;$sb2<count($this->side_buttons[$sb1]->fields);$sb2++)
											{
												$button_fields .= "'".$this->dataset[$i][$this->side_buttons[$sb1]->fields[$sb2]]."',";
											}

											$s_button_fields = $this->side_buttons[$sb1]->event."(".substr($button_fields,0,strlen($button_fields) - 1).")";
											// var_dump($s_button_fields);
											$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$s_button_fields\" > ";
										}
										else
										{

											if($this->side_buttons[$sb1]->event == ''){

												$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' > ";
											}
											else{
												$event = $this->side_buttons[$sb1]->event."()";
												$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$event\" > ";
											}
										}
									}
								}

								if($tmp_btn_container!="")
								{
									$btn_container[$i] = "<td>";
									$btn_container[$i] .= $tmp_btn_container;
									$btn_container[$i] .= "</td>";
								}

								$datatable .= $button;
							}
						}
					}
				}
				if(count($btn_container)>0 && $this->no_side_buttons==false)
				{
					$datatable .= "<th class='header header_action' style='width:".$this->action_width."'>Action</th></thead><tbody>";
				}
				if(count($this->dataset) != 0){
					for($i=0;$i<count($this->dataset);$i++)
					{

						$xmod = $i%2;

						// $xclass = 'even';

						if($xmod == 0){
							$xclass = 'odd';
						}
						else
						{
							$xclass = 'even';
						}


						$datatable .= "<tr id='".$this->dataset[$i][$this->table_id]."' class='".$xclass."'>";

						for($j=0;$j<count($this->fields);$j++){

							$xtype = $this->fields[$j]->type;
							$xhidden_type  = $this->fields[$j]->hidden_type;
							//add type here!!!
							switch($this->fields[$j]->type){
								case 'text':

									$field_handler_val = $this->dataset[$i][$this->fields[$j]->fieldname];

									if($this->fields[$j]->field_handler_table != '')
									{
										$field_handler_val = $this->generateFieldHandler($this->fields[$j],$this->dataset[$i][$this->fields[$j]->fieldname]);
									}

									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".str_replace("'", "&#039;",$this->dataset[$i][$this->fields[$j]->fieldname])."' type=\"$xtype\">".$field_handler_val."</td>";
									break;
								case 'date':
									// $date = date("m-d-Y",strtotime($this->dataset[$i][$this->fields[$j]->fieldname]));
									$date = date("Y-m-d",strtotime($this->dataset[$i][$this->fields[$j]->fieldname]));
									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$date."' type=\"$xtype\">".$date."</td>";
									break;
								case 'boolean':
									if($this->dataset[$i][$this->fields[$j]->fieldname]==1)
									{
										$xvalue='Y';
									}
									else if($this->dataset[$i][$this->fields[$j]->fieldname]==0)
									{
										$xvalue='N';
									}
									else 
									{
										$xvalue='';	
									}
									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$xvalue."' type=\"$xtype\">".$xvalue."</td>";

									break;
								case 'datetime':
									// $date = date("m-d-Y H:i:s",strtotime($this->dataset[$i][$this->fields[$j]->fieldname]));
									$date = date("Y-m-d H:i:s",strtotime($this->dataset[$i][$this->fields[$j]->fieldname]));
									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$date."' type=\"$xtype\">".$date."</td>";
									break;
								case 'select':
									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" >".$this->dataset[$i][$this->fields[$j]->fieldname]."</td>";
									break;
								case 'hidden':
									$datatable .= "<td style='display:none' id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" hidden_type=\"$xhidden_type\">".$this->dataset[$i][$this->fields[$j]->fieldname]."</td>";
									break;

								case 'number':
									// var_dump($this->fields[$j]->decimal_format);
									$field_handler_val = $this->dataset[$i][$this->fields[$j]->fieldname];

									if($this->fields[$j]->field_handler_table != '')
									{
										$field_handler_val = $this->generateFieldHandler($this->fields[$j],$this->dataset[$i][$this->fields[$j]->fieldname]);
									}

									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".number_format($this->dataset[$i][$this->fields[$j]->fieldname],$this->fields[$j]->decimal_format)."' type=\"$xtype\">".number_format($field_handler_val,$this->fields[$j]->decimal_format)."</td>";
									break;

								// case 'link':
								// 	$datatable .= "<td style='display:none' id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" hidden_type=\"$xhidden_type\"><a href=''>".$this->dataset[$i][$this->fields[$j]->fieldname]."</a></td>";
								// 	break;
								case 'checker':
									$datatable .= "<td><input type='checkbox' name='chkfield[".$i."][chk]' value='".$this->dataset[$i][$this->table_id]."' class='pager_checker_body'/></td>";
									break;
								case 'bool':
									if($this->dataset[$i][$this->fields[$j]->fieldname]==1)
									{
										$xvalue=$this->fields[$j]->xtrue;
									}
									else
									{
										$xvalue=$this->fields[$j]->xfalse;
									}
									$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$xvalue."' type=\"$xtype\">".$xvalue."</td>";
									break;
							}
						}


						// ini_set('display_errors',1);
						// error_reporting(E_ALL);

						if($this->button_handler != '')
						{
							$button = '';

							include ("../main/".$this->button_handler);

							$datatable .= $button;
						}
						else
						{
							#region side buttons
							if(!$this->no_side_buttons)
							{
								$xid = $this->dataset[$i][$this->table_id];

								$button = "<td>";

								if(count($this->side_buttons) == 0){

									$button .= "<input type='button' id='pager_default_edit' class='edit' value='Edit' onclick='edit_item(\"$xid\")'/> ";
									$button .= "<input type='button' id='pager_default_delete' class='delete' value='Delete' onclick='delete_item(\"$xid\")'/>";
								}
								else{

									for($sb1=0;$sb1<count($this->side_buttons);$sb1++)
									{
										if(count($this->side_buttons[$sb1]->fields) > 0)
										{
											if((strpos(strtolower($this->side_buttons[$sb1]->value), "inquire")!==false &&
											strpos(strtolower($this->side_buttons[$sb1]->value), "inquiry")!==false) && $this->has_inquiry==false)
											{
												continue;
											}
											elseif((strpos(strtolower($this->side_buttons[$sb1]->value), "edit") !== false ||
												strpos(strtolower($this->side_buttons[$sb1]->value), "apply to receivable") !== false ||
												strpos(strtolower($this->side_buttons[$sb1]->value), "apply to payable")!==false ||
												strpos(strtolower($this->side_buttons[$sb1]->value), "force close")!==false)
												 && $this->has_edit==false)
											{
												continue;
											}
											elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "delete")!==false && $this->has_delete==false)
											{
												continue;
											}
											elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "print")!==false && $this->has_print==false)
											{
												continue;
											}
											elseif(strpos(strtolower($this->side_buttons[$sb1]->value), "cancel")!==false && $this->has_cancel==false)
											{
												continue;
											}

											$s_button_fields = "";
											$button_fields = "";

											for($sb2=0;$sb2<count($this->side_buttons[$sb1]->fields);$sb2++){

												$button_fields .= "'".$this->dataset[$i][$this->side_buttons[$sb1]->fields[$sb2]]."',";
											}

											$s_button_fields = $this->side_buttons[$sb1]->event."(".substr($button_fields,0,strlen($button_fields) - 1).")";

											$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$s_button_fields\" > ";
										}
										else
										{

											if($this->side_buttons[$sb1]->event == ''){

												$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' > ";
											}
											else{
												$event = $this->side_buttons[$sb1]->event."()";
												$tmp_btn_container .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$event\" > ";
											}
										}
									}
								}

								// $button .= "</td>";

								// $datatable .= $button;
							}
							#end

						}
						$datatable.=$btn_container[$i];
						$datatable .= "</tr>";
					}
				}
				else{
					$xtdcount=count($this->fields)+1;
					$datatable .= "<tr><td colspan='".$xtdcount."'>No Records Found...</td></tr>";
				}

				$datatable .= "</tbody></table>";
				$pager_container .= $datatable;

				$current_page = $this->page;

				$pager_selector = "<div class='pager_selector' id='div_pager_selector' >Page : <select id='pager_selector' style='font-size:10px'>";

				for($i=1;$i<$this->totalpages + 1;$i++){

					$selected = "";

					if($current_page == $i){
						$selected = "selected";
					}

					$pager_selector .= "<option ".$selected." value='".$i."'>".$i."</option>";
				}

				$pager_selector .= "</select> of ".$this->totalpages."</div>";

				$pager_navigator = "<div class='pager_navigator'>
				<input class='pager_btn' id='pager_first' type='text' value='First' readonly/>
				<input class='pager_btn' id='pager_prev' type='text' value='Previous' readonly/>
				<input class='pager_btn' id='pager_next' type='text' value='Next' readonly/>
				<input class='pager_btn' id='pager_last' type='text' value='Last' readonly/></div>";

				$pager_limiter = "<div class='pager_limiter' id='div_pager_limiter'>Row(s) per Page : <select id='pager_limiter' style='font-size:10px'>";

				$limit_array = array(10,20,50,100);

				for($i=0;$i<count($limit_array);$i++){

					$selected = "";

					if($this->limit == $limit_array[$i]){
						$selected = "selected";
					}

					$pager_limiter .= "<option ".$selected." value='".$limit_array[$i]."'>".$limit_array[$i]."</option>";
				}

				$pager_limiter .= "</select></div>";

				if($this->showInfo == true){
					$hidden = "style='display:''";
				}
				else{
					$hidden = "style='display:none'";
				}

				$fieldstr = "";
				$headerstr = "";
				for($f=0;$f<count($this->fields);$f++){

				

					if($this->fields[$f]->type == 'text'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					if($this->fields[$f]->type == 'number'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'select'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'date'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'checkbox'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'boolean'){
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'hidden' && $this->fields[$i]->hidden_type == 'select' && $this->fields[$f]->modal_display == true)
					{
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
					else if($this->fields[$f]->type == 'hidden' && $this->fields[$i]->hidden_type == 'checkbox' && $this->fields[$f]->modal_display == true)
					{
						$fieldstr .= $this->fields[$f]->fieldname.",";
						$fieldtype .= $this->fields[$f]->type.",";
					}
				}
				
				for($f=0;$f<count($this->fields);$f++){
					$headerstr .= $this->headers[$f].",";
				}
				$_SESSION[$xg_appkey]['print_flds'] = $this->print_fields;
				$_SESSION[$xg_appkey]['print_hdr'] = $this->headers;

				$fieldstr = substr($fieldstr,0,strlen($fieldstr) - 1);
				$headerstr = substr($headerstr,0,strlen($headerstr) - 1);
				$fieldtype = substr($fieldtype,0,strlen($fieldtype) - 1);

				$th_index=isset($_POST['header_th_index']) ? $_POST['header_th_index']:0;
				$th_sort_order=isset($_POST['header_sort_order']) ? $_POST['header_sort_order']:'';
				
				$pager_container2 = "<table id='pager_container2' class='pager_container2' style='font-size:10px'>
				<tr>
					<td class='container2-left'>".$pager_selector."</td>
					<td class='container2-center'>".$pager_navigator."</td>
					<td class='container2-right'>".$pager_limiter."</td>
				</tr>

				<tr ".$hidden.">
					<td colspan='3'>
						<table>
						<tr><td>total_pages: <input type='text' id='pager_totalpages' value='".$this->totalpages."'/></td></tr>
						<tr><td>current_page: <input type='text' name='pager_page' id='pager_page' value='".$this->page."'/></td></tr>
						<tr><td>current_limit: <input type='text' name='pager_limit' id='pager_limit' value='".$this->limit."'/></td></tr>
						<tr><td>active_id: <input type='text' id='pager_active_id' /></td></tr>
						<tr><input type='hidden' id='pager_fields' name='pager_fields' value='".$fieldstr."'></tr>
						<tr><input type='hidden' id='pager_type' name='pager_type' value='".$fieldtype."'></tr>
						<tr><input type='hidden' id='pager_headers' name='pager_headers' value='".$headerstr."'></tr>
						<tr><input type='hidden' id='pager_table' name='pager_table' value='".$this->table."'></tr>
						<tr><input type='hidden' id='pager_table_id' value='".$this->table_id."'></tr>;
						<tr><input type='hidden' id='pager_xlink' name='pager_xlink' value='".$this->ajax_id."'></tr>
						<tr><input type='hidden' id='pager_xfield' value='".$this->field_name."'>
						<input type='hidden' id='pager_title' name='pager_title' value='".$this->title."'>
						<input type='hidden' id='print_flds' name='print_flds' value='".$_SESSION[$xg_appkey]['print_flds']."'>
						<input type='hidden' id='print_header' name='print_header' value='".$_SESSION[$xg_appkey]['print_hdr']."'>
						<input type='hidden' id='sort_order' name='sort_order' value='".($this->order_by!='' ? $this->order_by:"ASC") ."'>
						<input type='hidden' id='header_sort_order' name='header_sort_order' value='".$_POST['header_sort_order']."'>
						<input type='hidden' id='header_th_index' name='header_th_index' value='".$th_index."'>
						<input type='hidden' id='qry_orderby' name='qry_orderby' value='".$this->order."'>
						<input type='hidden' name='pager_event_action' id='pager_event_action' value='".$_POST['pager_event_action']."'>
						</tr>";

				for($i=0;$i<count($this->fields);$i++)
				{
					$pager_container2 .= "<tr>";
					$pager_container2 .= "<td>".$this->headers[$i]." : <input type='text' id='pager_".$this->fields[$i]->fieldname."' /></td>";
					$pager_container2 .= "</tr>";
				}

				$pager_container2 .= "</table></td></tr>
				</table>";

				$pager_container .= $pager_container2;
				$pager_container .= "</div></article>";

				$this->generateHtmlObjects();

				$objects = "<table style='".$this->modal_css."'>";

				for($i=0;$i<count($this->genfields);$i++){

					$objects .= "<tr>".$this->genfields[$i]."</tr>";
				}

				$objects .= "</table>";

				$pager_container .= "<div id='pager_modal' style='display:none;'>".$objects."</div>";

				if( $this->search_option )
				{
					$this->generateAdvSearch();

					$objects_advsearch = "<table style='".$this->modal_css."'>";

					for( $i=0; $i<count($this->gen_advsearch); $i++ )
					{

						$objects_advsearch .= "<tr>".$this->gen_advsearch[$i]."</tr>";
					}

					$objects_advsearch .= "</table>";

					$pager_container .= "<div id='div_pager_advsearch' style='display:none;'>".$objects_advsearch."</div>";
				}

				echo $pager_container;
				$this->loadJavascript();
			}
		}

		public function freeze_action(){
			echo	'	<script type="text/javascript">
							jQuery(document).ready(function() {
								var header_width = [];
								var action_header_index=null;
								jQuery("#div_pager #datatable th:visible").each(function(index,value)
								{
									if(jQuery(this).hasClass("header_action"))
									{
										action_header_index=index;
										header_width.push(jQuery(this)[0].style.width.replace("px",""));
									}
									else{
										header_width.push(window.getComputedStyle(this, null)["width"].replace("px", ""));
									}
								});
								
								jQuery("#div_pager #datatable").lst_freeze_table({
									column_width:header_width,
									right_fixed_column_index: [action_header_index],
									tbl_max_auto: "auto",
									tbl_max_height: "auto",
									hoverable_rows:false
								}) 
							})
						</script>
					';
		}

		public function freeze_custom(){
			$converted_header_width = json_encode($this->header_width);
			echo '	<script type="text/javascript">
				jQuery(document).ready(function() {
					let new_header_width = '.$converted_header_width.'
					new_header_width.splice(new_header_width.length - 1, 0 ,'.$this->action_width.'  ) 
					// new_header_width.push('.$this->action_width.'  ) 
					console.log(new_header_width);
					
					jQuery("#div_pager #datatable").lst_freeze_table({
						column_width:new_header_width,
						right_fixed_column_index:['.count($this->headers).' - 1],
						// right_fixed_column_index:[4],
						tbl_max_auto: "auto",
						tbl_max_height: "auto",
						hoverable_rows:false
					})
				})
				</script>
			';
			// [110,100,250,110,105,135,110,100,0]
			}
	}
