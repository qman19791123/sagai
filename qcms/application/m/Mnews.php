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

    /**
     * 公告
     * @param type $id
     * @return boolean
     */
    public function noticeNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = 'select `title`,`id`,`classifyid`,`time` from `news_config`  where `classifyId`= ' . $id . ' and `isdel`=0 and `checked`=999 order by id desc limit 5';
        return $this->conn->query($sql);
    }

    /**
     * 信息内容
     * @param type $id
     * @return boolean
     */
    public function contentNew($id = 0) {
        if (empty($id)) {
            return FALSE;
        }
        $sql = 'select * from news_config left join news_content on news_config.id = news_content.newsId  where id = ' . $id;
        return $this->conn->query($sql);
    }

    public function lrpage($classid, $id) {
        $sql = 'select id,classifyId,title from news_config where classifyId = ' . $classid . ' and isdel = 0 and  checked = 999 and id >' . $id . ' order by id asc limit 1 ;';
        $data['l'] = $this->conn->query($sql);
        $sql = 'select id,classifyId,title from news_config where classifyId = ' . $classid . ' and isdel = 0 and  checked = 999 and id <' . $id . ' order by id desc limit 1;';
        $data['r'] = $this->conn->query($sql);
        return $data;
    }

    /**
     * 信息列表
     * @param type $id
     * @return boolean
     */
    public function listContentNew($id = 0, $limit = [0, 10]) {
        if (empty($id)) {
            return FALSE;
        }
        $data = [];
        $sql = 'select `title`,`id`,`classifyid`,`time` ,`subtitle` from news_config  where classifyId = ' . $id . ' and `isdel`=0 and `checked`=999   order by id desc limit ' . join(',', $limit) . ';';

        $data['page'] = $this->conn->query($sql);
        //其实这个可以交sql 来处理,但是...数据库兼容问题 T_T.
        $sql = 'select  summary from `classify`  where id = ' . $id;
        $count = $this->conn->query($sql);
        $data['count'] =  $this->Page($count[0]['summary'], 1, 10, $limit[0]);

        return $data;
    }
}
