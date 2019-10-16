<?php
/*General function goes in this section of the file*/

/**
 * Caluclates the percentage value for the exixting grade.
 * @param  $value string
 * @return $percentage float
 */
function grade_percentage($value) {
	$value = (float) $value;
	if($value >1 && $value <= 100){
    	$percentage =$value;
    }else if ($value >=0 && $value <=1 ) {
    	$percentage = (($value)*100);
    } else{
    	$percentage = (float) "0.0";
    }

    return $percentage;
}
