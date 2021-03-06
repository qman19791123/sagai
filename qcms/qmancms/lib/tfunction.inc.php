<?php

class tfunction {

    public $conn, $less, $cache;
    private $zd;

    function __construct() {
        if (!class_exists('activeRecord') && !class_exists('conn')) {
            include lib . 'activeRecord.inc.php';
        }
        if (!class_exists('lessc')) {
            include plus . '/lessc.inc.php';
        }
        if (!class_exists('tcache')) {
            include lib . '/tcache.inc.php';
        }
        $this->conn = new activeRecord();
        $this->less = new lessc;
        $this->cache = new tcache();
    }

    /**
     * <b>less 转 css </b>
     * <p>
     *  通过赋予的 <b>$file</b>《LESS 文件名》生成CSS 并输出生成的文件地址<br/>
     * <b>注释：</b>
     *  生成的文件地址为 url 格式
     * </p>
     * <b>
     * 演示代码:
     * </b>
     * <pre>
     * <?php
     * $var = new tfunction();
     * $var->lessc('less.less');
     * ?>
     * </pre>
     * @param string $file <p>
     * Less文件  <b>Less文件不能为空,less文件名格式为 *.less<b>
     * </p>
     * @return string <p> 
     * 输出css 文件地址 
     * </p>
     */
    public function lessc($file = '') {
        $fileLess = install . 'css/less/' . $file;
        if (is_file($fileLess)) {
            $fileCssName = pathinfo($fileLess);
            $fileCss = 'css/css/' . $fileCssName['filename'] . '.css';
            $this->less->checkedCompile($fileLess, install . $fileCss);
            return $fileCss;
        }
    }

