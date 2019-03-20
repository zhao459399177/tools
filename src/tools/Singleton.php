<?php
namespace zcy\tools;
trait Singleton{

    private static $instance;

    static function getInstance(...$args)
    {
        if(!isset(self::$instance)){
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }
    private function __construct(){}
    private function __clone(){}
    private function __sleep(){}
    private function __wakeup(){}
}