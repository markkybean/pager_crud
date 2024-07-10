window.onload = initRegexLoader;

function initRegexLoader()
{
	console.log("-------------------------------");
	console.log("---- Regex Loader Loaded! ----");
	console.log("-------------------------------");

	var cl_amount, cl_alphanum, cl_number;
	var cl_hrs, cl_mins, cl_days;
	var date_picker;
	var cl_isNumericWithCommaPeriod;
	var cl_isNumericWithPeriodSlash;

	cl_amount = document.getElementsByClassName('regex-amt');
	cl_alphanum = document.getElementsByClassName('regex-alphanum');
	cl_alphanumcolon = document.getElementsByClassName('regex-alphanumcolon');
	cl_number = document.getElementsByClassName('regex-number');
	cl_hrs = document.getElementsByClassName('regex-hrs');
	cl_mins = document.getElementsByClassName('regex-mins');
	cl_days = document.getElementsByClassName('regex-days');
	date_picker = document.getElementsByClassName('date-picker');
	cl_isNumericWithCommaPeriod = document.getElementsByClassName('regex-numericwithcommaperiod');
	cl_isNumericWithPeriodSlash = document.getElementsByClassName('regex-numericwithperiodslash');

	cl_ischarwocomma = document.getElementsByClassName('regex-charwocomma');

	for( var i=0; i<date_picker.length; i++ )
	{
		document.getElementById(date_picker[i].id).setAttribute("readonly", "readonly");
	}

	for( var i=0; i<cl_amount.length; i++ )
	{
		document.getElementById(cl_amount[i].id).onkeypress = isNumberKeyWithPeriod;
		document.getElementById(cl_amount[i].id).setAttribute("maxlength", 18);
		document.getElementById(cl_amount[i].id).onpaste = pasteHandler;
		document.getElementById(cl_amount[i].id).ondrop = pasteHandler;
	}

	for( var i=0; i<cl_alphanum.length; i++ )
	{
		document.getElementById(cl_alphanum[i].id).onkeypress = isAlphaNumDash;
	}

	for( var i=0; i<cl_alphanumcolon.length; i++ )
	{
		document.getElementById(cl_alphanumcolon[i].id).onkeypress = isAlphaNumColon;
	}

	for( var i=0; i<cl_isNumericWithCommaPeriod.length; i++ )
	{
		document.getElementById(cl_isNumericWithCommaPeriod[i].id).onkeypress = isNumberKeyWithPeriodComma;
		document.getElementById(cl_isNumericWithCommaPeriod[i].id).onpaste = pasteHandler;
		document.getElementById(cl_isNumericWithCommaPeriod[i].id).ondrop = pasteHandler;
	}

	for( var i=0; i<cl_isNumericWithPeriodSlash.length; i++ )
	{
		document.getElementById(cl_isNumericWithPeriodSlash[i].id).onkeypress = isNumberKeyWithPeriodSlash;
		document.getElementById(cl_isNumericWithPeriodSlash[i].id).onpaste = pasteHandler;
		document.getElementById(cl_isNumericWithPeriodSlash[i].id).ondrop = pasteHandler;

	}

	for( var i=0; i<cl_number.length; i++ )
	{
		document.getElementById(cl_number[i].id).onkeypress = isNumberKey;
		document.getElementById(cl_number[i].id).onpaste = pasteHandler;
		document.getElementById(cl_number[i].id).ondrop = pasteHandler;
		
	}

	for( var i=0; i<cl_hrs.length; i++ )
	{
		document.getElementById(cl_hrs[i].id).onkeypress = isNumberKeyWithPeriod;
		document.getElementById(cl_hrs[i].id).setAttribute("maxlength", 2);
		document.getElementById(cl_hrs[i].id).onpaste = pasteHandler;
		document.getElementById(cl_hrs[i].id).ondrop = pasteHandler;
	}

	for( var i=0; i<cl_mins.length; i++ )
	{
		document.getElementById(cl_mins[i].id).onkeypress = isNumberKeyWithPeriod;
		document.getElementById(cl_mins[i].id).setAttribute("maxlength", 2);
		document.getElementById(cl_mins[i].id).onpaste = pasteHandler;
		document.getElementById(cl_mins[i].id).ondrop = pasteHandler;
	}

	for( var i=0; i<cl_days.length; i++ )
	{
		document.getElementById(cl_days[i].id).onkeypress = isNumberKeyWithPeriod;
		document.getElementById(cl_days[i].id).setAttribute("maxlength", 2);
		document.getElementById(cl_days[i].id).onpaste = pasteHandler;
		document.getElementById(cl_days[i].id).ondrop = pasteHandler;
	}

	for( var i=0; i<cl_ischarwocomma.length; i++ )
	{
		document.getElementById(cl_ischarwocomma[i].id).onkeypress = isCharacterKeywocomma;

	}

	function pasteHandler()
	{
		return false;
	}

}