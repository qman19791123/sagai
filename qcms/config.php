<?php

$systemName = 'qman-cms';
/* * ***********************************************
  数据库设置
 * *********************************************** */
//数据库类型
#$dataType='Mysql';
$dataType = 'SQLite';
//数据库地址
#$dataLocal='127.0.0.1';
$dataLocal = 'data/';
//数据库名字
$dataName = 'Data';
//数据库用户名
$dataUser = 'root';
//数据库密码
$dataPassword = ''; //数据库密码
//表名前缀
$dataCp = 'qman_';
/* * ***********************************************
  模版设置
 * *********************************************** */
//模版路径
$tempUrl = 'temp';
/* * ***********************************************
  网站设置
 * *********************************************** */
//安装目录
$install = '/';
//网站编码
$webCharset = 'utf-8';
//当为HTML时，1：自动生成 2：不生成
$operationFunction = 2;
//使用语言包
$language = 'cn.php';
//后台登陆验证码
$yzcode = 'qman';
//是否开启缓存
$cacheOpen = TRUE;
//缓存时间（以秒为单位）
$cachedTime = '90000000';
//缓存目录
$cachedPath = 'cache';
//缓存数据目录
$cacheData = 'cacheData';
//页面是否压缩
$compression = False;
//是否开启静态
$StaticOpen = False;
//静态文件目录
$staticFloder = 'zp';
/* * ***********************************************
  上传设置
 * *********************************************** */
//上传格式
$fileType = array('jpg', 'gif');
//上传大小
$fileMaxSize = '500000';

define('dataType', $dataType);
define('dataLocal', $dataLocal);
define('dataName', $dataName);
define('dataUser', $dataUser);
define('dataPassword', $dataPassword);
define('dataCharset', $webCharset == 'utf-8' ? 'UTF8' : 'GBK');
define('dataCp', $dataCp);


define('operationFunction', $operationFunction);
define('install', __dir__ . $install);
define('tempUrl', install . '/' . $tempUrl . '/');
//系统文件
define('core', install . 'qmancms/core/');
define('lib', install . 'qmancms/lib/');
define('plus', install . 'qmancms/plus/');
define('lang', install . 'qmancms/language/');





// 不可删除的管理员
define('unableRemoveManager', 'admin');
//页面压缩
define('compression', $compression);
//开启页面缓存
define('cacheOpen', $cacheOpen);
//缓存目录
define('cacheFloder', install . $cachedPath);
//缓存数据目录
define('cacheData', install . $cachedPath . '/' . $cacheData);
//是否开启静态
define('StaticOpen', $StaticOpen);
//静态目录
define('staticFloder', install . $staticFloder);

header("Content-type:text/html;charset=utf-8");

if (cacheOpen && !is_dir(cacheFloder)) {
    @mkdir(cacheFloder, 0777);
}


if (StaticOpen && !is_dir(staticFloder)) {

    mkdir(staticFloder, 0777);
}

if (!is_dir(cacheData)) {
    mkdir(cacheData, 0777);
}


//设置session
$cookiesSystemName = filter_input(INPUT_COOKIE, $systemName, FILTER_SANITIZE_STRING);
if (empty($cookiesSystemName)) {
    $p = uniqid('qmancms' . md5(microtime()));
    session_name($systemName);
    session_id($p);
    session_start();
    setcookie($systemName, $p, 0, '/');
} else {
    session_name($systemName);
    session_id($cookiesSystemName);
    session_start();
}

//设置页面缓存，这个只是模拟静态页面在再次访问时候页面被设置换缓存时候的效果，不可能和真正意义上的静态页面进行对比。

$If_Modified_Since = filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE');
$seconds_to_cache = 300;
if (!defined('noCache')) {
    if (!empty($If_Modified_Since) && strtotime($If_Modified_Since) > time() && cacheOpen) {
        header("Expires: $If_Modified_Since");
        header('Last-Modified: ' . $If_Modified_Since, true, 200);
        header('HTTP/1.1 304 Not Found');
        header("status: 304 Not Found");
        header("Pragma: cache");
        header('Cache-Control: max-age=' . $seconds_to_cache);
        exit();
    } else {
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
        header("Expires: $ts");
        header('Last-Modified: ' . $ts, true, 200);
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");
    }
}

