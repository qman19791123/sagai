<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tcache
 *
 * @author qman
 */
class tcache {

    private $isIpFun = '';

    /**
     * 每个页面会生成一个的唯一的编号
     * @var string 
     */
    public $cacheKey = 0;
    private $cacheDataFun = cacheDataFun;
    private $lib = lib;
    private $cacheCLass;
    private $cacheOpen = cacheOpen;

    public function __construct() {
        $this->cacheKey();
        $this->config();
    }

    /**
     * 唯一key 生成 
     */
    private function cacheKey() {
        $isFile = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
        $addr = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_URL);

        $this->isIpFun = $host;
        empty($this->isIpFun) && $this->isIpFun = $addr;

        $this->isIpFun = md5($this->isIpFun . '/' . $isFile);
        $this->cacheKey = substr($this->isIpFun, 8, 16);
    }

    /**
     * 配置
     * @return boolean
     */
    private function config() {
        $filename = $this->lib . '/cache/' . $this->cacheDataFun . '.php';
        if (is_file($filename)) {
            include_once $filename;
            if (!class_exists('cacheCLass')) {
                class_alias($this->cacheDataFun, 'cacheCLass');
                $this->cacheCLass = new cacheCLass();
            }
        }
    }

    /**
     *  载入缓存数据
     * @param string $key 编号(键)
     * @param string $str 内容 (值)
     * @return boolean
     */
    public function set($key, $str) {
        if ($this->cacheOpen) {
            return $this->cacheCLass->set($key, $str);
        }
    }

    /**
     * 输出缓存数据
     * @param string $key 编号(健)
     * @return object 成功输出数据,失败时候返回 FALSE
     */
    public function get($key) {
        if ($this->cacheOpen) {
            return $this->cacheCLass->get($key);
        }
    }

    /**
     * 删除数据
     *  @param string $key  编号(键)
     * @return boolean
     */
    public function del($key) {
        if ($this->cacheOpen) {
            return $this->cacheCLass->del($key);
        }
    }

    /**
     *  清空数据
     * @return boolean
     */
    public function flush() {
        if ($this->cacheOpen) {
            return $this->cacheCLass->flush();
        }
    }

}

//$t = new tcache();
//var_dump($t->set($t->cacheKey, ["asdasdasdas"]));
//print_r($t->get($t->cacheKey));