    /**
     * <b>提示信息</b>
     * <p>
     *  通过赋予的 <b>url</b>《地址》和<b>message</b>《提示消息》跳至到指定的页面或返回上一页<br/>
     * <b>注释：</b>
     * 无
     * </p>
     * <b>
     * 演示代码:
     * </b>
     * <pre>
     * <?php
     * $var  = new tfunction();
     * $var->message('message','url');
     * ?>
     * </pre>
     * @global  string      $url        <p>
     * 地址
     * </p> 
     * @param   string      $message    <p>
     * 消息  <b>消息不能为空</b>
     * </p>
     * @param   string      $url        <p>
     * 地址  <b>为空则不返回上一页面</b>
     * <p>
     * @return  string      <p>
     * 输出页面片段 
     * </p>
     */
    public function message($message, $url = '') {
        if (empty($message)) {
            return;
        }
        ob_start();
        include tempUrl . '/system/message.tp';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * <b>挑转页面</b>
     * <p>
     *  通过赋予的 <b>url</b>《地址》跳至到指定的页面或返回上一页
     * <b>注释：</b>
     * 无
     * </p>
     * <b>
     * 演示代码
     * </b>
     * <pre>
     * <?php
     * $var  = new tfunction();
     * $var->gotoUrl('地址'|'');
     * ?>
     * </pre>
     * @param string $url <p>
     * 地址  <b>为空则不返回上一页面</b>
     * </p> 
     * @return <p>
     * 不输出任何信息
     * </p>
     */
    public function gotoUrl($url = '') {
        if (empty($url)) {
            header('location:' . getenv("HTTP_REFERER"));
        } else {
            header('location:' . $url);
        }
    }

    /**
     * <b>中文转拼音</b>
     * <p>
     *  通过赋予的 <b>$ZNContent</b>《中文内容》将其转换为拼音字母<br/>
     * <b>注释：</b>
     * $ZNContent  长度不能超过20个字符
     * </p>
     * <b>
     * 演示代码:
     * </b>
     * <pre>
     * <?php
     * $var  = new tfunction();
     * $var->py('中文内容');
     * ?>
     * </pre>
     * @param string $ZNContent <p>
     * 中文内容  <b>不能为空</b>
     * </p> 
     * @param function  callBack
     * 回调方法
     * @param int len
     * 输入最长长度
     * @return sting|bool <p>
     * 在为超过20（中文）字符的时候系统会将转换为拼音字母并输出，超出时候则输出布尔型的FALSE以表示失败
     * </p>
     */
    public function py($ZNContent, $callBack = '', $len = 20) {
        $zd = '';
        if (!function_exists('zhconversion_hans')) {
            include plus . "Zdian.php";
            include plus . "convert.php";
            $this->zd = $zd;
        }

        if ($callBack) {
            $ZNContent = call_user_func_array($callBack, array($ZNContent, $len));
        }
        if (mb_strlen($ZNContent, 'utf-8') <= $len) {
            $string = zhconversion_hans($ZNContent);
            return strtr($string, $this->zd);
        } else {
            return FALSE;
        }
    }

    /**
     * <b>中文符合过滤</b>
     * @param string $str 
     * 中文内容  
     * @return string 
     * 过滤后的信息
     */
    public function ZNSymbolFilter($str, $len) {
        $re = ["\t", "\n", "\r", "\r\n", ' ', '·', '｜', '～', '！', '＠', '＃', '￥', '％', '…', '＆', '＊', '（', '）', '—', '＋', '－', '＝', '『', '』', '「', '」', '：', '“', '|', '；', '‘', '、', '《', '》', '？', '，', '。', '/', '@', '#', '%', '&', '*', '-', '=', '+', '【', '】', '~', '!', '$', '^', '(', ')', '_', '{', '}', '[', ']', ':', '"', ';', '\'', '\\', '<', '>', '?', ',', '.',];
        $filterStr = str_replace($re, '', $str);
        if (!empty($len)) {
            $slen = mb_strlen($str, 'utf-8');
            $slen < $len && $len = $slen;
            $filterStr = mb_substr($filterStr, 0, $len, 'utf-8');
        }
        return $filterStr;
    }

    /**
     * <b>分类递归</b> 
     * <p>
     *  输出分类所有信息，以递归的方案将数据库中分类从大类到小类归纳后输出<br/>
     * <b>注释：</b>
     * 无
     * </p>
     * <b>
     * 演示代码:
     * </b>
     * <pre>
     * <?php
     * $var  = new tfunction();
     * $var->classify();
     * ?>
     * </pre>
     * @param 
     * @return array <p>
     * 输出的内容为 数据格式
     * </p>
     */
    public function classify() {
        $data = [];
        if (!is_file(cacheData . '/classifyStyleArray.php')) {
            $this->classifyAchieved($data, 0);
            $classifyArray = var_export($data, true);
            file_put_contents(cacheData . '/classifyStyleArray.php', "<?php \n\r\$classifyStyleArray={$classifyArray} \r\n?>");
        } else {
            global $classifyStyleArray;
            include cacheData . '/classifyStyleArray.php';
            $data = $classifyStyleArray;
        }

        return $data;
    }

    /**
     *  <b>分类递归（数组）</b> 
     * @global type $classifyArray
     * @return type
     */
    public function classifyArray() {
        $data = [];
        if (!is_file(cacheData . '/classifyArray.php')) {
            $data = $this->classifyAchievedArray();
            $classifyArray = var_export($data, true);
            file_put_contents(cacheData . '/classifyArray.php', "<?php \n\r\$classifyArray={$classifyArray} \r\n?>");
        } else {
            global $classifyArray;
            include cacheData . '/classifyArray.php';
            $data = $classifyArray;
        }
        return $data;
    }

    /**
     * <b>分类递归实现方法</b> 
     * @param array $data <p>输出类
     * <b>函数的引用返回</b>
     * </p> 
     * @param int $id  <p>编号
     * <b>分类编号</b>
     * </p>
     * @param string $t <p>第归的格式
     * <b>递归格式(空格)</b>
     * </p>
     */
    private function classifyAchieved(&$data, $id = '', $t = '', $v = '') {
        $t .= '　　';
        $sql = 'select id,px,className,pid,setting,hide from classify where pid =' . $id . ' order by px desc';
        $rs = $this->conn->query($sql);

        foreach ($rs as $value) {
            $data [$value['id']]['pid'] = $value['pid'];
            $data [$value['id']]['id'] = $value['id'];
            $data [$value['id']]['px'] = $value['px'];
            $data [$value['id']]['className'] = $t . $v . $value['className'];
            $data[$value['id']]['setting'] = $value['setting'];
            $data[$value['id']]['hide'] = $value['hide'];
            $s = $this->classifyAchieved($data, $value['id'], $t, '├');
            if (!empty($s)) {
                $data [$value['id']]['disabled'] = 'disabled';
            }
        }
        return $rs;
    }

    /**
     * <b>分类递归 （数组）</b>
     * @param int $id  <p>编号
     * <b>分类编号</b>
     * </p>
     * @param string $t <p>第归的格式
     * <b>递归格式(空格)</b>
     * </p>
     */
    protected function classifyAchievedArray($id = 0) {
        $arr = array();
        //'id', 'pid', 'px', 'className', 'url', 'setting', 'folder'
        $sql = 'select id,pid,px,className as text,url,setting, folder,hide from classify where pid =' . $id . ' order by px desc';
        $rs = $this->conn->query($sql);
        foreach ($rs as $value) {

            $rs = $this->classifyAchievedArray($value['id']); //调用函数，传入参数，继续查询下级 
            if (!empty($rs)) {
                $value['state'] = 'closed';
                $value['children'] = $rs;
            }

            $arr [] = $value;
        }
        return $arr;
    }

    /**
     * <b>分页</b> 
     * <p>
     *  分页的逻辑算法<br/>
     * <b>注释：</b>
     * 原先 样式 上一页 1,2,3,4,5,6,7,8,9,10 下一页<br/>
     * 如果 当前页在 加 5 的状态下则<b>累算分页数</b>大于<b>分页总数 </b><br/>
     * 分页开始数则从 +4 位置为其实位
     * 输出结果为
     * 样式 上一页 2,3,4,5,6,7,8,9,10,11 下一页<br/>
     * </p>
     * <b>
     * 演示代码:
     * </b>
     * <pre>
     * <?php
     * $var  = new tfunction();
     * $var->Page('分页总数','分页开始数','分页结束数','当前页数');
     * ?>
     * @param int $pageTotal <p>
     * 分页总数 
     * </p>
     * @param int $pageStart <p>
     * 分页开始数 
     * </p>
     * @param int $PageCounts <p>
     * 每页数  <b></b>
     * </p>
     * @param type $page <p>
     * 当前页数 
     * </p>
     * @return array <p>
     * pageEnd 分页结束位数字
     * pageStart 分页开始位数字
     * pageCount 累算分页数
     * </p>
     */
    public function Page($pageTotal, $pageStart = 1, $PageCounts = 10, $page = 0) {
        //当前页数  如果为0 的状态下 初始化值则为1
        $Ispage = $page ? $page : 1;
        // 起始化尾页数为9 此数是3的倍数 如 3 、6、 9、12……；
        $pageEnd = 9;
        //累算分页数 
        $PageCount = ceil($pageTotal / $PageCounts);
        //如果分页累算总数小与分页结尾数据则不对数据进行计算并输出，反之则对分页起始数和结尾数进行计算
        if ($PageCount > $pageEnd) {
            //当前页数如果小与固定值10 的状况下则不对分页进行变化
            if ($Ispage >= 5) {
                $pageStart = $Ispage - 4;
                return $this->PageAchieved($Ispage, $pageStart, $pageEnd, $PageCount);
            }
        } else {
            $pageEnd = $PageCount;
        }
        return ['pageEnd' => $pageEnd, 'pageStart' => $pageStart, 'pageCount' => $PageCount];
    }

    /**
     * @param type $Ispage
     * @param type $pageStart
     * @param type $pageEnd
     * @param type $PageCount
     * @return type
     */
    private function PageAchieved($Ispage, $pageStart, $pageEnd, $PageCount) {
        if (($Ispage + 4) <= $PageCount) {
            $pageEnd = $Ispage + 4;
        } else {
            $pageStart = $PageCount - 8;
            $pageEnd = $PageCount;
        }
        return ['pageEnd' => $pageEnd, 'pageStart' => $pageStart, 'pageCount' => $PageCount];
    }

    /**
     * 格式化带有HTML标签内容
     * @param string  $Content 内容
     * @return string
     */
    public static function encode($Content) {
        return htmlspecialchars($Content, ENT_QUOTES);
    }

    /**
     * 解码内容返回带有HTML标签内容
     * @param string $Content 内容
     * @return string
     */
    public static function decode($Content) {
        return html_entity_decode($Content, ENT_QUOTES);
    }

    public static function dropQuote($Content) {
        $Content = str_replace('\'', '', $Content);
        $Content = str_replace('"', '', $Content);
        $Content = filter_var($Content, FILTER_SANITIZE_STRING);
        return $Content;
    }
    /**
     * 压缩驱除回车，tab 等符号.
     * @param type $str
     * @return type
     */
    public static function compressionFile($str) {
        return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</", "//", "'/\*[^*]*\*/'", "/\r\n/", "/\n/", "/\t/", '/>[ ]+</'), array(">\\1<", '', '', '', '', '', '><'), $str)));
    }

    public function arrayToXml($source, $charset = 'utf8') {
        if (empty($source)) {
            return false;
        }

        $xml = '';
        $xml .= $this->change($source);
        return $xml;
    }

    private function change($source) {
        $string = "";
        $tag = '';
        foreach ($source as $k => $v) {
            if (is_numeric($k)) {
                $k = '';
                $tag = 'node';
            }
            $string .= "<" . $tag . $k . ">";
            if (is_array($v) || is_object($v)) {
                $string .= $this->change($v) . "</" . $tag . $k . ">\r\n";
            } else {
                $string .= "<![CDATA[" . $v . "]]></" . $tag . $k . ">\r\n";
            }
            $string .= "";
        }
        return $string;
    }

}
