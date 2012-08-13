<?php

//Matches int or empty string
function is_int_string($num_str){
    $num_str = trim($num_str);
    $regex = '/^[0-9]*$/';
    return preg_match($regex, $num_str);
}

?>
