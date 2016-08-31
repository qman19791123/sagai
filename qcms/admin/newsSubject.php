<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;
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
            $classifyRsJson = [];

            $classifyRs = $conn->select('id,className')->get('classify');

            foreach ($classifyRs as $classifyValue) {
                $classifyRsJson['classify_' . $classifyValue['id']] = $classifyValue['className'];
            }

            $classifyJson = $classifyRsJson;

            $pagec = ($page - 1) < 0 ? 0 : ($page - 1) * 10;
            $data = $conn->select(['checked', 'time', 'id', 'sort', 'title', 'classifyId'])->where('1=1 and isdel=1')->order_by('id desc')->limit(10)->offset($pagec)->get('news_config');
            $dataCount = $conn->select('count(id) as cid')->where('1=1 and isdel=1')->get('news_config');
            ?>
            <dl>
                <dt>
                    文章回收
                <span class="addLink">
                    [<a href='newsSubject.php'>文章管理</a>]
                </span>
                </dt>
                <dd>
                    <div class="list atable">
                        <ul class="list atr" id="no">
                            <li>编号</li>
                            <li>排序</li>
                            <li style="width: 10%">栏目</li>
                            <li style="width: 40%">名称</li>
                            <li style="width: 5%">审核</li>
                            <li style="width: 17%">时间</li>
                            <li style="width: 20%">操作</li>
                        </ul>
                        <?php foreach ($data as $rs): ?>
                            <ul class="list atr">
                                <li><?php echo $rs['id'] ?></li>
                                <li><?php echo $rs['sort'] ?></li>
                                <li data-id="<?php echo $rs['classifyId'] ?>"></li>
                                <li><?php echo $rs['title'] ?></li>
                                <li><?php
                                    if (is_numeric($rs['checked'])) {
                                        switch ($rs['checked']) {
                                            case 0 :
                                                echo '未通过';
                                                break;
                                            case 1:
                                                echo '等待';
                                                break;
                                            case 999:
                                                echo '通过';
                                                break;
                                        }
                                    } else {
                                        echo '等待';
                                    }
                                    ?></li>
                                <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>
                                <li>
                                    <a href="?act=1&id=<?php echo $rs['id'] ?>" class="checked">还原</a>
                                    <a class="delmes nopt" href="?act=2&id=<?php echo $rs['id'] ?>">删除</a>
                                </li>

                            </ul>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($data)): ?>
                        <div class='page'>
                            <a href="?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>&<?php echo $poUrl ?>">上一页</a>
                            <?php
                            $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                            for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                                ?>
                                <a href="?page=<?php echo $i ?>&<?php echo $poUrl ?>"><span style="color:<?php echo $i == $page ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                                    <?php
                                endfor;
                                ?>
                            <a href='?page=<?php echo ($page < $p['pageCount'] ? $page + 1 : $page) ?>&<?php echo $poUrl ?>'>下一页</a>
                        </div>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
        <script type="text/javascript">
<?php printf('var classifyJson =%s;', json_encode($classifyJson)); ?>
            $('.atable ul li').each(function (i, p) {
                if ($(p).attr('data-id'))
                {
                    $(p).text(classifyJson['classify_' + $(p).attr('data-id')]);
                }
            });
        </script>
    </body>
</html>
