<?php 
ini_set('display_errors',true);
error_reporting(0);
ini_set('default_charset', 'utf-8');
header('Content-Type: text/html; charset=UTF-8');
if(file_exists('../../db_config.php'))
{
	require('../../db_config.php');
	// require 'file';
	// var_dump(file_exists('../../payroll/appconfig.php'));
	// var_dump("test");

	// require_once('../../payroll/appconfig.php');	
}
$auth_dbhost = $acc_host;
$auth_dbusername = $acc_uname;
$auth_dbuserpassword = $acc_pw;
$auth_cnstr = $acc_cnstr;
$dboptions = $acc_opt;


// var_dump($auth_cnstr, $auth_dbusername, $auth_dbuserpassword, $dboptions);
// $link_id = new PDO($pay_cnstr, $pay_uname, $pay_pw,$pay_opt);
// $link_id = new PDO($auth_cnstr, $auth_dbusername, $auth_dbuserpassword, $dboptions);
global $link_id;

// $apsystem_host = '8.8.8.146';
// $apsystem_uname = 'root';
// $apsystem_pw = 'lstsql';
// $apsystem_dbname = 'appsystem_lstv_my';
// $apsystem_cnstr = "mysql:host=$apsystem_host; dbname=$apsystem_dbname";
// $apsystem_opt = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true);

// $hr_host = '8.8.8.146';
// $hr_uname = 'root';
// $hr_pw = 'lstsql';
// $hr_dbname = 'lst_hris_lstv_my';
// $hr_cnstr = "mysql:host=$hr_host; dbname=$hr_dbname";
// $hr_opt = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true);

// $ess_host = '8.8.8.146';
// $ess_uname = 'root';
// $ess_pw = 'lstsql';
// $ess_dbname = 'lst_ess_lstv_my';
// $ess_cnstr = "mysql:host=$ess_host; dbname=$ess_dbname";
// $ess_opt = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true);


// $link_id_main = new PDO($apsystem_cnstr, $apsystem_uname, $apsystem_pw,$apsystem_opt);
// $link_id = new PDO($hr_cnstr, $hr_uname, $hr_pw,$hr_opt);
// $link_id_ess = new PDO($ess_cnstr, $ess_uname, $ess_pw, $ess_opt);

$link=$_POST['link_id'];
$table=$_POST['table'];
$searchCol=$_POST['searchCol'];
$tblcol=$_POST['tblcol'];
$tblcolheaders=explode(',',$_POST['tblcolheaders']);
$filter_txt=$_POST['filter_txt'];
$filter_col_name=$_POST['filter_col_name'];
$sqlfilter=$_POST['sqlfilter'];
$passValue=$_POST['passValue'];
$passValueTo=$_POST['passValueTo'];

// echo "<pre>";var_dump($_POST);
 

// var_dump($_POST['sqlfilter']);
// var_dump($sqlfilter);
$flds=$searchCol;

if(isset($tblcol) and trim($tblcol)!='')
{
	$temptblcol=explode(',',$tblcol);

	foreach ($temptblcol as $value) 
	{	
		if(strrpos($flds, $value)===false)
		{
			$flds.=",".$value;
		}
	}
}
$temppassValue;
if(isset($passValue) and trim($passValue)!='')
{
	$temppassValue=explode(',', $passValue);

	foreach ($temppassValue as $value) 
	{	
		if(strrpos($flds, $value)===false)
		{
			$flds.=",".$value;
		}
	}
}

// $xsql_syspar = "SELECT * FROM syspar2";
// $xstmt_syspar = $link_id->prepare($xsql_syspar);
// $xstmt_syspar->execute();
// $xins_syspar2 = $xstmt_syspar->fetch();

// if($xins_syspar2['ddlike'] == 'LEFTMOST')
// {
// 	$xfilter=" where $filter_col_name like '$filter_txt%'".$sqlfilter;
// }
// else if($xins_syspar2['ddlike'] == 'ANYWHERE')
// {
	// var_dump($sqlfilter);die();
	// $xfilter=" where $filter_col_name like '%$filter_txt%'".$sqlfilter;
	
// }
// else
// {
	
// }
// $xsql_syspar = "SELECT * FROM syspar2";
// $xstmt_syspar = $link_id->prepare($xsql_syspar);
// $xstmt_syspar->execute();
// $xins_syspar2 = $xstmt_syspar->fetch();

// if($xins_syspar2['ddlike'] == 'LEFTMOST')
// {
// 	$xfilter = " where $filter_col_name like '$filter_txt%' " .$sqlfilter;
// }
// else if($xins_syspar2['ddlike'] == 'ANYWHERE')
// {
// 	$xfilter = " where $filter_col_name like '%$filter_txt%' " .$sqlfilter;
// }


// temp for demo
$xfilter = " where $filter_col_name like '%$filter_txt%' " .$sqlfilter;


