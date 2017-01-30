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

class Cindex extends controllers {

    private $index = '';
    private $loadingModel = null;

    public function __construct() {
        parent::__construct();
        $this->index = new Mindex();
        $this->loadingModel = $this->loadingModel('news','');
    }

    public function index($p = '') {
        $data = [];
        //$data['news'] = $this->loadingModel->news->json();
        $data['class']  = $this->index->classifyArray();
        $data['notice'] = $this->loadingModel->news->noticeNew(32);
        $data['DeveloperDynamics'] = $this->loadingModel->news->listContentNew(27);
        $data['DevelopmentManual'] = $this->loadingModel->news->listContentNew(28);
        $data['HTTP_SERVER'] = HTTP_SERVER;
        
         $this->Cout($data);
    }

}
