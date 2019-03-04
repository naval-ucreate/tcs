<?php
function toGetTimeDifference()
{
    $date1Timestamp = 1551682383;
    $date2Timestamp = time();
    
    //Calculate the difference.
    $difference = $date2Timestamp - $date1Timestamp;
    
    echo $difference;



}