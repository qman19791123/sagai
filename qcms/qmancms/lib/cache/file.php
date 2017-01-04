<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author qman
 */
class file {

    private $cache = null;
    private $cacheFloder = cacheFloder;
    private $cachedTime = cachedTime;

    public function set($key, $value) {
        $this->init($key);
        $v = gzcompress(serialize($value), 9);
        file_put_contents($this->cache, sprintf('<?php defined("systemName") ||die("404");?>%s' . "\r\n", $v));
        return TRUE;
    }

    public function get($key) {
        if ($this->init($key) && filemtime($this->cache) + $this->cachedTime > time()) {
            return unserialize(gzuncompress(substr(file_get_contents($this->cache), 43, -2)));
        } else {
            $this->del($key);
            return FALSE;
        }
    }

    public function del($key) {
        if ($this->init($key)) {
            return unlink($this->cache);
        }
    }

    public function flush() {
        $arrFile = glob($this->cacheFloder . '*.data');
        array_map('unlink', $arrFile);
    }

    private function init($key) {
        $this->cache = $this->cacheFloder . $key . '.data';
        return (is_file($this->cache));
    }

}
