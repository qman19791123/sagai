<?php
phpinfo();
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
////test code
//
//
//$xhprof_data = xhprof_disable();
//$XHPROF_ROOT = "../xhprof_lib/utils/";
//include_once $XHPROF_ROOT . "xhprof_lib.php";
//include_once $XHPROF_ROOT . "xhprof_runs.php";
//$xhprof_runs = new XHProfRuns_Default();
//$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");
//echo "http://localhost/xhprof/xhprof_html/index.php?run={$run_id}&source=xhprof_testing\n";
?>
<?php

//$isFile = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
//$host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
//$addr = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_URL);
//$isIpFun = $host;
//empty($isIpFun) && $isIpFun = $addr;
//$isIpFun = md5($isIpFun . '/' . $isFile);
//$isIpFun = substr($isIpFun, 8, 16);
//$i = 0;
//$cx = [];
//do {
//    $cx[] = ord($this->isFile[$i++]);
//} while (!empty($this->isFile[$i]));
//$cc = (int) join(array_slice($cx, 4, 8)) + (int) join(array_slice($cx, 0, 4)) + (int) join(array_slice($cx, 8, 15)) + (int) join(array_slice($cx, 8, 12));
//$shmid = shmop_open($cc, 'c', 0755, 1024);
////shmop_write($shmid, 111111, 0);
//echo shmop_read($shmid, 0, 20);
//shmop_close($shmid);
//for ($key = array(); sizeof($key) < strlen($filename); $key[] = ord(substr($filename, sizeof($key), 1)))
//    ;
//return dechex(array_sum($key))
//
//

