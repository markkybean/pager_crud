<?php
    ob_start();
    
    require_once('std_header.php');

    $xmonth         = $_POST['creditmonth'];
    $xyear          = $_POST['credityear'];
    $xpayrollgroup  = $_POST['payrollgroup'];

    //$query_periodcovered = "SELECT RecID, CONCAT(DateFrom,' to ',DateTo) as PeriodCovered FROM `payperiod` WHERE CreditMonth=$xMonth AND CreditYear=$xYear AND PayGroup='$xPayrollGroup'";
    //$query_periodcovered = "SELECT RecID, DateFrom,DateTo 
    //                          FROM `payperiod` WHERE CreditMonth=$xMonth AND CreditYear=$xYear AND PayGroup='$xPayrollGroup'";
    $xqry   = "SELECT recid, concat(datefrom,' to ', dateto) as periodcovered from payperiod 
                WHERE  creditmonth=? AND credityear=? and payrollgroup=?";
    $xstmt_tran = $link_id->prepare($xqry);
    $xstmt_tran->execute(array($xmonth, $xyear, $xpayrollgroup));
    check_qry($xstmt_tran);
    
    $rs_numrows = $xstmt_tran->rowCount();

    if ($rs_numrows==0)
    {
       // echo "<option>No Period Covered </option>";
        //echo "No Period Covered,";
         echo "";
    } 
    else 
    {
        //echo "<option>hello</option>"
        //echo ",";
        while ($field= $xstmt_tran->fetch())
        {
            //echo "<option value=\"$row_periodcovered->PeriodCovered\">$row_periodcovered->PeriodCovered</option>";
            // echo date("m-d-Y",strtotime($field["datefrom"])). " to " . date("m-d-Y",strtotime($field["dateto"])) . ",";
            echo date("Y-m-d",strtotime($field["datefrom"])). " to " . date("Y-m-d",strtotime($field["dateto"])) . ",";
            
        }
    }
    ob_end_flush();
?>
