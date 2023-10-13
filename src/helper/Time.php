<?php
namespace zcy\helper;
class Time
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today():array
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
    public static function yesterday():array
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
    public static function week():array
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
    public static function lastWeek():array
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
    public static function month($everyDay = false):array
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
    public static function lastMonth():array
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
    public static function year():array
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
    public static function lastYear():array
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
    public static function dayToNow(int $day = 1,bool $now = true):array
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
    public static function daysAgo(int $day = 1):int
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
    public static function daysAfter(int $day = 1):int
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
    public static function daysToSecond(int $day = 1):int
    {
        return $day * 86400;
    }
    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weekToSecond(int $week = 1):int
    {
        return self::daysToSecond() * 7 * $week;
    }

    /** 获得现在的毫秒
     * @param int $isMicrosecond
     * @return float|int
     */
    public static function nowMillisecond(int $isMicrosecond = 0):int
    {
        $multiple = 1000;
        if ($isMicrosecond) {
            $multiple = $multiple * 10;
        }
        return ceil(microtime(true) * $multiple);
    }

    /** 把时间戳转换成可读格式
     * @param int $timestamp
     * @param int $type
     * @return false|string
     */
    public static function timeToFormat(int $timestamp = 0, int $type = 1):string
    {
        switch ($type) {
            case 1:
                $format = 'Y-m-d H:i:s';
                break;
            case 2:
                $format = 'Y/m/d H:i:s';
                break;
            case 3:
                $format = 'YmdHis';
                break;
            case 4:
                $format = 'Y-m-d';
                break;
            case 5:
                $format = 'Ymd';
                break;
            default:
                $format = 'Y-m-d H:i:s';
                break;
        }
        return self::timeFormat($timestamp,$format);
    }

    /** 把时间戳转换成可读格式
     * @param int $timestamp
     * @return false|string
     * @deprecated
     */
    public static function timeToInt(int $timestamp = 0): string
    {
        return self::timeFormat($timestamp,'YmdHis');
    }

    /**
     * @param int $timestamp
     * @param string $format
     * @return false|string
     */
    private static function timeFormat(int $timestamp,string $format)
    {
        if ($timestamp == 0) {
            $timestamp = time();
        }
        return date($format,$timestamp);
    }

    /** 把时间差秒数转换为可识别的时间
     * @param int $seconds
     * @param bool $accurate
     * @return string
     */
    public static function secondsToStr(int $seconds = 0, bool $accurate = true, int $length = 6):string
    {
        $seconds = intval($seconds);
        if (is_int($seconds)) {
            $arr = [
                ['name' => '年', 'seconds' => 31536000],
                ['name' => '月', 'seconds' => 2592000],
                ['name' => '天', 'seconds' => 86400],
                ['name' => '小时', 'seconds' => 3600],
                ['name' => '分', 'seconds' => 60],
                ['name' => '秒', 'seconds' => 1],
            ];
            $temp = [];
            foreach ($arr as $k => $v) {
                if ($seconds >= $v['seconds']) {
                    $temp[] = sprintf('%d%s', floor($seconds / $v['seconds']), $v['name']);
                    $seconds = ($seconds % $v['seconds']);
                }
            }
            $t = '';
            if (count($temp) == 0) {
                $t = '0秒';
            } else {
                $i = 0;
                foreach ($temp as $v) {
                    if ($accurate) {
                        $i++;
                        $t .= $v;
                        if ($i >= $length) break;
                    } else {
                        $t = $v . '前';
                        break;
                    }
                }
            }
            return $t;
        } else {
            return '';
        }
    }

    private static function startTimeToEndTime()
    {
    }
}
