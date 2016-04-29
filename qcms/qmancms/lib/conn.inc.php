<?php
/**
* 
*/
class conn
{
    function __construct(){
        if(strtolower(dataType) == 'mysql'){
            $dsn = sprintf('mysql:host=%s;dbname=%s',dataLocal,dataName);
            $this->db = new PDO($dsn,dataUser,dataPassword,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.dataCharset));
        }
        else
        {
             $dsn = sprintf('sqlite:%s/%s/%s',install,dataLocal,dataName);
             $this->db = new PDO($dsn);
             $this->db->query('SET NAMES '.dataCharset);
        }
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        #$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /**
     * 查询数据
     * @param unknown $sql
     * @return multitype:unknown
     */
    public function  query($sql){
        $Arr = array();
        $rs = $this->db->query($sql,2);
        if(empty($rs)) return;
        foreach($rs as $row)
        {
            $Arr[]=$row;
        }
        return $Arr;
    }
    /**
     * 添加 删除 修改
     * @param unknown $sql
     * @return boolean
     */
    public function aud($sql){
        $this->db->exec($sql);
        if(is_int(strripos($sql,'insert into'))){
            return  $this->db->lastInsertId();
        }
        return true;
    }
   /**
     * 格式化sql 语句
     * @param unknown $sql
     * @return string
     */
    public function pd($sql){
        return addslashes($p);
    }

}