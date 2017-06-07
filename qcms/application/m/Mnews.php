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

class Mnews extends models {

    //put your code here
    public function indexNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = 'select * from news_config where classifyId = ' . $id;
        return $this->conn->query($sql);
    }

    public function noticeNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = "select news_config.* from classify left join news_config on classify.id = news_config.classifyId where classify.id= " . $id . ' and isdel = 0';
        return $this->conn->query($sql);
    }

    public function contentNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = 'select * from news_config left join news_content on news_config.id = news_content.newsId  where id = ' . $id;
        return $this->conn->query($sql);
    }

    public function listContentNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = 'select news_config.*,news_content.description,news_content.keywords from news_config left join news_content on news_config.id = news_content.newsId  where news_config.classifyId = ' . $id;
        return $this->conn->query($sql);
    }
}
