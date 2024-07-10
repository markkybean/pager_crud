<?php
	require('appconfig.php');
// 	    ini_set('display_errors',true);
// error_reporting(E_ALL);
	require('../../include/JSON.php');
	require("../lx.pdodb.php");
	require_once ("../stdfunc01.php");
	require_once ("../../include/stdfunc04.php");

	$xlink_id= $_POST['pager_xlink'];

	// for($i=0;$i<count($conn_id);$i++)
	// {
	// 	if($xlink_id == $conn_flag[$i])
	// 	{
	// 		$link_id = $conn_id[$i];
	// 	}
	// }

	$xusrcde1 = ($_SESSION[$xg_appkey]['usrcde']);
	$xfullname1 = ($_SESSION[$xg_appkey]['fullname']);
	// $xactivity1 = "Request Leave";
	$xtablename1 = $_POST['table'];
	$xprogmodule1 = $_POST['title'];
	$xsuccess1 = true;
	$xwebpage1 = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$xexplode=explode(" ",$xprogmodule1);
	$xmainfield=$xexplode[0];
	
	if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'add')
	{
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField'];
		$fields = explode(",",$_POST['fields']);
		$filter_fields = explode(",",$_POST['filterflds']);
		$xx = $_POST['validate'];
		$xfilter = '';
		$xpar = array();
		$xr = 0;

		$xlog = "";
		$xcontinue = true;

		// var_dump($xx,$filter_fields);
		// die();
		foreach ($fields as $key => $value) {
			if($modalField[$value] == "")
			{
				$xlog = "Fill up all fields.";
				$xcontinue = false;
				goto last;
			}
		}

		$xfd = count($filter_fields) > 1 ? $filter_fields : ($filter_fields[0]==''? $fields : $filter_fields);
		for($i=0;$i<count($xfd);$i++) {
			if($xfilter != '') {
					$xfilter .= ' AND ';
				}
			$xfilter .= $xfd[$i]." = ?";
			$xpar[count($xpar)] = htmlentities($modalField[$xfd[$i]]);
		} 
		if($xfilter != '') {
			$xfilter .= " AND $table_id != ?";
			$xpar[count($xpar)] = $modalField[$table_id];
		}
		
		$sql = "SELECT count(*) as b FROM $table WHERE $xfilter";
		$stmt_cnt = $link_id->prepare($sql);
		$stmt_cnt->execute($xpar);
		$val = $stmt_cnt -> fetch();
		
		if($xx=='validate')
		{
			$xr = intval($val['b']);
		}

		if($xr > 0)
		{
			$xlog .= "Record already exist";
			$xcontinue = false;
		}
		else
		{
			for($i=0;$i<count($fields);$i++){
				
				$xparams[$fields[$i]] = htmlentities($modalField[$fields[$i]]);
			}

			PDO_InsertRecord($link_id,$table,$xparams,false);

        	$logarr['module'] = $_POST['modname'];
			$logarr['remarks']="Added ".$_POST['modname'].": ".$xparams[$fields[0]];
            $logarr['activity'] = "Add";
	        $logarr['webpage'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	        PDO_UserActivityLog($link_id,$table,$logarr['usrname'],$logarr['usrcde'],$logarr['activity'],$logarr['remarks'],$logarr['webpage'],"",$logarr['module'],true);

	        $xlog .= "Record added.";
			$xcontinue = true;
		}

		last:
				
		$xret = array();
		$xret['log'] = $xlog;
		$xret['msg'] = $xcontinue ? "success" : "failed";

		$json = new Services_JSON();
		echo $json->encode($xret);
		
		
	}
	else if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'update')
	{
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField']; //value
		$fields = explode(",",$_POST['fields']);
		$filter_fields = explode(",",$_POST['filterflds']);
		$fldname = $_POST['fieldname'];
		$xx = $_POST['validate'];
		$xr = 0;

		$xlog = "";
		$xcontinue = true;

		foreach ($fields as $key => $value) {
			if($modalField[$value] == "")
			{
				$xlog = "Fill up all fields.";
				$xcontinue = false;
				goto last1;
			}
		}

		$xfilter = '';
		$xpar = array();
		$xfd = count($filter_fields) > 1 ? $filter_fields : ($filter_fields[0]==''? $fields : $filter_fields);
		for($i=0;$i<count($xfd);$i++) {
			if($xfilter != '') {
					$xfilter .= ' AND ';
				}
			$xfilter .= $xfd[$i]." = ?";
			$xpar[count($xpar)] = htmlentities($modalField[$xfd[$i]]);
		} 
		if($xfilter != '') {
			$xfilter .= " AND $table_id != ?";
			$xpar[count($xpar)] = $modalField[$table_id];
		}

		$sql = "SELECT count(*) as b FROM $table WHERE $xfilter";
		$stmt_cnt = $link_id->prepare($sql);
		$stmt_cnt->execute($xpar);
		$val = $stmt_cnt -> fetch();

		if($xx=='validate')
		{
			$xr = intval($val['b']);
		}

		if($xr > 0) 
		{
			$xlog .= "Item already exist";
			$xcontinue = false;
		}
		else 
		{

			for($i=0;$i<count($fields);$i++)
			{
				$xparams[$fields[$i]] = htmlentities($modalField[$fields[$i]]);
			}
			$xfromto = check_newvalue($table,$modalField[$table_id],$xparams,'recid = ?',$_POST['fields']);
			if($fldname!='empcode')
			{
			    $xqry = "SELECT table_name, column_name FROM information_schema.columns WHERE column_name = ? AND table_name != ?";
			    $xstmt_field = $link_id->prepare($xqry);
			    $xstmt_field->execute(array($fldname,$table));
			   	
			    while ($xrs_fieldname = $xstmt_field->fetch()) 
			    {
			    	$xqry_get="SELECT $fldname from $table where recid=?";
			    	$xstmt_get=$link_id->prepare($xqry_get);
			    	$xstmt_get->execute(array($modalField[$table_id]));
			    	$xrs_get=$xstmt_get->fetch();

					$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_fieldname['TABLE_NAME']." WHERE ".$xrs_fieldname['column_name']."=?";   
					$xstmt_rec = $link_id->prepare($xqry_rec);
					$xstmt_rec->execute(array($xrs_get[$fldname]));
					
					if ($xrs_fieldvalue=$xstmt_rec->fetch(PDO::FETCH_ASSOC))
					{
					   $xupdate="UPDATE ".$xrs_fieldname['TABLE_NAME']." set ".$xrs_fieldname['column_name']." = ? where ".$xrs_fieldname['column_name']." =?";
					   $xstmt_up=$link_id->prepare($xupdate);
					   $xstmt_up->execute(array($modalField[$fields[0]],$xrs_get[$fldname]));
					   
					}
					
			    }
				PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]));
			}
			else
			{
				
				PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]));
			}
			
        	$logarr['module'] = $_POST['modname'];
            $logarr['activity'] = "Edit";
        	for($x=0;$x<count($xfromto);$x++)
			{
				$xnewval=$xfromto[$x][1];
				$xoldval=$xfromto[$x][2];
				$xhead = $fields[$xfromto[$x][3]];
				$logarr['remarks']="EDIT $xhead FROM : $xoldval TO : $xnewval ";
		        $logarr['webpage'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		        PDO_UserActivityLog($link_id,$table,$logarr['usrname'],$logarr['usrcde'],$logarr['activity'],$logarr['remarks'],$logarr['webpage'],"",$logarr['module'],true);
			}
			
			$xlog .= "Record updated.";
			$xcontinue = true;
		}


		last1:
				
		$xret = array();
		$xret['log'] = $xlog;
		$xret['msg'] = $xcontinue ? "success" : "failed";

		$json = new Services_JSON();
		echo $json->encode($xret);

	}
	else if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'delete')
	{
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField'];
		$fldname = $_POST['fieldname'];
		$fields = explode(",",$_POST['fields']);
		$xbool=true;


		$xfilter = " WHERE ".$table_id."=?";
		
		if($fldname!='emp')
		{
				  // $xtable_qry = "SHOW TABLES FROM lst_traccxaviernuvali";
				  $xtable_qry="SELECT TABLE_NAME FROM ".$_SESSION[$xg_appkey]['dbname'];
				  $xstmt = $link_id->prepare($xtable_qry);
				  $xstmt->execute();

				  while($xrs_table = $xstmt->fetch())
				  {

					    $xqry = "SELECT table_name, column_name FROM ".$_SESSION[$xg_appkey]['dbname'].".columns WHERE table_name= ? AND column_name = ? AND table_name != ?";
					    $xstmt_field = $link_id->prepare($xqry);
					    $xstmt_field->execute(array($xrs_table['TABLE_NAME'],$fldname,$table));

					    if ($xrs_fieldname = $xstmt_field->fetch()) 
					    {	

					    	$xqry_get="SELECT $fldname from $table where recid=?";
					    	$xstmt_get=$link_id->prepare($xqry_get);
					    	$xstmt_get->execute(array($modalField[$table_id]));
					    	$xrs_get=$xstmt_get->fetch();
					    	// $xget = get_params($table,$fldname,$modalField[$table_id],'recid');
					    	// var_dump($table,$fldname,$modalField[$table_id]);
							$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_table['TABLE_NAME']." WHERE ".$xrs_fieldname['column_name']."=?";   
							$xstmt_rec = $link_id->prepare($xqry_rec);
							$xstmt_rec->execute(array($xrs_get[$fldname]));
							
							if ($xrs_fieldvalue=$xstmt_rec->fetch())
							{
							    $xret='Code already used by other modules!'; //&z
							    $xbool=false;
							    break;
							}
					    }
				  }

				if($xbool)
				{
					$sql = "SELECT * FROM ".$table.$xfilter;
					$stmt=$link_id->prepare($sql);		
					$stmt->execute(array($modalField[$table_id]));
					$xrs=$stmt->fetch();
					$xdata = $xrs[$fields[0]];
					// var_dump($table,$xfilter,$modalField[$table_id]);
					$sql = "DELETE FROM ".$table.$xfilter;
					$stmt=$link_id->prepare($sql);		
					$stmt->execute(array($modalField[$table_id]));
					$xret="Successfully deleted item";
				}
		}
		else
		{

			$sql = "SELECT * FROM ".$table.$xfilter;
			$stmt=$link_id->prepare($sql);		
			$stmt->execute(array($modalField[$table_id]));
			$xrs=$stmt->fetch();
			$xdata = $xrs[$fields[0]];
			
			// var_dump("pass");
			$sql = "DELETE FROM ".$table.$xfilter;
			$stmt=$link_id->prepare($sql);		
			$stmt->execute(array($modalField[$table_id]));
			$xret="Successfully deleted item";
		}
		
		if($xret=="Successfully deleted item")
		{
			
        	$logarr['module'] = $_POST['modname'];
			$logarr['remarks']="Deleted ".$_POST['modname'].": ".$xdata;
            $logarr['activity'] = "Delete";
	        $logarr['webpage'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	        PDO_UserActivityLog($link_id,$table,$logarr['usrname'],$logarr['usrcde'],$logarr['activity'],$logarr['remarks'],$logarr['webpage'],"",$logarr['module'],true);
	    }
	 
		$json = new Services_JSON();
		echo $json->encode($xret);
		//~ PDO_UserActivityLog($link_id, $table , $_SESSION[$xg_appkey]['usrcde'] , $_POST['title'] , "Deleted data-".$_POST['title']." code: ".$rs_sel['repcode'] , "view_employee_report.php" , "Delete" , "HRIS");
	}
	// echo json_encode($xres);
?>
