<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;


$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);


// 一个匿名方法 获取文章和对应的栏目
$AMNClassifyNewContent = function($ajaxId) use($conn) {
    $classify = $conn->select(['a.folder as folder', 'a.summary as summary', 'pinyin', 'a.id as aid', 'b.id as bid'])
            ->join('classify as a', 'a.id=b.classifyId', 'left')
            ->where('b.id in(' . $ajaxId . ')')
            ->get('news_config as b');


    return $classify;
};

switch ($act) {
    case 1:
        $ajaxId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$ajaxId) {
            $ajaxId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!$ajaxId) {
                die('false');
            }
        }

        $ClassifyNewConten = $AMNClassifyNewContent($ajaxId);

        foreach ($ClassifyNewConten as $k => $v) {
            $file = trim(staticFloder) . '/' . trim($v['folder']) . '/' . trim($v['pinyin']) . '.html.del';

            if (StaticOpen && is_file($file)) {
                //存在则修改文件名
                //去除.del的后缀
                rename($file, mb_substr($file, 0, -4, 'UTF-8'));
                //rename($file, rtrim($file, '.del'));
            }
            $classifyCount[$v['aid']] = $v['summary'];

            $classifyIdArr[$v['aid']] = empty($classifyIdArr[$v['aid']]) || !isset($classifyIdArr[$v['aid']]) ? 1 : $classifyIdArr[$v['aid']] + 1;

            $newidArr[] = $v['bid'];
        }
        if (!empty($classifyIdArr)) {
            foreach ($classifyIdArr as $k => $v) {
                $conn->where(['id' => $k])->update('classify', ['summary' => ($classifyCount[$k] + $v)]);
                //防止操作过于频繁造成的计算过大的问题
                sleep(0.5);
            }
            $conn->where('id in (' . $ajaxId . ')')->update('news_config', ['isdel' => 0]);
            sleep(0.5);
            $p = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_BOOLEAN);
            if ($p === true) {
                die('true');
            }
        } else {
            die($tfunction->message($lang['newsArticlesRestoreFailed'], 'newsSubject.php'));
        }
        die($tfunction->message($lang['newsArticlesRestoreSuccess'], 'newsSubject.php'));

        break;
    case 2:
        //为了避免误差操作，程序会将这些被删除的数据以文本的方式存放在硬盘中,用户可以自行删除此类数据，文件存放在 dustbin 文件夹中（已时间存放）。

        $ajaxId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$ajaxId) {
            $ajaxId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!$ajaxId) {
                die('false');
            }
        }

        $rs_news_config_bak = var_export($conn->where('id in (' . $ajaxId . ')')->get('news_config'), true);
        $rs_news_content_bak = var_export($conn->where('newsId in (' . $ajaxId . ')')->get('news_content'), true);
        $dustbin = install . '/dustbin/' . date('Ymd');

        if (!is_dir($dustbin)) {
            mkdir($dustbin, 0777, true);
        }

        //备份数据
        file_put_contents($dustbin . '/NewsContentBak_' . str_replace(' ', '-', microtime()), $rs_news_content_bak);
        file_put_contents($dustbin . '/NewsConfigBak_' . str_replace(' ', '-', microtime()), $rs_news_config_bak);

        //开始删除静态文件和数据
        $ClassifyNewConten = $AMNClassifyNewContent($ajaxId);

        foreach ($ClassifyNewConten as $k => $v) {
            $file = trim(staticFloder) . '/' . trim($v['folder']) . '/' . trim($v['pinyin']) . '.html.del';

            if (StaticOpen && is_file($file)) {
                //存在则删除文件
                unlink($file);
            }
        }

        $conn->where('id in (' . $ajaxId . ')')->delete('news_config');
        $conn->where('id in (' . $ajaxId . ')')->delete('news_content');

        die($tfunction->message($lang['mesgRemoveContentSuccess'], 'newsSubject.php'));

        break;
}
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
            $pagec = ($page - 1) < 0 ? 0 : ($page - 1) * 10;

            $poUrl = '';

            $classifyRsJson = [];

            $classifyRs = $conn->select('id,className')->get('classify');

            foreach ($classifyRs as $classifyValue) {
                $classifyRsJson['classify_' . $classifyValue['id']] = $classifyValue['className'];
            }

            $classifyJson = $classifyRsJson;

            $data = $conn->select(['checked', 'time', 'id', 'sort', 'title', 'classifyId'])->where('1=1 and isdel=1')->order_by('id desc')->limit(10)->offset($pagec)->get('news_config');
            $dataCount = $conn->select('count(id) as cid')->where('1=1 and isdel=1')->get('news_config');
            ?>
            <dl>
                <dt>
                    <?php echo $lang['newsArticlesRecycling']; ?>
                <span class="addLink">
                    [<a href='news.php'><?php echo $lang['newsArticleManager']; ?></a>]
                </span>
                </dt>
                <?php if (!empty($data)): ?>
                    <dd>
                        <div class="list atable">
                            <ul class="list atr" id="no">
                                <li><?php echo $lang['choose']; ?></li>
                                <li><?php echo $lang['id']; ?></li>
                                <li><?php echo $lang['sort']; ?></li>
                                <li style="width: 10%"><?php echo $lang['columns']; ?></li>
                                <li style="width: 40%"><?php echo $lang['name']; ?></li>
                                <li style="width: 5%"><?php echo $lang['check']; ?></li>
                                <li style="width: 17%"><?php echo $lang['time']; ?></li>
                                <li style="width: 20%"><?php echo $lang['operation']; ?></li>
                            </ul>
                            <?php foreach ($data as $rs): ?>
                                <ul class="list atr">
                                    <li><input name="checkid" type="checkbox" value="<?php echo $rs['id'] ?>"></li>
                                    <li><?php echo $rs['id'] ?></li>
                                    <li><?php echo $rs['sort'] ?></li>
                                    <li data-id="<?php echo $rs['classifyId'] ?>"></li>
                                    <li><?php echo $rs['title'] ?></li>
                                    <li><?php
                                        if (is_numeric($rs['checked'])) {
                                            switch ($rs['checked']) {
                                                case 0 :
                                                    echo $lang['notPass'];
                                                    break;
                                                case 1:
                                                    echo $lang['wait'];
                                                    break;
                                                case 999:
                                                    echo $lang['pass'];
                                                    break;
                                            }
                                        } else {
                                            echo $lang['wait'];
                                        }
                                        ?></li>
                                    <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>
                                    <li>
                                        <a href="?act=1&id=<?php echo $rs['id'] ?>" class="checked"><?php echo $lang['restore'] ?></a>
                                        <a class="delmes nopt" href="?act=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['remove'] ?></a>
                                    </li>

                                </ul>
                            <?php endforeach; ?>
                        </div>
                    </dd>
                    <dd class='toolbar'>
                    <button type='button' id='all' onclick="javascript:$('input[name=checkid]').attr('checked', true).prop('checked', true)">全选</button>
                    <button type='button' id='cancel' onclick="javascript:$('input[name=checkid]').removeAttr('checked', '').prop('checked', false)">取消</button>
                    <button type='button' id='reduction' ><?php echo $lang['restore'] ?></button>
                    <button type='button' id='delete'><?php echo $lang['remove'] ?></button>
                    </dd>
                    <dd class='page'>

                        <a href="?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>"><?php echo $lang['lastPage'] ?></a>
                        <?php
                        $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                        for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                            ?>
                            <a href="?page=<?php echo $i ?>"><span style="color:<?php echo $i == $page || ($page <= 0 && $i == 1) ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                            <?php
                        endfor;
                        ?>
                        <a href='?page=<?php echo ($page < $p['pageCount'] ? $page <= 0 ? $page + 2 : $page + 1 : $page) ?>'><?php echo $lang['nextPage'] ?></a>
                    </dd>
                <?php else: ?>
                    <dd class='notInfo'><span><?php echo $lang['notInfo'] ?></span></dd>
                <?php endif; ?>
            </dl>
        </div>
        <!--dialog  -->
    <link rel="stylesheet" href="../js/Dialog/css/ui-dialog.css">
    <script type="text/javascript" src="../js/Dialog/dist/dialog-min.js"></script>
    <script type="text/javascript">
