<?php
	require_once('../../main/appconfig.php');
	require_once('../../main/include_sys_par.php');
	require_once('../../main/branch_filter.php');

	$xretobj = array();

	if(isset($_POST['event_action']) && $_POST['event_action'] == 'get_add_form')
	{
		$xtable = $_POST['table'];
		$xrequestor = $_POST['requestor'];
		$xdialog_title = "";
		$xhtml_content = "";

		if(strtolower($xtable) == "customerfile")
		{
			$xdialog_title = "Add Customer";
					                      					                 	                   					               
			$xhtml_content = get_customermasterfile_content();
		}

		elseif(strtolower($xtable) == "supplierfile")
		{
			$xdialog_title = "Add Supplier";
					                      					                 	                   					               
			$xhtml_content = get_suppliermasterfile_content();
		}
		$xretobj['divtitle']   = $xdialog_title;
		$xretobj['divcontent'] = $xhtml_content;

	}

	echo json_encode($xretobj);


	function get_customermasterfile_content()
	{
		include GLOBAL_VARIABLES_PATH;
		$xhtml_content .= "<div class = \"cla_buttons\" style = \"border-bottom: 1px solid #d4d4d4;margin: 10;\">";
			$xhtml_content .= "<input type= \"button\" class =\"save class_srch_save_cus\" value =\"SAVE\">";
		$xhtml_content .= "</div>";
		$xhtml_content  .= "<div id = \"div_customer\" class ='module width_full' style=\"overflow: hidden;height: 88%;margin-top:0 !important\">";
			$xhtml_content .= "<div id = \"div_customer_info\">";
				$xhtml_content .= "	<ul>
					    				<li><a href=\"#div_customer_info1\">Customer Info</a></li>
					    				<li><a href=\"#div_customer_ship\">Customer Shipping Address</a></li>";
					    			if($xrs_sys2['cusdefinfo']=='1')
					    			{
				$xhtml_content .= "		<li $xcus_other_hide ><a href=\"#div_other_info\">Other Info</a></li>";
					    			}
				$xhtml_content .=  "</ul>";
				$xhtml_content .=	customer_info();
				$xhtml_content .=	customer_ship();	  	
				$xhtml_content .=	$xrs_sys2['cusdefinfo']=='1' ? div_other_info('CUS') : "" ;	  				
			$xhtml_content .= "</div>";
		$xhtml_content .= "</div>";


		$xhtml_content .="
		    <script type=\"text/javascript\">
		        jQuery(document).ready(function()
		        {	

		            jQuery(\".srh_actcde\").searchable(
		            {
		                modalTitle:\"Search Account\",
		                modalWidth:1050,
		                modalHeight:500,
		                table:\"accountsfile \",
		                tableCol:\"actcde,actdsc\",
		                tableColHeader:\"Account Code,Account Desc.\",
		                link_id:\"link_id\",
		                searchCol:\"actcde\",//value needed to be fetched
		                searchByDesc:[\"Account Code.\",\"Account Desc.\"],
		                searchByValue:[\"actcde\",\"actdsc\"],
		                sqlfilter:\"AND allowentry ='Y'\",
		                passValueTo:[\"\"],
		                passValue:[\"\"],
		                orderBy:\"actdsc\"    
		            });
		            jQuery(\".srh_gldepcde\").searchable(
		            {
		                modalTitle:\"Search Department\",
		                modalWidth:1050,
		                modalHeight:500,
		                table:\"gldepartmentfile \",
		                tableCol:\"gldepcde,gldepdsc\",
		                tableColHeader:\"Department Code,Department Desc.\",
		                link_id:\"link_id\",
		                searchCol:\"gldepcde\",//value needed to be fetched
		                searchByDesc:[\"Department Code.\",\"Department Desc.\"],
		                searchByValue:[\"gldepcde\",\"gldepdsc\"],
		                sqlfilter:\"\",
		                passValueTo:[\"\"],
		                passValue:[\"\"],
		                orderBy:\"gldepdsc\"    
		            });
		            jQuery(\"#div_customer_info\").tabs();
		            // jQuery(\"#add_cus_ship\").click(function()
		            jQuery(\"#add_cus_ship\").on(\"click\", function()
		            {
		            	var xdate = new Date();
		            	var xtime = xdate.getTime();
		            	jQuery(\"#tbl_cus_ship\").find(\"tbody\").append(\"<tr id= '\"+xtime+\"'><td><input type='text' name = 'txtshipinfo[\"+xtime+\"][shipto]' class ='input'></td><td><input type='text' name = 'txtshipinfo[\"+xtime+\"][shipto2]' class ='input'></td><td><i class='fa fa-times-circle' aria-hidden='true' id='img' onclick='func_rowdel(\"+xtime+\"	);'></i></td>\</tr>\");
		           	});
		        });

		        function func_rowdel(xid)
		        {
		        	jQuery('#'+xid).detach();
		        }
		    </script>";

		return $xhtml_content;
	}
	function customer_info()
	{	
		include GLOBAL_VARIABLES_PATH;
		$xbrhhide = $xrs_sys['multibranch'] == 1 ? '' : 'hidden';
		$xgrphide = $xrs_sys2['cusgrpapp'] == 1 ? '' : 'hidden';
		$xcurcde  = $xrs_sys2['basecur'];
		if($xrs_sys2['chkglcus'] == 1)
		{
			$xaracct  = $xrs_sys2['cusactcde'];
			$xadvacct = $xrs_sys2['cusactcde2'];
		}
		$chk_linksup = $xrs_sys2['deflinksup'] == 1 ? "CHECKED" : '';
		$cla_required2prc = $xrs_sys2['prccdelock'] == 1 ? "required2" : "";
		$xhtml_customer_info = "";
		$xhtml_customer_info .= "<div id = \"div_customer_info1\" style=\"overflow: auto;height: 90%\">
							    <table border=\"0\" style = \"width:90%\" cellpadding=\"0\" cellspacing=\"4\" > 
							     
							        <tr>
							            <td>Customer Name : </td>
							            <td colspan=\"4\">
							                <input type=\"text\" name=\"txtinfo[txtcusdsc]\" id=\"txtcusdsc\" class=\"input required2 validateinput\" style=\"width:100%;\"/>
							                <input type=\"hidden\" name=\"lbl_cusdsc\" id=\"lbl_cusdsc\" style=\"color: red;border:0;background: transparent;\">
							            </td>
							        </tr>
							        <tr>
							            <td>&nbsp;</td>
							        </tr>
							        <tr>
							            <td>Address 1 :</td>
							            <td colspan=\"3\">
							                <input type=\"text\" name=\"txtinfo[txtcusadd1]\" id=\"txtcusadd1\" class=\"input\" style=\"width:100%;\"
							            </td>
							        </tr>
							        <tr>
							            <td>Address 2 :</td>
							            <td colspan=\"3\">
							                <input type=\"text\" name=\"txtinfo[txtcusadd2]\" id=\"txtcusadd2\" class=\"input\" style=\"width:100%;\"
							            </td>
							        </tr>
							        <tr>
							            <td>Office Tel No. :</td>
							            <td>
							                <input type=\"text\" name=\"txtinfo[txtofficetelno]\" id=\"txtofficetelno\" class=\"input\" style=\"width:100%;\"/>
							            </td>
							            <td>TIN :</td>
							            <td>
							                <input type=\"text\" name=\"txtinfo[txttin]\" id=\"txttin\" class=\"input\" style=\"width:100%;\"/>
							            </td>
							        </tr>
							        <tr>
							           <td>Contact Person :</td>
							            <td colspan=\"3\">
							                <input type=\"text\" name=\"txtinfo[txtconper]\" id=\"txtconper\" class=\"input\" style=\"width:100%;\"
							                />
							            </td>
							        </tr>
							        <tr>
							            <td>Remarks :</td>
							            <td colspan=\"3\">
							                <input type=\"text\" name=\"txtinfo[txtremarks]\" id=\"txtremarks\" class=\"input\"  style=\"width:100%;\"
							                />
							            </td>
							        </tr>
							        <tr>
							            <td>Territory :</td>
							            <td>
							                 <select name=\"txtinfo[txtterritory]\" id=\"txtterritory\" class=\"input\" style=\"width:100%;\">
							                    <option></option>";
				// $xhtml_customer_info .=                        ListOption_return($link_id,'territoryfile','tercde',',');
				$xhtml_customer_info .=                        ListOption_return($link_id,'territoryfile','terdsc',',');
				$xhtml_customer_info .=                "</select>
								            </td>
								            <td>Terms :</td>
								            <td>
								                <select name=\"txtinfo[txttrmcde]\" id=\"txttrmcde\" class=\"input required2\" style=\"width:100%;\">
								                    <option></option>";
				// $xhtml_customer_info .=                       ListOption_return($link_id,'termfile','trmcde',$xtrmcde,'');
				$xhtml_customer_info .=                       ListOption_return($link_id,'termfile','trmdsc',$xtrmcde,'');
				$xhtml_customer_info .= "				</select>
								        </td>
								    </tr>
								    <tr>
								        <td>Salesman :</td>
								        <td>
								            <select name=\"txtinfo[txtsleman]\" id=\"txtsleman\" class=\"input required2\" style=\"width:100%;\">
								                <option></option>";
				// $xhtml_customer_info .=                       ListOption_return($link_id,'salesmanfile '.$xfilter_pager_fields,'smncde',$xsleman,'');
				$xhtml_customer_info .=                       ListOption_return($link_id,'salesmanfile '.$xfilter_pager_fields,'smndsc',$xsleman,'');
				$xhtml_customer_info .=                    "
								            </select>
								        </td>
								        <td>Price List :</td>
								        <td>
								            <select name=\"txtinfo[txtprccde]\" id=\"txtprccde\" class=\"input $cla_required2prc\" style=\"width:100%;\">
								                <option></option>";
				$xhtml_customer_info .=                         ListOption_return($link_id,'pricecodefile1'.$xfilter_pager_fields,'prccde',$xprcde,'');
				$xhtml_customer_info .=           " </select>
								        </td>
								    </tr>
									<tr>
								        <td>&nbsp;</td>
								        <td>&nbsp;</td>
								        ";
												if($xrs_sys['multicur']==1)
									            {
				$xhtml_customer_info .= "					<td>Currency :</td>
									                <td>
									                    <select name=\"txtinfo[txtcurcde]\" id=\"txtcurcde\" class=\"input\" style=\"width:100%;\">
									                        ";
				$xhtml_customer_info .=	                    	ListOption_return($link_id,'currencyfile','curcde',$xrs_sys2['basecur'],'');
				$xhtml_customer_info .=                           "        
									                    </select>
									                 </td>";
									            }
									            else
									            {
				$xhtml_customer_info .= " 				<input type=\"hidden\" name=\"txtinfo[txtcurcde]\" id=\"txtcurcde\" /> ";
									            }
				$xhtml_customer_info .= "               
									</tr>
									<tr>
									    <td>Buss. Style :</td>
									    <td>
									                
									        <input type=\"text\" class=\"input required2\" name=\"txtinfo[txtbus_style]\" id=\"txtinfo[txtbus_style]\">
									    </td>
									    <td>
									        <input type=\"radio\" name=\"txtinfo[rdiocustyp]\" id=\"rdiocustyp\" checked value=\"chkpri\" />&nbsp;
									        <label class=\"\">Private</label>
									    </td>
									    <td>
									        <input type=\"radio\" name=\"txtinfo[rdiocustyp]\" id=\"rdiocustyp\" value=\"chkgov\"/>&nbsp;
									        <label class=\"\">Government</label>
									    </td>
									</tr>
									<tr>
									    <td>Home Tel. No. :</td>
									    <td>
									        <input type=\"text\" name=\"txtinfo[txthmetelno]\" id=\"txthmetelno\" class=\"input\" value=\"\" style=\"width:100%;\" />
									    </td>
									    <td $xbrhhide>Branch :</td>
									    <td $xbrhhide>
									        <input type=\"text\" name=\"txtinfo[txtbrhcde]\" id=\"txtbrhcde\" class=\"input\" value=\"$xbrh\" readonly/>
									    </td>";
							                
			$xhtml_customer_info .= "	        </tr>
								        <tr>
											<td>Mobile :</td>
											<td>
												<input type=\"text\" name=\"txtinfo[txtmobile]\" id=\"txtmobile\" class=\"input\" value=\"$xmobnum\" style=\"width:100%;\"/>
											</td>";
											
											if($xrs_sys2['custyp']=='Y')
							                {
			$xhtml_customer_info .= "           <td>Type :</td>
							                    <td>
							                        <select name=\"txtinfo[txtcustypcde]\" id=\"txtcustypcde\" class=\"input\" style=\"width:100%;\">
							                            <option></option>";
			$xhtml_customer_info .=                                	 ListOption_return($link_id,"customertypefile","custypcde",$xcustypcde,"");
			$xhtml_customer_info .= "                      </select>
							                    </td>";
							                }			
			$xhtml_customer_info .= "	        </td>
								        </tr>
								        <tr>
								            <td>Fax :</td>
								            <td>
								                <input type=\"text\" name=\"txtinfo[txtfax]\" id=\"txtfax\" class=\"input\" style=\"width:100%;\"/>
								            </td>
								            <td $xgrphide;>Payment Group :</td>
											<td $xgrphide;>";
											if($xpaygrpdis != "") 
								            {
			$xhtml_customer_info .= "			<input type = \"text\" name=\"txtinfo[txtpygrp]\" id=\"txtpygrp\" class=\"input disabled\" style=\"width:100%;\" readonly>";
								            }
								            else 
								            {
			$xhtml_customer_info .= "			<select name=\"txtinfo[txtpygrp]\" id=\"txtpygrp\" class=\"input\" style=\"width:100%;\">
								                    <option></option>";
			$xhtml_customer_info .=					ListOption_return($link_id,'customergroupfile'.$xfilter_pager_fields,'cusgrpdsc',$xcusgrdcde,'');
			$xhtml_customer_info .=	            "</select>";            
				                   			}
			$xhtml_customer_info .= "	</tr>
										<tr>		
											<td>E-mail :</td>
											<td>
												<input type=\"text\" name=\"txtinfo[txtemail]\" id=\"txtemail\" class=\"input\" style=\"width:100%;\"/>
											</td>";
								                $xhidden = "";
								                if($xrs_sys2['chkglcus']!=1)
								                {   
								                   $xhidden = 'hidden'; 
								                }

			$xhtml_customer_info .= " 		<td $xhidden > AR Account :</td>
								            <td $xhidden >
								                <input type=\"text\" name=\"txtinfo[txtaracct]\" id=\"txtaracct\" value=\"$xaracct\" class=\"input srh_actcde\" style=\"width:100%;\"
								                readonly/>
								            </td>
										</tr>
										
								        <tr>
								            <td>Credit Limit</td>
								            <td>
								                <input type=\"text\" name=\"txtinfo[txtcrelim]\" id=\"txtcrelim\" class=\"input regex-amt\" style=\"width:100%;\"/>
								            </td>";
							                $xhidden = "";
							                if($xrs_sys2['chkglcus']!=1)
							                {   
							                   $xhidden = 'hidden'; 
							                }   
			$xhtml_customer_info .= "				<td $xhidden > Advances Account :</td>
								            <td $xhidden >
								                <input type=\"text\" name=\"txtinfo[advactcde]\" id=\"advactcde\" value=\"$xadvacct\" readonly class=\"input srh_actcde\" style=\"width:100%;\"/>
								            </td>
								        </tr>
								        <tr>
								            <td></td>    
								            <td>(Note:zero or leave it blank for no credit limit)</td>";    
								                $xhidden = '';
								                if($xrs_sys2['chkglcus']!=1)
								                {   
								                    $xqry1="SELECT enabledep FROM glparameters;";
								                    $xstmt1=$link_id->prepare($xqry1);
								                    $xstmt1->execute();
								                    $xrs1 = $xstmt1->fetch();
								                    if($xrs1['enabledep']!=1)
								                    {
								                        $xhidden = "hidden";
								                    }
								                }  

			$xhtml_customer_info .= "	            <td $xhidden >GL Dept. :</td>
								            <td $xhidden >
								                <input type=\"text\" name=\"txtinfo[txtgldepcde]\" id=\"txtgldepcde\" class=\"input srh_gldepcde\" style=\"width:100%;\" readonly/>
								            </td>      
								        </tr>
										<tr>
											<td></td>
											<td colspan=\"4\">
												<input  type=\"checkbox\" name=\"txtinfo[chk_holdcrelim]\" id=\"chk_holdcrelim\" $xchk_holdcreli/>
								                <label class=\"\">Hold Sales if Credit limit is exceeded</label>
											</td>
										</tr>
										<tr>
											<td></td>
											<td colspan=\"4\">
												<input type=\"checkbox\" name=\"txtinfo[chk_holdsales]\" id=\"chkhldsles\" /> 
								                <label class=\"\">Hold Sales</label>";
							                    if($xrs_sys2['cspwddis']==1)
							                    {
			$xhtml_customer_info .= "                     <input type=\"checkbox\" name=\"txtinfo[chkscpwd]\" id=\"chkscpwd\" style=\"margin-left:250px;\"/> 
							                        <label class=\"\">With SC/PWD Disc.</label>";
							                    }
			$xhtml_customer_info .= "             </td>
										</tr>	";	
						            if($xrs_sys['getitmewt']==0)
						            {

			$xhtml_customer_info .= "  		<tr>
						                    <td>VAT :</td>
						                    <td>
						                        <select name=\"txtinfo[txttaxcde]\" id=\"txttaxcde\" class=\"input\" style=\"width:100%;\">
						                            <option></option>";
			$xhtml_customer_info .=  	                  ListOption_return($link_id,'taxcodefile','taxcde',$xtaxcde,'');
			$xhtml_customer_info .= "	          </select>
								        </td>
						                </tr>
						                <tr>
						                    <td>EWT :</td>
						                    <td>
						                        <select name=\"txtinfo[txtewtcde]\" id=\"txtewtcde\" class=\"input\" style=\"width:100%;\">
						                            <option></option>";
			$xhtml_customer_info .=  	                 ListOption_return($link_id,'ewtcodefile','ewtcde',$xewtcde,'');
			$xhtml_customer_info .= "	                  
						                        </select>
						                    </td>
						                </tr>
						                <tr>
						                    <td>EVAT :</td>
						                    <td>
						                        <select name=\"txtinfo[txtevatcde]\" id=\"txtevatcde\" class=\"input\" style=\"width:100%;\">
						                            <option></option>";
			$xhtml_customer_info .=  	                 ListOption_return($link_id,'evatcodefile','evatcde',$xevatcde,'');
			$xhtml_customer_info .=  	                  "
						                        </select>
						                    </td>
						                </tr>";
							        }

			$xhtml_customer_info .= "	        <tr>
								            <td>&nbsp;</td>
								            <td>&nbsp;</td>
								            <td>&nbsp;</td>
								            <td>
								                <input type=\"checkbox\" name=\"txtinfo[chk_inactive]\" id=\"chk_inactive\" autocomplete=\"off\"/>
								                <label class=\"\">Inactive</label>
								            </td>
								        </tr>

								        <tr>
								            <td>&nbsp;</td>
								            <td>&nbsp;</td>
								            <td>&nbsp;</td>
								            <td>
								                <input type=\"checkbox\" name=\"txtinfo[chk_linksup]\" id=\"chk_linksup\"  $chk_linksup autocomplete=\"off\"/>
								                <label class=\"\">Customer is also Supplier</label>
								            </td>
								        </tr>
							    </table>";	
		$xhtml_customer_info .= "</div>";
		return $xhtml_customer_info;
	}

	function customer_ship()
	{	
		include GLOBAL_VARIABLES_PATH;
		$xhtml_ship_info = "";
		$xhtml_ship_info .= "<div id=\"div_customer_ship\" style=\"overflow: auto;height: 100%\">";
			$xhtml_ship_info .="<table>";
				$xhtml_ship_info .="<tr>";
					$xhtml_ship_info .="<td colspan = \"5\" align = \"center\"><input type =\"button\" id=\"add_cus_ship\" value = \"ADD\" class =\"save\"></td>";
				$xhtml_ship_info .="</tr>";	
			$xhtml_ship_info .="</table>";
			$xhtml_ship_info .="<div = \"div_shit_tbl\" style=\"height: 75%;overflow-y: scroll;\">";
				$xhtml_ship_info .= "<table border=\"1\" align=\"center\" style=\"width : 100%;\" cellpadding=\"2\" cellspacing=\"0\" class=\"hoverTable\" id=\"tbl_cus_ship\">";
					$xhtml_ship_info .= "<thead>";
						$xhtml_ship_info .= "<tr>";
							$xhtml_ship_info .= "<th> Ship To </th>";
							$xhtml_ship_info .= "<th> Ship To 2 </th>";
							$xhtml_ship_info .= "<th> Action </th>";
						$xhtml_ship_info .= "</tr>";
					$xhtml_ship_info .= "</thead>";
					$xhtml_ship_info .= "<tbody>";
					$xhtml_ship_info .= "</tbody>";
				$xhtml_ship_info .= "</table>";
			$xhtml_ship_info .= "</div>";
		$xhtml_ship_info .= "</div>";
		return $xhtml_ship_info;
	}
	function div_other_info($xtrncde)
	{
		include GLOBAL_VARIABLES_PATH;
		$xhtml_other_info = "";
		$xhtml_other_info .= "<div id=\"div_other_info\" style=\"overflow: auto;height: 100%\">";
		$xhtml_other_info .=	"<div style=\"width:90%;margin:0 auto\">";
			$xhtml_other_info .=	"<table border=0 width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" style=\"float:left\">";
					                    if($xrs_sys2['cusdefinfo']==1)
					                    {
					                        $xqry = "SELECT * FROM sysfielddef where trncde=? ORDER BY fieldname limit 0,10";
					                        $xstmt = $link_id->prepare($xqry);
					                        $xstmt->execute(array($xtrncde));
					                        while($xrs = $xstmt->fetch())
					                        {   
					                            if($xrs['activate']==1)
					                            {	
					                            	$xhtml_other_info .= "
    						                                            <tr>
    						                                                <td>".$xrs['caption']."</td>
    						                                                <td><input type='text' name='txt_".strtolower($xrs['fieldname'])."'' id='txt_".strtolower($xrs['fieldname'])."'' class='input' style='width:100%''></td>
    						                                            </tr>
    						                                            ";
					                            }
					                        }
					                    }
			$xhtml_other_info .= " 	</table>";
			$xhtml_other_info .=	"<table border=0 width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" style=\"float:rigth\">";
					                    if($xrs_sys2['cusdefinfo']==1)
					                    {
					                        $xqry = "SELECT * FROM sysfielddef where trncde=? ORDER BY fieldname limit 10,10";
					                        $xstmt = $link_id->prepare($xqry);
					                        $xstmt->execute(array($xtrncde));
					                        while($xrs = $xstmt->fetch())
					                        {   
					                            if($xrs['activate']==1)
					                            {	
					                            	$xhtml_other_info .= "
    						                                            <tr>
    						                                                <td>".$xrs['caption']."</td>
    						                                                <td><input type='text' name='txt_".strtolower($xrs['fieldname'])."'' id='txt_".strtolower($xrs['fieldname'])."'' class='input' style='width:100%''></td>
    						                                            </tr>
    						                                            ";
					                            }
					                        }
					                    }
			$xhtml_other_info .= " 	</table>";

		$xhtml_other_info .= "</div>";
		return $xhtml_other_info;
	}
	function get_suppliermasterfile_content()
	{
		include GLOBAL_VARIABLES_PATH;
		$xhtml_content .= "<div class = \"cla_buttons\" style = \"border-bottom: 1px solid #d4d4d4;margin: 10;\">";
			$xhtml_content .= "<input type= \"button\" class =\"save class_srch_save_sup\" value =\"Save\">";
		$xhtml_content .= "</div>";
		$xhtml_content  .= "<div id = \"div_supplier\" class ='module width_full' style=\"overflow: hidden;height: 90%;margin-top:0 !important\">";
			$xhtml_content .= "<div id = \"div_supplier_info\">";
				$xhtml_content .= "	<ul>
					    				<li><a href=\"#div_supplier_info1\">Supplier Info</a></li>";
					    			if($xrs_sys2['supdefinfo']=='1')
					    			{
				$xhtml_content .= "		<li><a href=\"#div_other_info\">Other Info</a></li>";
					    			}
				$xhtml_content .=  "</ul>";
				$xhtml_content .=	supplier_info();
				$xhtml_content .=	$xrs_sys2['supdefinfo']=='1' ? div_other_info('SUP') : "" ;	  				
			$xhtml_content .= "
									<input type=\"hidden\" id=\"par_purtax\" value=\"".$xrs_sys2['purtax']."\"/>
    								<input type=\"hidden\" id=\"par_chkglsup\" value=\"".$xrs_sys2['chkglsup']."\"/>";
			$xhtml_content .= "</div>";
			$xhtml_content .="
			    <script type=\"text/javascript\">
			    	jQuery(\".srh_actcde\").searchable(
			    	        {
			    	            modalTitle:\"Search Account\",
			    	            modalWidth:1050,
			    	            modalHeight:500,
			    	            table:\"accountsfile \",
			    	            tableCol:\"actcde,actdsc\",
			    	            tableColHeader:\"Account Code,Account Desc.\",
			    	            link_id:\"link_id\",
			    	            searchCol:\"actcde\",
			    	            searchByDesc:[\"Account Code.\",\"Account Desc.\"],
			    	            searchByValue:[\"actcde\",\"actdsc\"],
			    	            sqlfilter:\"AND allowentry = 'Y'\",
			    	            passValueTo:[\"\"],
			    	            passValue:[\"\"],
			    	            orderBy:\"actdsc\"    
			    	        });
			    	        jQuery(\".srh_gldepcde\").searchable(
			    	        {
			    	            modalTitle:\"Search Department\",
			    	            modalWidth:1050,
			    	            modalHeight:500,
			    	            table:\"gldepartmentfile \",
			    	            tableCol:\"gldepcde,gldepdsc\",
			    	            tableColHeader:\"Department Code,Department Desc.\",
			    	            link_id:\"link_id\",
			    	            searchCol:\"gldepcde\",
			    	            searchByDesc:[\"Department Code.\",\"Department Desc.\"],
			    	            searchByValue:[\"gldepcde\",\"gldepdsc\"],
			    	            sqlfilter:\"\",
			    	            passValueTo:[\"\"],
			    	            passValue:[\"\"],
			    	            orderBy:'gldepdsc'    
			    	        });
			    	        if(jQuery(\"#rdiocustyp\").is(\":checked\"))
			    	        {
			    	            chk_trade(\"trade\");
			    	        }
			    	        else
			    	        {
			    	            chk_trade(\"nontrade\");
			    	        }

			    	        if(jQuery(\"#par_chkglsup\").val() != 1)
			    	        {
			    	            jQuery(\".hide_actcde\").css(\"visibility\",\"hidden\");
			    	        }
			    	        function chk_trade(xtype)
			    	        {   
			    	            xpar_purtax = jQuery(\"#par_purtax\").val();
			    	            if( xtype == \"trade\" && xpar_purtax == \"ITEM\" )
			    	            {
			    	                jQuery(\".cla_tax\").hide();
			    	            }
			    	            else
			    	            {
			    	                jQuery(\".cla_tax\").show();   
			    	            }
			    	        }
			    	jQuery(\"#div_supplier_info\").tabs();
			    </script>
			    ";

		$xhtml_content .= "</div>";
		return $xhtml_content;
	}
	function supplier_info()
	{	
		include GLOBAL_VARIABLES_PATH;
		$xarr_table_dt = array('supplierfile');
		require_once('../../main/datatype_include.php');
		$xbrhhide = $xrs_sys['multibranch'] == 1 ? '' : 'hidden';
		$xgrphide = $xrs_sys2['supgrpapp'] == 'Y' ? '' : 'hidden';
		$xcurcde  = $xrs_sys2['basecur'];
		$chk_linksup = $xrs_sys2['deflinkcus'] == 1 ? "CHECKED" : '';
		$cla_required2prc = $xrs_sys2['prccdelock'] == 1 ? "required2" : "";
		if($xrs_sys2['chkglsup'] == 1)
		{
			$xapactcde  = $xrs_sys2['supactcde'];
			$xadvactcde = $xrs_sys2['supactcde2'];
		}
		$xcur_hide = $xrs_sys['multicur'] != 1 ? 'hidden' : '';
		$xsuprchide = $xrs_sys2['supprc'] == 1 ? '' : 'hidden';
		$xhtml_supplier_info = "";
		$xhtml_supplier_info .= "	<div id = \"div_supplier_info1\" style=\"overflow: auto;height: 90%\">";
			$xhtml_supplier_info .="	<table border=\"0\" style = \"width:90%\" cellpadding=\"0\" cellspacing=\"4\" >
						    		        <tr>
						    		            <td width=\"20.5%\"></td>
						    		            <td width=\"28.5%\"></td>
						    		            <td width=\"20.5%\"></td>
						    		            <td width=\"28.5%\"></td>
						    		        </tr>
						    		        <tr hidden>
						    		            <td>Supplier Code : </td>
						    		            <td>
						    		                <input type=\"text\" class=\"input required2\" name=\"txtsupcde\" id=\"txtsupcde\" class=\"validateinput\" autocomplete=\"off\" size=\"60\"".
						    		                ${"xattr_maxlen_" . $xarr_table_dt[0] }['supcde']."
						    		                />
						    		            </td>
						    		            <td>
						    		                <input type=\"hidden\" name=\"lbl_supcde\" id=\"lbl_supcde\" style=\"color: red;border:0;background: transparent;\" readonly>

						    		            </td>
						    		        </tr>
						    		        <tr>
						    		            <td>Supplier Name : </td>
						    		            <td colspan=\"3\">
						    		                <input type=\"text\" class=\"input required2\" name=\"txtsupdsc\" id=\"txtsupdsc\" class=\"validateinput\" autocomplete=\"off\"size=\"71\"
						    		                ".${"xattr_maxlen_" . $xarr_table_dt[0] }['supdsc']."
						    		                />
						    		                <input type=\"hidden\" name=\"lbl_supdsc\" id=\"lbl_supdsc\" style=\"color: red;border:0;background: transparent;\" readonly>
						    		            </td>
						    		        </tr>
						    		        <tr>
						    		            <td>Address 1 :</td>
						    		            <td colspan=\"3\">
						    		                <input type=\"text\" class=\"input\" name=\"txtcusadd1\" id=\"txtcusadd1\" size=\"71\"
						    		                 ".${"xattr_maxlen_" . $xarr_table_dt[0] }['supadd1']."
						    		                />
						    		            </td>
						    		        </tr>
							    		    <tr>
							    		        <td>Address 2 :</td>
							    		        <td colspan=\"3\">
							    		            <input type=\"text\" class=\"input\" name=\"txtcusadd2\" id=\"txtcusadd2\"  size=\"71\"
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['supadd2']."
							    		            />
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td>Office Tel. No. :</td>
							    		        <td>
							    		            <input type=\"text\" class=\"input\" name=\"txtofficetelno\" id=\"txtofficetelno\" value=\"$xtelno\" 
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['telno']."
							    		            />
							    		        </td>
							    		        <td>Zip Code :</td>
							    		        <td>
							    		            <input type=\"text\" class=\"input\" name=\"txtzipcode\" id=\"txtzipcode\"
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['zipcode']."
							    		            />
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td>Contact Person :</td>
							    		        <td colspan=\"3\">
							    		            <input type=\"text\" class=\"input\" name=\"txtconper\" id=\"txtconper\" size=\"71\"
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['conper']."
							    		            />
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td>Remarks :</td>
							    		        <td colspan=\"3\">
							    		            <input type=\"text\" name=\"txtremarks\" class=\"input\" id=\"txtremarks\" size=\"71\"
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['remark']."
							    		            />
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td>Terms :</td>
							    		        <td>
							    		            <select name=\"txttrmcde\" id=\"txttrmcde\" class=\"input required2\" style=\"width:100%\">
							    		                <option></option>
							    		                ".ListOption_return2_dsc($link_id,'termfile','trmcde','trmdsc',$xtrmcde,'')."
							    		                </select>
							    		        </td>
							    		        <td>TIN :</td>
							    		        <td>
							    		            <input type=\"text\" class=\"input\" name=\"txttin\" id=\"txttin\" />
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td $xcur_hide>Currency :</td>
							    		        <td $xcur_hide>
							    		            <select name=\"txtcurcde\" id=\"txtcurcde\" class=\"input\">";
							    		                
						    		                    if($xrs_sys['multicur'] == 1)
						    		                    {
						    		        				$xhtml_supplier_info .= 
						    		        				ListOption_return($link_id,'currencyfile','curcde',$xcurcde,'');
						    		                    }
						    		                    else 
						    		                    {
						    		        				$xhtml_supplier_info .= "
						    		        				<option>".$xrs_sys2['basecur']."</option>
						    		        				";
						    		                    }
													$xhtml_supplier_info .="
													</select>
							    		        </td>
							    		        <td>Fax No. :</td>
							    		        <td>
							    		            <input type=\"text\" class=\"input\" name=\"txtfaxno\" id=\"txtfaxno\"  value=\"$xfaxno\"
							    		            ".${"xattr_maxlen_" . $xarr_table_dt[0] }['faxno']."
							    		             />
							    		        </td>
							    		    </tr>   
							    		    <tr>
							    		        <td>Business Type :</td>
							    		        <td>
							    		             <input type=\"text\" class=\"input\" name=\"txtbus_style\" id=\"txtfaxno\" value=\"$xbusstyle\" 
							    		             ".${"xattr_maxlen_" . $xarr_table_dt[0] }['busstyle']."
							    		             />
							    		        </td>
							    		        <td $xgrphide>Payment Group :</td>
							    		        <td $xgrphide>";
							    		             
							    		            if($xpaygrpdis != '') 
							    		            {
							    		            	$xhtml_supplier_info .= "
							    		            	<input type = \"text\" name=\"txtsupgrpcde\" id=\"txtsupgrpcde\" class=\"input disabled\" style=\"width:100%\" readonly>";
							    		            }
							    		            else 
							    		            {
							    		                $xhtml_supplier_info .= "
							    		                <select name=\"txtsupgrpcde\" id=\"txtsupgrpcde\" class=\"input\" style=\"width:100%\">
							    		                            <option></option>
							    		                            ".ListOption_return($link_id,"suppliergroupfile".$xfilter_pager_fields,"supgrpdsc",$xsupgrpcde,"")."
							    		              	</select>";            
							    		            }
						$xhtml_supplier_info .="</td>
							    		    </tr>
							    		    <tr>
							    		        <td class=\"hide_actcde\">A/P Account</td>
							    		        <td class=\"hide_actcde\">
							    		            <input type=\"text\" name=\"txtactcde\" id=\"txtactcde\" class = \"input srh_actcde\" readonly value=\"$xapactcde\">
							    		        </td>
							    		        <td $xsuprchide >Supplier Price List :</td>
							    		        <td $xsuprchide>
							    		            <select name=\"txtsupprcde\" id=\"txtsupprcde\" class=\"input\" style=\"width:100%\">
							    		                <option></option>
							    		                    ".ListOption_return($link_id,'supplierpricefile1'.$xfilter_pager_fields,'supprccde',$xsupprccde,'')."
							    		            </select>
							    		        </td>
							    		    </tr>
							    		    <tr>
							    		        <td class=\"hide_actcde\">Advances Account </td>
							    		        <td class=\"hide_actcde\">
							    		            <input type=\"text\" name=\"txtadvactcde\" id=\"txtadvactcde\" class = \"input srh_actcde\" readonly value=\"$xadvactcde\">
							    		        </td>
							    		        <td colspan=\"2\">
							    		            <input type=\"radio\" name=\"rdiocustyp\" id=\"rdiocustyp\" value=\"chkpri\" onclick=\"chk_trade(\"trade\")\" $xchkpri checked/>&nbsp;Trade
							    		            <input type=\"radio\" name=\"rdiocustyp\" id=\"rdiocustyp2\" value=\"chkgov\" onclick=\"chk_trade(\"nontrade\")\" $xchkgov/>&nbsp;Non-Trade
							    		        </td> 
							    		    </tr>
							    		    <tr>
							    		        <td class=\"hide_actcde\">GL Dept.</td>
							    		        <td class=\"hide_actcde\">
							    		            <input type=\"text\" name=\"txtgldepcde\" id=\"txtgldepcde\" class = \"input srh_gldepcde\" readonly value=\"$xgldepcde\">
							    		        </td>
							    		        <td>
							    		            <input type=\"checkbox\" $xchk_nonvat; name=\"chk_nonvat\" id=\"chk_nonvat\" >Non-VAT
							    		        </td>
							    		    </tr>
							    		    <tr class=\"cla_tax\">
							    		        <td>VAT :</td>
							    		        <td>
							    		            <select name=\"txtvatcde\" id=\"txtvatcde\" class=\"input\" style=\"width:100%\">
							    		                <option></option>
							    		                    ".ListOption_return($link_id,'taxcodefile','taxcde',$xtaxcde,'')."
							    		            </select>
							    		        </td>
							    		        
							    		    </tr>
						    		        <tr class=\"cla_tax\">
						    		            <td>EWT :</td>
						    		            <td>
						    		                <select name=\"txtewtcde\" id=\"txtewtcde\" class=\"input\" style=\"width:100%\">
						    		                    <option></option>
						    		                        ".ListOption_return($link_id,'ewtcodefile','ewtcde',$xewtcde,'')."
						    		                </select>
						    		            </td>
						    		        </tr>
						    		        <tr class=\"cla_tax\">
						    		            <td>EVAT :</td>
						    		            <td>
						    		                <select name=\"txtevatcde\" id=\"txtevatcde\" class=\"input\" style=\"width:100%\">
						    		                    <option></option>
						    		                        ".ListOption_return($link_id,'evatcodefile','evatcde',$xevatcde,'')."
						    		                </select>
						    		            </td>
						    		        </tr>
						    		        <tr $xbrhhide>
						    		            <td>Branch :</td>
						    		            <td>
						    		                <input type = \"text\" name=\"txtinfo[txtbrhcde]\" id=\"txtbrhcde\" readonly class=\"input\" style=\"width:100%\" value=\"$xbrh\">
						    		            </td>
						    		        </tr>
						    		        <tr>
						    		            <td>&nbsp;</td>
						    		            <td>&nbsp;</td>
						    		            <td>&nbsp;</td>
						    		            <td>    
						    		                <input type=\"checkbox\" name=\"chk_linkcus\" id=\"chk_linkcus\"  $chk_linkcus; autocomplete=\"off\"/>&nbsp;&nbsp;Supplier is also a Customer
						    		            </td>
						    		        </tr>
						    		        <tr>
						    		            <td>&nbsp;</td>
						    		            <td>&nbsp;</td>
						    		            <td>&nbsp;</td>
						    		            <td>
						    		                <input type=\"checkbox\" name=\"chk_inactive\" id=\"chk_inactive\"  $chk_inactive; />&nbsp;&nbsp;Inactive
						    		            </td>
						    		        </tr>
						    		    </table>";
		$xhtml_supplier_info .= "	</div>";

		return $xhtml_supplier_info;
	}
?>
