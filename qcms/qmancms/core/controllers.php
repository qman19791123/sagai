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

    public function __get($name) {
        if (empty($name)) {
            return FALSE;
        }
        if (is_file(lib . 'qmanvmc/' . $name . '.php')) {
            include lib . 'qmanvmc/' . $name . '.php';
        } else {
            die('<h1>err</h1>' . lib . 'qmanvmc/' . $name . '.php Non-existent');
        }

        if (class_exists($name)) {
            $CMyControllersClass = new $name;
        } else {
            die('<h1>err</h1>' . $name . ' Non-existent');
        }
        return $CMyControllersClass;
    }

}
