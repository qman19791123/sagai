<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
$tfunction = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>">
        <link rel="stylesheet"  type="text/css" href="../css/Font-Awesome/font-awesome.min.css">



    </head>
    <body>
        <?php
        $c = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_STRING);
        switch ($c) {
            case 'out':
                setcookie($systemName, 'cccc', time() - 1);
                session_unset();
                session_destroy();
                header('refresh:3;url=index.php');
                die('<div class="msgdiv"><span>准备提出系统请稍后</span></div>');
                break;
            default:
                # code...
                break;
        }
        ?>

        <div class="adminMain">
            <div class="top "><?php echo $systemName ?><span class="fa"></span></div>
            <div class="content1">
                <menu>
                    <li class="fa"><span>用户管理</span>
                        <p><a class="fa" href="./user.php" target="contentiframe">用户管理</a></p>
                    </li>
                    <li class="fa"><span>栏目管理</span>
                        <p><a class="fa" href="./classify.php" target="contentiframe">栏目管理</a></p>
                        <p><a class="fa" href="./classify.php?cpage=1" target="contentiframe">添加栏目</a></p>
                    </li>
                    <li class="fa"><span>内容管理</span>
                        <p><a class="fa" href="./news.php" target="contentiframe">文章管理</a></p>
                        <p><a class="fa" href="./newssubject.php" target="contentiframe">专题管理</a></p>
                    </li>
                    <li class="fa"><span>商店管理</span>
                        <p><a class="fa" href="./news.php" target="contentiframe">商品管理</a></p>
                        <p><a class="fa" href="./news.php" target="contentiframe">支付入口</a></p>
                    </li>
                    <li class="fa"><span>论坛管理</span>
                        <p><a class="fa" href="./news.php" target="contentiframe">论坛栏目管理</a></p>
                        <p><a class="fa" href="./news.php" target="contentiframe">论坛内容管理</a></p>
                    </li>

                    <li class="fa"><span>广告管理</span>
                        <p><a class="fa" href="" target="contentiframe">广告管理</a></p>
                    </li>
                    <li class="fa"><span>评论管理</span>
                        <p><a class="fa" href="" target="contentiframe">评论管理</a></p>
                    </li>
                    <li class="fa"><span>友情链接</span>
                        <p><a class="fa" href="" target="contentiframe">友情链接</a></p>
                    </li>


                    <li class="fa"><span>附件管理</span>
                        <p><a class="fa" href="./news.php" target="contentiframe">附件管理</a></p>
                    </li>

                    <li class="fa"><span>更新HTML</span>
                        <p><a class="fa" href="./news.php" target="contentiframe">更新主页HTML</a></p>
                        <p><a class="fa" href="./news.php" target="contentiframe">更新栏目HTML</a></p>
                        <p><a class="fa" href="./news.php" target="contentiframe">更新文档HTML</a></p>
                        <p><a class="fa" href="./news.php" target="contentiframe">更新专题HTML</a></p>

                    </li>



                    <li class="fa"><span>其他设置</span>
                        <p><a class="fa" href="" target="contentiframe">插件中心</a></p>
                        <p><a class="fa" href="" target="contentiframe">模板管理</a></p>
                        <p><a class="fa" href="" target="contentiframe">JS调用</a></p>
                        <p><a class="fa" href="" target="contentiframe">数据导入</a></p>
                    </li>
                </menu>
                <p class="webinfo">
                    <span class="fa">网站信息</span>
                    <a href="?c=out">退出后台</a>
                    <a href="?c=ContactUs">联系我们</a>
                    <a href="?c=Getnew">获取新版</a>
                </p>
            </div>
            <div class="content2">
                <iframe src="./pagemain.php" id="iframepage" class="contentiframe" name="contentiframe" onload="iFrameHeight()" scrolling="auto"></iframe>
            </div>
        </div>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/move.js"></script>
        <script type="text/javascript">

                    function iFrameHeight() {
                        try {
                            var ifm = document.getElementById("iframepage");
                            var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
                            if (ifm != null && subWeb != null) {
                                ifm.height = 0;
                                ifm.width = 0;
                                ifm.height = window.screen.height - 210;
                                //ifm.height = subWeb.body.scrollHeight;
                                //ifm.width = subWeb.body.scrollWidth;
                            }
                        } catch (e) {
                            return;
                        }
                    }
                    $(function (argument) {
                        var show = false;
                        $('.top span').on('click', function () {
                            if (!show) {
                                move('.content1')
                                        .set('background', 'rgba(50, 50, 50, 0.8)')
                                        .ease('in-out').x(0)
                                        .end();
                                show = true;
                            } else
                            {
                                move('.content1')
                                        .set('background', 'rgba(50, 50, 50, 0)')
                                        .ease('in-out').x(-800)
                                        .end();
                                show = false;
                            }
                        })
                        $('.content1 menu li:eq(0) p').show();
                        $('.content1 menu li').on('click', function () {
                            $('.content1 menu li p').hide();
                            $(this).find('p').show().on({'mousemove': function () {
                                    $(this).css('background', '#ccc');
                                }, 'mouseout': function () {
                                    $(this).removeAttr('style');
                                    $(this).css('display', 'block');
                                }
                            });
                        })
                        $('.content2').on('click', function () {
                            console.log("aaa");
                        })
                    })
        </script>
    </body>
</html>