<?php
    
    require_once('include/JSON.php');
    require_once('db_config.php');

    if ($_POST["event_action"]=="get_addform"){
        $xretobj["form"] = "";

        $xretobj["form"] .= '
            <form id= "form_data" autocomplete="off">
            <div class="form-group row">
                <label for="txt_fullname" class="col-sm-4 col-form-label">Full Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[fullname]" id="txt_fullname" placeholder="Full Name">
                </div>
            </div>
            <div class="form-group row">
                <label for="txt_address" class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[address]" id="txt_address" placeholder="Address">
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_birthdate" class="col-sm-4 col-form-label ">Birth Day</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control birthdate" name="txtfld[birthdate]" id="txt_birthdate">
                    <script>
								jQuery(".birthdate").datepicker({dateFormat:"yy-mm-dd"});
                    </script>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="rdo_gender" class="col-sm-4 col-form-label ">Gender</label>
                <div class="col-sm-5">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_male" value="Male" checked>
                        <label class="form-check-label" for="rdo_male">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_female" value="Female">
                        <label class="form-check-label" for="rdo_female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_civilstat" class="col-sm-4 col-form-label">Civil Status</label>
                <div class="col-sm-8">
                <select class="form-control" id="cbo_civilstat" name="txtfld[civilstat]">
                    <option>Single</option>
                    <option>Married</option>
                </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_contactnum" class="col-sm-4 col-form-label">Contact Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[contactnum]" id="txt_contactnum" placeholder="Contact Number">
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_salary" class="col-sm-4 col-form-label">Salary</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="txtfld[salary]" id="txt_salary">
                </div>
            </div>
            <div class="form-group row">
                <label for="chk_active" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <input  type="checkbox" name="txtfld[isactive]" id="chk_inactive">
                    <label for="chk_inactive">
                        Active
                    </label>
                </div>
            </div>
            </form>
            
        ';
    }

    if ($_POST["event_action"]=="get_editform"){
        
        $xqry = "SELECT * FROM employeefile WHERE recid=?";
        $xstmt=$link_id->prepare($xqry);
        $xstmt->execute(array($_POST["recid"]));
        $xrs=$xstmt->fetch(PDO::FETCH_ASSOC);

        $xretobj["form"] = "";

        $xchecked_male = "";
        $xchecked_female = "";
        if (strtolower($xrs["gender"]) == "male"){
            $xchecked_male = "checked";
        }
        else if (strtolower($xrs["gender"]) == "female"){
            $xchecked_female = "checked";
        }
        $xselected_single = "";
        $xselected_married = "";
        if ($xrs["civilstat"] == "Single"){
            $xselected_single = "selected";
        }
        else if ($xrs["civilstat"] == "Married"){
            $xselected_married = "selected";
        }

        $xcheched = "";
        if ($xrs["isactive"] == 1){
            $xcheched = "checked";
        }
       
        $xretobj["form"] .= '
            <form id= "form_data" autocomplete="off">
            <div class="form-group row">
                <label for="txt_fullname" class="col-sm-4 col-form-label">Full Name</label>
                <div class="col-sm-8">
                <input type="hidden" class="form-control" name="txtfld[recid]" id="txt_recid" value="'.$xrs["recid"].'">
                    <input type="text" class="form-control" name="txtfld[fullname]" id="txt_fullname" value="'.$xrs["fullname"].'">
                </div>
            </div>
            <div class="form-group row">
                <label for="txt_address" class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[address]" id="txt_address" value="'.$xrs["address"].'">
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_birthdate" class="col-sm-4 col-form-label ">Birth Day</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control birthdate" name="txtfld[birthdate]" id="txt_birthdate" value="'.$xrs["birthdate"].'">
                    <script>
								jQuery(".birthdate").datepicker({dateFormat:"yy-mm-dd"});
                    </script>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="rdo_gender" class="col-sm-4 col-form-label ">Gender</label>
                <div class="col-sm-5">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_male" value="Male" '.$xchecked_male.'>
                        <label class="form-check-label" for="rdo_male">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_female" value="Female" '.$xchecked_female.'>
                        <label class="form-check-label" for="rdo_female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_civilstat" class="col-sm-4 col-form-label">Civil Status</label>
                <div class="col-sm-8">
                <select class="form-control" id="cbo_civilstat" name="txtfld[civilstat]">
                    <option '.$xselected_single.'>Single</option>
                    <option '.$xselected_married.'>Married</option>
                </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_contactnum" class="col-sm-4 col-form-label">Contact Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[contactnum]" id="txt_contactnum"  value="'.$xrs["contactnum"].'">
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_salary" class="col-sm-4 col-form-label">Salary</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="txtfld[salary]" id="txt_salary" value="'.$xrs["salary"].'">
                </div>
            </div>
            <div class="form-group row">
                <label for="chk_active" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <input  type="checkbox" name="txtfld[isactive]" id="chk_inactive" '.$xcheched.'>
                    <label for="chk_inactive">
                        Active
                    </label>
                </div>
            </div>
            </form>
            
        ';
    }
    if ($_POST["event_action"]=="get_viewform"){
        
        $xqry = "SELECT * FROM employeefile WHERE recid=?";
        $xstmt=$link_id->prepare($xqry);
        $xstmt->execute(array($_POST["recid"]));
        $xrs=$xstmt->fetch(PDO::FETCH_ASSOC);

        $xretobj["form"] = "";

        $xchecked_male = "";
        $xchecked_female = "";
        if (strtolower($xrs["gender"]) == "male"){
            $xchecked_male = "checked";
        }
        else if (strtolower($xrs["gender"]) == "female"){
            $xchecked_female = "checked";
        }
        $xselected_single = "";
        $xselected_married = "";
        if ($xrs["civilstat"] == "Single"){
            $xselected_single = "selected";
        }
        else if ($xrs["civilstat"] == "Married"){
            $xselected_married = "selected";
        }

        $xcheched = "";
        if ($xrs["isactive"] == 1){
            $xcheched = "checked";
        }
       
        $xretobj["form"] .= '
            <form id= "form_data" autocomplete="off">
            <div class="form-group row">
                <label for="txt_fullname" class="col-sm-4 col-form-label">Full Name</label>
                <div class="col-sm-8">
                <input type="hidden" class="form-control" name="txtfld[recid]" id="txt_recid" value="'.$xrs["recid"].'" readonly disabled>
                    <input type="text" class="form-control" name="txtfld[fullname]" id="txt_fullname" value="'.$xrs["fullname"].'"  readonly disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="txt_address" class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[address]" id="txt_address" value="'.$xrs["address"].'"  readonly disabled>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_birthdate" class="col-sm-4 col-form-label ">Birth Day</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control birthdate" name="txtfld[birthdate]" id="txt_birthdate" value="'.$xrs["birthdate"].'" readonly disabled>
                    <script>
								jQuery(".birthdate").datepicker({dateFormat:"yy-mm-dd"});
                    </script>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="rdo_gender" class="col-sm-4 col-form-label ">Gender</label>
                <div class="col-sm-5">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_male" value="Male" '.$xchecked_male.' readonly disabled>
                        <label class="form-check-label" for="rdo_male">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_female" value="Female" '.$xchecked_female.' readonly disabled>
                        <label class="form-check-label" for="rdo_female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_civilstat" class="col-sm-4 col-form-label">Civil Status</label>
                <div class="col-sm-8">
                <select class="form-control" id="cbo_civilstat" name="txtfld[civilstat]" readonly disabled>
                    <option '.$xselected_single.'>Single</option>
                    <option '.$xselected_married.'>Married</option>
                </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_contactnum" class="col-sm-4 col-form-label">Contact Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="txtfld[contactnum]" id="txt_contactnum"  value="'.$xrs["contactnum"].'"  readonly disabled>
                </div>
            </div>

            <div class="form-group row">
                <label for="txt_salary" class="col-sm-4 col-form-label">Salary</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="txtfld[salary]" id="txt_salary" value="'.$xrs["salary"].'"  readonly disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="chk_active" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <input  type="checkbox" name="txtfld[isactive]" id="chk_inactive" '.$xcheched.' readonly disabled>
                    <label for="chk_inactive">
                        Active
                    </label>
                </div>
            </div>
            </form>
            
        ';
    }

    if ($_POST["event_action"]=="save_transaction"){

        $xparams = $_POST["txtfld"];
       
        $xarr_params["fullname"] = $xparams["fullname"];
        $xarr_params["address"] = $xparams["address"];
        $xarr_params["birthdate"] = date_format(date_create($xparams["birthdate"]), "Y-m-d");
        $xarr_params["gender"] = $xparams["gender"];

        $xcurdate = date("Y-m-d");
        $xage = date_diff(date_create($xarr_params["birthdate"]), date_create($xcurdate));
        $xarr_params["age"] = (int)$xage->format("%y");
        $xarr_params["civilstat"] = $xparams["civilstat"];
        $xarr_params["contactnum"] = $xparams["contactnum"];
        $xarr_params["salary"] = floatval($xparams["salary"]);
        $xarr_params["isactive"] = isset($xparams["isactive"]) ? 1 : 0;
        
        $xsuccess = PDO_InsertRecord($link_id, "employeefile", $xarr_params, false);
		
        if ($xsuccess){
            $xretobj["success"] = true;
            $xretobj["msg"] = "Successfully added";
        }
        else{
            $xretobj["success"] = false;
            $xretobj["msg"] = "Something went wrong";
        }
        
        
    }

    if ($_POST["event_action"]=="edit_transaction"){

        $xparams = $_POST["txtfld"];
        $xarr_params["fullname"] = $xparams["fullname"];
        $xarr_params["address"] = $xparams["address"];
        $xarr_params["birthdate"] = date_format(date_create($xparams["birthdate"]), "Y-m-d");
        $xarr_params["gender"] = $xparams["gender"];

        $xcurdate = date("Y-m-d");
        $xage = date_diff(date_create($xarr_params["birthdate"]), date_create($xcurdate));
        $xarr_params["age"] = (int)$xage->format("%y");
        $xarr_params["civilstat"] = $xparams["civilstat"];
        $xarr_params["contactnum"] = $xparams["contactnum"];
        $xarr_params["salary"] = floatval($xparams["salary"]);
        $xarr_params["isactive"] = isset($xparams["isactive"]) ? 1 : 0;
        
        $xsuccess = PDO_UpdateRecord($link_id, "employeefile", $xarr_params,"recid=?",array($xparams["recid"]), false);
		
        if ($xsuccess){
            $xretobj["success"] = true;
            $xretobj["msg"] = "Successfully edited";
        }
        else{
            $xretobj["success"] = false;
            $xretobj["msg"] = "Something went wrong";
        }
    }

    if ($_POST["event_action"] == "delete_emp"){
        $xqry = "DELETE FROM employeefile WHERE recid=?";
        $xstmt=$link_id->prepare($xqry);
        $xstmt->execute(array($_POST["recid"]));

        $xretobj["success"] = true;
        $xretobj["msg"] = "Successfully deleted";
    }

    $json = new Services_JSON();
	echo $json->encode($xretobj);

?>