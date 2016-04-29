<?php

$systemName = 'qman-cms';
/*************************************************
数据库设置
*************************************************/
//数据库类型
#$dataType='Mysql';
$dataType = 'SQLite';
//数据库地址
#$dataLocal='127.0.0.1';
$dataLocal='data/';
//数据库名字
$dataName='Data';
//数据库用户名
$dataUser='root';
//数据库密码
$dataPassword='';//数据库密码
//表名前缀
$dataCp='qman_';
/*************************************************
模版设置
*************************************************/
//模版路径
$tempUrl = 'temp';
/*************************************************
网站设置
*************************************************/
//安装目录
$install = '/';
//网站编码
$webCharset = 'utf-8'; 
//当为HTML时，1：自动生成 2：不生成
$operationFunction = 2;
//使用语言包
$language='cn.php';
//后台登陆验证码
$yzcode='qman'; 
//是否开启缓存
$cacheOpen = False;
//缓存时间（以秒为单位）
$cachedTime = '90000000';
//缓存目录
$cachedPath = 'cache';
//页面是否压缩
$compression = False;
//是否开启静态
$StaticOpen = False;
//静态文件目录
$staticFloder = 'zp';
/*************************************************
上传设置
*************************************************/
//上传格式
$fileType = array('jpg','gif');
//上传大小
$fileMaxSize  = '500000';

define('dataType', $dataType);
define('dataLocal' , $dataLocal);
define('dataName' , $dataName);
define('dataUser' , $dataUser);
define('dataPassword' , $dataPassword);
define('dataCharset' , $webCharset=='utf-8'?'UTF8':'GBK');
define('dataCp' , $dataCp);


define('operationFunction' , $operationFunction);
define('install' , __dir__.$install);
define('tempUrl' , install.'/'.$tempUrl.'/');
//系统文件
define('lib' , install.'qmancms/lib/');
define('plus' , install.'qmancms/plus/');

// 不可删除的管理员
define('unableRemoveManager' , 'admin'); 
//页面压缩
define('compression' , $compression); 
//开启页面缓存
define('cacheOpen' , $cacheOpen);
//缓存目录
define('cacheFloder' ,install.$cachedPath);
//是否开启静态
define('StaticOpen' , $StaticOpen);
//静态目录
define('staticFloder' , install.$staticFloder);

if(cacheOpen && !is_dir(cacheFloder)){
    @mkdir(cacheFloder,0777);
}
if(StaticOpen && !is_dir(staticFloder)){
    @mkdir(staticFloder,0777);
}
//设置session
if(empty($_COOKIE[$systemName])){
    session_name($systemName);
    session_id('qmancms'.md5(rand(10000,99999)+time()).uniqid(time()));
    session_start();
}
else
{
    session_name($systemName);
    session_id($_COOKIE[$systemName]);
    session_start();
}
