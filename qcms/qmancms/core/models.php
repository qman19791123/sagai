<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of models
 *
 * @author qman
 */
class models extends tfunction {

    //put your code here

    public function conn() {
        $tfunction = new tfunction();
        $Rs = $tfunction->conn;
        return $Rs;
    }

}
