<?php
ini_set('display_errors', false);
error_reporting(E_ALL);

require_once("./header.php");
require_once("include/lx.pdodb.php");
$xparams = array(); //parameter instantiation
$xfilter_pager_fields .= " WHERE true "; //default filter
if (isset($_POST['pager_search_input']) && $_POST['pager_search_input'] != '') {
    $xfilter_pager_fields .= " AND " . $_POST['pager_search'] . " LIKE ?";
    $xparams[count($xparams)] = "%" . $_POST['pager_search_input'] . "%";
}

if (isset($_POST['txt_pager_qsearch']) && trim($_POST['txt_pager_qsearch']) != "") {
    $xex_fields = explode(",", $_POST['pager_fields']);
    $xex_type = explode(",", $_POST['pager_type']);

    $xfilter_pager_fields .= " AND ";
    $xfilter_pager_fields .= "(";

    $xfilter_qsearch = "";

    for ($i = 0; $i < count($xex_fields); $i++) {
        $xvalue = $_POST['txt_pager_qsearch'];
        if (
            $xex_type[$i] == 'date'
            && (is_this_date('mm-dd-yyyy', $xvalue) || is_this_date('yyyy-mm-dd', $xvalue))
        ) {
            $xvalue = format_date($xvalue);
        } else {
            $xvalue = "%" . $xvalue . "%";
        }

        $xfilter_qsearch .= $xfilter_qsearch != "" ? " OR " : "";
        $xfilter_qsearch .= $xex_fields[$i] . " LIKE ?";
        $xparams[count($xparams)] = $xvalue;
    }

    $xfilter_pager_fields .= $xfilter_qsearch;
    $xfilter_pager_fields .= ")";

    // added fixed apostrophe after submit Rolly Cavan 08/17/2022
    $searchInput = $_POST['txt_pager_qsearch'];
}
// echo "<pre>";
// var_dump($_POST);
// var_dump($_POST['pager_event_action'],$_POST['txtfld']);
else if (isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'advance_search') {
    $xarr_advs = $_POST['txtfld']['advs'];
    $xex_fields = explode(",", $_POST['pager_fields']);
    $xex_type = explode(",", $_POST['pager_type']);
    // if ($xarr_advs["canceldoc"] == "N") {
    //     $xarr_advs["canceldoc"] = "0";
    // } else if ($xarr_advs["canceldoc"] == "Y") {
    //     $xarr_advs["canceldoc"] = "1";
    // }
    foreach ($xarr_advs as $key => $value) {
        if (count($value) > 1) {
            if ($value['from'] != "") {
                if (
                    $xex_type[array_search($key, $xex_fields)] == 'date'
                    && (is_this_date('mm-dd-yyyy', str_replace("/", "-", $value['from'])) || is_this_date('yyyy-mm-dd', str_replace("/", "-", $value['from'])))
                ) {
                    $value['from'] = format_date($value['from']);
                }

                $xfilter_pager_fields .= $xfilter_pager_fields != "" ? " AND " : "";
                $xfilter_pager_fields .= $key . " >= ?";
                $xparams[count($xparams)] = $value['from'];
            }

            if ($value['to'] != "") {
                if (
                    $xex_type[array_search($key, $xex_fields)] == 'date'
                    && (is_this_date('mm-dd-yyyy', str_replace("/", "-", $value['from'])) || is_this_date('yyyy-mm-dd', str_replace("/", "-", $value['from'])))
                ) {
                    $value['to'] = format_date($value['to']);
                }

                $xfilter_pager_fields .= $xfilter_pager_fields != "" ? " AND " : "";
                $xfilter_pager_fields .= $key . " <= ?";
                $xparams[count($xparams)] = $value['to'];
            }
        } else {
            if ($value == "") continue;

            if ($xex_type[array_search($key, $xex_fields)] == 'number') {
                $value =  LVALDou($value);
            }
            $xfilter_pager_fields .= $xfilter_pager_fields != "" ? " AND " : "";
            $xfilter_pager_fields .= $xex_type[array_search($key, $xex_fields)] == 'number' ? "round(" . $key . ",2)" . " LIKE ?" : $key . " LIKE ?";
            $xparams[count($xparams)] = "%" . $value . "%";
        }
    }
    // var_dump($xfilter_pager_fields);
    // die();
}

