<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>"/>
        <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css"/>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
    </head>
    <body>
        <div class="adminContent">
            <dl>
                <dt>
                    文章回收
                    <span class="addLink">
                                [<a href='?cpage=1'>添加文章</a>]
                                [<a href='javascript:void(0)' id='retrievedArticles'>检索文章</a>]
                                [<a href='javascript:void(0)' id='column'>栏目管理</a>]
                                [<a href='?cpage=1'>更新列表</a>]
                                [<a href='?cpage=1'>更新文档</a>]
                                [<a href='newsSubject.php'>文章回收</a>]
                    </span>
                </dt>
           <dl>
         </div>
    </body>
</html>
