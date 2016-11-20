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
    private $page;
    private $act;

    public function show($class = '', $fun = '', $page, $act) {

       
        ($class !== $fun && empty($act) && empty($page)) && die("Sorry, not this page");

        $not = 0;
        $temp = '';

        $this->page = $page;
        $this->act = $act;
        $page_ = $this->page === 'list' ? 'ntmp' : 'ctemp';


        if (empty($act) && empty($page)) {
            $this->page = 'index';
        } else {
            $temp = $this->conn->select([$page_])->where(['folder' => $act])->get('classify');
            if (empty($temp)) {
                $fun = $page;
            }
        }
        $template = tempUrl . 'template/' . (!empty($temp) ? $class . '/' . $temp[0][$page_] : $class . '/' . $this->page . ".xsl" );
       

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


        $not && die("Sorry, not this page");

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
            echo '<!-- this\'s not existed template,   template\'s  address  "' . $template . '" -->';
            print $Show;
        }
    }

    public function content() {

        $fun = $this->fun;
        if (method_exists($this->content, $fun)) {
            $this->content->$fun($this->act);
            $cout = $this->content->Cout;
            unset($this->content->Cout);
            return $cout;
        } else {
            die("Sorry, not this page");
        }
    }

}