// echo "<pre>";
// var_dump($_POST);
$xsort_order = 'ORDER BY fullname';
if (isset($_POST['header_sort_order']) && $_POST['header_sort_order'] != '') {
    $xsort_order = "ORDER BY " . $_POST['header_sort_order'] . " " . $_POST['sort_order'];
}

// echo "<pre>";
// var_dump($_POST);
// var_dump($xfilter_pager_fields);
$xpage = isset($_POST['pager_page']) ? $_POST['pager_page'] : 1;
$xlimit = isset($_POST['pager_limit']) ? $_POST['pager_limit'] : 10;
$xarr_hdr = array();
$xarr_fld = array();
$xarr_wid = array();
// $xarr_hdr_advsrch = array();

#region fields
$field = new HtmlObject();
$field->link = $link_id;
$field->type = 'text';
$field->fieldname = 'fullname';
$field->format = '';
$xarr_fld[] = $field;
$xarr_hdr[] = "Fullname";
$xarr_wid[] = 18;

$field = new HtmlObject();
$field->link = $link_id;
$field->type = 'text';
$field->fieldname = 'address';
$field->format = '';
$xarr_fld[] = $field;
$xarr_hdr[] = "Address";
$xarr_wid[] = 16;

$field = new HtmlObject();
$field->link = $link_id;
$field->type = 'text';
$field->fieldname = 'gender';
$field->format = '';
$xarr_fld[] = $field;
$xarr_hdr[] = "Gender";
$xarr_wid[] = 12;

$field = new HtmlObject();
$field->link = $link_id;
$field->type = 'text';
$field->fieldname = 'contactnum';
$field->format = '';
$xarr_fld[] = $field;
$xarr_hdr[] = "Contact No.";
$xarr_wid[] = 16;

$field = new HtmlObject();
$field->link = $link_id;
$field->type = 'date';
$field->fieldname = 'birthdate';
$field->qs_fromto = true;
$field->format = '';
$xarr_fld[] = $field;
$xarr_hdr[] = "Birthdate";
$xarr_wid[] = 16;

#region header buttons
$add_btn = new Button();
$add_btn->id = 'add_btn';
$add_btn->name = 'add_btn';
$add_btn->class = 'save';
$add_btn->value = 'Add';
$add_btn->event = 'pager_add_click';
$add_btn->fields = array();

$print_btn = new Button();
$print_btn->id = 'print_btn';
$print_btn->name = 'print_btn';
$print_btn->class = 'print';
$print_btn->value = 'Print';
$print_btn->event = 'pager_print_click';
$print_btn->fields = array();

$export_btn = new Button();
$export_btn->id = 'export_btn';
$export_btn->name = 'export_btn';
$export_btn->class = 'print';
$export_btn->value = 'Export';
$export_btn->event = 'pager_export_click';
$export_btn->fields = array();
#end

#region side buttons
$view_btn = new Button();
$view_btn->id = 'view_btn';
$view_btn->name = 'view_btn';
$view_btn->class = 'print';
$view_btn->value = 'View';
$view_btn->event = 'pager_view_click';
$view_btn->fields = array('recid'); //pager always have recid or table_id

$edit_btn = new Button();
$edit_btn->id = 'edit_btn';
$edit_btn->name = 'edit_btn';
$edit_btn->class = 'save';
$edit_btn->value = 'Edit';
$edit_btn->event = 'pager_edit_click';
$edit_btn->fields = array('recid'); //pager always have recid or table_id

#region side buttons
$print_side_btn = new Button();
$print_side_btn->id = 'print_btn';
$print_side_btn->name = 'print_btn';
$print_side_btn->class = 'print';
$print_side_btn->value = 'Print';
$print_side_btn->event = 'print_dataitem';
$print_side_btn->fields = array('docnum', 'recid'); //pager always have recid or table_id


$delete_btn = new Button();
$delete_btn->id = 'delete_btn';
$delete_btn->name = 'delete_btn';
$delete_btn->class = 'exit';
$delete_btn->value = 'Delete';
$delete_btn->event = 'pager_delete_click';
$delete_btn->fields = array('recid');

