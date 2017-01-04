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

    private $Cmyclass;
    private $Err;
    private $id;
    private $M, $V, $C, $class, $fun;
    private $fileExists;
    private $template;

    public function __construct($class) {
        parent::__construct();

        $this->Err = 'Sorry, this page no';

        $this->fileExists = FALSE;
        if (!empty($class)) {
            $this->fileExists = TRUE;
            $this->M = 'application/m/M' . $class . '.php';
            $this->C = 'application/c/C' . $class . '.php';
            $this->V = 'application/v/V' . $class . '.php';

            $this->fileExists = is_file($this->C);
            ($this->fileExists == TRUE) && $this->fileExists = is_file($this->M);
            ($this->fileExists == TRUE) && $this->fileExists = is_file($this->V);

            $this->class = $class;
        }
    }

    public function show($fun = '', $page = array()) {

        if ($this->fileExists === FALSE || empty($fun)) {
            return $this->Err;
        }

        $cout = $this->cache->get($this->cache->cacheKey);
        if (empty($cout) && $this->classExist() === TRUE) {

            $this->fun = strtolower($fun) == "index" ? "index" : "content";
            $this->id = !empty($page[0]) ? $page[0] : [];

            call_user_func_array([$this->Cmyclass, $this->fun], $page);
            $cout = $this->Cmyclass->Cout;

            if (is_array($cout)) {
                ob_start();
                include($this->V);
                $cout_ = ob_get_contents();
                ob_end_clean();
                $cout = $this->templateExist($cout_);
            }
        } elseif (empty($cout)) {
            return $this->Err;
        }
        return $cout;
    }

    private function templateExist($content) {
        
        /* 数据库名 不统一的 后遗症 嗨  start */
        $menu ['activity'] = ['table' => 'activity', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['special'] = ['table' => 'special_config', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['news'] = ['table' => 'special_config', 'index' => 'ntmp', 'content' => 'ctemp'];
        /* 数据库名 不统一的 后遗症 嗨  end */
        
        if ($this->class !== 'index') {
            $Rs = ( $this->conn->query('select ' . $menu[$this->class][$this->fun] . ' as temp from ' . $menu[$this->class]['table'] . ' where id=' . $this->id));
        } else {
            $Rs[0]['temp'] = $this->class;
        }
        $template = $this->template($this->class, $Rs);
        $htmlContent = $this->generateHtml($content, $template);
        $template && $this->cache->set($this->cache->cacheKey, $htmlContent);
        return $htmlContent;
    }

    private function template($tempURL, $tempName) {
        $this->template = 'template/' . $tempURL . '/' . $tempName[0]['temp'] . ".xsl";
        $template = tempUrl . $this->template;
        return is_file($template) ? $template : FALSE;
    }

    private function generateHtml($content, $template) {
        if ($template !== FALSE) {
            header('Content-Type:text/html;charset=' . dataCharset);
            $XML = new DOMDocument();
            $XML->loadXML($content);
            $xslt = new XSLTProcessor();
            $xslt->registerPHPFunctions();
            $XSL = new DOMDocument();
            $XSL->load($template);
            $xslt->importStylesheet($XSL);
            return $xslt->transformToXML($XML);
        } else {
            header('Content-Type:text/xml;charset=' . dataCharset);
            return sprintf('<!-- this\'s not existed template,   template\'s  address  "%s" -->%s', $this->template, $content);
        }
    }

    //  
    private function classExist() {
        include($this->C);
        include($this->M);
        class_alias('C' . $this->class, 'Cmyclass');
        class_alias('M' . $this->class, 'Mmyclass');

        if (!class_exists('Cmyclass')) {
            return FALSE;
        }
        if (!class_exists('Mmyclass')) {
            return FALSE;
        }
        $this->Cmyclass = new Cmyclass();
        return TRUE;
    }

    public function content() {
        $fun = $this->Cmyclass->Cout;
        if (!empty($fun)) {
            $cout = $fun;
            unset($fun);
            return $cout;
        } else {
            return FALSE;
        }
    }

}
