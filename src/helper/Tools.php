<?php
namespace zcy\helper;
class Tools{

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

    /** 输出 JSON 数据
     * @param $arr
     * @param int $code
     * @param int $httpCode
     */
    public static function responseJson($arr, $code = 200, $httpCode = 200){
        http_response_code($httpCode);
        header('Content-Type:application/json; charset=utf-8');
        $msg = isset($arr['msg']) ? $arr['msg'] : '';
        $return['code'] = $code;
        $return['msg'] = $msg;
        $return['data'] = isset($arr['data']) ? $arr['data'] : [];
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }
}