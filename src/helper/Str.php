<?php
namespace zcy\helper;
class Str{
    protected static $snakeCache = [];
    protected static $camelCache = [];
    protected static $studlyCache = [];

    /**
     * 获取指定长度的随机字母数字组合的字符串
     *
     * @param  int $length
     * @return string
     */
    public static function random(int $length = 16):string {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return static::substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
    /**
     * 字符串转小写
     *
     * @param  string $value
     * @return string
     */
    public static function lower(string $value):string {
        return mb_strtolower($value, 'UTF-8');
    }
    /**
     * 字符串转大写
     *
     * @param  string $value
     * @return string
     */
    public static function upper(string $value):string {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     *
     * @param  string $value
     * @return int
     */
    public static function length(string $value):int  {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     *
     * @param  string   $string
     * @param  int      $start
     * @param  int|null $length
     * @return string
     */
    public static function substr(string $string,int $start, $length = null):string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    public static function snake(string $value,string $delimiter = '_'):string
    {
        $key = $value;
        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        return static::$snakeCache[$key][$delimiter] = $value;
    }
    /**
     * 下划线转驼峰(首字母小写)
     *
     * @param  string $value
     * @return string
     */
    public static function camel(string $value):string {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }
        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     *
     * @param  string $value
     * @return string
     */
    public static function studly(string $value):string
    {
        $key = $value;
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * @param $str
     * @return false|string
     */
    public static function utf82Gb2312(string $str):string {
        return iconv('utf-8', 'gb2312//IGNORE', $str);
    }

    /**
     * @param $str
     * @return false|string
     */
    public static function gb23122Utf8(string $str):string {
       return iconv('gb2312', 'utf-8//IGNORE', $str);
    }
    
    /**
 * 数字转字母 相当于26制
 * @param int $num
 * @return string|null
 */
public function numberToLetter(int $num): ?string
{
    if ($num <= 0) { // 检测列数是否正确
        return null;
    }
    $str = [];
    do {
        --$num;
        $mod = $num % 26; // 取余
        $str[] = chr($mod + 65);
        $num = ($num - $mod) / 26; // 计算剩下值
    } while ($num > 0);
    return implode('', array_reverse($str)); // 返回反转后的字符串
}

/**
 * 字母转数字 相当于26制
 * @param String $letter
 * @return int
 */
public function letterToNumber(string $letter): int
{
    // 检查字符串是否为空
    if ($letter == null || empty($letter)) {
        return -1;
    }
    $upperLetter=strtoupper($letter); // 转为大写字符串
    if (!preg_match("/[A-Z]/",$upperLetter)) { // 检查是否符合，不能包含非字母字符
        return -1;
    }
    $num = 0; // 存放结果数值
    $base = 1;
    // 从字符串尾部开始向头部转换
    for ($i = strlen($upperLetter) - 1; $i >= 0; $i--) {
        $ch = substr($upperLetter, $i, 1);

        $num += (ord($ch) - ord('A') + 1) * $base;
        $base *= 26;
        if ($num > PHP_INT_MAX) { // 防止内存溢出
            return -1;
        }
    }
    return (int) $num;
}
}
