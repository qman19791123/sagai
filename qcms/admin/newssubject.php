<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;
$uuid = md5(uniqid(microtime(TRUE)));
// get
$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);
$style = filter_input(INPUT_GET, 'style', FILTER_VALIDATE_BOOLEAN);

// post
// 数据添加 删除 修改 操作
$specialNameINPUT = filter_input(INPUT_POST, 'specialName', FILTER_SANITIZE_MAGIC_QUOTES);
$introductionINPUT = filter_input(INPUT_POST, 'introduction', FILTER_CALLBACK, ['options' => 'tfunction::encode']);
$idINPUT = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

$pIdINPUT = filter_input(INPUT_POST, 'pid', FILTER_SANITIZE_STRING);
$classifyNameINPUT = filter_input(INPUT_POST, 'classifyName', FILTER_SANITIZE_STRING);
$templateINPUT = filter_input(INPUT_POST, 'template', FILTER_SANITIZE_STRING);
$templateContentINPUT = filter_input(INPUT_POST, 'templateContent', FILTER_SANITIZE_STRING);
$classifyIdINPUT = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
$specialIdINPUT = filter_input(INPUT_POST, 'specialId', FILTER_SANITIZE_STRING);
$sortINPUT = filter_input(INPUT_POST, 'sort', FILTER_VALIDATE_INT);
switch ($act) {
    case 1:
        empty($introductionINPUT) && die($tfunction->message($lang['mesgIntroductionNotEmpty']));
        empty($specialNameINPUT) && die($tfunction->message($lang['mesgSpecialNameNotEmpty']));
        $pinyin = $tfunction->py($introduction, 'tfunction::ZNSymbolFilter');
        $conn->insert('special_config', [
            'specialName' => $specialNameINPUT,
            'introduction' => $introductionINPUT,
            'pinyin' => $pinyin,
            'time' => time(),
            'id' => $idINPUT
        ]);
        if (!$style) {
            die($tfunction->message($lang['mesgUpdateContentSuccess'], 'newssubject.php'));
        } else {
            exit(json_encode([TRUE]));
        }
        break;
    case 2:
        empty($introductionINPUT) && die($tfunction->message($lang['mesgIntroductionNotEmpty']));
        empty($specialNameINPUT) && die($tfunction->message($lang['mesgSpecialNameNotEmpty']));
        $conn->where('id=' . $id)->update('special_config', [ 'specialName' => $specialNameINPUT, 'introduction' => $introductionINPUT]);
        if (!$style) {
            die($tfunction->message($lang['mesgUpdateContentSuccess'], 'newssubject.php'));
        } else {
            exit(json_encode([TRUE]));
        }
        // die($tfunction->message($lang['mesgUpdateContentSuccess'], 'newssubject.php'));
        break;
    case 5:

        if (!empty($idINPUT) && !empty($Rs = $conn->select('count(id) as cid')->where('id=' . $idINPUT)->get('special_classify'))) {
            //print_r([$classifyNameINPUT, $templateINPUT, $templateContentINPUT, $idINPUT]);
            $conn->update('special_classify', ['pinyin' => $tfunction->py($classifyNameINPUT, 'tfunction::ZNSymbolFilter'),
                'sort' => $sortINPUT,
                'pid' => $pIdINPUT,
                'classifyName' => $classifyNameINPUT,
                'template' => $templateINPUT,
                'templateContent' => $templateContentINPUT], 'id="' . $idINPUT . '"');
        }
        exit();

        break;
    case 6:
        if (!empty($idINPUT) && !empty($Rs = $conn->select('count(id) as cid')->where('id=' . $idINPUT)->get('special_config')) && (!empty($Rs[0]['cid']))) {
            $conn->insert('special_classify', ['id' => trim($uuid), 'specialId' => trim($idINPUT)]);
            exit(json_encode([TRUE, $uuid]));
        } else {
            exit(json_encode([FALSE]));
        }
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
                            <form method="post"  enctype="multipart/form-data" 
                                  action="?act=<?php
                                  echo $cpage;
                                  printf('&id=%s', !empty($id) ? $id : $uuid);
                                  ?>">
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

                                <fieldset>
                                    <legend>栏目设置<span class='addclassify' data-id="<?php print(!empty($id) ? $id : $uuid); ?>">添加</span></legend>
                                    <ul class="newscontent">
                                        <li>
                                            <strong>栏目设置
                                                <span>
                                                    <a class="nodeadd" href="javascript:void(0)">提交</a>
                                                    <a class="nodedel" href="javascript:void(0)">删除</a>
                                                </span>
                                            </strong>
                                            <p>
                                                <label>上级栏目:</label>
                                                <select style="width: 150px;" name="pid">
                                                    <option>主目录:</option>
                                                </select>
                                            </p>
                                            <p><label>栏目名称:</label><input type="text" style="width: 65%"><label>排序</label><select class='sort'data-sort='0'></select></p>
                                            <p><label>专题列表模板:</label></p>
                                            <p><label>专题内容模板:</label></p>
                                            <strong>旗下文章<span id="<?php print(!empty($id) ? $id : $uuid); ?>">获取</span></strong>
                                            <p class="newlist">

                                            </p>
                                            <p class="page"><p>
                                        </li>

                                        <?php
                                        if (!empty($id)):
                                            $Rs = $conn->where('specialId=' . $id)->get('special_classify');
                                            if ($Rs):
                                                foreach ($Rs as $k => $v):
                                                    ?><li data-id="<?php echo $v['id'] ?>">
                                                        <strong>栏目设置
                                                            <span>
                                                                <a class="nodeadd" href="javascript:void(0)" >提交</a>
                                                                <a class="nodedel" href="javascript:void(0)" >删除</a>
                                                            </span>
                                                        </strong>
                                                        <p>
                                                            <label>上级栏目:</label>
                                                            <select style="width: 150px;">
                                                                <option>主目录:</option>
                                                            </select>

                                                        </p>
                                                        <p>
                                                            <label>栏目名称:</label><input value="<?php echo $v['classifyName'] ?>" type="text" style="width: 55%">
                                                            <label>排序</label><select class='sort' data-sort='<?php echo empty($v['sort']) ? 0 : $v['sort'] ?>'></select>
                                                        </p>
                                                        <p><label>专题列表模板:</label><input value="<?php echo $v['template'] ?>" type="text" style="width: 65%"></p>
                                                        <p><label>专题内容模板:</label><input value="<?php echo $v['templateContent'] ?>" type="text" style="width: 65%"></p>
                                                        <strong>旗下文章<span id="<?php print(!empty($id) ? $id : $uuid); ?>">获取</span></strong>
                                                        <p class="newlist">

                                                        </p>
                                                        <p class="page"><p>
                                                    </li>
                                                    <?php
                                                endforeach;
                                            endif;
                                        endif;
                                        ?>

                                    </ul>
                                </fieldset>
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
            var mesgConfirmDeletion = '<?php echo $lang['mesgConfirmDeletion']; ?>';
        </script>
        <script src="../js/qmancms.js" type="text/javascript"></script>

        <script type="text/javascript">

            $('.newscontent li').eq(0).hide();
            $('.addclassify').on('click', function () {

                if ($('input[name=specialName]').val() && $('textarea[name=introduction]').val()) {
                    var id = $(this).data('id');
                    $.post('?act=6', {'id': id}, function (data) {
                        if (!data) {
                            if (confirm('专题名稱及介绍未保存，只有保存后才能执行添栏目操作，现在是否保存?')) {
                                $.post('?act=1&style=1',
                                        {'specialName': $('input[name=specialName]').val(),
                                            'introduction': $('textarea[name=introduction]').val(),
                                            'id': id
                                        },
                                        function (data) {
                                            if (data)
                                            {
                                                $.post('?act=6', {'id': id}, function (data) {
                                                    $('.newscontent').prepend('<li data-id="' + data[1] + '">' + $('.newscontent li').html() + '</li>');
                                                });
                                            }
                                        });
                            }
                            return NULL;
                        }
                        $('.newscontent').prepend('<li data-id="' + data[1] + '">' + $('.newscontent li').html() + '</li>');
                    }, 'JSON');
                } else
                {
                    alert('请先填写专题名稱及介绍');
                }

//                
            });
            $('fieldset').on('click', '.nodeadd', function ($data) {
                var data = $(this).parents('li');
                var inputData = {};
                inputData['id'] = data.data('id');
                inputData['classifyName'] = data.find('input:eq(0)').val();
                inputData['template'] = data.find('input:eq(1)').val();
                inputData['templateContent'] = data.find('input:eq(2)').val();
                inputData['sort'] = data.find('.sort').val();
                $.post('?act=5', inputData, function (data) {
                    console.log(data);
                });
            });
            $('fieldset').on('click', '.nodedel', function (data) {
                alert($(this).parents('li').data('id'));
            });
        </script>

    </body>

</html>