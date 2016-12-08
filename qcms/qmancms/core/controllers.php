<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controllers
 *
 * @author qman
 */
class controllers {

    public $Cout, $CMyControllersClass;

    //put your code here
    public function __construct() {
        
    }

    public function Cout($obj) {
        $this->Cout = $obj;
    }

    public function content() {
        return $this->Cout;
    }

    public function loadingSystemClass() {
        $p = func_get_args();
        $p = array_unique($p);
        foreach ($p as $t) {
            if (is_file(lib . 'qmanvmc/' . $t . '.php')) {
                include_once lib . 'qmanvmc/' . $t . '.php';
            } else {
                die('<h1>err</h1>' . lib . 'qmanvmc/' . $t . '.php Non-existent');
            }

            $CMyControllersClass[$t] = new $t();
        }
        return $CMyControllersClass;
    }
}
