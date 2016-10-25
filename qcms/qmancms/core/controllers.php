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

}
