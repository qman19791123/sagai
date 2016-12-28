<?php

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

$isFile = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
$host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
$addr = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_URL);
$isIpFun = $host;
empty($isIpFun) && $isIpFun = $addr;
$isIpFun = md5($isIpFun . '/' . $isFile);
$isIpFun = substr($isIpFun, 8, 16);
$cx[] = 0;
$cc = 0;

for ($ci = 0,$i = 0; $i < strlen($isIpFun); ++$i,++$ci) {
    $cx[$ci] = ord($isIpFun[$i]);
    if ($ci > 8) {
        $cc += (int)$cx[0] . $cx[1] . $cx[2].$cx[3]  + (int) $cx[4] . $cx[5].$cx[6].$cx[7];
        $ci = 0;
    }
}

$shmid = shmop_open($cc, 'c', 0755, 1024);
shmop_write($shmid, 111111, 0);
echo shmop_read($shmid,0,20);
shmop_close($shmid);