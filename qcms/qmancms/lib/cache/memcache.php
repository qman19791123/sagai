<?php

/*
 * memcache类
 */

class memcache {

    //声明静态成员变量
    private $m = null;
    private $cache = null;
    private $host = '';
    private $port = '';

    private function __construct() {
        $this->m = new Memcache();
        $this->m->connect($this->host, $this->port); //写入缓存地址,端口
    }

    /**
     * 添加缓存数据
     * @param string $key 获取数据唯一key
     * @param String||Array $value 缓存数据
     * @param $time memcache生存周期(秒)
     */
    public function set($key, $value) {
        $this->m->set($key, $value, 0, cachedTime);
    }

    /**
     * 获取缓存数据
     * @param string $key
     * @return
     */
    public function get($key) {
        return $this->m->get($key);
    }

    /**
     * 删除对应缓存数据
     * @param string $key
     * @return
     */
    public function del($key) {
        $this->m->delete($key);
    }

    /**
     * 删除所有缓存数据
     */
    public function flush() {
        $this->m->flush();
    }

}
