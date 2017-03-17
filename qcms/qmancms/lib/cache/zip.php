<?php

//$zip = new ZipArchive; 
////$res = $zip->open('data', ZipArchive::CREATE);
//$zip->open('data');
//
////print_r($zip->statIndex(0));
////if ($zip->open('web.zip') === TRUE) {
////    echo $zip->getNameIndex(3);
////    var_dump($zip->getFromName($zip->getNameIndex(3))) ;
////   $zip->close();
////}
//$zip->addFromString('test2', 'file content goes here');
////echo $zip->getNameIndex(0);
//$zip->close();

class zip {

    private $zip;
    private $cacheFloder = cacheFloder;
    private $cachedTime = cachedTime;
    private $cache = 'data.zip';

    public function __construct() {
        
        $this->zip = new ZipArchive;
        $this->zip->open($this->cacheFloder.'/'.$this->cache, ZipArchive::CREATE);
    }

    public function get($key) {
        $stat = $this->stat($key);
        if(!$stat ||  $stat['mtime']+ $this->cachedTime < time()){
            return $this->zip->getFromName($key);
        }
    }

    public function set($key, $value) {
        $this->zip->addFromString($key, $value);
    }

    public function init($key) {
        return $this->zip->statName($key);
    }

    public function del($key) {
        
    }

    public function flush() {
        
    }

}

