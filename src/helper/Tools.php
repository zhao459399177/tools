<?php
namespace zcy\helper;
class Tools{
    /** 数字转大写
     * @param $number
     * @return mixed|string
     */
    public static function numberToBig($number)
    {
        $chiNum = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'];
        $chiUni = ['', '十', '百', '千', '万', '十', '百', '千', '亿'];

        $num_str = (string)$number;

        $count = strlen($num_str);
        $last_flag = true; //上一个 是否为0
        $zero_flag = true; //是否第一个
        $temp_num = null; //临时数字

        $chiStr = '';//拼接结果
        if ($count == 2) {//两位数
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        } else if ($count > 2) {
            $index = 0;
            for ($i = $count - 1; $i >= 0; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag) {
                        $chiStr = $chiNum[$temp_num] . $chiStr;
                        $last_flag = true;
                    }
                } else {
                    $chiStr = ($i == 0 && $chiUni[$index % 9] === '十' ? $chiUni[$index % 9] : $chiNum[$temp_num] . $chiUni[$index % 9]) . $chiStr;
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index++;
            }
        } else {
            $chiStr = $chiNum[$num_str[0]];
        }
        return $chiStr;
    }

     /** 列表转树
     * @param $items
     * @param string $id_field
     * @param string $parent_field
     * @param string $child_field
     * @return array
     */
    public static function listToTree($items, $id_field = 'id', $parent_field = 'parentId', $child_field = 'child')
    {
        $tree = []; //格式化好的树
        foreach ($items as &$item) {
            if (isset($items[$item[$parent_field]])) {
                $items[$item[$parent_field]][$child_field][] = &$items[$item[$id_field]];
            } else {
                $tree[] = &$items[$item[$id_field]];
            }
        }
        return $tree;
    }

    public static function listSortBy($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = [];
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }

    public static function treeToList($tree, $child = '_child', &$list = [], $order = '')
    {
        if (is_array($tree)) {
            $refer = [];
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    self::treeToList($value[$child], $child, $list, $order);
                }
                $list[] = $reffer;
            }
            if ($order !== '') {
                $list = self::listSortBy($list, $sortby = 'asc', $order);
            }
        }
        return $list;
    }

    public static function isUrl($str)
    {
        if (strtolower(substr($str, 0, 7)) == 'http://' || strtolower(substr($str, 0, 8)) == 'https://') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 数字转换为中文
     * @param  string|integer|float  $num  目标数字
     * @param  integer $mode 模式[true:金额（默认）,false:普通数字表示]
     * @param  boolean $sim 使用小写（默认）
     * @return string
     */
    public static function numberToChinese($num, $mode = true, $sim = true)
    {
        if (!is_numeric($num)) return $num;
        $char = $sim ? array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九') : array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        $unit = $sim ? array('', '十', '百', '千', '', '万', '亿', '兆') : array('', '拾', '佰', '仟', '', '萬', '億', '兆');
        $retval = $mode ? '元' : '点';
        //小数部分
        if (strpos($num, '.')) {
            list($num, $dec) = explode('.', $num);
            $dec = strval(round($dec, 2));
            if ($mode) {
                $retval .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";
            } else {
                for ($i = 0, $c = strlen($dec); $i < $c; $i++) {
                    $retval .= $char[$dec[$i]];
                }
            }
        }

        //整数部分
        $str = $mode ? strrev(intval($num)) : strrev($num);
        for ($i = 0, $c = strlen($str); $i < $c; $i++) {
            $out[$i] = $char[$str[$i]];
            if ($mode) {
                $out[$i] .= $str[$i] != '0' ? $unit[$i % 4] : '';
                if ($i > 1 and $str[$i] + $str[$i - 1] == 0) {
                    $out[$i] = '';
                }
                if ($i % 4 == 0) {
                    $out[$i] .= $unit[4 + floor($i / 4)];
                }
            }
        }
        $retval = join('', array_reverse($out)) . $retval;
        return $retval;
    }

    /** 把文件大小转换成人读的格式
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public static function humanFileSize($bytes, $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /** 把时间差秒数转换为可识别的时间
     * @param $time
     * @return string
     */
    public static function secondsStr($time)
    {
        if (is_numeric($time)) {
            $value = [
                "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            ];
            if ($time >= 86400) {
                $value["days"] = floor($time / 86400);
                $time = ($time % 86400);
            }
            if ($time >= 3600) {
                $value["hours"] = floor($time / 3600);
                $time = ($time % 3600);
            }
            if ($time >= 60) {
                $value["minutes"] = floor($time / 60);
                $time = ($time % 60);
            }
            $value["seconds"] = floor($time);
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

    /**
     * 产生随机字符串
     * @param int $length 指定字符长度
     * @param string $str 字符串前缀
     * @return string
     */
    public static function createNoncestr($length = 32, $str = "")
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 数组 转 对象
     * @param array $arr 数组
     * @return object
     */
    public static function arrayToObject($arr) {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)self::arrayToObject($v);
            }
        }
        return (object)$arr;
    }

    /**
     * 对象 转 数组
     * @param object $obj 对象
     * @return array
     */
    public static function objectToArray($array) {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::objectToArray($value);
            }
        }
        return $array;
    }
}