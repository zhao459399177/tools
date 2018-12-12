<?php
namespace zcy\helper;
class Mime{

    public static $cache_path = null;
    //处理缓存的类
    public static $cache=null;
    /**
     * 根据文件后缀获取文件MINE
     * @param array $ext 文件后缀
     * @param array $mine 文件后缀MINE信息
     * @return string
     * @throws LocalCacheException
     */
    public static function getExtMine($ext, $mine = [])
    {
        $mines = self::getMines();
        foreach (is_string($ext) ? explode(',', $ext) : $ext as $e) {
            $mine[] = isset($mines[strtolower($e)]) ? $mines[strtolower($e)] : 'application/octet-stream';
        }
        return join(',', array_unique($mine));
    }

    /**
     * 获取所有文件扩展的mine
     * @return array
     * @throws LocalCacheException
     */
    private static function getMines()
    {
        $mines = self::getCache('all_ext_mine');
        if (empty($mines)) {
            $content = file_get_contents('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
            preg_match_all('#^([^\s]{2,}?)\s+(.+?)$#ism', $content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                foreach (explode(" ", $match[2]) as $ext) {
                    $mines[$ext] = $match[1];
                }
            }
            self::setCache('all_ext_mine', $mines);
        }
        return $mines;
    }

    /**
     * 缓存配置与存储
     * @param string $name 缓存名称
     * @param string $value 缓存内容
     * @param int $expired 缓存时间(0表示永久缓存)
     * @throws LocalCacheException
     */
    public static function setCache($name, $value = '', $expired = 3600)
    {
        if(self::$cache!==null){
            return self::$cache->setCache($name,$value,$expired);
        }else{

            $cache_file = self::getCacheName($name);
            $content = serialize(['name' => $name, 'value' => $value, 'expired' => time() + intval($expired)]);
            if (!file_put_contents($cache_file, $content)) {
                throw new LocalCacheException('local cache error.', '0');
            }
        }
    }

    /**
     * 应用缓存目录
     * @param string $name
     * @return string
     */
    private static function getCacheName($name)
    {
        if (empty(self::$cache_path)) {
            self::$cache_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        }
        self::$cache_path = rtrim(self::$cache_path, '/\\') . DIRECTORY_SEPARATOR;
        file_exists(self::$cache_path) || mkdir(self::$cache_path, 0755, true);
        return self::$cache_path . $name;
    }

    /**
     * 移除缓存文件
     * @param string $name 缓存名称
     * @return bool
     */
    public static function delCache($name)
    {
        if(self::$cache!==null){
            return self::$cache->delCache($name);
        }else{
            $cache_file = self::getCacheName($name);
            return file_exists($cache_file) ? unlink($cache_file) : true;
        }
    }

    /**
     * 获取缓存内容
     * @param string $name 缓存名称
     * @return null|mixed
     */
    public static function getCache($name)
    {
        if(self::$cache!==null){
            return self::$cache->getCache($name);
        }else{
            $cache_file = self::getCacheName($name);
            if (file_exists($cache_file) && ($content = file_get_contents($cache_file))) {
                $data = unserialize($content);
                if (isset($data['expired']) && (intval($data['expired']) === 0 || intval($data['expired']) >= time())) {
                    return $data['value'];
                }
                self::delCache($name);
            }
            return null;
        }
    }
}