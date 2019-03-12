<?php
function toGetTimeDifference()
{
    $date1Timestamp = 1551682383;
    $date2Timestamp = time();
    $difference = $date2Timestamp - $date1Timestamp;
    echo $difference;
}

function json_validator($data=NULL) {
    if (!empty($data)) {
            $data=@json_decode($data,'true');
            if(json_last_error() === JSON_ERROR_NONE){
                return $data;
            }
            return false;
    }
    return false;
  }