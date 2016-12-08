<?php

ini_set('date.timezone', 'Asia/Shanghai');
define('noCache', TRUE);
include 'config.php';
include lib . 'tfunction.inc.php';

class_exists('XSLTProcessor') || die("XSLTProcessor  no longer exis");


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

$class1 = empty($_AppPathArr [1]) ? "index" : $_AppPathArr [1];

$class2 = empty($_AppPathArr [2]) ? "home" : $_AppPathArr [2];

if (!empty($_AppPathArr)) {
    unset($_AppPathArr[0], $_AppPathArr[1], $_AppPathArr[2]);
    $rsArray = array_values($_AppPathArr);
}

$class3 = empty($_AppPathArr [3]) ? [] : $rsArray;

$load = new load($class1);
//$load->show($class, 'index', $class2, $class3);
$load->show($class2, $class3);
?>