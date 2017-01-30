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

class controllers {

    public $Cout;

    //put your code here
    public function __construct() {
        
    }

    public function Cout($obj) {
        $this->Cout = $obj;
    }

    public function content() {
        return $this->Cout;
    }

    public function loadingModel() {
        $p = func_get_args();
        $p = array_unique($p);
        $CMyControllersClass = (object) [];
        foreach ($p as $val) {
            $class = 'M' . $val;
            $file = 'application/m/' . $class . '.php';
            if (is_file($file)) {
                include_once $file;
                $CMyControllersClass->{$val} = new $class();
            }
        }
        return $CMyControllersClass;
    }

}
