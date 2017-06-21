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
 * 查询接口，此功能只是为了现实现功能。 使用like 只是现在完成功能。
 * 
 * @author qman
 */
class Mquery extends models {

    //put your code here
    public function query($query) {
        //因为不会长用，所以直接先拿出来用了再说。那东西蛮消耗的
        require_once plus . 'phpanalysis2.0/phpanalysis.class.php';
        $pa = new PhpAnalysis('utf-8', 'utf-8', false);
        $pa->SetSource($query);
        $pa->resultType = 1;
        $pa->differMax = true;

        $pa->StartAnalysis();

        $key = array_filter(array_map(function($data) {
                    if (mb_strlen($data) >= 2) {
                        return $data;
                    }
                }, array_keys($pa->GetFinallyIndex())));
       
        $sql = '%' . join('%\' or subtitle like \'%', $key) . '%';
        $sql = 'select * from news_config where subtitle like \'' . $sql . '\' ';
        return $this->conn->query($sql);
       
    }

}
