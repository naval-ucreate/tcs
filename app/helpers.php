<?php

function toGetTimeDifference(){
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

/**
 * json array checking 
 * @param array, string
 * @return bool
 */
function array_exists($array ,$niddle){
    $array = json_decode($array, true);
    foreach($array as $key => $value):
        if($niddle==$value['idMember'] && $value['memberType']=='admin'){
            return true;
        }
    endforeach;
    return false;
}

function newArrayElement($old, $new){
    $new_array = [];
    foreach($new as $value){
        if(!in_array($value['trello_list_id'], $old)){
            $new_array[] = $value;
        }
    }
    return $new_array;
}