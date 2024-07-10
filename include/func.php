<?php

	function is_this_date($format='mm-dd-yyyy', $value)
	{
		$date = $value;
		$bool = false;

		if( $format == 'mm-dd-yyyy' )
		{
			if(preg_match("/^(\d{2})-(\d{2})-(\d{4})$/", $date, $matches)) 
			{
				if(checkdate($matches[1], $matches[2], $matches[3]))
			   	{
			    	$bool = true;
			   	}
			}
		}

        
		if( $format == 'yyyy-mm-dd' )
		{
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) 
			{
				if(checkdate($matches[2], $matches[3], $matches[1]))
			   	{
			    	$bool = true;
			   	}
			}
		}

		return $bool;
	}

    function format_date($date)
    {
        $date=trim($date);
        $xlen=strlen($date);
        if ($xlen==0)
        {
            return $date;
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

        $mchar=str_pad($mchar,2,"0",STR_PAD_LEFT);
        $dchar=str_pad($dchar,2,"0",STR_PAD_LEFT);
        return "$ychar-$mchar-$dchar";
    }

    function LVALDou($xvar)
    {
         $retvar=str_replace(",","",$xvar);
         settype($retvar,"double");
         return $retvar;
    }
?>