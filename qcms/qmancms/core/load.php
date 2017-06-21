<?php

/*
 * The MIT License
 *
 * Copyright 2017 qman.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class load extends tfunction {

    private $Cmyclass;
    private $Err;
    private $id;
    private $M, $V, $C, $class, $fun;
    private $fileExists;
    private $template;
    private $compression = compression;

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

    /**
     *  输出页面内容及内容
     * @param string $fun 所需结构
     * @param array $page 参数结构
     * @return string 输出页面内容及内容
     */
    public function cout($fun = '', $page = array()) {

        if ($this->fileExists === FALSE || empty($fun)) {
            return $this->Err;
        }
        
        if (!empty($cout = $this->cache->get($this->cache->cacheKey))) {
            return $cout;
        }

        // 判断所有需要class 和方法 是否存在。
        if ($this->classExist() === TRUE && method_exists($this->Cmyclass, $fun)) {
            $this->id = !empty($page[0]) ? $page[0] : 0;

            call_user_func_array([$this->Cmyclass, $fun], $page);

            if (is_array($this->Cmyclass->cout)) {
                ob_start();
                include($this->V);
                $Vcontent = ob_get_contents();
                ob_end_clean();
                return $this->templateExist($Vcontent, $fun);
            }
            return $this->Cmyclass->cout;
        }
        return $this->Err;
    }

    /**
     * 模板数据库的方法
     */
    private function templateDBWay() {
        /* 数据库名 不统一的 后遗症 嗨  start */
        $menu ['activity'] = ['table' => 'activity', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['special'] = ['table' => 'special_config', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['news'] = ['table' => 'classify', 'index' => 'template', 'content' => 'templateContent'];
        return $menu;
        /* 数据库名 不统一的 后遗症 嗨  end */
    }

    /**
     * 输出结构体积内容
     * @param string  $content  视图层生成的 xml 数据
     * @return string
     */
    private function templateExist($content, $fun) {

        $template = empty($this->Cmyclass->tmp) ? $this->class : $this->Cmyclass->tmp;


        if ($this->class !== 'index') { 
            $menu = $this->templateDBWay();
            $Rs = ( $this->conn->query('select ' . $menu[$this->class][$fun] . ' as temp from ' . $menu[$this->class]['table'] . ' where id=' . $this->id));
            empty($Rs[0]['temp']) || $template = $Rs[0]['temp'];
        }
        $htmlContent = $this->generateHtml($content, $template);

        //清楚xsl 使用php特定的标签信息
        $str = str_replace(' xmlns:php="http://php.net/xsl"', '', $htmlContent);

        // 文件压缩
        if ($this->compression === TRUE) {
            $str = $this->compressionFile($str);
        }

        //建立缓存
        $this->cache->set($this->cache->cacheKey, $str);
        return $str;
    }

    /**
     * 将xml 数据与 xsl 模版 组合 生成html  
     * @param string $content
     * @param string $template
     * @return string
     */
    private function generateHtml($content, $template) {

        $template = tempUrl . 'template/' . $this->class . '/' . $template . '.xsl';

        if (is_file($template) !== FALSE) {
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

    /**
     * 所需类是否存在
     * @return boolean
     */
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
     
        $this->Cmyclass = new Cmyclass(new Mmyclass());
        return TRUE;
    }

    /**
     * 暴露接口 
     * @return object
     */
    public function content() {


        $fun = $this->Cmyclass->cout;
        if (!empty($fun)) {
            $cout = $fun;
            unset($fun);
            return $cout;
        } else {
            return FALSE;
        }
    }

    /**
     * 暴露tfunction 方法接口 
     * @param type $fun
     * @param type $value
     * @return type
     */
    public static function fun($fun, $value) {
        $tfunction = new tfunction();
        return $tfunction->{$fun}($value);
    }

}