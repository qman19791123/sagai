<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classify
 *
 * @author qman
 */
class classify extends tfunction {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    /**
     *  面包屑 导航
     * @return type
     */
    public function BreadcrumbNavigation() {
        $Rs = $this->conn->get('classify');
        return $Rs;
    }

    /**
     * 大类菜单
     */
    public function MaxMenu() {
        return $this->Menu();
    }

    /**
     * 小类菜单
     */
    public function MinMenu($id) {
        return $this->Menu($id);
    }

    private function Menu($id = 0, $max = TRUE) {

        if ($max) {
            $data = ' pid =' . $id;
            $Rs = $this->conn->select(['id', 'pid', 'px', 'className as text', 'url', 'setting', 'folder'])->where($data)->get('classify');
        } else {

            $Rs = $this->classifyAchievedArray($id);
        }
        return $Rs;
    }

}
