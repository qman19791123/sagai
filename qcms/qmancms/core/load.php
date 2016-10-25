<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of load
 *
 * @author qman
 */
class load {

    public $content;
    private $fun;

    public function show($class = '', $fun = '') {

        if ($class == '') {
            return;
        }

        $not = 0;
        // ob_start ();
        if (is_file('application/m/M' . $class . '.php')) {
            include('application/m/M' . $class . '.php');
        } else {
            $not = 1;
        }


        if (!$not && is_file('application/c/C' . $class . '.php')) {

            include('application/c/C' . $class . '.php');
            class_alias('C' . $class, 'Cmyclass');
            if (class_exists('Cmyclass')) {
                $this->content = new Cmyclass();
                $this->fun = $fun;
            } else {
                $not = 1;
            }
        } else {
            $not = 1;
        }


        if (!$not && is_file('application/v/V' . $class . '.php')) {
            include('application/v/V' . $class . '.php');
        } else {
            $not = 1;
        }

        $not && print("对不起没有此页");

        // $this->Show = ob_get_contents ();
        // ob_end_clean ();
    }

    public function content() {

        $fun = $this->fun;
        if (method_exists($this->content, $fun)) {
            $this->content->$fun();
            $cout = $this->content->Cout;
            unset($this->content->Cout);
            return $cout;
        } else {
            die("对不起没有此页");
        }
    }

}
