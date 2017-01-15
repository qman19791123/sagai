<?php

/* 
 * The MIT License
 *
 * Copyright 2017 qman.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
