//check_greaterdate( greaterdate , smallerdate )
function check_greaterdate( xcurdte , xdeldte )
{	
	var xmsg = false;
	var separators = '-';
	var currentElement = "";
	var includeEmpties;
	var fixedExplode1 = new Array(1);
	var fixedExplode2= new Array(1);
	var count=0;
	separators = new String(separators)
	
	/*var brokenstring=xdeldte.split(” “);
	alert(brokenstring);
	return false;*/
	
	separators = " :-:/";
	//current date
	
	for(x=0; x < xcurdte.length; x++) 
	{
		char = xcurdte.charAt(x);
		if(separators.indexOf(char) != -1) 
		{
			if ( ( (includeEmpties <= 0) || (includeEmpties == false)) && (currentElement == "")) { } 
			else 
			{
				fixedExplode1[count] = currentElement;
				
				count++;
				currentElement = ""; 
			} 
		}
		else 
		{
			currentElement += char; 
		}
	}
	fixedExplode1[count] = currentElement;

	currentElement = '';
	
	//delivery date
	count=0;
	for(x=0; x < xdeldte.length; x++) 
	{
		char = xdeldte.charAt(x);
		if(separators.indexOf(char) != -1) 
		{
			if ( ( (includeEmpties <= 0) || (includeEmpties == false)) && (currentElement == "")) { } 
			else 
			{
				fixedExplode2[count] = currentElement;
				count++;
				currentElement = ""; 
			} 
		}
		else 
		{ 
			currentElement += char; 
		}
	}
	fixedExplode2[count] = currentElement;		
	// alert(fixedExplode1[2]); //year
	// alert(fixedExplode1[1]); //month
	// alert(fixedExplode1[0]); //day
	if( parseInt(fixedExplode1[2],'10') < parseInt(fixedExplode2[2],'10') )
	{
		xmsg = false;
		
	}
	else if( parseInt(fixedExplode1[2],'10') == parseInt(fixedExplode2[2],'10') )
	{
		
		
		if( parseInt(fixedExplode1[0],'10') < parseInt(fixedExplode2[0],'10') )
		{
			xmsg = false;
		}
		else if( parseInt(fixedExplode1[0],'10') == parseInt(fixedExplode2[0],'10') )
		{
			if( parseInt(fixedExplode1[1],'10') < parseInt(fixedExplode2[1],'10') )
			{
				
				xmsg = false;
			}
			else if( parseInt(fixedExplode1[1],'10') ==  parseInt(fixedExplode2[1],'10') )
			{
				xmsg = false;
			}
			else
			{
				xmsg = true;
			}
		}
		else
		{
			xmsg = true;
		}
	}
	else
	{
		xmsg = true;
	}
	return xmsg;
}