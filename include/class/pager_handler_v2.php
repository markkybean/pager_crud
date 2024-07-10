<?php
	require('appconfig.php');
// 	    ini_set('display_errors',true);
// error_reporting(E_ALL);
	require('../../include/JSON.php');
	require("../lx.pdodb.php");
	require_once ("../stdfunc01.php");
	require_once ("../../include/stdfunc04.php");

	$xlink_id= $_POST['pager_xlink'];

	for($i=0;$i<count($conn_id);$i++)
	{
		if($xlink_id == $conn_flag[$i])
		{
			$link_id = $conn_id[$i];
		}
	}

	$xusrcde1 = ($_SESSION[$xg_appkey]['usrcde']);
	$xfullname1 = ($_SESSION[$xg_appkey]['fullname']);
	// $xactivity1 = "Request Leave";
	$xtablename1 = $_POST['table'];
	$xprogmodule1 = $_POST['title'];
	$xsuccess1 = true;
	$xwebpage1 = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$xexplode=explode(" ",$xprogmodule1);
	$xmainfield=$xexplode[0];

	if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'add'){
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField'];
		$fields = explode(",",$_POST['fields']);
		// var_dump($fields);
		for($i=0;$i<count($fields);$i++)
		{
			$xparams[$fields[$i]] = htmlentities($modalField[$fields[$i]]);
		}
		$xaction1 = "Add";
		$xactivity1 = "Add ".$xmainfield;
		$xremarks1 = "Add ".$xmainfield." : ".$xparams[$fields[0]];
		PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1, $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1,$xnewval,$xoldval);			
		// var_dump(count($xparams));
		// die();
		if(count($xparams)=='1')
		{
			if(trim($xparams[$fields[0]])=='')
			{
				// echo json_encode("Code should not be blank");
				$xtmp = 'code';
			}
			else
			{
				$xqry_validate="SELECT * FROM $table WHERE $fields[0]=?";
	            $xstmt_validate=$link_id->prepare($xqry_validate);
	            $xstmt_validate->execute(array($modalField[$fields[0]]));
	            $xrs_validate = $xstmt_validate->fetch();
	            // var_dump($xqry_validate,$modalField[$fields[0]]);
	            if($xrs_validate)
	            {
					// echo json_encode("Code is already exists!");
					$xtmp = 'exists';
	            }
	            else
	            {
	            	PDO_InsertRecord($link_id,$table,$xparams,false);
					// echo json_encode("Successfully Added item");
					$xtmp = 'success';
	            }
			}
		}
		else
		{
			if(trim($xparams[$fields[0]])=='')
			{
				// echo json_encode("Code should not be blank");
				$xtmp = 'code';

			}
			else if (trim($xparams[$fields[1]])=='') {
				// echo json_encode("Description should not be blank");
				$xtmp = 'desc';
			}
			else
			{
				$xqry_validate="SELECT * FROM $table WHERE $fields[0]=?";
	            $xstmt_validate=$link_id->prepare($xqry_validate);
	            $xstmt_validate->execute(array($modalField[$fields[0]]));
	            $xrs_validate = $xstmt_validate->fetch();
	            // var_dump($xqry_validate,$modalField[$fields[0]]);
	            if($xrs_validate)
	            {
					// echo json_encode("Code is already exists!");
					$xtmp = 'exists';
	            }
	            else
	            {
	            	PDO_InsertRecord($link_id,$table,$xparams,false);
					// echo json_encode("Successfully Added item");
					$xtmp = 'success';

	            }
				
			}
		}
		$xres = $xtmp;
		$json = new Services_JSON();
		echo $json->encode($xres);

		// die();
		// echo json_encode($xres);

	}
	else if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'update'){
		//~ var_dump($_POST['fields']);
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField']; //value
		$fields = explode(",",$_POST['fields']);
		$head = explode(",",$_POST['head']);
		$fldname = $_POST['fieldname'];
		// var_dump($modalField[$table_id],$fldname);
		for($i=0;$i<count($fields);$i++){
			
			$xparams[$fields[$i]] = htmlentities($modalField[$fields[$i]]);
		}
		$xfromto = check_newvalue($table,$modalField[$table_id],$xparams,'recid = ?',$_POST['fields']);
		if(count($xparams)=='1')
		{
			if(trim($xparams[$fields[0]])=='')
			{
				// echo json_encode("Code should not be blank");
				$xtmp = 'desc';
			}
			else
			{
				$xqry_validate="SELECT * FROM $table WHERE $fields[0]=? and $table_id!=?";
	            $xstmt_validate=$link_id->prepare($xqry_validate);
	            $xstmt_validate->execute(array($modalField[$fields[0]],$modalField[$table_id]));
	            $xrs_validate = $xstmt_validate->fetch();
	            // var_dump($xqry_validate,$modalField[$fields[0]]);
	            if($xrs_validate)
	            {
					// echo json_encode("Code is already exists!");
					$xtmp = 'exists';
	            }
	            else
	            {
					if($fldname!='empcode')
					{
					#region-added
								  // $xtable_qry = "SHOW TABLES FROM lst_webinventory";
								  $xtable_qry = "SHOW TABLES FROM".$_SESSION[$xg_appkey]['dbname'];
								  $xstmt = $link_id->prepare($xtable_qry);
								  $xstmt->execute();
								  while($xrs_table = $xstmt->fetch())
								  {
									    $xqry = "SELECT table_name, column_name FROM information_schema.columns WHERE table_name= ? AND column_name = ? AND table_name != ?";
									    $xstmt_field = $link_id->prepare($xqry);
									    $xstmt_field->execute(array($xrs_table[0],$fldname,$table));

									    // var_dump($xqry,$xrs_table[0],$xfield,$xtable);
									    if ($xrs_fieldname = $xstmt_field->fetch()) 
									    {
									    	$xget = get_params($table,$fields[0],$modalField[$table_id],'recid');
											$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_table[0]." WHERE ".$xrs_fieldname['column_name']."=?";   
											$xstmt_rec = $link_id->prepare($xqry_rec);
											$xstmt_rec->execute(array($xget));
											
											if ($xrs_fieldvalue=$xstmt_rec->fetch())
											{
											   $xupdate="UPDATE $xrs_table[0] set ".$xrs_fieldname['column_name']." = ? where ".$xrs_fieldname['column_name']." =?";
											   $xstmt_up=$link_id->prepare($xupdate);
											   $xstmt_up->execute(array($modalField[$fields[0]],$xget));
											    // var_dump($xupdate,$xdcode,$xval);
											}
									    }
								  }
						#end-region
						PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]),false);
					}
					else
					{
						PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]),false);
					}
					// echo json_encode("Successfully updated item");
					$xtmp = 'success';
				}
			}
		}
		else
		{
			if(trim($xparams[$fields[0]])=='')
			{
				// echo json_encode("Code should not be blank");
				$xtmp = 'code';

			}
			else if (trim($xparams[$fields[1]])=='') {
				// echo json_encode("Description should not be blank");
				$xtmp = 'desc';
			}
			else
			{
				$xqry_validate="SELECT * FROM $table WHERE $fields[0]=? and $table_id!=?";
	            $xstmt_validate=$link_id->prepare($xqry_validate);
	            $xstmt_validate->execute(array($modalField[$fields[0]],$modalField[$table_id]));
	            $xrs_validate = $xstmt_validate->fetch();
	            // var_dump($xqry_validate,$modalField[$fields[0]]);
	            if($xrs_validate)
	            {
					// echo json_encode("Code is already exists!");
					$xtmp = 'exists';
	            }
	            else
	            {
					if($fldname!='empcode')
					{
					#region-added
								  // $xtable_qry = "SHOW TABLES FROM lst_webinventory";
								  $xtable_qry = "SHOW TABLES FROM".$_SESSION[$xg_appkey]['dbname'];
								  $xstmt = $link_id->prepare($xtable_qry);
								  $xstmt->execute();
								  while($xrs_table = $xstmt->fetch())
								  {
									    $xqry = "SELECT table_name, column_name FROM information_schema.columns WHERE table_name= ? AND column_name = ? AND table_name != ?";
									    $xstmt_field = $link_id->prepare($xqry);
									    $xstmt_field->execute(array($xrs_table[0],$fldname,$table));

									    // var_dump($xqry,$xrs_table[0],$xfield,$xtable);
									    if ($xrs_fieldname = $xstmt_field->fetch()) 
									    {
									    	$xget = get_params($table,$fields[0],$modalField[$table_id],'recid');
											$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_table[0]." WHERE ".$xrs_fieldname['column_name']."=?";   
											$xstmt_rec = $link_id->prepare($xqry_rec);
											$xstmt_rec->execute(array($xget));
											
											if ($xrs_fieldvalue=$xstmt_rec->fetch())
											{
											   $xupdate="UPDATE $xrs_table[0] set ".$xrs_fieldname['column_name']." = ? where ".$xrs_fieldname['column_name']." =?";
											   $xstmt_up=$link_id->prepare($xupdate);
											   $xstmt_up->execute(array($modalField[$fields[0]],$xget));
											    // var_dump($xupdate,$xdcode,$xval);
											}
									    }
								  }
						#end-region
						PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]),false);
					}
					else
					{
						PDO_UpdateRecord($link_id,$table,$xparams,$table_id."= ?",array($modalField[$table_id]),false);
					}
					// echo json_encode("Successfully updated item");
					$xtmp = 'success';
				}
			}
		}

		// for($x=0;$x<count($xfromto);$x++)
		// {
		// 	$xnewval=$xfromto[$x][1];
		// 	$xoldval=$xfromto[$x][2];
		// 	$xhead = $head[$xfromto[$x][3]];
		// 	PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1.' To '.$xnewval , $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1,$xnewval,$xoldval,$xhead);
		// }

		$xaction1 = "Update";
		$xactivity1 = "Update ".$xmainfield;
		$xremarks1 = "Update ".$xmainfield." : ".$xparams[$fields[0]];
		PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1, $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1,$xnewval,$xoldval);
		

		$xres = $xtmp;
		$json = new Services_JSON();
		echo $json->encode($xres);
		// echo json_encode($xres);
	}
	else if(isset($_POST['pager_event_action']) && trim($_POST['pager_event_action']) == 'delete'){
     	
		$xparams = array();
		$table = $_POST['table'];
		$table_id = $_POST['table_id'];
		$modalField = $_POST['modalField'];
		$fldname = $_POST['fieldname'];
		$fields = explode(",",$_POST['fields']);
		$table_exemption = $_POST['table_exemption'];
		$xbool=true;
		$xfilter_tblexmp='';
		$xdbtype='my';
		if($table_exemption!='')
		{
			for($i=0;$i<count($table_exemption);$i++)
			{
				// $xfilter_tblexmp.=" AND table_name != '".$table_exemption[$i]."'";  // original
				//added ferlyn 2015-10-29
				$xfilter_tblexmp.=" AND table_name != '".$table_exemption."'";
				//end
			}
		}
		
		// var_dump($xdbtype,$xfilter_tblexmp);die();
		for($i=0;$i<count($fields);$i++){
			
			$xparams[$fields[$i]] = htmlentities($modalField[$fields[$i]]);
		}
		// $fields = explode(",",$_POST['fields']);
		
		// for($i=0;$i<count($fields);$i++){
			
		// 	$xparams[$fields[$i]] = $modalField[$fields[$i]];
		// }
		//~ $xqry="select * from $table where recid=?";
		//~ $xstmt=$link_id->prepare($xqry);
		//~ $xstmt->execute(array($modalField['recid']));
		//~ $xrs=$xstmt->fetch();
	   //~ var_dump($modalField);
      //~ die();
		$xfilter = " WHERE ".$table_id."=?";
		
		if($fldname!='emp')
		{
			
			// #region-added
			// 	$xqry1="SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE column_name = ? AND TABLE_NAME != ?";
			// 	$xstmt=$link_id->prepare($xqry1);
			// 	$xstmt->execute(array($fldname,$table));
			// 	$xbool=false;
				
			// 	while($xrs=$xstmt->fetch())
			// 	{
			// 		$get_tbl=$xrs['TABLE_NAME'];
			// 		$get_col=$xrs['COLUMN_NAME'];
					
			// 		$xget = get_params($table,$fldname,$modalField[$table_id],'recid');
			// 		// $xget= get_params2($link_id,$table,array($fldname),"recid = ?",array($modalField[$table_id]));
						
			// 			$xqry_get='SELECT count(*) as xcnt from '.$get_tbl.' where '.$get_col.'= ?';
			// 			$xstmt_get=$link_id->prepare($xqry_get);
			// 			$xstmt_get->execute(array($xget));
						
			// 			$x=$xstmt_get->fetch();
			// 			// var_dump("pass1",$xqry_get,$xget,$x['xcnt']);
			// 			if($x['xcnt'] >=1 )
			// 			{
							
			// 				if($x)
			// 				{	
								
			// 					// var_dump("not deleted");
			// 					$xret="Deletion unsuccessful!";
			// 					$xbool=false;
			// 					break;
							
			// 				}
			// 				else
			// 				{
			// 					// var_dump("deleted1");
								
			// 					$sql = "DELETE FROM ".$table.$xfilter;
			// 					$stmt=$link_id->prepare($sql);		
			// 					$stmt->execute(array($modalField[$table_id]));
			// 					$xret="Successfully deleted item";
			// 					break;
			// 				}
			// 			}
			// 			else
			// 			{
			// 				$xbool=true;
			// 				// // $sql = "DELETE FROM ".$table.$xfilter;
			// 				// // $stmt=$link_id->prepare($sql);		
			// 				// // $stmt->execute(array($modalField[$table_id]));
			// 				// $xret="Successfully deleted item";
			// 				// break;
			// 			}
			// 	}

			// 	if($xbool)
			// 	{
					
			// 		// var_dump("deleted2");
			// 		$sql = "DELETE FROM ".$table.$xfilter;
			// 		$stmt=$link_id->prepare($sql);		
			// 		$stmt->execute(array($modalField[$table_id]));
			// 		$xret="Successfully deleted item";
			// 		// break;
			// 	}
			// #end-region

				  if($xdbtype=='my')
				  {
				  	$xtable_qry = "SHOW TABLES FROM ".$_SESSION[$xg_appkey]["dbname"];
				  }
				  else
				  {
				  	$xtable_qry = "SELECT TABLE_NAME FROM information_schema.tables";
				  }
				  // var_dump($xtable_qry);
				  $xstmt = $link_id->prepare($xtable_qry);
				  $xstmt->execute();
				  while($xrs_table = $xstmt->fetch())
				  {
				  		if($xdbtype=='my')
				 		{
						    $xqry = "SELECT table_name, column_name FROM information_schema.columns WHERE table_name= ? AND column_name = ? AND table_name != ?".$xfilter_tblexmp;
						    $xstmt_field = $link_id->prepare($xqry);
						    $xstmt_field->execute(array($xrs_table[0],$fldname,$table));
					    }
					    else
					    {
					    	$xqry = "SELECT table_name, column_name FROM information_schema.columns WHERE table_name= ? AND column_name = ? AND table_name != ?".$xfilter_tblexmp;
						    $xstmt_field = $link_id->prepare($xqry);
						    $xstmt_field->execute(array($xrs_table['TABLE_NAME'],$fldname,$table));
					    }
					    // var_dump($xqry);
					    if ($xrs_fieldname = $xstmt_field->fetch()) 
					    {	
					    	// var_dump($xrs_fieldname);
					    	$xget = get_params($table,$fldname,$modalField[$table_id],'recid');
					    	
					    	 // var_dump($xstmt_rec->errorinfo());die();
					    	if($xdbtype=='my')
				 			{
						    	
								$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_table[0]." WHERE ".$xrs_fieldname['column_name']."=?";   
								$xstmt_rec = $link_id->prepare($xqry_rec);
								$xstmt_rec->execute(array($xget));
							}
							else
							{
								$xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_table['TABLE_NAME']." WHERE ".$xrs_fieldname['column_name']."=?";   
								$xstmt_rec = $link_id->prepare($xqry_rec);
								$xstmt_rec->execute(array($xget));
							}
							// var_dump($xqry_rec,$xget);
							// var_dump($xstmt_rec->errorinfo());
							if ($xrs_fieldvalue=$xstmt_rec->fetch())
							{
							    $xret='Code already used!';
							    $xbool=false;
							    break;
							}
					    }
				  }
				  
				if($xbool)
				{
					if($table_exemption=='itemunitfile')
					{
						$sql1 = "Select * from ".$table.$xfilter;
						$stmt1=$link_id->prepare($sql1);		
						$stmt1->execute(array($modalField[$table_id]));
						$rs1 = $stmt1->fetch();

						$sql2 = "DELETE FROM ".$table_exemption." where itmcde=?";
						$stmt2=$link_id->prepare($sql2);		
						$stmt2->execute(array($rs1['itmcde']));
					}

					$sql = "DELETE FROM ".$table.$xfilter;
					$stmt=$link_id->prepare($sql);		
					$stmt->execute(array($modalField[$table_id]));
					
					$xret="Successfully deleted item";

					$xfromto = check_newvalue($table,$modalField[$table_id],$xparams,'recid = ?',$_POST['fields']);
					$xnewval = $xfromto;
					$xaction1 = "Delete";
					$xactivity1 = "Delete ".$xmainfield;
					$xremarks1 = "Delete ".$xmainfield." : ".$xparams[$fields[0]];
					PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1, $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1,$xnewval,$xoldval);	
				}
		}
		else
		{
			// die();
			$sql = "DELETE FROM ".$table.$xfilter;
			$stmt=$link_id->prepare($sql);		
			$stmt->execute(array($modalField[$table_id]));
			$xret="Successfully deleted item";

			$xfromto = check_newvalue($table,$modalField[$table_id],$xparams,'recid = ?',$_POST['fields']);
			$xnewval = $xfromto;
			$xaction1 = "Delete";
			$xactivity1 = "Delete ".$xmainfield;
			$xremarks1 = "Delete ".$xmainfield." : ".$xparams[$fields[0]];
			PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1, $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1,$xnewval,$xoldval);	
		}
		

		$json = new Services_JSON();
		echo $json->encode($xret);
		//~ PDO_UserActivityLog($link_id, $table , $_SESSION[$xg_appkey]['usrcde'] , $_POST['title'] , "Deleted data-".$_POST['title']." code: ".$rs_sel['repcode'] , "view_employee_report.php" , "Delete" , "HRIS");
	}
	// echo json_encode($xres);
?>
