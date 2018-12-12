<?php
namespace zcy\helper;

class Log
{
    //默认日志保存的文件路径
    public static $log_path=null;

    public static function writeLogger($filename,$strdata,$append=true){
        try{
            $dirname=dirname($filename);
            file_exists($dirname) || mkdir($dirname, 0755, true);
            if(!is_string($strdata)){
                $strdata = print_r($strdata,true);
            }
            $str = "[" . date("Y-m-d H:i:s") . "]" . $strdata . "\r\n";
            if($append)
                $rs = fopen($filename, "a+");
            else{
                $rs = fopen($filename, "w");
            }
            fwrite($rs, $str);
            fclose($rs);
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }
}