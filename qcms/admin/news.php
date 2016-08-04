<?php
include '../config.php';
include lib.'tfunction.inc.php';
include lib.'conn.inc.php';
include 'isadmin.php';
$tfunction  = new tfunction();
$conn = $tfunction->conn;
$var = filter_input(INPUT_POST,'cpage',FILTER_VALIDATE_INT);
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <div class="adminContent">
            <?php 
                switch($cpage) :
                case 0:
            ?>
                <dl>
                    <dt>
                        分类管理
                        <span class="addLink">[<a href='?cpage=1'>添加新闻</a>]</span>
                    </dt>
                    <dd>
                        <div class="list atable">
                            <ul class="list atr" id="no">
                                <li>编号</li>
                                <li>排序</li>
                                <li style="width: 47%">名称</li>
                                <li style="width: 30%">操作</li>
                            </ul>
                            <?php
                                $sql = '';
                               $conn->query();
                            ?>

                        </div>
                    </dd>
                </dl>
            <?php
            break;
            endswitch;
            ?>
        </div>
    </body>
    
</html>