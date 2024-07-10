function checkdate(objname,option) 
{
    var datefield = objname;
    /*
    alert(datefield.name)
    datefield.select();
    document.forms.myForm.txtHireDate.focus();
    return false;
    */	
    if (chkdate(objname,option) == false) 
	{
    	datefield.select();
    	alert("That date is invalid.  Please try again.");
    	// datefield.focus();
    	datefield.trigger('focus');
    	return false;
    }
    else 
	{
    	return true;
    }
}

function chkdate(objname,option) 
{
    var strdatestyle	= "US"; //United States date style
    //var strdatestyle = "EU";  //European date style
    var strdate;
    var strdatearray;
    var strday;
    var strmonth;
    var stryear;
    var intday;
    var intmonth;
    var intyear;
    var boofound 		= false;
    var datefield 		= objname;
    //var strseparatorarray = new Array("-"," ","/",".");
    var strseparatorarray = new Array("-","/",".");
    var intelementnr;
    var err = 0;
    var strmontharray = new Array(12);
    /*
    strmontharray[0] = "Jan";
    strmontharray[1] = "Feb";
    strmontharray[2] = "Mar";
    strmontharray[3] = "Apr";
    strmontharray[4] = "May";
    strmontharray[5] = "Jun";
    strmontharray[6] = "Jul";
    strmontharray[7] = "Aug";
    strmontharray[8] = "Sep";
    strmontharray[9] = "Oct";
    strmontharray[10] = "Nov";
    strmontharray[11] = "Dec";
    */
    
    strmontharray[0] = "1";
    strmontharray[1] = "2";
    strmontharray[2] = "3";
    strmontharray[3] = "4";
    strmontharray[4] = "5";
    strmontharray[5] = "6";
    strmontharray[6] = "7";
    strmontharray[7] = "8";
    strmontharray[8] = "9";
    strmontharray[9] = "10";
    strmontharray[10] = "11";
    strmontharray[11] = "12";
    
    strdate = datefield.value;
    //alert("strdate.length:"+strdate.length)
    if (strdate.length < 1 ) 
    {
        //alert("option:"+option)
        if (option=="allowempty")
        { 
			return true;
		}
        else
        { 
			return false;
		}
    }
    
    for (intelementnr = 0; intelementnr < strseparatorarray.length; intelementnr++) 
    {
        if (strdate.indexOf(strseparatorarray[intelementnr]) != -1) 
        {
            strdatearray	= strdate.split(strseparatorarray[intelementnr]);
            //alert("strdatearray:"+strdatearray)
            if (strdatearray.length != 3) 
            {
                err		= 1;
                return false;
            }
            else 
            {
                strday 		= strdatearray[0];
                strmonth 	= strdatearray[1];
                stryear 	= strdatearray[2];
            }
            boofound = true;
        }
    }
    /*
    alert("strday:"+strday)
    alert("strmonth:"+strmonth)
    alert("stryear:"+stryear)
    */
    if (!strday)
    {
        //alert("undefined recognized")
        return false;
    }
    
    if (!strmonth)
    {
        //alert("undefined recognized")
        return false;
    }
    
    if (!strmonth)
    {
        //alert("undefined recognized")
        return false;
    }
    if (boofound == false) 
	{
    	if (strdate.length>5) 
		{
    		strday 		= strdate.substr(0, 2);
    		strmonth 	= strdate.substr(2, 2);
    		stryear 	= strdate.substr(4);
       }
    }
    if (stryear.length == 2) 
	{
    	stryear = '20' + stryear;
    }
    // US style
    if (strdatestyle == "US") 
	{
    	strtemp 	= strday;
    	strday 		= strmonth;
    	strmonth 	= strtemp;
    }
    intday = parseInt(strday, 10);
    if (isNaN(intday)) 
	{
    	err = 2;
    	return false;
    }
    intmonth = parseInt(strmonth, 10);
    if (isNaN(intmonth)) 
	{
    	for (i = 0;i<12;i++) 
		{
    		if (strmonth.toUpperCase() == strmontharray[i].toUpperCase()) 
			{
    			intmonth = i+1;
    			strmonth = strmontharray[i];
    			i = 12;
       		}
    	}
    	if (isNaN(intmonth)) 
		{
    		err = 3;
    		return false;
       	}
    }
    intyear = parseInt(stryear, 10);
    if (isNaN(intyear)) 
	{
    	err = 4;
    	return false;
    }
    if (intmonth>12 || intmonth<1) 
	{
    	err = 5;
    	return false;
    }
    if ((intmonth == 1 || intmonth == 3 || intmonth == 5 || intmonth == 7 || intmonth == 8 || intmonth == 10 || intmonth == 12) && (intday > 31 || intday < 1)) 
	{
    	err = 6;
    	return false;
    }
    if ((intmonth == 4 || intmonth == 6 || intmonth == 9 || intmonth == 11) && (intday > 30 || intday < 1)) 
	{
    	err = 7;
    	return false;
    }
    if (intmonth == 2) 
	{
    	if (intday < 1) 
		{
    		err = 8;
    		return false;
    	}
    	if (leapyear(intyear) == true) 
		{
    		if (intday > 29) 
			{
    			err = 9;
    			return false;
    		}
    	}
    	else 
		{
    		if (intday > 28) 
			{
    			err = 10;
    			return false;
    		}
    	}
    }
    if (strdatestyle == "US") 
	{
    	//datefield.value = strmontharray[intmonth-1] + " " + intday+" " + stryear;
    	datefield.value		= strmontharray[intmonth-1] + "-" + intday+"-" + intyear;
    }
    else 
	{
    	datefield.value 	= intday + " " + strmontharray[intmonth-1] + " " + stryear;
    }
    return true;
}

function leapyear(intyear) 
{
    if (intyear % 100 == 0) 
	{
    	if (intyear % 400 == 0) 
		{ 
			return true; 
		}
    }
    else 
	{
    	if ((intyear % 4) == 0) 
		{ 
			return true; 
		}
    }
    return false;
}

function dodatecheck(from, to) 
{
	if (Date.parse(from.value) <= Date.parse(to.value)) 
	{
    	alert("The dates are valid.");
   	}
    else 
	{
    	if (from.value == "" || to.value == "") 
    		alert("Both dates must be entered.");
   		else 
    		alert("To date must occur after the from date.");
    }
}