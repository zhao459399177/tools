<?php
if(!function_exists('var_print')){
    function var_print($data,$exit=1){
        echo '<pre>';
        print_r($data);
        if ($exit) exit;
    }
}