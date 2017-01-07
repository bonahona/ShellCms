<?php
function ArrayKeyExistsCaseInsensitive($needle, $haystack)
{
    foreach(array_keys($haystack) as $key){
        if(strtolower(($key) == strtolower($needle))){
            return true;
        }
    }

    return false;
}

function First($collection)
{
    if(!is_array($collection)){
        return null;
    }

    if(count($collection) > 0){
        return $collection[0];
    }else{
        return null;
    }
}