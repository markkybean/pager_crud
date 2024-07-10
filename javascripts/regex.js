/**
*** used of names
*** alphabet + dash(-) + period(.)
**/
function isNameKey(evt)
{
	
	if(evt.keyCode!=8 && evt.keyCode!=32)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		//var regex = /[0-9]|\./;
		var regex = /[- . +a-zA-Z]/;

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;	
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}

	
}


function isNumberKeyWithPeriodSlash(evt)
{
	if(evt.keyCode!=8)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\.|\//;
		// var regex = /[0-9]/; 

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}


function isNumberKeyWithPeriodComma(evt)
{
	if(evt.keyCode!=8)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		// var regex = /[0-9]|\./;
		// var regex = /[0-9]/; 
		var regex = /[0-9]|\.|\,/;

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}
/**
*** number only
**/
function isNumberKey(evt)
{

	if(evt.keyCode!=8)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		//var regex = /[0-9]|\./;
		var regex = /[0-9]/;

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}

/**
*** number + period(.)
**/
function isNumberKeyWithPeriod(evt)
{		
	
	if(evt.keyCode!=8)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /[0-9]|\./;
		// var regex = /[0-9]/; 

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}

function isCharacterKey(evt)
{
	
	if(evt.keyCode!=8 && evt.keyCode!=32)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		//var regex = /[0-9]|\./;
		var regex = /[, . a-zA-Z]/;

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;	
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}


/**
*** number + minus sign
**/
function isContactKey(evt)
{
	if(evt.keyCode!=8)
	{
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		//var regex = /[0-9]|\./;
		var regex = /[0-9]|\+|\(|\)|\-/;

		if( !regex.test(key) )
		{
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	}
}

/**
*** validation if the inputed value is a valid email
**/
function isEmailKey(id) {
		
	var x = $('#'+id).val();
	var atpos = x.indexOf("@");
	var dotpos = x.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
		alert("Not a valid e-mail address");
		$('#'+id).val('');
	}
}







