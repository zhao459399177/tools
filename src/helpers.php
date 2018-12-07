<?php
/**递归创建文件夹**/
function mkdirs($dir)
{
    return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir, 0777));
}

if(!function_exists('var_print')){
    function var_print($data,$exit=1){
        echo '<pre>';
        print_r($data);
        if($exit){
            exit;
        }
    }
}