<?php

	class PagerV2 {
		public $link_id;
		public $ajax_id;
		public $table;
		public $table_id;
		public $headers;
		public $header_width;
		public $fields;
		public $filter;
		public $params;
		public $group;
		public $order;
		public $limit;
		public $page;
		public $no_header_buttons = false;
		public $no_side_buttons = false;
		public $header_font_size;
		public $body_font_size;
		public $header_buttons;
		public $side_buttons;
		public $hovering_type = 'hover';
		public $modal_width;
		public $modal_height;
		public $modal_css = 'font-size:14px;font-family:arial';
		public $dataset;
		public $totalpages;
		public $showInfo;
		public $pager_keys;
		public $genfields = array();
		public $search = true;
		public $pager_width = '100%';
		public $action_width = '145px';
		public $searchvalue = '';
		public $searchfield = '';
		public $show_checkbox = false;
		public $show_checked = false; //addded for asset assignment module
		public $chkbox = ''; //addded for asset assignment module
		public $field_name;
		public $show_print = false;
		public $show_export = false;
		public $exclude_recid = false;
		public $print_fields;
		public $button_handler;
		public $table_exemption;
		public $show_note = false;
		public $module_name;
		public $validate_fields='';
		public $filter_fields = array();

		public $order_by = 'ASC'; // added 20160323 -jep; for sort order ASC/DESC

		public function __construct($page,$limit,$showInfo = false){
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

		public function convertQueryLimit($table, $table_id, $fields, $filter='', $limit='', $offset = '', $sort = '' ,$group = '',$showrecid=false,$notinfield=''){
			
			global $xdbtype;
			$xbool = true;
			$query = '';
			// $xtemp = explode(',',$table);
		
			// if(count($xtemp) > 1){
							
			// 	// var_dump('pass');			
			// 	if($xdbtype == 'ms')
			// 	{	
			// 		$query = "With results AS
			// 						( SELECT 
			// 						 rows = ROW_NUMBER() OVER (order by ".$table_id."), ".$fields." FROM ".$xtemp[0].", ".$xtemp[1]." ".$filter.")
			// 					select *
			// 					from results
			// 					Where rows Between ".($offset + 1)." and ".($offset + $limit);
					
			// 	}
			// 	else
			// 	{
			// 		$query = "SELECT ".$fields."  FROM ".$table." ".$filter." ".$group." ".$sort." LIMIT ". $limit." OFFSET ".$offset;
			// 	}
				
			// 	$xbool = false;

			// }
			
			if($xdbtype == 'ms' && $xbool)
			{
				// if($filter !='')
				// {
					// $filter .= ' AND ';
				// }
				// else 
				// {
					// $filter  .= ' WHERE ';
				// }
				
				if($offset >= 0)
				{

					if($showrecid)
					{
						$filter .= " AND ".$notinfield." NOT IN(SELECT TOP ". $offset." ".$notinfield." FROM ".$table." ".$group." ".$sort.")";
					}
					else
					{
						$filter .= " AND ".$table_id." NOT IN(SELECT TOP ". $offset." ".$table_id." FROM ".$table." ".$filter." ".$group." ".$sort.")";
           			}
					
					$query = "SELECT TOP " .$limit ." ".$fields. " FROM ". $table. " ".$filter ." ".$group." ".$sort; 
					// var_dump($query);
				}
			}
			elseif($xdbtype=='my' && $xbool)
			{
				$query = "SELECT ".$fields."  FROM ".$table." ".$filter." ".$group." ".$sort." LIMIT ". $limit." OFFSET ".$offset;
			}
			// var_dump($query);
			$xret = $query;
			return $xret;
		}

		public function generateResultSet()
		{
		 global $xdbtype;
		 if($this->group!="")
		 {
				$sql = "SELECT COUNT(*) OVER () as xcount FROM ".$this->table." ".$this->filter." ".$this->group;
         }
         else
         {
            $sql = "SELECT count(*) as xcount FROM ".$this->table." ".$this->filter;
         }
			$stmt = $this->link_id->prepare($sql);

			if($this->filter != ''){
				$stmt->execute($this->params);
			}
			else{
				$stmt->execute();
			}
			// var_dump($sql,$this->params);
			$rs = $stmt->fetch();
			$rows = $rs['xcount'];
			 //~ var_dump($stmt->errorinfo(),$sql,$this->params,$rs);
			$this->totalpages = ceil($rows/$this->limit);
					
			$offset = $this->page <= 1 ? 0 : ($this->page - 1) * $this->limit;

			$xfields = '';
			
			for($i=0;$i<count($this->fields);$i++){
				$xfields .= $this->fields[$i]->fieldname.",";
			}
			
			$xfields = substr($xfields, 0, strlen($xfields)-1);
			
	        if(!$this->exclude_recid)
	        {
				$xfields = $this->table_id.",".$xfields;
				$sql = $this->convertQueryLimit($this->table,$this->table_id,$xfields,$this->filter,$this->limit,$offset,$this->order,$this->group);
				$stmt = $this->link_id->prepare($sql);
				// var_dump($sql);
			}
			else
			{
				
				$xfields = $xfields;
				$sql = $this->convertQueryLimit($this->table,$this->table_id,$xfields,$this->filter,$this->limit,$offset,$this->order,$this->group,$this->exclude_recid,$this->fields[0]->fieldname);
				$stmt = $this->link_id->prepare($sql);
				// var_dump($sql);
			}		
			// var_dump($sql);						
			if($this->filter != ''){
				
				$xparams = array();
			
				$xbool = true;
				if($xdbtype == 'ms' && $xbool)
				{
					for($x=0;$x<2;$x++)
					{
						for($i=0;$i<count($this->params);$i++)
						{
							$xparams[count($xparams)] = $this->params[$i];
						}
					}
				}
				elseif($xdbtype=='my' && $xbool)
				{
					for($x=0;$x<1;$x++)
					{
						for($i=0;$i<count($this->params);$i++)
						{
							$xparams[count($xparams)] = $this->params[$i];
						}
					}
				}
				$stmt->execute($xparams);
			}
			else
			{
				$stmt->execute();
			}

			// echo "<pre>";
			//  var_dump($sql,$xparams,$stmt);
			//  echo "</pre>";
			//  die();
			// var_dump($this->filter);
			$result = $stmt->fetchAll();
			// echo "<pre>";
			//  var_dump($sql,$xparams,$stmt->errorInfo(),$result);
			//  echo "</pre>";
			$datarow = array();

			foreach($result as $rs){

				$resultset = array();
				$xtbid = explode(".", $this->table_id);
				$resultset[$xtbid[count($xtbid)-1]] = $rs[$xtbid[count($xtbid)-1]];

				for($i=0;$i<count($this->fields);$i++){
					$xtempfield = explode(".", $this->fields[$i]->fieldname);
					$this->fields[$i]->fieldname = $xtempfield[count($xtempfield)-1];
					$resultset[$this->fields[$i]->fieldname] = $rs[$this->fields[$i]->fieldname];			
				}

				array_push($datarow,$resultset);
			}

			$this->dataset = $datarow;
			
		}

		private function generateHtmlObjects(){

			$this->genfields[0] = "<td colspan='2'><input type='hidden' id='modalField_".$this->table_id."' name='modalField[".$this->table_id."]' /></td>";
			
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

				// jQuery(document).ready(function(){

					// jQuery('#txtchk').click(function(){
						
					//    document.forms.myform.pager_page.value=page;
			  //          document.forms.myform.action='".$_SERVER['PHP_SELF']."';
			  //          document.forms.myform.method='post';
			  //          document.forms.myform.target='_self';
			  //          document.forms.myform.submit();
			  //       });

					// jQuery('.datepicker').datepicker();
					// AddAutoCompleteHandlers();

					// jQuery('ul.ui-autocomplete').css('z-index',1100);
					
				// });

				var fields = jQuery('#pager_fields').val().split(',');
				var headers = jQuery('#pager_headers').val().split(',');
				var table_exemption = jQuery('#table_exemption').val().split(',');
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
					var x = '".$this->validate_fields."';
					var y = ".json_encode($this->filter_fields).";

					var addParams = jQuery('#pager_modal *').serialize()+\"&table=\"+table+\"&table_id=\"+table_id+\"&validate=\"+x+\"&filterflds=\"+y+\"&title=\"+xtitle+\"&head=\"+headers+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=add\";
							
					jQuery.ajax({
						url:'class/pager_handler.php',
						type:'post',
						dataType:'json',
						data: addParams,
						success:function(response){

							if(response=='exist')
							{
								alertify.alert('Record already exist');
							}
							else
							{
								alertify.alert('Successfully added!')
								jQuery('#pager_modal table input').val('');
								jQuery('#pager_modal').dialog('close');
								pager_reload();
							}
							
						}
					});
				}
				
				function updateRow()
				{

					var x='".$this->validate_fields."';
					var y = ".json_encode($this->filter_fields).";
					var updateParams = jQuery(\"#pager_modal *\").serialize()+\"&table=\"+table+\"&table_id=\"+table_id+\"&validate=\"+x+\"&filterflds=\"+y+\"&head=\"+headers+\"&title=\"+xtitle+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=update&fieldname=\"+xfield;
					
					jQuery.ajax({
						url:'class/pager_handler.php',
						type:'post',
						dataType:'json',
						data: updateParams,
						success:function(response){
							
							if(response=='exist')
							{
								alertify.alert('Record already exist');
							}
							else
							{
								alertify.alert('Successfully updated!!')
								jQuery('#pager_modal table input').val('');
								jQuery('#pager_modal').dialog('close');
								pager_reload();
							}
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

					jQuery('#div_pager #datatable tbody tr').each(function(key,val){s

						var xactive = jQuery(val).attr('id');

						if(active_id == xactive){

							for(var i=0;i<pager_keys.length;i++){
								var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
								jQuery('#modalField_'+pager_keys[i]).val(dataval);
							}										
						}
					
					});
					
					jQuery('#pager_modal').hide();
					
					alertify.confirm('Delete this record?',
					function(){
						blockui();
						var deleteParams = jQuery('#pager_modal *').serialize()+\"&table_exemption=\"+table_exemption+\"&table=\"+table+\"&table_id=\"+table_id+\"&head=\"+headers+\"&title=\"+xtitle+\"&fields=\"+fields+\"&pager_xlink=\"+xlink_id+\"&pager_event_action=delete&fieldname=\"+xfield;
						
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

				function header_click(fieldname){
					
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
					document.forms.myform.action='".$_SERVER['PHP_SELF']."';
					document.forms.myform.method='POST';
					document.forms.myform.target='_self';
					document.forms.myform.submit();
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

					$javascript .= "jQuery('#div_pager #datatable tbody tr ').each(function(key,val){
				 
						// jQuery(val).hover(function(){
						jQuery(val).on('mouseenter', function(){

							var active_id = jQuery(this).attr('id');
							
							jQuery('#pager_active_id').val(active_id);
							
							for(var i=0;i<pager_keys.length;i++){
								var dataval = jQuery(val).find('#'+pager_keys[i]).attr('value');
								jQuery('#pager_'+pager_keys[i]).val(dataval);
							}
							
						});
					});";
				}

			$javascript .= "</script>";

			echo $javascript;
		}

		public function render(){
			global $xg_appkey;
			// $pager_container = "<article id='pager_article' class='pager-module' style='min-width:60%;max-width:100%;width:".$this->pager_width."'>
			$pager_container = "<article id='pager_article' class='pager-module' style='min-width:60%;max-width:100%;width:100%'>
							<header>
								<h3 class='tabs_involved'>".$this->title."</h3>
							</header>
							<div class='tab_container'><div id='div_pager' class='pager tab_content'>";

			#region header buttons

			if(!$this->no_header_buttons){

				$h_button = "";
				
				if(count($this->header_buttons) == 0){

						$h_button .= "<input type='button' id='pager_default_add' class='add' value='Add' onclick='add_item()'/>";
				}
				else
				{
               		if($this->show_checkbox)
					{
						$xapply = "<select style='align:left' id='".$this->fields[0]->select_id."' name='".$this->fields[0]->select_name."' class='".$this->fields[0]->select_class."' >";

						for($chk1=0;$chk1<count($this->fields[0]->select_optionval);$chk1++)
						{
							$xapply .= "<option value='".$this->fields[0]->select_optionval[$chk1]."'>".$this->fields[0]->select_optiondesc[$chk1]."</option>";
						}

						$xbtn_event = $this->fields[0]->apply_btn_event."()";

						$xapply .= "</select> <input type='button' id='".$this->fields[0]->apply_btn_id."' style='margin-right:5px' name='".$this->fields[0]->apply_btn_name."' class='".$this->fields[0]->apply_btn_class."' value='".$this->fields[0]->apply_btn_value."' onclick=\"$xbtn_event\"/>";

						$h_button .= $xapply;
					}
					
					for($hb1=0;$hb1<count($this->header_buttons);$hb1++){

						if(count($this->header_buttons[$hb1]->fields) > 0){

							$h_button_fields = $this->header_buttons[$hb1]->event."()";
							
							$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' onclick=\"$h_button_fields\" /> ";
						}
						else{
								
							if($this->header_buttons[$hb1]->event == ''){

								$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' /> ";
							}
							else{
								$event = $this->header_buttons[$hb1]->event."()";
								$h_button .= "<input type='button' name='".$this->header_buttons[$hb1]->name."' id='".$this->header_buttons[$hb1]->id."' class='".$this->header_buttons[$hb1]->class."' value='".$this->header_buttons[$hb1]->value."' onclick=\"$event\" /> ";
							}

						}
					}
				}
				if($this->show_print)
				{
					if($this->title == "Employee File")
					{
						$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:80px' value='Print' onclick='print_item()'/>";

					}
					else
					{
						$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:110px' value='Print Masterfile' onclick='print_item()'/>";

					}
				}
				if($this->show_export)
				{

					$h_button .= "&nbsp;<input type='button' id='pager_default_add' class='print' style='width:122px' value='Export Masterfile' onclick='export_item()'/>";
				}
				$h_button .= "</td>";
			}
			#end
		
			$searchfield = "<select name='pager_search' id='pager_search' onchange='clear_searchbox();'>";

			for($i=0;$i<count($this->fields);$i++){

				if($this->headers[$i] != '')
				{
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
							$searchfield .= "<option $xsel value='".$this->fields[$i]->fieldname."'>".$this->headers[$i]."(yyyy-mm-dd)</option>";
						}
						else
						{
							$searchfield .= "<option $xsel value='".$this->fields[$i]->fieldname."'>".$this->headers[$i]."</option>";
						}
					}
				}
			}

			$searchfield .= "</select><input type='search' name='pager_search_input' id='pager_search_input' value='".$this->searchvalue."'/><input type='button' id='pager_search_btn' class='search' value='Search' onclick='pager_search()'>";

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

			$datatable = "<table id='datatable' class='hoverTable'><thead style='font-size:".$this->header_font_size."'><tr>";

			for($i=0;$i<count($this->headers);$i++){
				
				if($this->headers[$i] != ''){

					if($this->headers[$i] == "checker")
					{
						$datatable .= "<th class='header'><input type='checkbox' id='pager_checker_head' onclick='pager_check_all(this)'/></th>";
					}
					else
					{
						// if($this->headers[$i]=='Total Amount' || $this->headers[$i]=='Amount' || $this->headers[$i]=='Balance' || $this->headers[$i]=='Percentage')
						// {
						// 	$thstyle = "width:".$this->header_width[$i]."px;text-align:right;";
						// }
						// else
						// {
							$thstyle = "width:".$this->header_width[$i]."px";
						// }
						// $datatable .= "<th class='header' style=".$thstyle.">".$this->headers[$i]."</th>";
						$datatable .= "<th class='header'  style='width:".$this->header_width[$i]."px;cursor:hand;".$thstyle."'>".$this->headers[$i]."</th>";
					}
				}
			}

			if($this->button_handler != '')
			{
				$datatable .= "<th class='header' style='width:".$this->action_width."'>Action</th>";	
			}
			else
			{
				if(!$this->no_side_buttons){
					$datatable .= "<th class='header' style='width:".$this->action_width."'>Action</th>";	
				}
			}

			$datatable .= "</tr>"; 

			// $datatable .= "</thead><tbody style='font-size:".$this->body_font_size."'>";
			$datatable .= "</thead><tbody>";

			if(count($this->dataset) != 0){

				for($i=0;$i<count($this->dataset);$i++){
					
					$xmod = $i%2;

					$xclass = 'even';

					if($xmod == 0){
						$xclass = 'odd';
					}
					
					$datatable .= "<tr id='".$this->dataset[$i][$this->table_id]."' class='".$xclass."'>";

					for($j=0;$j<count($this->fields);$j++){
						
						$xtype = $this->fields[$j]->type;
						$xhidden_type  = $this->fields[$j]->hidden_type;
						//add type here!!!
						switch($this->fields[$j]->type){
							case 'text':

								// $field_handler_val = $this->dataset[$i][$this->fields[$j]->fieldname]; // original 2015-10-29
								// added ferlyn 2015-10-29
								if($this->fields[$j]->numberformat)
								{
										$field_handler_val = number_format($this->dataset[$i][$this->fields[$j]->fieldname],2);
										$align = "style='text-align:right;'";
								}
								else if($this->fields[$j]->numberformat1)
								{
										$field_handler_val = number_format($this->dataset[$i][$this->fields[$j]->fieldname],2);
										$align = "";
								}
								else if($this->fields[$j]->numberformat2)
								{
										$field_handler_val = number_format($this->dataset[$i][$this->fields[$j]->fieldname]);
										$align = "";
								}
								else
								{
										$field_handler_val = $this->dataset[$i][$this->fields[$j]->fieldname];
										$align = '';
								}

								
								//end

								if($this->fields[$j]->field_handler_table != '')
								{
									$field_handler_val = $this->generateFieldHandler($this->fields[$j],$this->dataset[$i][$this->fields[$j]->fieldname]);
								}

								// $datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" $align>".$field_handler_val."</td>";
								$datatable .= "<td id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" >".$field_handler_val."</td>";

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
								else
								{
									$xvalue='N';
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
							// case 'link':
							// 	$datatable .= "<td style='display:none' id='".$this->fields[$j]->fieldname."' value='".$this->dataset[$i][$this->fields[$j]->fieldname]."' type=\"$xtype\" hidden_type=\"$xhidden_type\"><a href=''>".$this->dataset[$i][$this->fields[$j]->fieldname]."</a></td>";
							// 	break;
							case 'checker':
								$datatable .= "<td><input type='checkbox' name='chkfield[".$i."][chk]' value='".$this->dataset[$i][$this->table_id]."' class='pager_checker_body'/></td>";
								break;
						}	
					}

				

					if($this->button_handler != '')
					{
						$button = '';

						include ("../main/".$this->button_handler);

						$datatable .= $button;
					}
					else
					{

						#region side buttons
						if(!$this->no_side_buttons){
							
							$xid = $this->dataset[$i][$this->table_id];
							
							$button = "<td>";

							if(count($this->side_buttons) == 0){

								$button .= "<input type='button' id='pager_default_edit' class='edit' value='Edit' onclick='edit_item(\"$xid\")'/> ";
								$button .= "<input type='button' id='pager_default_delete' class='delete' value='Delete' onclick='delete_item(\"$xid\")'/>";
							}
							else{

								for($sb1=0;$sb1<count($this->side_buttons);$sb1++){

									if(count($this->side_buttons[$sb1]->fields) > 0){
									
										$s_button_fields = "";
										$button_fields = "";

										for($sb2=0;$sb2<count($this->side_buttons[$sb1]->fields);$sb2++){

											$button_fields .= "'".$this->dataset[$i][$this->side_buttons[$sb1]->fields[$sb2]]."',";
										}

										$s_button_fields = $this->side_buttons[$sb1]->event."(".substr($button_fields,0,strlen($button_fields) - 1).")";
										
										$button .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$s_button_fields\" /> ";
									}
									else{

										if($this->side_buttons[$sb1]->event == ''){

											$button .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' /> ";
										}
										else{
											$event = $this->side_buttons[$sb1]->event."()";
											$button .= "<input type='button' name='".$this->side_buttons[$sb1]->name."' id='".$this->side_buttons[$sb1]->id."' class='".$this->side_buttons[$sb1]->class."' value='".$this->side_buttons[$sb1]->value."' onclick=\"$event\" /> ";
										}
									}
								}
							}
							$button .= "</td>";

							$datatable .= $button;
						}
						#end

					}

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

			$pager_selector = "<div class='pager_selector'>Page : <select id='pager_selector' style='font-size:10px'>";

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


			$pager_limiter = "<div class='pager_limiter'>Row(s) per Page : <select id='pager_limiter' style='font-size:10px'>";
			
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
				}
				else if($this->fields[$f]->type == 'select'){
					$fieldstr .= $this->fields[$f]->fieldname.",";
				}
				else if($this->fields[$f]->type == 'date'){
					$fieldstr .= $this->fields[$f]->fieldname.",";
				}
				else if($this->fields[$f]->type == 'checkbox'){
					$fieldstr .= $this->fields[$f]->fieldname.",";
				}
				else if($this->fields[$f]->type == 'hidden' && $this->fields[$i]->hidden_type == 'select' && $this->fields[$f]->modal_display == true)
				{
					$fieldstr .= $this->fields[$f]->fieldname.",";
				}
				else if($this->fields[$f]->type == 'hidden' && $this->fields[$i]->hidden_type == 'checkbox' && $this->fields[$f]->modal_display == true)
				{
					$fieldstr .= $this->fields[$f]->fieldname.",";
				}					
			}
			
			// die();
			for($f=0;$f<count($this->fields);$f++){
				$headerstr .= $this->headers[$f].",";
			}

			for($f=0;$f<count($this->table_exemption);$f++){
				$table_exemptionstr .= $this->table_exemption[$f].",";
			}

			$_SESSION[$xg_appkey]['print_flds'] = $this->print_fields;
			$_SESSION[$xg_appkey]['print_hdr'] = $this->headers;

			$fieldstr = substr($fieldstr,0,strlen($fieldstr) - 1);
			$headerstr = substr($headerstr,0,strlen($headerstr) - 1);
			$table_exemptionstr = substr($table_exemptionstr,0,strlen($table_exemptionstr) - 1);

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
					<tr><input type='hidden' id='pager_headers' name='pager_headers' value='".$headerstr."'></tr>
					<tr><input type='hidden' id='pager_table' name='pager_table' value='".$this->table."'></tr>
					<tr><input type='hidden' id='pager_table_id' value='".$this->table_id."'></tr>;
					<tr><input type='hidden' id='pager_xlink' name='pager_xlink' value='".$this->ajax_id."'></tr>
					<tr><input type='hidden' id='pager_xfield' value='".$this->field_name."'>
					<input type='hidden' id='pager_title' name='pager_title' value='".$this->title."'>
					<input type='hidden' id='table_exemption' name='table_exemption' value='".$table_exemptionstr."'>
					<input type='hidden' id='print_flds' name='print_flds' value='".$_SESSION[$xg_appkey]['print_flds']."'>
					<input type='hidden' id='print_header' name='print_header' value='".$_SESSION[$xg_appkey]['print_hdr']."'>
					<input type='hidden' id='sort_order' name='sort_order' value='".$this->order_by."'>
					<input type='hidden' id='header_sort_order' name='header_sort_order' value=''>
					<input type='hidden' id='qry_orderby' name='qry_orderby' value='".$this->order."'>
					<input type='hidden' id='module_name' name='module_name' value='".$this->module_name."'>
					</tr>";

					// var_dump($_SESSION[$xg_appkey]['print_hdr']);
				
			for($i=0;$i<count($this->fields);$i++){

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
			if($this->show_note)
			{
				// $objects .="<table style='font-size:14px;font-family:arial'>";
				$objects .="<table style='font-size:14px'>";
				$objects .="<br><hr>";
				$objects .="<tr><center><b>Transaction Codes</b></center></tr><br>";
				$objects .="<tr><b>ADJ</b> - Adjustments (A positive quantity increases inventory while
					a negative value decreases inventory)</tr><br><br>";
				$objects .="<tr><b>OUT</b> - Decreasess Inventory (does not allow negative value)</tr><br><br>";
				$objects .="<tr><b>STT</b> - Stock Transfer (deducts inventory from one warehouse and Transfer
					)</tr>";
				$objects .="</table>";
			}
			

			$pager_container .= "<div id='pager_modal' style='display:none;'>".$objects."</div>";

			echo $pager_container;
			$this->loadJavascript();
		}
	}
	
