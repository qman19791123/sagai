<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;

// get
$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); //新闻编号 (有时间修改此命名)
$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);


// post
// 数据添加 删除 修改 操作
$specialName = filter_input(INPUT_POST, 'specialName', FILTER_SANITIZE_MAGIC_QUOTES);
$introduction = filter_input(INPUT_POST, 'introduction', FILTER_CALLBACK, ['options' => 'tfunction::encode']);


switch ($act) {
    case 1:
        empty($introduction) && die($tfunction->message($lang['mesgIntroductionNotEmpty']));
        empty($specialName) && die($tfunction->message($lang['mesgSpecialNameNotEmpty']));
        $pinyin = $tfunction->py($introduction, 'tfunction::ZNSymbolFilter');
        $conn->insert('special_config', [
            'specialName' => $specialName,
            'introduction' => $introduction,
            'pinyin' => $pinyin,
            'time' => time()
        ]);
        die($tfunction->message($lang['mesgAddContentSuccess'], 'newssubject.php'));
        break;
    case 2:
        empty($introduction) && die($tfunction->message($lang['mesgIntroductionNotEmpty']));
        empty($specialName) && die($tfunction->message($lang['mesgSpecialNameNotEmpty']));
        $conn->where('id=' . $id)->update('special_config', [ 'specialName' => $specialName, 'introduction' => $introduction]);
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php echo $systemName ?>
        </title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>" />
        <!--字体图标 css -->
        <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css" />
        <script type="text/javascript" src="../js/jquery.min.js"></script>
    </head>
    <body>
        <div class="adminContent">
            <?php
            switch ($cpage) :
                case 0:
                    ?>

                    <?php
                    $pagec = ($page - 1) < 0 ? 0 : ($page - 1) * 10;

                    $poUrl = '';

                    $classifyRsJson = [];

                    $classifyRs = $conn->select('id,className')->get('classify');

                    foreach ($classifyRs as $classifyValue) {
                        $classifyRsJson['classify_' . $classifyValue['id']] = $classifyValue['className'];
                    }

                    $classifyJson = $classifyRsJson;

                    $data = $conn->select(['id', 'specialName', 'time'])->order_by('id desc')->limit(10)->offset($pagec)->get('special_config');
                    $dataCount = $conn->select('count(id) as cid')->where('1=1 and isdel=1')->get('special_config');
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['specialArticleManager']; ?>
                            <span class="addLink">
                                [<a href='?cpage=1'><?php echo $lang['specialAddTheme']; ?></a>]
                                [<a href='?cpage=1'><?php echo $lang['newsUpdateList']; ?></a>]
                                [<a href='?cpage=1'><?php echo $lang['newsUpdateDocumentation']; ?></a>]
                                [<a href='#'><?php echo $lang['specialThemeRecycling']; ?></a>]
                            </span>
                        </dt>
                        <?php if (!empty($data)): ?> 

                            <dd>
                                <div class="list atable">
                                    <ul class="list atr" id="no">
                                        <li>
                                            <?php echo $lang['choose']; ?>
                                        </li>
                                        <li>
                                            <?php echo $lang['id']; ?>
                                        </li>
                                        <li style="width: 40%">
                                            <?php echo $lang['name']; ?>
                                        </li>
                                        <li style="width: 17%">
                                            <?php echo $lang['time']; ?>
                                        </li>
                                        <li style="width: 25%">
                                            <?php echo $lang['operation']; ?>
                                        </li>
                                    </ul>
                                    <?php foreach ($data as $rs): ?>
                                        <ul class="list atr">
                                            <li><input name="checkid" type="checkbox" value="<?php echo $rs['id'] ?>"></li>
                                            <li><?php echo $rs['id'] ?></li>
                                            <li><?php echo $rs['specialName'] ?></li>
                                            <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>
                                            <li>
                                                <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>"><?php echo $lang['reading']; ?></a>
                                                <a href="?cpage=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['update']; ?></a>
                                                <a href="?cpage=3&id=<?php echo $rs['id'] ?>">专题栏管理</a>
                                                <a href="?cpage=4&id=<?php echo $rs['id'] ?>">专题文章管理</a>
                                                <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?> ?>"><?php echo $lang['remove']; ?></a>
                                            </li>
                                        </ul>
                                    <?php endforeach; ?>
                                </div>
                            </dd>
                            <dd class='toolbar'>
                                <button type='button' id='all' onclick="javascript:$('input[name=checkid]').attr('checked', true).prop('checked', true)"><?php echo $lang['selectAll']; ?></button>
                                <button type='button' id='cancel' onclick="javascript:$('input[name=checkid]').removeAttr('checked', '').prop('checked', false)"><?php echo $lang['cancel']; ?></button>
                                <button type='button' id='delete'><?php echo $lang['remove']; ?></button>
                            </dd>
                            <dd class='page'>
                                <a href="?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>&<?php echo $poUrl ?>"><?php echo $lang['lastPage'] ?></a>
                                <?php
                                $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                                for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                                    ?>
                                    <a href="?page=<?php echo $i ?>&<?php echo $poUrl ?>"><span style="color:<?php echo $i == $page || ($page <= 0 && $i == 1) ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                                    <?php
                                endfor;
                                ?>
                                <a href='?page=<?php echo ($page < $p['pageCount'] ? $page <= 0 ? $page + 2 : $page + 1 : $page) ?>&<?php echo $poUrl ?>'><?php echo $lang['nextPage'] ?></a>
                            </dd>
                        <?php else: ?>
                            <dd class='notInfo'>
                                <span><?php echo $lang['notInfo'] ?></span>
                            </dd>
                        <?php endif; ?>
                    </dl>
                    <?php
                    break;
                case 1:
                case 2:
                    $specialName = '';
                    $introduction = '';
                    if (!empty($id)) {

                        $Rs = $conn
                                ->select(['specialName', 'introduction'])
                                ->where(['id' => $id])
                                ->get('special_config');

                        $specialName = $Rs[0]['specialName'];
                        $introduction = tfunction::decode($Rs[0]['introduction']);
                    }
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['specialArticleManager']; ?>
                        </dt>
                        <dd>
                            <form method="post"  enctype="multipart/form-data" action="?act=<?php echo $cpage ?><?php !empty($id) && print('&id=' . $id) ?>">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>
                                            专题名稱:
                                        </li>
                                        <li>
                                            <input style="width: 80%;" name="specialName" value="<?php echo $specialName ?>">
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>
                                            介绍:
                                        </li>
                                        <li>
                                            <textarea style="width: 80%;height: 100px;" name="introduction" ><?php echo $introduction; ?></textarea>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tijiao">
                                    <button type="submit"><?php echo $lang['submit']; ?></button>
                                    <button type="reset"><?php echo $lang['reset']; ?></button>
                                    <button type="button" id='fanhui'><?php echo $lang['back']; ?></button>
                                </div>
                            </form>
                        </dd>
                    </dl>

                    <?php
                    break;
                case 3:
                    ?>
                    <dl>
                        <dt>
                            aaa 3
                        </dt>
                    </dl>
                    <?php
                    break;
                case 4:
                    ?>
                    <dl>
                        <dt>
                            aaa 4
                        </dt>
                    </dl>
            <?php endswitch; ?> 
        </div>
        <script>
            //mouseover
            $('.adminContent .atable ul').on('mouseover', function () {
                $(this).css({'background': '#444'});
                $(this).find('a').css({'background': '', 'color': '#fff'});
            }).on('mouseout', function () {
                $(this).css({'background': '', 'color': ''});
                $(this).find('a').css({'background': '', 'color': ''});
            });
            //删除提示 code start
            $('.delmes').on('click', function () {
                return confirm('<?php echo $lang['mesgConfirmDeletion']; ?>');
            });
            //返回上一页 按钮 code start
            $('#fanhui').on('click', function () {
                self.history.go(-1);
            });


        </script>
    </body>

</html>