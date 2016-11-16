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
class load extends tfunction {

    public $content;
    private $fun;

    public function show($class = '', $fun = '') {

        $not = 0;
        $temp = '';

        $act = filter_input(INPUT_GET, 't', FILTER_SANITIZE_STRING);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING) == 'list' ? 'ntmp' : 'ctemp';

        if (is_file('application/m/M' . $class . '.php')) {
            include('application/m/M' . $class . '.php');
        } else {
            $not = 1;
        }

        ob_start();

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


        $Show = ob_get_contents();
        ob_end_clean();


        $not && die("对不起没有此页");



        if (empty($act)) {
            $act = 'index.xsl';
        } else {
            $temp = $this->conn->select([$page])->where(['folder' => $act])->get('classify');
        }
        if ($class == '') {
            return;
        }

        $template = tempUrl . 'template/' . (!empty($temp) ? $temp[0][$page] : $act);
        if (is_file($template)) {
            header('Content-Type:text/html;charset=' . dataCharset);

            $XML = new DOMDocument();
            $XML->loadXML($Show);

            $xslt = new XSLTProcessor();
            $XSL = new DOMDocument();

            $XSL->load($template);
            $xslt->importStylesheet($XSL);
            print $xslt->transformToXML($XML);
        } else {
            header('Content-Type:text/xml;charset=' . dataCharset);
            echo '<!-- Without template, template name is "' . $temp[0][$page] . '.xsl" -->';
            print $Show;
            
        }
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