//To cover Date Filtering
$sql_coltype="SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? and table_name = ?  AND COLUMN_NAME = ? ";
$stmt_coltype=$link_id->prepare($sql_coltype);
$stmt_coltype->execute(array($pay_dbname,$table,$filter_col_name));
$rs_coltype=$stmt_coltype->fetch(2);

if(strpos(strtolower($rs_coltype['DATA_TYPE']), 'date')!==false){
	if($filter_txt <> '' && $filter_txt <> null){
		$xfilter = " WHERE $filter_col_name = '".sr_format_date($filter_txt)."' ".$sqlfilter;
	}
	else{
		$xfilter = " WHERE $filter_col_name > '1900-01-01' ".$sqlfilter;
	}
}


// $xfilter=" where $filter_col_name like ? ".$sqlfilter; //customized by vincent 2018-08-07

$sql="SELECT $flds from $table $xfilter";
if(isset($_POST['sqlorderby']) and trim($_POST['sqlorderby'])!='')
{
	$sql.=' order by '.$_POST['sqlorderby']." ".$_POST['sqlorder'];
}
// $stmt->execute(array('% '.$filter_txt.' %'));
// $filter_txt = utf8_encode($filter_txt);

// $stmt->execute(array($filter_txt.'%'));
// var_dump($sql,$filter_txt);
// die();
$stmt=$link_id->prepare($sql);
// $stmt->execute();
$stmt->execute(array('%'.$filter_txt.'%')); //customized by vincent 2018-08-07
// var_dump($stmt,$table,$sqlfilter);


// var_dump($link,$stmt->execute(array('%'.$filter_txt.'%')));
$xret="<br><br>";
$xret.= "<table class=\"defaultfontsize hoverTable\" border = \"1\">";
$xret.="<thead>";
$xret.="<tr>";

foreach ($tblcolheaders as $value) 
{
	$xret.="<th>$value</th>";
}

$xret.="<th align=center style='text-align:center;'>Action</th>";
$xret.="</tr>";
$xret.="</thead>";

$xret.="<tbody>";

$tblcol=explode(",",$tblcol);
$tbody_cntents="";
// echo "<pre>";
// var_dump($stmt->errorinfo());
// var_dump($stmt);
// var_dump($rs=$stmt->fetch(PDO::FETCH_ASSOC));
// die();
$trclass = 'odd';
while ($rs=$stmt->fetch(PDO::FETCH_ASSOC))
{

	$tbody_cntents.="<tr class = '".$trclass."'>";
	$trclass = $trclass == 'odd'  ? 'even' : 'odd';
	

	foreach ($tblcol as $tblcolkey) 
	{

		$sql_coltype="SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
  			WHERE TABLE_SCHEMA = ? and table_name = ?  AND COLUMN_NAME = ? ";
  		$stmt_coltype=$link_id->prepare($sql_coltype);
  		$stmt_coltype->execute(array($acc_dbname,$table,$tblcolkey));
  		$rs_coltype=$stmt_coltype->fetch(2);
  		// var_dump($stmt_coltype->errorinfo());

  		// echo "<pre>";var_dump($tblcolkey,$rs_coltype['DATA_TYPE']);
  		// echo "<pre>";var_dump(array($table,$tblcolkey));

		if(strpos($tblcolkey, 'dte')!==false || strpos($tblcolkey, 'date')!==false)
		{
			// $tbody_cntents.="<td align=\"center\">".date('m-d-Y',strtotime($rs[$tblcolkey]))."</td>";
			$tbody_cntents.="<td align=\"center\">".date('Y-m-d',strtotime($rs[$tblcolkey]))."</td>";
		}
		else
		{
			if($rs_coltype['DATA_TYPE']=='double' || $rs_coltype['DATA_TYPE']=='int' || $rs_coltype['DATA_TYPE']=='decimal')
			{
				$tbody_cntents.="<td align=\"right\">".number_format($rs[$tblcolkey],2)."</td>";
			}
			else
			{
				$tbody_cntents.="<td>".$rs[$tblcolkey]."</td>";
			}
		}
	}

	$tbody_cntents.="<td align=center class='searchable_td'>";

	if(strpos($searchCol, 'dte')!==false || strpos($searchCol, 'date')!==false)
	{
		// $rs[$searchCol] = date('m-d-Y', strtotime($rs[$searchCol]));
		$rs[$searchCol] = date('Y-m-d', strtotime($rs[$searchCol]));
		$tbody_cntents.="<button value=\"".$rs[$searchCol]."\" class='filter_select edit'>Select</button>";
		// var_dump($value);
	}
	else
	{
		// $tbody_cntents.="<button value=\"".$rs[$searchCol]."\" class='filter_select edit'>Select</button>";
		$tbody_cntents.="<button value=\"".htmlspecialchars($rs[$searchCol],ENT_QUOTES)."\" class='filter_select edit'>Select</button>";
	}
	// $tbody_cntents.="<button value=\"".$rs[$searchCol]."\" class='filter_select edit'>Select</button>"; commented by vincent 10-15-18
	
	if(isset($passValue) and trim($passValue)!='')
	{
		foreach ($rs as $key => $value) 
		{
			if(in_array($key, $temppassValue))
			{

				$passValueKey=array_search($key, $passValueTo);

				// $tbody_cntents.="<input type=\"hidden\" value=\"$value\" id=\"$key\" class=\"passValue\">";
				// $tbody_cntents.="<input type=\"hidden\" value=\"{$value}\" class=\"passValue_{$passValueKey}\">";


				 //commented by vincent 10-15-18


				if(strpos($passValueKey, 'dte')!==false || strpos($passValueKey, 'date')!==false)
				{
					// $value = date('m-d-Y', strtotime($value));
					$value = date('Y-m-d', strtotime($value));
					$tbody_cntents.="<input type=\"hidden\" value=\"$value\" class=\"passValue_{$passValueKey}\">";
					// var_dump($value);
				}
				else if(strpos($passValueKey, 'amt')!==false || strpos($passValueKey, 'amount')!==false)
				{
					$value = number_format($value, 2);
					$tbody_cntents.="<input type=\"hidden\" value=\"$value\" class=\"passValue_{$passValueKey}\">";
					// var_dump($value);
				}
				else
				{
					// $tbody_cntents.="<input type=\"hidden\" value=\"{$value}\" class=\"passValue_{$passValueKey}\">";
					$tbody_cntents.="<input type=\"hidden\" value=\"$value\" class=\"passValue searchable_{$key}\">";
				}
			}
		}
	}
	$tbody_cntents.="</td>";
	$tbody_cntents.="</tr>";
}

