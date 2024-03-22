<?php

/**
 * @author 
 * @copyright 2014
 */

//functions

function is_leap_year($year)
{
	if(($year % 4 == 0 && $year % 100 != 0) || ($year % 4 == 0 && $year % 100 == 0 && $year % 400 == 0))
	{
		return "yes";
	}
	else
	{
		return "no";
	}
}

?>