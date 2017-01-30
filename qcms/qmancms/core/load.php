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

        $cout = $this->cache->get($this->cache->cacheKey);
        if (empty($cout) && $this->classExist() === TRUE) {

            $this->fun = strtolower($fun) == "index" ? "index" : "content";
            $this->id = !empty($page[0]) ? $page[0] : [];

            call_user_func_array([$this->Cmyclass, $this->fun], $page);
            $cout = $this->Cmyclass->Cout;

            if (is_array($cout)) {
                ob_start();
                include($this->V);
                $Vcontent = ob_get_contents();
                ob_end_clean();
                $cout = $this->templateExist($Vcontent);
            }
        } elseif (empty($cout)) {
            return $this->Err;
        }
        return $cout;
    }

    /**
     * 输出结构体积内容
     * @param string  $content  视图层生成的 xml 数据
     * @return string
     */
    private function templateExist($content) {

        /* 数据库名 不统一的 后遗症 嗨  start */
        $menu ['activity'] = ['table' => 'activity', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['special'] = ['table' => 'special_config', 'index' => 'template', 'content' => 'templateContent'];
        $menu ['news'] = ['table' => 'classify', 'index' => 'template', 'content' => 'templateContent'];
        /* 数据库名 不统一的 后遗症 嗨  end */

        if ($this->class !== 'index') {
            $Rs = ( $this->conn->query('select ' . $menu[$this->class][$this->fun] . ' as temp from ' . $menu[$this->class]['table'] . ' where id=' . $this->id));
        } else {
            $Rs[0]['temp'] = $this->class;
        }
        $template = $this->template($this->class, $Rs);
        $htmlContent = $this->generateHtml($content, $template);

        $str = str_replace(' xmlns:php="http://php.net/xsl"', '', $htmlContent);

        // 文件压缩
        if ($this->compression === TRUE) {
            $str = ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</", "//", "'/\*[^*]*\*/'", "/\r\n/", "/\n/", "/\t/", '/>[ ]+</'), array(">\\1<", '', '', '', '', '', '><'), $str)));
        }

        $template && $this->cache->set($this->cache->cacheKey, $str);
        return $str;
    }

    /**
     * 获取模版
     * @param type $tempURL
     * @param type $tempName
     * @return type
     */
    private function template($tempURL, $tempName) {
        $this->template = 'template/' . $tempURL . '/' . $tempName[0]['temp'] . ".xsl";
        $template = tempUrl . $this->template;
        return is_file($template) ? $template : FALSE;
    }

    /**
     * 将xml 数据与 xsl 模版 组合 生成html  
     * @param string $content
     * @param string $template
     * @return string
     */
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
        $this->Cmyclass = new Cmyclass();
        return TRUE;
    }

    /**
     * 暴露接口 
     * @return object
     */
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

    public static function fun($fun, $value) {
        $tfunction = new tfunction();
        return  $tfunction->$fun($value);
}

}
