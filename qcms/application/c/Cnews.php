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

/**
 * Description of Cnews
 *
 * @author qman
 */
class Cnews extends controllers {

    private $index = '';

    public function __construct() {
        parent::__construct();
        $this->news = new Mnews();
    }

    public function index($p = '', $limit = 0) {

        $data = $this->publicFun();
        
        $limits = $limit > 0 ? [ ($limit - 1) * 10, 10] : [0, 10];
        $data['list'] = $this->news->listContentNew($p, $limits);
        $data['pId'] = $p;
        $data['upPage'] = $limit - 1 > 0 ? $limit - 1 : 1;
        $data['downPage'] = $limit + 1 < $data['list']['count']['pageCount'] ? $limit + 1 : $data['list']['count']['pageCount'];
        $data['HTTP_SERVER'] = HTTP_SERVER;
        $this->cout($data);
    }

    public function content($p = 0, $a = 0) {

        $data = $this->publicFun();
        $data['content'] = $this->news->contentNew($a);
        $data['lrpage'] = $this->news->lrpage($p, $a);
        $data['pId'] = $p;
        $data['HTTP_SERVER'] = HTTP_SERVER;

        $this->cout($data);
    }

    private function publicFun() {
        $data = [];
        $data['class'] = $this->news->classifyArray();
        $data['notice'] = $this->news->noticeNew(32);
        return $data;
    }

    public function json() {
        $data['class'] = $this->news->classifyArray();
        $this->cout(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

}
