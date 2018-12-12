<?php
namespace zcy\helper;
class Time
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today()
    {
        list($y, $m, $d) = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y)
        ];
    }
    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y'))
        ];
    }
    /**
     * 返回本周开始和结束的时间戳
     *
     * @return array
     */
    public static function week()
    {
        list($y, $m, $d, $w) = explode('-', date('Y-m-d-w'));
        if($w == 0) $w = 7; //修正周日的问题
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y), mktime(23, 59, 59, $m, $d - $w + 7, $y)
        ];
    }
    /**
     * 返回上周开始和结束的时间戳
     *
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }
    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     */
    public static function month($everyDay = false)
    {
        list($y, $m, $t) = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y)
        ];
    }
    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return array
     */
    public static function lastMonth()
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, date('t', $begin), $y);
        return [$begin, $end];
    }
    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year()
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, $y),
            mktime(23, 59, 59, 12, 31, $y)
        ];
    }
    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }

    public static function dayOf()
    {
    }
    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow(int $day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }
        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end
        ];
    }
    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAgo(int $day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }
    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAfter(int $day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }
    /**
     * 天数转换成秒数
     *
     * @param int $day
     * @return int
     */
    public static function daysToSecond(int $day = 1)
    {
        return $day * 86400;
    }
    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weekToSecond(int $week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

    /**
     *返回现在的微秒时间
     */
    public static function nowMicrotime(){
        return microtime(true) * 10000;
    }

    /** 把时间戳转换成可读格式
     * @param int $timestamp
     * @return false|string
     */
    public static function timeToFormat(int $timestamp=0){
        return self::timeFormat($timestamp,'Y-m-d H:i:s');
    }

    /** 把时间戳转换成可读格式
     * @param int $timestamp
     * @return false|string
     */
    public static function timeToInt(int $timestamp=0){
        return self::timeFormat($timestamp,'YmdHis');
    }

    private static function timeFormat(int $timestamp,$format){
        if ($timestamp == 0) {
            $timestamp = time();
        }
        return date($format,$timestamp);
    }

    /** 把时间差秒数转换为可识别的时间
     * @param $seconds
     * @return string
     */
    public static function secondsToStr(int $seconds=0)
    {
        if (is_numeric($seconds)) {
            $value = [
                "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            ];
            if ($seconds >= 86400) {
                $value["days"] = floor($seconds / 86400);
                $seconds = ($seconds % 86400);
            }
            if ($seconds >= 3600) {
                $value["hours"] = floor($seconds / 3600);
                $seconds = ($seconds % 3600);
            }
            if ($seconds >= 60) {
                $value["minutes"] = floor($seconds / 60);
                $seconds = ($seconds % 60);
            }
            $value["seconds"] = floor($seconds);
            $t = '';
            $t .= $value['days'] ? $value["days"] . "天" : '';
            $t .= $value['hours'] ? $value["hours"] . "小时" : '';
            $t .= $value['minutes'] ? $value["minutes"] . "分" : '';
            $t .= $value["seconds"] . "秒";
            return $t;
        } else {
            return '';
        }
    }

    private static function startTimeToEndTime()
    {
    }
}