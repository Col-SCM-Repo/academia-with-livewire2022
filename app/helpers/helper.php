<?php

function convertArrayUpperCase(array $array, bool $wantObject = true)
{
    $temp = array();
    foreach ($array as $key => $value) {
        $temp[$key] = strtoupper($value);
    }
    return $wantObject ? (object) $temp : $temp;
}
