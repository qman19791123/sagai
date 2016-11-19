<?php

ini_set('date.timezone', 'Asia/Shanghai');
define('noCache', TRUE);
include 'config.php';
include lib . 'tfunction.inc.php';



if (is_file(core . 'controllers.php')) {
    include(core . 'controllers.php');
}
if (is_file(core . 'models.php')) {
    include(core . 'models.php');
}
if (is_file(core . 'load.php')) {
    include(core . 'load.php');
}

$PATH_INFO = filter_input(INPUT_SERVER, 'PATH_INFO', FILTER_SANITIZE_MAGIC_QUOTES);
isset($PATH_INFO) && $_AppPathArr = explode("/", $PATH_INFO);
$class = empty($_AppPathArr [1]) ? "index" : $_AppPathArr [1];
empty($_AppPathArr [2])|| $class2 = $_AppPathArr [2];
empty($_AppPathArr [3])|| $class3 = $_AppPathArr [3];

$load = new load();
$load->show($class, 'index',$class2,$class3);

?>