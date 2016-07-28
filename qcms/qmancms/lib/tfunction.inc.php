<?php

class tfunction{
    public $conn,$less,$zd;
    function __construct()
    {
        if(!class_exists('conn'))
        {
            include lib.'conn.inc.php';
        }
        if(!class_exists('lessc')){
            include plus.'/lessc.inc.php';

        }
        if(!function_exists('zhconversion_hans')){
            include plus."Zdian.php";
            include plus."convert.php";
            $this->zd = $zd;
        }
        $this->conn = new conn;
        $this->less = new lessc;
    }

    public function lessc($file='')
    {
        $fileLess = install.'css/less/'.$file;
        if(is_file($fileLess)){
            $fileCssName = pathinfo($fileLess);
            $fileCss = 'css/css/'.$fileCssName['filename'].'.css';
            $this->less->checkedCompile($fileLess,install.$fileCss);
            return $fileCss;
        }
    }

    public function message($message = '' , $url = ''){
       if(empty($message)){
            return ;
        }
       ob_start();
       include tempUrl.'/system/message.tp';
       $content = ob_get_contents();
       ob_end_clean();
       return $content;
    }

    public function gotoUrl($url = ''){
        $message = '';
        ob_start();
        include tempUrl.'/system/message.tp';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function py($string){
      $string = zhconversion_hans($string);
      return strtr($string , $this->zd);
    }
    
    public function classify(){
        $rsd = array();
        $sql = 'select id,px,className,pid from classify where pid = 0';
        $rs = $this->conn->query($sql);
        foreach ($rs as $value) {
          $data='';
          $rsd[] = $value;
          $this->meun($value['id'],'　',$data);
          if(empty($data)) continue;
            foreach ($data as $value1) {
              $rsd[] = $value1;
            }
        }
        return $rsd;
    }

    private function meun($id='',$t='　',&$data)
    {
        $t.='　　';
        $sql = 'select id,px,className,pid from classify where pid ='.$id.' order by px desc';
        $rs = $this->conn->query($sql);
        foreach ($rs as $value) {
            $data [$value['id']]['pid']= $value['pid'];
            $data [$value['id']]['id']= $value['id'];
            $data [$value['id']]['px']= $value['px'];
            $data [$value['id']]['className']= $t.'├'.$value['className'];
            $this->meun($value['id'],$t,$data);
        }
    }
}