$pager = new PagerAdvSearch($xpage, $xlimit, false);
$pager->title = "Employee";
$pager->table = 'employeefile';
$pager->table_id = 'recid';
$pager->debug = false;

/////////////////////////////////////////////////////////////////////////////////////
// use_mod - for joining tables
// sample:
// 
// $pager->use_mod = true;
// 
// $join_connector = $link_id->lstv_dbtype=="my" ? "":"dbo.";
// 
// $pager->mod_query = " {$link_id->lstv_dbname}.{$join_connector}table1 as t1
//                       INNER JOIN {$link_id->lstv_dbname}.{$join_connector}table2 as t2
//                       on t1.fieldname1 = t2.fieldname1
//                       INNER JOIN {$link_id->lstv_dbname}.{$join_connector}table3 as t3
//                       on t3.fieldname2 = fieldname2";
// 
// $pager->mod_fields = "t1.fieldname1 as name1,
//                       t2.fieldname2 as name2,
//                       t1.fieldname3 as name3,
//                       t1.fieldname4 as name4,
//                       t3.fieldname5 as name5";
// 
// 
// $pager->mod_table_id = "t1.recid";	
// 
// 
// other samples check:
//      pager_samples.txt
// 
//////////////////////////////////////////////////////////////////////////////////////

$pager->link_id = $link_id;
$pager->headers = $xarr_hdr;
$pager->header_width = $xarr_wid;
$pager->fields = $xarr_fld;
$pager->filter = $xfilter_pager_fields;
$pager->params = $xparams;
$pager->pager_width = '100%';
$pager->search = !true;
$pager->group = '';
$pager->order = $xsort_order;
$pager->order_by = $_POST['sort_order'];
$pager->no_header_buttons = false; // no header buttons if true
$pager->no_side_buttons = false; // no side buttons if true

#PAGER BUTTONS
$pager->header_buttons = array($add_btn, $print_btn, $export_btn); // if empty array automatically generate default header buttons (ADD BUTTON)
$pager->side_buttons = array($view_btn, $edit_btn, $delete_btn); // if empty array automatically generate default side buttons (EDIT AND DELETE)

$pager->header_font_size = '14px';
$pager->body_font_size = '12px';
$pager->hovering_type = 'hover'; //hover,click //for now hover
$pager->modal_height = '400';
$pager->modal_width = '650';
$pager->searchvalue = $_POST['pager_search_input'];
$pager->searchfield = $_POST['pager_search'];
$pager->show_print = false;
$pager->show_export = false;
$pager->action_width = "300";
$pager->generateResultSet();
$pager->freeze_action();
?>
<div style="width: 1240px !important; margin: 0 auto 50px; padding-bottom: 25px;">
    <center>
        <div id='mypager' style='width:100%;'>
            <?php
            $pager->render();
            ?>
        </div>
        <div id="div_info"></div>
    </center>
</div>
<form name="myform" id="myform" action="pdf_employeelist.php" method="post" target="_blank">
    <input type="hidden" name="txt_repoutput" id="txt_repoutput" value="">
</form>
<?php require_once("./modals.php"); ?>


