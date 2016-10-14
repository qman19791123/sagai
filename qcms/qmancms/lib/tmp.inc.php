<?php

class tmp {

    private $plus = '';
    private $plist = '';
    private $list = '';

    public function __construct() {
        $this->plus = '<%plus:(.*?)[\(](.*?)[\)]%>';
        $this->plist = '<%plist row=(.*?) name=(.*?) sql=(.*?)%>(*.?)<%endplist%>';
        $this->list = '<%list row=(.*?) name=(.*?) sql=(.*?)%>(*.?)<%endlist%>';
    }

    /**
     * 插件
     * @param type $str
     * @return type
     */
    public function plus($str = '') {
        $p = '';
        $j = 0;
        preg_match_all($this->plus, $str, $p);

        if (empty($p) || empty($p[1]) || empty($p[2])) {
            return;
        }
        foreach ($p[1] as $v) {
            $filename = install . 'plus\\' . trim($v) . '.php';
            if (is_file($filename)) {
                include $filename;
                if (function_exists(trim($v))) {
                    call_user_func_array(trim($v), mb_split(',', $p[2][$j]));
                }
            }
            $j++;
        }
    }

    /**
     * 分页
     * @param type $str
     * @return string
     */
    public function plist($str = '') {

        $p = '';
        preg_match_all($this->plist, $str, $p);
        if (empty($p) || empty($p[1]) || empty($p[2]) || empty($p[3]) || empty($p[4])) {
            return;
        }

        $size = $p[1][0]; //多少条
        $style = $p[2][0]; //格式数据
        $sqlpasge = $p[3][0]; //sql 语句
        $modes = $p[4][0]; //模板

        $sql = 'select * from ';
        return $sql;
    }

    /**
     * 列表
     * @param type $str
     * @return string
     */
    public function lists($str = '') {

        $p = '';
        preg_match_all($this->list, $str, $p);
        if (empty($p) || empty($p[1]) || empty($p[2]) || empty($p[3]) || empty($p[4])) {
            return;
        }

        $size = $p[1][0]; //多少条
        $style = $p[2][0]; //格式数据
        $sqlpasge = $p[3][0]; //sql 语句
        $modes = $p[4][0]; //模板

        $sql = 'select * from ';
        return $sql;
    }

    /**
     * 菜单
     * @param type $str
     * @return type
     */
    public function menu($str = '') {
        return;
    }

    /**
     * 迷你菜单
     * @param type $str
     * @return type
     */
    public function smemuMenu($str = '') {
        return;
    }

    /**
     * 面包屑
     * @param type $str
     * @return type
     */
    public function crumbs($str = '') {
        return;
    }

}