<?php printf('var classifyJson =%s;', json_encode($classifyJson)); ?>
                    $('.atable ul li').each(function (i, p) {
                        if ($(p).attr('data-id'))
                        {
                            $(p).text(classifyJson['classify_' + $(p).attr('data-id')]);
                        }
                    });

                    $('#delete').on('click', function () {
                        dialog({
                            backdropBackground: '',
                            title: '<?php echo $lang['prompt'] ?>',
                            content: '<?php echo $lang['mesgIsRemoveSomeContent'] ?>',
                            okValue: '<?php echo $lang['ok'] ?>',
                            width: '500px',
                            ok: function () {
                                this.title('<?php echo $lang['mesgDeletion'] ?>');
                                this.content('<?php echo $lang['mesgRemovePleaseWait'] ?>');
                                var checkboxp = '';
                                $('input[name=checkid]:checked').each(function (i, p) {
                                    checkboxp += $(p).val() + ',';
                                });
                                if (checkboxp) {
                                    checkboxp = checkboxp.substr(0, checkboxp.length - 1);
                                    $.ajax({
                                        type: "GET",
                                        data: {'id': checkboxp},
                                        url: '?p=on&act=2',
                                        success: function (e) {
                                            if (Boolean(e) === true) {
                                                history.go(0);
                                            }
                                        }
                                    });
                                }
                                return false;
                            },
                            cancelValue: '<?php echo $lang['cancel'] ?>',
                            cancel: function () {}
                        }).showModal();
                    });

                    $('#reduction').on('click', function () {
                        dialog({
                            backdropBackground: '',
                            title: '<?php echo $lang['prompt'] ?>',
                            content: '<?php echo $lang['mesgIsRestoreSomeContent'] ?>',
                            okValue: '确定',
                            width: '500px',
                            ok: function () {
                                this.title('<?php echo $lang['mesgRestore'] ?>');
                                this.content('<?php echo $lang['mesgRestorePleaseWait'] ?>');
                                var checkboxp = '';
                                $('input[name=checkid]:checked').each(function (i, p) {
                                    checkboxp += $(p).val() + ',';
                                });
                                if (checkboxp) {
                                    checkboxp = checkboxp.substr(0, checkboxp.length - 1);
                                    $.ajax({
                                        type: "GET",
                                        data: {'id': checkboxp},
                                        url: '?p=on&act=1',
                                        success: function (e) {
                                            if (Boolean(e) === true) {
                                                history.go(0);
                                            }
                                        }
                                    });
                                }
                                return false;
                            },
                            cancelValue: '<?php echo $lang['cancel'] ?>',
                            cancel: function () {}
                        }).showModal();
                    });

                    $('.delmes').on('click', function () {
                        return confirm('<?php echo $lang['mesgConfirmDeletion']; ?>');
                    });

    </script>
</body>
</html>