<script>
    $(document).ready(function() {
        // Add New button click handler to open add modal
        $("#add_btn").click(function() {
            $('#addEmployeeModal').modal('show');
        });

        // Save button click handler in add modal
        $("#btn_save_modal").click(function() {
            var formData = {
                fullName: $("#txt_fullname").val(),
                address: $("#txt_address").val(),
                birthdate: $("#txt_birthdate").val(),
                age: $("#txt_age").val(),
                gender: $("input[name='txtfld[gender]']:checked").val(),
                civilStatus: $("#cbo_civilstat").val(),
                contactNumber: $("#txt_contactnum").val(),
                salary: $("#txt_salary").val(),
                isactive: $("#chk_inactive").prop("checked") ? 1 : 0
            };

            $.ajax({
                url: "add_employee.php",
                type: "POST",
                dataType: "json",
                data: formData,
                success: function(response) {
                    if (response.status == 'success') {
                        alertify.alert(response.message, function() {
                            location.reload();
                        });
                    } else {
                        alertify.alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alertify.alert("An error occurred while processing your request.");
                }
            });

            $('#addEmployeeModal').modal('hide');
        });

        // Edit button click handler
        window.pager_edit_click = function(recid) {
            $.ajax({
                url: "get_employee.php",
                type: "POST",
                dataType: "json",
                data: { recid: recid },
                success: function(response) {
                    $("#edit_recid").val(response.recid);
                    $("#edit_txt_fullname").val(response.fullname);
                    $("#edit_txt_address").val(response.address);
                    $("#edit_txt_birthdate").val(response.birthdate);
                    $("#edit_txt_age").val(response.age);
                    $("input[name='edit_txtfld[gender]'][value='" + response.gender + "']").prop("checked", true);
                    $("#edit_cbo_civilstat").val(response.civilstat);
                    $("#edit_txt_contactnum").val(response.contactnum);
                    $("#edit_txt_salary").val(response.salary);
                    $("#edit_chk_inactive").prop("checked", response.isactive == 1);

                    $('#editEmployeeModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alertify.alert("An error occurred while processing your request.");
                }
            });
        };

        // View button click handler
        window.pager_view_click = function(recid) {
            $.ajax({
                url: "get_employee.php",
                type: "POST",
                dataType: "json",
                data: { recid: recid },
                success: function(response) {
                    $("#view_recid").val(response.recid);
                    $("#view_txt_fullname").val(response.fullname).prop("readonly", true);
                    $("#view_txt_address").val(response.address).prop("readonly", true);
                    $("#view_txt_birthdate").val(response.birthdate).prop("readonly", true);
                    $("#view_txt_age").val(response.age).prop("readonly", true);
                    $("input[name='view_txtfld[gender]'][value='" + response.gender + "']").prop("checked", true).prop("disabled", true);
                    $("#view_txt_civilstat").val(response.civilstat).prop("readonly", true);
                    $("#view_txt_contactnum").val(response.contactnum).prop("readonly", true);
                    $("#view_txt_salary").val(response.salary).prop("readonly", true);
                    $("#view_chk_inactive").prop("checked", response.isactive == 1).prop("disabled", true);

                    $('#viewEmployeeModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alertify.alert("An error occurred while processing your request.");
                }
            });
        };

        // Update button click handler in edit modal
        $("#btn_update_modal").click(function() {
            var formData = {
                recid: $("#edit_recid").val(),
                fullName: $("#edit_txt_fullname").val(),
                address: $("#edit_txt_address").val(),
                birthdate: $("#edit_txt_birthdate").val(),
                age: $("#edit_txt_age").val(),
                gender: $("input[name='edit_txtfld[gender]']:checked").val(),
                civilStatus: $("#edit_cbo_civilstat").val(),
                contactNumber: $("#edit_txt_contactnum").val(),
                salary: $("#edit_txt_salary").val(),
                isactive: $("#edit_chk_inactive").prop("checked") ? 1 : 0
            };

            $.ajax({
                url: "edit_employee.php",
                type: "POST",
                dataType: "json",
                data: formData,
                success: function(response) {
                    if (response.status == 'success') {
                        alertify.alert(response.message, function() {
                            location.reload();
                        });
                    } else {
                        alertify.alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alertify.alert("An error occurred while processing your request.");
                }
            });

            $('#editEmployeeModal').modal('hide');
        });

        // Delete button click handler
        window.pager_delete_click = function(recid) {
            alertify.confirm("Delete employee?", function() {
                $.ajax({
                    url: "delete_employee.php",
                    type: "POST",
                    dataType: "json",
                    data: { recid: recid, event_action: 'delete_emp' },
                    success: function(response) {
                        alertify.alert(response.msg, function() {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alertify.alert("An error occurred while processing your request.");
                    }
                });
            });
        };

        // Print button click handler
        function pager_print_click() {
            document.forms.myform.method = 'POST';
            document.forms.myform.target = '_blank';
            document.forms.myform.action = 'pdf_employeelist.php';
            document.forms.myform.txt_repoutput.value = "pdf";
            document.forms.myform.submit();
        }

    });
</script>
