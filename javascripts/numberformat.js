function number_format (xnumber, decimal) 
{
	//decimal  - the number of decimals after the digit from 0 to 3
	//-- Returns the passed number as a string in the xxx,xxx.xx format.
	//anynum=eval(obj.value);
 	anynum=xnumber;
 	//alert("anynum:"+anynum)
           
   divider =10;
   switch(decimal){
		case 0:
			divider =1;
			break;
		case 1:
			divider =10;
			break;
		case 2:
			divider =100;
			break;
		default:  	 //for 3 decimal places
			divider =1000;
	}

	   workNum=Math.abs((Math.round(anynum*divider)/divider));

	   workStr=""+workNum

	   if (workStr.indexOf(".")==-1){workStr+="."}

	   dStr=workStr.substr(0,workStr.indexOf("."));dNum=dStr-0
	   pStr=workStr.substr(workStr.indexOf("."))

	   while (pStr.length-1 < decimal){pStr+="0"}

	   if(pStr =='.') pStr ='';

	   //--- Adds a comma in the thousands place.    
	   if (dNum>=1000) {
		  dLen=dStr.length
		  dStr=parseInt(""+(dNum/1000))+","+dStr.substring(dLen-3,dLen)
	   }

	   //-- Adds a comma in the millions place.
	   if (dNum>=1000000) {
		  dLen=dStr.length
		  dStr=parseInt(""+(dNum/1000000))+","+dStr.substring(dLen-7,dLen)
	   }
	   retval = dStr + pStr
	   //-- Put numbers in parentheses if negative.
	   if (anynum<0) {retval="("+retval+")";}

	  
	//You could include a dollar sign in the return value.
	  //retval =  "P "+retval
	  //alert("retval:"+retval);
	  return retval;
 }


//Usage: Usage: <input type=text onChange='format(this,2)'> 