<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include lib . 'conn.inc.php';
include 'isadmin.php';
$tfunction = new tfunction();
$conn = $tfunction->conn;
$cpage = filter_input(INPUT_POST, 'cpage', FILTER_VALIDATE_INT);
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
            <?php
            switch ($cpage) :
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
                                    <li style="width: 30%">名称</li>
                                    <li style="width: 17%">时间</li>
                                    <li style="width: 30%">操作</li>
                                </ul>
                                <?php
                                $sql = 'select * from `news_config` limit 10 Offset 0';
                                $data = $conn->query($sql);
                                foreach ($data as $rs):
                                    ?>
                                    <ul class="list atr">
                                        <li><?php echo $rs['id'] ?></li>
                                        <li><?php echo $rs['sort'] ?></li>
                                        <li><?php echo $rs['title'] ?></li>
                                        <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>

                                        <li>
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>">修改</a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?>">删除</a>
                                        </li>
                                    </ul>
                                <?php endforeach; ?>
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