if($tbody_cntents!='')
{
	$xret.=$tbody_cntents;
}
else
{
	$xret.="<tr><td colspan=".(count($tblcolheaders)+1)." align=center valign=middle><p style='font-size:50px;'><b>No Result</b></p></td></tr>";
	// $xret.="<tr><td colspan=".(count($tblcolheaders)+1)." align=center valign=middle><p style='font-size:50px;'><b>DEBUG LANG!</b></p></td></tr>";
}

$xret.="</tbody>";
$xret.= "</table>";
$xret.="<br><br>";

$xretobj=array();
// $xretobj='';
// $xretobj["content"]=utf8_encode($xret);
$xretobj["content"]=($xret);

// var_dump($xretobj["content"]);
// var_dump($stmt->fetchAll(PDO::FETCH_ASSOC),array($filter_txt),$stmt->errorInfo(),$sql);

// $link_id_list=array();
// $link_id_list['link_id']="dale ka";
// $link_id_list['link_id_main']="dale ka2";
// $link_id_list['link_id_ess']="dale ka3";
// $link_id_list['link_id_tkm']="dale ka4";
// $link_id_list['link_subsdry']="dale ka5";

// $xret="";
// $xret=date('Y-m-d i:m:s',strtotime(('now')));
// $xretobj['content']='wat';

// var_dump($xretobj);

function sr_format_date($date)
{
	// changes mm-dd-yyyy to yyyy-mm-dd
	//echo "date $date";

	//msgbox("format 1:$date");
	$date=trim($date);
	$xlen=strlen($date);
	if ($xlen==0)
	{
		return $date;
		//  break;
	}
	$type=1; // 1 is month, 2 is day, 3 is year;
	$mchar="";
	$dchar="";
	$ychar="";
	$m=0;
	$d=0;
	$y=0;
	for ($i=0;$i<$xlen;$i++)
	{
		$char=substr($date,$i,1);
		//msgbox("format 1a:$char");
		if ($char=="/" or $char=="-")
		{$type=$type+1;} 
		else
		{
			switch ($type)
			{
			case 1:
				$mchar=$mchar.$char;
				break;
			case 2:
				$dchar=$dchar.$char;
				break;
			case 3:
				$ychar=$ychar.$char;
				break;
			}
		}
	}

	//msgbox("format 2:m$mchar d$dchar y$ychar");

	$mchar=str_pad($mchar,2,"0",STR_PAD_LEFT);
	$dchar=str_pad($dchar,2,"0",STR_PAD_LEFT);
	return "$ychar-$mchar-$dchar";
	/*
	//99-99-9999
	$m=substr($date,0,2);
	$d=substr($date,3,2);
	$y=substr($date,6,4);

	//echo "mon $m";
	//echo "day $d";
	//echo "year $y";

	$retval="$y-$m-$d";
	//echo "retval $retval";
	return $retval;
	*/
}

// $xretobj='wat';
echo json_encode($xretobj);

?>