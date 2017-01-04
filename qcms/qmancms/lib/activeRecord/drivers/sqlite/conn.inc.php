<?php

/**
 * 
 */
class conn {

    function __construct() {
        $dsn = sprintf('sqlite:%s/%s/%s', install, dataLocal, dataName);
        $this->db = new PDO($dsn);
        $this->db->query('SET NAMES ' . dataCharset);
    }

    /**
     * 查询数据
     * @param unknown $sql
     * @return multitype:unknown
     */
    public function query($sql) {
        $Arr = array();
        $rs = $this->db->query($sql, 2);
        if (empty($rs))
            return;
        foreach ($rs as $row) {
            $Arr[] = $row;
        }
        return $Arr;
    }

    /**
     * 添加 删除 修改
     * @param unknown $sql
     * @return boolean
     */
    public function aud($sql) {
        $this->db->exec($sql);
        if (is_int(strripos($sql, 'insert into'))) {
            return $this->db->lastInsertId();
        }
        return true;
    }

}
