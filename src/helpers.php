<?php
if(!function_exists('var_print')){
    function var_print($data,$exit=1){
        header('Content-type:text/html;charset=utf-8');
        echo '<pre>';
        print_r($data);
        if ($exit) exit;
    }
}