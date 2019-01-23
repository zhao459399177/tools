<?php
namespace zcy\helper;
class Arr{
    /** 列表转树
     * @param $items
     * @param string $id_field
     * @param string $parent_field
     * @param string $child_field
     * @return array
     */
    public static function listToTree(array $items,string $id_field = 'id',string $parent_field = 'parentId',string $child_field = 'child'):array
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

    /**
     * @param array $list
     * @param string $field
     * @param string $sortby
     * @return array|bool
     */
    public static function listSortBy(array $list,string $field,string $sortby = 'asc')
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

    /**
     * @param array $tree
     * @param string $child
     * @param array $list
     * @param string $order
     * @return array
     */
    public static function treeToList(array $tree,string $child = '_child',array &$list = [],string $order = ''):array
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

    /**
     * 数组 转 对象
     * @param array $arr 数组
     * @return object
     */
    public static function arrayToObject(array $arr):object {
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

    /** 对象 转 数组
     * @param $array
     * @return array
     */
    public static function objectToArray($array):array {
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

    /**
     * @param array $members
     * @param int $mid
     * @param bool $hasroot
     * @return array
     */
    public static function getSonMember(array $members,int $mid, bool $hasroot = false):array
    {
        //$members:总数据（id自增顺序）
        //mid 要查的id
        $teams = [];
        $teams[$mid] = '';
        foreach ($members as $k => $v) {
            if (isset($teams[$v['pid']])) {
                $teams[$v['id']] = '';
                unset($members[$k]);
            } else {
                unset($members[$k]);
            }
        }
        //因为获取的是第一个会员下级,所以删除第一个会员
        if (!$hasroot) unset($teams[$mid]);
        return array_keys($teams);
    }

    /**
     * @param $array
     * @param $columnKey
     * @param null $indexKey
     * @return array
     * 返回 key val 数组，对array_columns 补充
     */
    public static function arrayColumns($array, $columnKey, $indexKey = null):array
    {
        $result = [];
        if (!is_array($columnKey)) {
            $result = array_column($array, $columnKey, $indexKey);
        } elseif (empty($columnKey)) {
            $result = array_column($array, null, $indexKey);
        } else {
            foreach ($array as $subArray) {
                if (!is_null($indexKey)) $columnKey[] = $indexKey;
                $subArray = array_intersect_key($subArray, array_combine($columnKey, $columnKey));
                if (is_null($indexKey)) {
                    $result[] = $subArray;
                } elseif (array_key_exists($indexKey, $subArray)) {
                    $index = is_object($subArray) ? $subArray->$indexKey : $subArray[$indexKey];
                    unset($subArray[$indexKey]);
                    $result[$index] = $subArray;
                }
            }
        }
        return $result;
    }
}