<?php 
include '../config.php';
include lib.'tfunction.inc.php';
include lib.'conn.inc.php';
include 'isadmin.php';
$tfunction  = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $systemName?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo '../'.$tfunction->lessc('admin.less')?>">
    <link rel="stylesheet"  type="text/css" href="../css/Font-Awesome/font-awesome.min.css">
</head>
<body>
    <div class="adminMain">
        <div class="top "><?php echo $systemName?><span class="fa"></span></div>
        <div class="content1">
            <menu>
                <li class="fa">系统管理
                    <p><a class="fa" href="./user.php" target="contentiframe">用户管理</a></p>
                    <p><a class="fa" href="." target="contentiframe">更新缓存</a></p>
                    <p><a class="fa" href="" target="contentiframe">生成静态</a></p>
                </li>
                <li class="fa">分类管理
                    <p><a class="fa" href="./classify.php" target="contentiframe">分类管理</a></p>
                    <p><a class="fa" href="./classify.php?cpage=1" target="contentiframe">添加分类</a></p>
                </li>
                <li class="fa">内容管理
                    <p><a class="fa" href="" target="contentiframe">内容管理</a></p>
                    <p><a class="fa" href="" target="contentiframe">添加内容</a></p>
                    <p><a class="fa" href="" target="contentiframe">批量替换</a></p>
                    <p><a class="fa" href="" target="contentiframe">信息采集</a></p>
                </li>
                <li class="fa">留言板
                    <p><a class="fa" href="" target="contentiframe">留言板</a></p>
                </li>
                <li class="fa">其他设置
                    <p><a class="fa" href="" target="contentiframe">插件中心</a></p>
                    <p><a class="fa" href="" target="contentiframe">模板管理</a></p>
                    <p><a class="fa" href="" target="contentiframe">JS调用</a></p>
                    <p><a class="fa" href="" target="contentiframe">数据导入</a></p>
                </li>
            </menu>
        </div>
        <div class="content2">
            <iframe src="./pagemain.php" id="iframepage" class="contentiframe" name="contentiframe" onload="iFrameHeight()" scrolling="no"></iframe>
        </div>
    </div>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/move.js"></script>
    <script type="text/javascript">

        function iFrameHeight() {   
            var ifm= document.getElementById("iframepage");   
            var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;   
            if(ifm != null && subWeb != null) {
                ifm.height=0;
                ifm.width=0;
                ifm.height = 770;
                //ifm.height = subWeb.body.scrollHeight;
                //ifm.width = subWeb.body.scrollWidth;
            }
        }
        $(function (argument) {
            var show=false;
            $('.top span').on('click',function(){
                if(!show){
                    move('.content1')
                    .set('background', 'rgba(50, 50, 50, 0.8)')
                    .ease('in-out').x(0)
                    .end();
                    show=true;
                }
                else
                {
                     move('.content1')
                    .set('background', 'rgba(50, 50, 50, 0)')
                    .ease('in-out').x(-800)
                    .end();
                    show=false;
                }
            })
            $('.content1 menu li:eq(0) p').show();
            $('.content1 menu li').on('click',function(){
                $('.content1 menu li p').hide();
                    $(this).find('p').show().on({'mousemove':function(){
                        $(this).css('background','#ccc');
                    },'mouseout':function(){
                        $(this).removeAttr('style');
                        $(this).css('display','block');
                    }
                });
            })
            $('.content2').on('click',function(){
                console.log("aaa");
            })
        })
    </script>
</body>
</html>