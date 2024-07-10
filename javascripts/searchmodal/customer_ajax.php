<?php


	require_once('../../main/appconfig.php');
	require_once('../../main/JSON.php');
    require_once('../../main/branch_filter.php');
    require_once('../../main/include_sys_par.php');
    require_once('../../include/stdfunc2017.php');
    require_once('../../main/stdfunc06.php');
	$xres = array();
    $xtablename1 = 'customerfile';
    $xprogmodule1 = "Customer Masterfile";
    $xfullname1 = ($_SESSION[$xg_appkey]['fullname']);
    $xusrcde1 = ($_SESSION[$xg_appkey]['usrcde']);
    $xsuccess1 = true;
    $xwebpage1 = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(isset($_POST['event_action']) && trim($_POST['event_action'])=='save_customer')
    {
        $xid = $_POST['txtid'];
        
        $xlog = true;
        $xmsg = '';

        $txtcuscde = $_POST['txtcuscde'];
        $xtxt_info = $_POST['txtinfo'];

        $xpassvalue   = $_POST['passValue'];
        $xpassvalueto = $_POST['passvalueto'];
        $xarr_passvalue = explode(',',  $xpassvalue);       
        $xbolval = false;
        
        $xmsgval = "";    

        // if(check_spechar($xtxt_info['txtcuscde'],$xrs_sys2['spechar']))
        // {   
        //     $xbolval = true;
        //     $xlog = false;
        //     $xmsg .="<br>Special characters not allowed !";
        // }   

        // if(trim($xtxt_info['txtcuscde'])=='')
        // {
        //     $xmsg .='<br>Customer Code should not be blank!';
        //     $xbolval = true;
        //     $xlog = false;
        // }
        if(trim($xtxt_info['txtcusdsc'])=='')
        {
            $xmsg .='<br>Customer Name should not be blank!';
            $xbolval = true;
            $xlog = false;
        }
        if(trim($xtxt_info['txttrmcde'])=='')
        {
            $xmsg .='<br>Term should not be blank!';
            $xbolval = true;
            $xlog = false; 
        }
        if(trim($xtxt_info['txtsleman'])=='')
        {
            $xmsg .='<br>Salesman should not be blank!';
            $xbolval = true;
            $xlog = false;
        }
        if(trim($xtxt_info['txtbus_style'])=='')
        {
            $xmsg .='<br>Business Style should not be blank!';
            $xbolval = true;
            $xlog = false;
        }
        if($xrs_sys['multicur'] == 1)
        {
            if (!func_checkdata(array($xtxt_info['txtcurcde']),"currencyfile","WHERE curcde=?"))
            {
                $xmsg .="<br>Invalid Currency!";
                $xbolval = true;
                $xlog = false;
            }    
        }
        
        if ($xtxt_info['txtterritory'] != "")
        {
            if (!func_checkdata(array($xtxt_info['txtterritory']),"territoryfile","WHERE tercde=?"))
            {
                $xmsg .="<br>Invalid Territory!";
                $xbolval = true;
                $xlog = false;
            }
        }
        if ($xtxt_info['txtsleman'] != "")
        {
            if (!func_checkdata(array($xtxt_info['txtsleman']),"salesmanfile","WHERE smndsc=?"))
            {
                $xmsg .="<br>Invalid Salesman!";
                $xbolval = true;
                $xlog = false;
            }
        }

        if ($xtxt_info['txttrmcde'] != "")
        {
            if (!func_checkdata(array($xtxt_info['txttrmcde']),"termfile","WHERE trmdsc=?"))
            {
                $xmsg .="<br>Invalid Term!";
                $xbolval = true;
                $xlog = false;
            }
        }
        if ($xrs_sys2['prccdelock'] == 1 && $xtxt_info['txtprccde']== "")
        {
            $xmsg .="<br>Price List Should Not Be Blank!";
            $xbolval = true;
            $xlog = false;
        }
        if ($xtxt_info['txtprccde'] != "")
        {
            if ($xrs_sys['multicur'] != 1)
            {   
                if (!func_checkdata(array($xtxt_info['txtprccde']),"pricecodefile1","WHERE prccde=?"))
                {
                    $xmsg .="<br>Invalid Price List!";
                    $xbolval = true;
                    $xlog = false;
                }
            }
            else
            {
                if (!func_checkdata(array($xtxt_info['txtprccde'],$xtxt_info['txtcurcde']),"pricecodefile1","WHERE prccde=? AND curcde = ? "))
                {
                    $xmsg .= '<br>Invalid Price List.<ol>Price List : '.$xtxt_info['txtprccde']. "</ol><ol>Currency : " .$xtxt_info['txtcurcde'].'</ol>';
                    $xbolval = true;
                    $xlog = false;
                }
            }
        }

        if ($xtxt_info['txtpygrp'] != "")
        {
            if (!func_checkdata(array($xtxt_info['txtpygrp']),"customergroupfile","WHERE cusgrpcde=?"))
            {
               $xmsg .="<br>Invalid Customer Payment Group!";
                $xbolval = true;
                $xlog = false;
            }
        }

        if ($xrs_sys2['chkglcus'] == 1)
        {
            if ($xrs_sys2['reqactcde'] == 1)
            {
                if (trim($xtxt_info['txtaracct']) == "" && trim($xtxt_info['advactcde']) == "")
                { 
                    $xmsg .="<br>A/R or Advances Account should not be blank!";
                    $xbolval = true;
                    $xlog = false;
                }
            }

            if (trim($xtxt_info['txtaracct']) != "")
            {
                if (!func_checkdata(array($xtxt_info['txtaracct']),"accountsfile","WHERE actcde=? AND allowentry = 'Y' "))
                {
                   $xmsg .="<br>Invalid A/R Account!";
                    $xbolval = true;
                    $xlog = false;
                }
            }

            if (trim($xtxt_info['advactcde']) != "")
            {
                if (!func_checkdata(array($xtxt_info['advactcde']),"accountsfile","WHERE actcde=? AND allowentry = 'Y'"))
                {
                   $xmsg .="<br>Invalid Advances Account!";
                    $xbolval = true;
                    $xlog = false;
                }
            }

            if (trim($xtxt_info['txtgldepcde']) != "")
            {
                if (!func_checkdata(array($xtxt_info['txtgldepcde']),"gldepartmentfile","WHERE gldepcde=?"))
                {
                   $xmsg .="<br>Invalid GL Depeartment!";
                    $xbolval = true;
                    $xlog = false;
                }
            }
        }

        if ($xtxt_info['txtcurcde'] != "")
        {
            if (!func_checkdata(array($xtxt_info['txtcurcde']),"currencyfile","WHERE curcde=?"))
            {
               $xmsg .="<br>Invalid Currency!";
                $xbolval = true;
                $xlog = false;
            }
        }

        //Gabriel
        if(strlen($xtxt_info['txtcusdsc'])>100){
            $xmsg .='<br>Customer Name should not exceed to 100 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtcusadd1'])>150){
            $xmsg .='<br>Address 1 should not exceed to 150 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtcusadd2'])>150){
            $xmsg .='<br>Address 2 should not exceed to 150 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtofficetelno'])>100){
            $xmsg .='<br>Office Tel. No. should not exceed to 100 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtconper'])>30){
            $xmsg .='<br>Contact Person should not exceed to 30 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtremarks'])>50){
            $xmsg .='<br>Remarks should not exceed to 50 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txttin'])>30){
            $xmsg .='<br>TIN should not exceed to 30 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtbus_style'])>50){
            $xmsg .='<br>Business Style should not exceed to 50 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txthmetelno'])>100){
            $xmsg .='<br>Home Tel. No. should not exceed to 100 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtmobile'])>20){
            $xmsg .='<br>Mobile No. should not exceed to 20 charaters!';
            $xbolval = true;
            $xlog = false;
        }
        if(strlen($xtxt_info['txtfax'])>100){
            $xmsg .='<br>Fax should not exceed to 10 charaters!';
            $xbolval = true;
            $xlog = false;
        }

        if(trim($xtxt_info['txtemail']) != "" && !filter_var(trim($xtxt_info['txtemail']), FILTER_VALIDATE_EMAIL)){
            $xmsg .='<br>Invalid E-mail!';
            $xbolval = true;
            $xlog = false;
        }

        if(strlen($xtxt_info['txtcrelim'])>30){
            $xmsg .='<br>Credit Limit should not exceed to 30 charaters!';
            $xbolval = true;
            $xlog = false;
        } else if( (strlen($xtxt_info['txtcrelim']) > 0) && (!is_numeric(str_replace(",","",$xtxt_info['txtcrelim']))) ) {
            $xmsg .="<br>Credit Limit must only contain comma, period and numeric characters!";
            $xbolval = true;
            $xlog = false;
        }
        
        if(!$xbolval)
        {
            $xrdiocustyp = ($xtxt_info['rdiocustyp']=='chkpri')?'P':'G';

            $xqryvat = 'SELECT * FROM taxcodefile where taxcde=?';
            $xstmtvat = $link_id->prepare($xqryvat);
            $xstmtvat->execute(array($xtxt_info['txttaxcde']));
            $xrsvat = $xstmtvat->fetch(); 

            $xqryewt = 'SELECT * FROM ewtcodefile where ewtcde=?';
            $xstmtewt = $link_id->prepare($xqryewt);
            $xstmtewt->execute(array($xtxt_info['txtewtcde']));
            $xrsewt = $xstmtewt->fetch(); 

            $xqryevat = 'SELECT * FROM evatcodefile where evatcde=?';
            $xstmtevat = $link_id->prepare($xqryevat);
            $xstmtevat->execute(array($xtxt_info['txtevatcde']));
            $xrsevat = $xstmtevat->fetch(); 

            $arr_record = array();
            $arr_record1= array();

            $xsmncde = GetDescription('salesmanfile','smndsc',$xtxt_info['txtsleman'],'smncde');
            $xtrmcde = GetDescription('termfile','trmdsc',$xtxt_info['txttrmcde'],'trmcde');

            // $arr_record['cuscde']   = trim($xtxt_info['txtcuscde']);
            $arr_record['cusdsc']   = trim($xtxt_info['txtcusdsc']);
            $arr_record['cusadd1']  = trim($xtxt_info['txtcusadd1']);
            $arr_record['cusadd2']  = trim($xtxt_info['txtcusadd2']);
            $arr_record['telno']    = trim($xtxt_info['txtofficetelno']);
            $arr_record['tinnum']   = trim($xtxt_info['txttin']);
            $arr_record['conper']   = trim($xtxt_info['txtconper']);
            $arr_record['remark']   = trim($xtxt_info['txtremarks']);
            $arr_record['tercde']   = trim($xtxt_info['txtterritory']);

            // $arr_record['trmcde']   = trim($xtxt_info['txttrmcde']);
            // $arr_record['smncde']   = trim($xtxt_info['txtsleman']);
            $arr_record['trmcde']   = $xtrmcde;
            $arr_record['smncde']   = $xsmncde;

            $arr_record['prccde']   = trim($xtxt_info['txtprccde']);
            
            if($xrs_sys['multicur']==1)
            {
                $arr_record['curcde'] = $xtxt_info['txtcurcde'];
            }
            else
            {
                $arr_record['curcde'] = $xrs_sys2['basecur'];
                // $arr_record['currte'] = 1;
            }
            $arr_record['cusbuscde']  = trim($xtxt_info['txtbus_style']);
            $arr_record['telno1']     = trim($xtxt_info['txthmetelno']);
            $arr_record['custypcde']  = trim($xtxt_info['txtcustypcde']);
            $arr_record['mobnum']     = trim($xtxt_info['txtmobile']); 
            $arr_record['cusgrpcde']  = trim($xtxt_info['txtpygrp']);
            $arr_record['faxnum']     = trim($xtxt_info['txtfax']);
            $arr_record['aractcde']   = trim($xtxt_info['txtaracct']);
            $arr_record['advactcde']  = trim($xtxt_info['advactcde']);
            $arr_record['email']      = trim($xtxt_info['txtemail']);
            $arr_record['gldepcde']   = trim($xtxt_info['txtgldepcde']);
            $arr_record['crelim']     = LVALDou(trim($xtxt_info['txtcrelim']));
            $arr_record['holdcrelim'] = ($xtxt_info['chk_holdcrelim']=='on')?'1':'0';
            $arr_record['holdsales']  = ($xtxt_info['chk_holdsales']=='on')?'1':'0';
            $arr_record['scpwd']      = ($xtxt_info['chkscpwd']=='on')?'1':'0';
            $arr_record['custyp']     = $xrdiocustyp;
            $arr_record['taxcde']     = $xtxt_info['txttaxcde'];
            $arr_record['taxper']     = $xrsvat['taxper'];
            $arr_record['ewtcde']     = $xtxt_info['txtewtcde'];
            $arr_record['ewtrte']     = $xrsewt['ewtrte'];
            $arr_record['evatcde']    = $xtxt_info['txtevatcde'];
            $arr_record['evatrte']    = $xrsevat['evatrte'];
            $arr_record['linksup']    = ($xtxt_info['chk_linksup']=='on')?'Y':'N';
            $arr_record['inactive']   = ($xtxt_info['chk_inactive']=='on')?'1':'0';
            // $arr_record['brhcde']     = trim($xtxt_info['txtbrhcde']);
            $arr_record['brhcde']     = $xbrh;
            
               
            if($xid =='' || $xid=='undefined')
            {   
                $xqry="SELECT * FROM customerfile where cuscde = ?";
                $xqry1="SELECT * FROM customerfile where cusdsc= ?";
                $xarr = array($txtcuscde);
                $xarr2 = array($xtxt_info['txtcusdsc']);
            }
            else
            {
                $xqry="SELECT * FROM customerfile where cuscde= ? AND recid <> ?";
                $xqry1="SELECT * FROM customerfile where cusdsc= ? AND recid <> ?"; 

                $xarr = array($txtcuscde,$xid);
                $xarr2 = array($xtxt_info['txtcusdsc'],$xid);
            }

            $xstmt=$link_id->prepare($xqry);
            $xstmt->execute($xarr);
            $xrs1 = $xstmt->fetch();
            
            $xstmt1=$link_id->prepare($xqry1);
            $xstmt1->execute($xarr2);
            $xrs2 = $xstmt1->fetch();
            $xbool = true;
            if($xrs1)
            {
                // $xlog = false;
                // $xmsg .= '<br>Code already exists!';
                // $xbool = false;
            }
            if($xrs2)
            {
                $xlog = false;
                $xmsg .= '<br>Name already exists!';
                $xbool = false;
            }

            if($xbool)
            {   
                
                if($xid=='' || $xid=='undefined')
                {   


                    $next_mfcode = $xrs_mfcode['mf_cuscde'];
                    $maykaparehoako=false;

                    retry :
                    $xdocnum =  sysparlock("codemasterfile", 'mf_cuscde', 'cuscdelock');
                    if( $maykaparehoako )
                    {
                        $next_mfcode =  LNexts($next_mfcode);
                    }
                    while($xdocnum['islock'] )
                    {
                        $xdocnum =  sysparlock("codemasterfile", 'mf_cuscde', 'cuscdelock');
                    }
                    lockunlock_docnum('codemasterfile', 'cuscdelock', 1);
                    
                    $arr_record['cuscde']=$next_mfcode;


                    $xbolinsert = PDO_InsertRecord($link_id,'customerfile',$arr_record,false);

                    if( $xbolinsert === true)
                    {
                        $xqry  = "UPDATE codemasterfile SET mf_cuscde = ? ";
                        $stmt = $link_id->prepare($xqry);
                        $stmt->execute(array( LNexts($next_mfcode)));

                    }
                    else
                    {	
                        if(func_checkdata(array($next_mfcode),'customerfile',"",false))
                        {
                            $maykaparehoako=true;
                            lockunlock_docnum('codemasterfile', 'cuscdelock', 0);
                            goto retry;
                        }
                    }
                    lockunlock_docnum('codemasterfile', 'cuscdelock', 0);


                    // PDO_InsertRecord($link_id, "customerfile", $arr_record,false);
                    // PDO_InsertRecord($link_id, "customershipfile", $arr_record1,false);
                    PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , 'Add Customer' , "Add Customer : ".$arr_record['cuscde'], $xwebpage1, 'Add', $xprogmodule1 , $xsuccess1);

                    $xmsg .= '<b>Successfully added new record!';
                    $txtcuscde = $next_mfcode;

                }
                else
                {
                    $xcontinue = true;
                    $xUpdCde = false;
                    $xUpdDsc = false;
                    $xUpdgrp = false;

                    $xsql = "SELECT * FROM customerfile where recid=?";
                    $xstmt = $link_id->prepare($xsql);
                    $xstmt->execute(array($xid));
                    $xrs = $xstmt->fetch(PDO::FETCH_ASSOC);
                    $xOldCde = $xrs['cuscde'];
                    $xOldDsc = $xrs['cusdsc'];
                    $xOldGrp = $xrs['cusgrpcde'];
                    
                    $xcuscde = trim($xtxt_info['txtcuscde']);
                    $xcusdsc = trim($xtxt_info['txtcusdsc']);
                    $xcusgrp = trim($xtxt_info['txtpygrp']);
                    if (strtoupper($xcusdsc) !== strtoupper($xOldDsc))
                    {
                        $CheckDup = CheckDup('customerfile', "cusdsc", $xcusdsc, "Customer Already Exist!");
                        if($CheckDup) 
                        {
                            $xcontinue = false;
                            $xmsg = "Customer Already Exist!";
                        }
                        $xUpdCde = true;
                    }
                    if ($xcusdsc !== $xOldDsc)
                    {
                        $xUpdDsc = true;
                    }
                    if ($xcusgrp !== $xOldGrp)
                    {
                        $xUpdgrp = true;
                    }
                    $fldname = 'cuscde';
                    $table = 'customerfile';
             
                    if ($xcontinue)
                    {

                        PDO_UpdateRecord($link_id, "customerfile", $arr_record,"recid=?",array($xid),false);
                        // erick 2021-05-04 commented
                        // UpdateAllTables('customerfile', "cuscde", "cusdsc", $xUpdCde, $xUpdDsc, $xOldCde, $xcuscde, $xOldDsc, $xcusdsc);
                        // UpdateAllTables('customergroupfile', "cuscde", "cusgrpcde", true, $xUpdgrp, $xcuscde, $xcuscde,$xOldGrp,  $xcusgrp);
                        $xfromto = check_newvalue('customerfile',$xid,$arr_record);
                        $xfield=$_POST['txt_name'];
                        $xnewval=$xfromto[$x][1];
                        $xoldval=$xfromto[$x][2];
                        $xhead=$xfield[$xfromto[$x][0]];
                        //Marviel
                        if($xUpdCde)
                        {
                            $xremarksz =  "Update Customer  From " . $xOldCde . ' To ' .$arr_record['cuscde'];
                        }
                        else
                        {
                            $xremarksz =  "Update Customer  : ".$arr_record['cuscde'];
                        }
                        PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , "Update Customer" ,$xremarksz, $xwebpage1, "Update", $xprogmodule1 , $xsuccess1,$xnewval,$xoldval,$xhead);
    
                        $xmsg = 'Successfully updated record!';
                    }
                    $txtcuscde = $xrs['cuscde'];

                }


                if($xtxt_info['chk_linksup']=='on')
                {
                    $arr_record1['supcde']   = trim($txtcuscde);
                    $arr_record1['supdsc']   = trim($xtxt_info['txtcusdsc']);
                    $arr_record1['supadd1']  = trim($xtxt_info['txtcusadd1']);
                    $arr_record1['supadd2']  = trim($xtxt_info['txtcusadd2']);
                    $arr_record1['telno']    = trim($xtxt_info['txtofficetelno']);
                    $arr_record1['faxno']   = trim($xtxt_info['txtfax']);
                    $arr_record1['tinnum']   = trim($xtxt_info['txttin']);
                    $arr_record1['conper']   = trim($xtxt_info['txtconper']);
                    $arr_record1['remark']   = trim($xtxt_info['txtremarks']);
                    $arr_record1['trmcde']   = trim($xtxt_info['txttrmcde']);
                    // $arr_record1['brhcde']     = trim($xtxt_info['txtbrhcde']);
                    $arr_record1['brhcde']     = $xbrh;
                    // $arr_record1['prccde']   = trim($xtxt_info['txtprccde']);
                    // $arr_record1['cusbuscde']= trim($xtxt_info['txtbus_style']);
                    $arr_record1['taxcde']   = $xtxt_info['txttaxcde'];
                    $arr_record1['taxper']   = $xrsvat['taxper'];
                    $arr_record1['ewtcde']   = $xtxt_info['txtewtcde'];
                    $arr_record1['ewtrte']   = $xrsewt['ewtrte'];
                    $arr_record1['linkcus']   = 1;
                    
                    // if($xrs_sys['multicur']==1)
                    // {
                    //     $arr_record1['curcde'] = $xtxt_info['txtcurcde'];
                    // }
                    // else
                    // {
                    //     $arr_record1['curcde'] = $xrs_sys2['basecur'];
                    // }

                    // $arr_record1['evatcde']   = $xtxt_info['txtevatcde'];
                    // $arr_record1['evatrte']   = $xrsevat['evatrte'];
                    $xqry_sel="SELECT *  FROM supplierfile WHERE supcde=?";
                    $xstmt_sel=$link_id->prepare($xqry_sel);
                    $xstmt_sel->execute(array(trim($xtxt_info['txtcuscde'])));
                    $xrs_sel = $xstmt_sel->fetch();

                    if($xrs_sel)
                    {
                        PDO_UpdateRecord($link_id, "supplierfile", $arr_record1,"supcde=?",array(trim($xtxt_info['txtcuscde'])),false);
                    }
                    else
                    {
                        PDO_InsertRecord($link_id, "supplierfile", $arr_record1,false);
                    }
                }
                $xlog = true;
            }
        }

        $chkbox = $_POST['chk'];
        $xparams['cusewtreq'] = $chkbox['cusewtreq'] == "checked" ? 1 : 0;
        $xparams['cusewtreqsal'] = $chkbox['cusewtreqsal'] == "checked" ? 1 : 0;
        $xparams['cusewtreqard'] = $chkbox['cusewtreqard'] == "checked" ? 1 : 0;
        $xparams['cusewtreqcs'] = $chkbox['cusewtreqcs'] == "checked" ? 1 : 0;
        $xparams['cusewtreqdrs'] = $chkbox['cusewtreqdrs'] == "checked" ? 1 : 0;
        $xparams['cusewtreqtyp'] = $_POST['rdo_cusewtreqtyp'];

        $xparams['cusevatreq'] = $chkbox['cusevatreq'] == "checked" ? 1 : 0;
        $xparams['cusevatreqsal'] = $chkbox['cusevatreqsal'] == "checked" ? 1 : 0;
        $xparams['cusevatreqard'] = $chkbox['cusevatreqard'] == "checked" ? 1 : 0;
        $xparams['cusevatreqcs'] = $chkbox['cusevatreqcs'] == "checked" ? 1 : 0;
        $xparams['cusevatreqdrs'] = $chkbox['cusevatreqdrs'] == "checked" ? 1 : 0;
        $xparams['cusevatreqtyp'] = $_POST['rdo_cusevatreqtyp'];

        $xqry_sel="SELECT *  FROM customerfile WHERE cuscde=?";
        $xstmt_sel=$link_id->prepare($xqry_sel);
        $xstmt_sel->execute(array(trim($txtcuscde)));
        $xrs_sel = $xstmt_sel->fetch();

        if($xbool){
            
            if($xrs_sel)
            {
                PDO_UpdateRecord($link_id, "customerfile", $xparams,"cuscde=?",array(trim($txtcuscde)),false);
            }
            else
            {
                PDO_InsertRecord($link_id, "customerfile", $xparams,false);
            }
    
        }
      
        $xqry_select  = "SELECT * FROM customerfile $xfilter_pager_fields AND cusdsc = ? ";
        $xstmt_select = $link_id->prepare($xqry_select);   
        $xstmt_select->execute(array($xtxt_info['txtcusdsc']));        
        $xres_select  =  $xstmt_select->fetch();

        foreach ($xarr_passvalue as $key => $value) {
            $xres[$value] = $xres_select[$value];
        }


        $srhcol = $_POST['searchcol'];
        $xres['log'] = $xlog ? 'success' : 'failed';
        $xres['msg'] = $xmsg;
        $xres['ret_code'] = $xtxt_info['txtcusdsc'];
        $xres['searchcol'] = $xres_select[$srhcol];

        
    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='save_otherinfo')
    {
        $xcuscde = $_POST['txt_cuscde'];
        $xarr_record = array();
        $xarr_record['field01']     = $_POST['txt_field01'];
        $xarr_record['field02']     = $_POST['txt_field02'];
        $xarr_record['field03']     = $_POST['txt_field03'];
        $xarr_record['field04']     = $_POST['txt_field04'];
        $xarr_record['field05']     = $_POST['txt_field05'];
        $xarr_record['field06']     = $_POST['txt_field06'];
        $xarr_record['field07']     = $_POST['txt_field07'];
        $xarr_record['field08']     = $_POST['txt_field08'];
        $xarr_record['field09']     = $_POST['txt_field09'];
        $xarr_record['field10']     = $_POST['txt_field10'];
        $xarr_record['field11']     = $_POST['txt_field11'];
        $xarr_record['field12']     = $_POST['txt_field12'];
        $xarr_record['field13']     = $_POST['txt_field13'];
        $xarr_record['field14']     = $_POST['txt_field14'];
        $xarr_record['field15']     = $_POST['txt_field15'];
        $xarr_record['field16']     = $_POST['txt_field16'];
        $xarr_record['field17']     = $_POST['txt_field17'];
        $xarr_record['field18']     = $_POST['txt_field18'];
        $xarr_record['field19']     = $_POST['txt_field19'];
        $xarr_record['field20']     = $_POST['txt_field20'];

        PDO_UpdateRecord($link_id, "customerfile", $xarr_record,"cuscde=?",array($xcuscde),false);
        // die();
    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='chk_cuscde')
    {
        $xrecid = $_POST['recid'];
        $xcuscde = $_POST['cuscde'];

        $xlog = true ;
        $xmsg = '';   
        if($xrecid=='' || $xrecid=='undefined')
        {
            $xsql = "SELECT count(*) as xcount from customerfile WHERE cuscde = '".$xcuscde."' ";   
        }
        else
        {
            $xsql = "SELECT count(*) as xcount from customerfile WHERE cuscde = '".$xcuscde."' and recid!='".$xrecid."' ";   
        }
            $xstmt = $link_id->prepare($xsql);
            $xstmt->execute();
            $xrs = $xstmt->fetch();
            if($xrs['xcount']>0)
            {
               $xlog = false ;
               $xmsg = 'Code already exists!!';
            }
        $xres['msg'] = $xmsg;
        $xres['log'] = $xlog ? 'success' : 'failed';
    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='chk_cusdsc')
    {
        $xrecid = $_POST['recid'];
        $xcusdsc = $_POST['cusdsc'];

        $xlog = true ;
        $xmsg = '';   
        if($xrecid=='' || $xrecid=='undefined')
        {
            $xsql = "SELECT count(*) as xcount from customerfile WHERE cusdsc = '".$xcusdsc."' ";   
        }
        else
        {
            $xsql = "SELECT count(*) as xcount from customerfile WHERE cusdsc = '".$xcusdsc."' and recid!='".$xrecid."' ";   
        }
            $xstmt = $link_id->prepare($xsql);
            $xstmt->execute();
            $xrs = $xstmt->fetch();
            if($xrs['xcount']>0)
            {
               $xlog = false ;
               $xmsg = 'Name already exists!!';
            }
        $xres['msg'] = $xmsg;
        $xres['log'] = $xlog ? 'success' : 'failed';
    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='delete')
    {
        $xrecid = $_POST['recid'];
        $fldname = 'cuscde';
        $table = 'customerfile';
        $table1 = 'customershipfile';

        $xcontinue = true;
        $xmsg = '';
        $xcuscde = $_POST['cuscde'];
        $ValDel01 = ValDel01("customerfile", "cuscde", $xcuscde);
         /*var_dump($ValDel01);
            die();*/
        if($ValDel01 != "") 
        {
            $xcontinue = false;
           
            $xmsg = $ValDel01;
        }
        /*$xqry = "SELECT table_schema,table_name, column_name FROM information_schema.columns WHERE column_name = ? AND table_name != ? and table_name!= ? and table_schema='".$_SESSION[$xg_appkey]['dbname']."'";
        $xstmt_field = $link_id->prepare($xqry);
        $xstmt_field->execute(array($fldname,$table,$table1));
        // var_dump($xqry);
        // var_dump(array($fldname,$table,$table1));
        while ($xrs_fieldname = $xstmt_field->fetch()) 
        {

            $xtmp_filter = "";
            $xqry2 = "SELECT table_schema,table_name, column_name FROM information_schema.columns WHERE column_name = ? AND table_name != ? and table_schema='".$_SESSION[$xg_appkey]['dbname']."'";
            $xstmt_field2 = $link_id->prepare($xqry2);
            $xstmt_field2->execute(array('brhcde',$xrs_fieldname['table_name']));
            if ($xstmt_field2->fetch())
            {
                $xtmp_filter = "and brhcde = '".$xbrh."'";
            }

            $xqry_rec = "SELECT ".$xrs_fieldname['column_name']." FROM ".$xrs_fieldname['table_name']." WHERE ".$xrs_fieldname['column_name']."=? $xtmp_filter";
            $xqry_get="SELECT $fldname from $table where recid=?";
            $xstmt_get=$link_id->prepare($xqry_get);
            $xstmt_get->execute(array($xrecid));
            $xrs_get=$xstmt_get->fetch();
            // var_dump($xqry_get,$xid);

            $xstmt_rec = $link_id->prepare($xqry_rec);
            $xstmt_rec->execute(array($xrs_get[$fldname]));
            // var_dump($xqry_rec,$xrs_get[$fldname]);
            if ($xrs_fieldvalue=$xstmt_rec->fetch(PDO::FETCH_ASSOC))
            {
                $xcontinue = false;
                $xmsg = 'Cannot delete record , already used in transaction!';
                break;          
            }
            // var_dump($xmsg);
        }*/

        if($xcontinue)
        {
            $xsql = 'SELECT cuscde from customerfile where recid =? ';
            $xstmt = $link_id->prepare($xsql);
            $xstmt->execute(array($xrecid));
            $xrs = $xstmt->fetch();
          
            $xsql_delete = 'DELETE from customershipfile where cuscde=?';
            $xstmt_delete = $link_id->prepare($xsql_delete);
            $xstmt_delete->execute(array($xrs['cuscde']));

            $xsql_delete1 = 'DELETE from customerfile where recid=?';
            $xstmt_delete1 = $link_id->prepare($xsql_delete1);
            $xstmt_delete1->execute(array($xrecid));

            $xcontinue = true;
            $xmsg = 'Successfully deleted record!';

            $xaction1 = "Delete";
            $xactivity1 = "Delete Customer ";
            $xremarks1 = "Delete Customer : ".$xrs['cuscde'];
            PDO_UserActivityLog($link_id, $xtablename1 , $xfullname1 , $xusrcde1 , $xactivity1 , $xremarks1, $xwebpage1, $xaction1, $xprogmodule1 , $xsuccess1);

        }

        $xres['msg'] = $xmsg;
        $xres['log'] = $xcontinue ? 'success' : 'failed';

    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='view_attachment')
    {
        $xcuscde = $_POST['cuscde'];
        $xtable_html = "";

        $xqry_selattch  = "SELECT * FROM customerattachfile WHERE cuscde = ?";
        $xstmt_selattch = $link_id->prepare($xqry_selattch);
        $xstmt_selattch->execute(array($xcuscde));

        $xtable_html.= "<tr>";
            $xtable_html.= "<td hidden>ID</td>";
            $xtable_html.= "<td style='width:80%'>File name</td>";
            $xtable_html.= "<td style='text-align:center' colspan='2'>Action</td>";
        $xtable_html.="</tr>";

        if($xstmt_selattch->rowCount() > 0){
            while($xrs_data = $xstmt_selattch->fetch(PDO::FETCH_ASSOC)){
                $xtable_html.= "<tr class='tbldetails'>";
                    $xtable_html.= "<td hidden>". $xrs_data['recid'] ."</td>";

                    $xtable_html.= "<td>";
                        $xtable_html.= "<label>". $xrs_data['custfilename'] ."</label>";
                    $xtable_html.= "</td>";

                    $xtable_html.= "<td>";
                        $xtable_html.= "<input id='btndlattch' type='button' class='attchbtn' name='btndlattch' onclick='dlattchmnt(\"". $xrs_data['custfilename'] ."\")' value='Download'>";
                    $xtable_html.= "</td>";

                    $xtable_html.= "<td>";
                        $xtable_html.= "<input id='btnremattch' type='button' class='attchbtn' name='btnremattch' onclick='rmvattchmnt(\"". $xrs_data['recid'] ."\")' value='Remove'>";
                    $xtable_html.= "</td>";
                $xtable_html.="</tr>";
            }
        }else{
            $xtable_html.= "<tr>";
                $xtable_html.= "<td colspan='2' style='text-align:center'>No record.</td>";
            $xtable_html.="</tr>";
        }

        $xres['html_body'] = $xtable_html;
        
    }

    if(isset($_POST['event_action']) && trim($_POST['event_action'])=='remove_attachment')
    {
        $xattchid = $_POST['attchid'];
        $xbool = false;
        $xmsg = "Something went wrong...";

        $xqry_remattch  = "DELETE FROM customerattachfile WHERE recid = ?";
        $xstmt_remattch = $link_id->prepare($xqry_remattch);
        $xstmt_remattch->execute(array($xattchid));

        if($xstmt_remattch->rowCount() > 0){
            $xbool = true;
            $xmsg = "Sucessfully remove attachement";
        }
        
        $xres['bool'] = $xbool;
        $xres['msg'] = $xmsg;
    }

    
    

	echo json_encode($xres);
?>
