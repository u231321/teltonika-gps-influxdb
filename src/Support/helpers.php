<?php

/**
 * Pad an hexadecimal value to fit the specified number of bytes
 *
 * @param string $value
 * @param number $bytes
 * @return string
 */
function padHex(string $value, $bytes)
{
    return str_pad($value, $bytes * 2, '0', STR_PAD_LEFT);
}


function str2hex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}
 
 
function hex2str($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}