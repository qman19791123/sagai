<?php

$xhprofTest = FALSE;
$xhprof = function ($callbaxk) {
    xhprof_enable(XHPROF_FLAGS_MEMORY + XHPROF_FLAGS_CPU + XHPROF_FLAGS_NO_BUILTINS, array('ignored_functions' => array('call_user_func', 'call_user_func_array')));
    xhprof_sample_enable();
    $callbaxk();
    $xhprof_data = xhprof_sample_disable();
    include_once "xhprof_lib/utils/xhprof_lib.php";
    include_once "xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();
    $xhprof_runs->save_run($xhprof_data, str_replace(".", "_", basename(__FILE__)));
};

$content = function () {
    ini_set('date.timezone', 'Asia/Shanghai');

    include 'config.php';
    include lib . 'tfunction.inc.php';
    include lib . 'tcache.inc.php';

    class_exists('XSLTProcessor') || die("XSLTProcessor  no longer exis");

    include(core . 'controllers.php');
    include(core . 'models.php');
    include(core . 'load.php');

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
    print( $load->show($class2, $class3));
};

if ($xhprofTest) {
    $xhprof($content);
} else {
    $content();
}
?>