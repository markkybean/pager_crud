<?php
	require_once("./header.php");
?>

<div name='div_transaction' id='div_transaction'></div>
<div class="container container_margin">
	<!-- <form id="myfrom" name="myform" method='POST'> -->
		<button type="button" class="btn btn-secondary container_margin" id="btn_add">Add</button>
		<button type="button" class="btn btn-secondary container_margin" id="btn_print" onclick="print_click('pdf')">Print</button>
		<button type="button" class="btn btn-secondary container_margin" id="btn_export" onclick="print_click('tab')">Export</button>
		<input type="text" name="txt_repoutput" hidden>
	<!-- </form> -->
	<article>
		<div>
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th scope="col">Full Name</th>
						<th scope="col">Address</th>
						<th scope="col">Gender</th>
						<th scope="col">Contact No.</th>
						<th scope="col">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$xqry = "SELECT * FROM employeefile";
						$xstmt=$link_id->prepare($xqry);
						$xstmt->execute(array());
						while($xrs=$xstmt->fetch())
						{
							
					?>
							<tr>
								<td><?php echo $xrs["fullname"]?></td>
								<td><?php echo $xrs["address"]?></td>
								<td><?php echo $xrs["gender"]?></td>
								<td><?php echo $xrs["contactnum"]?></td>
								<td>
									<input type="button" class="btn btn-primary" onclick="edit_click(<?php echo $xrs['recid']?>)" value="Edit"> 
									<input type="button" class="btn btn-danger" onclick="delete_click(<?php echo $xrs['recid']?>)" value="Delete">
								</td>
							</tr>
					<?php	
						}

					?>
				</tbody>
			</table>
		</div>
	</article>
</div>

<script  type="text/javascript">
		var xpar = "";
		// $('.birthdate').datepicker({dateFormat:'mm-dd-yy'});
		$('.birthdate').datepicker({dateFormat:'yy-mm-dd'});

		$(document).ready(function (){
			$( "#tabs" ).tabs();

			$("#div_transaction").dialog
			({
				modal : true,
				width : 600,
				height: 580,
				title : "Add Employee",
				autoOpen:false,
				closeOnEscape : false,
				resizable : false,
				open : function (){
				jQuery('.ui-dialog-titlebar-close').hide();
				},
				buttons : 
				{
					"Save" : function (){ save_employee(); },
					"Close" : function () { jQuery(this).dialog('close'); }
				}
			});
		});

		$("#btn_add").click(function(){
			var xdata = "event_action=get_addform";
			$.ajax({
				url: "ajax_crud.php",
				type: "POST",
				dataType: "json",
				data: xdata,
				success: function(xres){
					$('#div_transaction').empty('');
					$('#div_transaction').append(xres.form);
					$('#div_transaction').dialog('open');
					xpar = "ADD";
				}
			});
			
		});

		function save_employee(){
			if (xpar === "ADD"){
				var xdata = $('#form_data').serialize()+"&event_action=save_transaction&sample_key=test_sample";
				$.ajax({
					url: "ajax_crud.php",
					type: "POST",
					dataType: "json",
					data: xdata,
					success: function(xres){
						alertify.alert(xres["msg"], function(){
							location.reload();
						});
					}
				});
			}
			else if (xpar === "EDIT"){
				var xdata = $('#form_data').serialize()+"&event_action=edit_transaction";
				$.ajax({
					url: "ajax_crud.php",
					type: "POST",
					dataType: "json",
					data: xdata,
					success: function(xres){
						alertify.alert(xres["msg"], function(){
							location.reload();
						});
					}
				});
			}
			
		}

		function add_click() {
			var xdata = "event_action=get_addform";
			$.ajax({
				url: "ajax_crud.php",
				type: "POST",
				dataType: "json",
				data: xdata,
				success: function(xres){
					$('#div_transaction').empty('');
					$('#div_transaction').append(xres.form);
					$('#div_transaction').dialog('open');
					xpar = "ADD";
				}
			});
		}

		function view_click(xrecid){
			var xdata = "recid="+xrecid+"&event_action=get_viewform";
			$.ajax({
				url: "ajax_crud.php",
				type: "POST",
				dataType: "json",
				data: xdata,
				success: function(xres){
					$('#div_transaction').empty('');
					$('#div_transaction').append(xres.form);
					$('#div_transaction').dialog('open');
					xpar = "VIEW";

					console.log($("span.ui-button-text"));
				}
			});
		}

		function edit_click(xrecid){
			var xdata = "recid="+xrecid+"&event_action=get_editform";
			$.ajax({
				url: "ajax_crud.php",
				type: "POST",
				dataType: "json",
				data: xdata,
				success: function(xres){
					$('#div_transaction').empty('');
					$('#div_transaction').append(xres.form);
					$('#div_transaction').dialog('open');
					xpar = "EDIT";
				}
			});
		}

		function delete_click(xrecid){
			alertify.confirm("Delete employee ?", function(){
				var xdata = "recid="+xrecid+"&event_action=delete_emp";
				$.ajax({
				url: "ajax_crud.php",
				type: "POST",
				dataType: "json",
				data: xdata,
				success: function(xres){
					alertify.alert(xres["msg"], function(){
							location.reload();
					});
				}
			});
			});
		}

		function print_click(xtype){
			document.forms.myform.method = 'POST';
			document.forms.myform.target = '_blank';
			document.forms.myform.action = 'pdf_employeelist.php';
			document.forms.myform.txt_repoutput.value = xtype;
			document.forms.myform.submit();
		}
</script>
<?php
	require_once("./footer.php");
?>