<?php

function CheckEmpty($var) {
    return (!empty($var)) ? $var : "--";
}

function CheckEquality($var, $var2, $var3) {
    return (!empty($var) && $var == $var2) ? $var3 : "";
}

function d($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}
