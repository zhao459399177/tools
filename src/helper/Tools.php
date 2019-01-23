<?php
namespace zcy\helper;
class Tools{

     /** 列表转树
     * @param $items
     * @param string $id_field
     * @param string $parent_field
     * @param string $child_field
     * @return array
      * @deprecated
     */
    public static function listToTree(array $items,string $id_field = 'id',string $parent_field = 'parentId',string $child_field = 'child'):array
    {
        return Arr::listToTree($items, $id_field, $parent_field, $child_field);
    }

    /**
     * @param array $list
     * @param string $field
     * @param string $sortby
     * @return array|bool
     * @deprecated
     */
    public static function listSortBy(array $list,string $field,string $sortby = 'asc')
    {
        return Arr::listSortBy($list, $field, $sortby);
    }

    /**
     * @param array $tree
     * @param string $child
     * @param array $list
     * @param string $order
     * @return array|bool
     * @deprecated
     */
    public static function treeToList(array $tree,string $child = '_child',array &$list = [],string $order = '')
    {
        return Arr::treeToList($tree, $child, $list, $order);
    }

    /**
     * @param string $str
     * @return bool
     */
    public static function isUrl(string $str):bool
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
    public static function humanFileSize(int $bytes,int $decimals = 2):string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * 数组 转 对象
     * @param array $arr 数组
     * @return object
     * @deprecated
     */
    public static function arrayToObject(array $arr):object {
        return Arr::arrayToObject($arr);
    }

    /** 对象 转 数组
     * @param $array
     * @return array
     * @deprecated
     */
    public static function objectToArray($array) {
        return Arr::objectToArray($array);
    }

    /**
     * @param array $arr
     * @param int $code
     * @param int $httpCode
     * @return string
     * 输出 JSON 数据
     */
    public static function responseJson(array $arr,int $code = 200,int $httpCode = 200):string
    {
        http_response_code($httpCode);
        header('Content-Type:application/json; charset=utf-8');
        $msg = isset($arr['msg']) ? $arr['msg'] : '';
        $return['code'] = $code;
        $return['msg'] = $msg;
        $return['data'] = isset($arr['data']) ? $arr['data'] : [];
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }
}