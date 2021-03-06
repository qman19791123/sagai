<?php
/*
 * The MIT License
 *
 * Copyright 2017 qman.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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
$newid = filter_input(INPUT_GET, 'newid', FILTER_SANITIZE_STRING);
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

/**
 * 一个匿名方法 排序分类
 */
$AMNnewssubjectClassify = function () use($conn) {

    function data($conn, $id = 0, $t = '', $p = '') {
        $t.='　';
        $arr = array();
        $sql = 'select id,classifyName  from special_classify where pid="' . $id . '"';

        $rs = $conn->query($sql);
        foreach ($rs as $value) {
            $arr [$value['id']]['classifyName'] = $t . $p . $value['classifyName'];
            $arr [$value['id']]['id'] = $value['id'];
            $d = data($conn, $value['id'], $t, '├');
            if (!empty($d)) {
                $arr = $arr + $d;
            }
        }
        return $arr;
    }

    return data($conn);
};

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
    case 4:
        //删除分集
        if (!empty($idINPUT)) {
            $conn->delete('special_classify', ['id' => $idINPUT]);
            $conn->delete('special_content', ['specialClassifyId' => $idINPUT]);
            exit(json_encode([TRUE]));
        }
        die(json_encode(FALSE));
        break;
    case 5:
        //修改分集
        $Rs = $conn->select('count(id) as cid,id')->where('id=' . $idINPUT)->get('special_classify');
        if (!empty($idINPUT) &&
                !empty($Rs) &&
                !empty($Rs[0]['cid'])) {
            //print_r([$classifyNameINPUT, $templateINPUT, $templateContentINPUT, $idINPUT]);

            if ($Rs[0]['id'] == $pIdINPUT) {
                die(json_encode([FALSE, 1]));
            }
            $conn->update('special_classify', ['pinyin' => $tfunction->py($classifyNameINPUT, 'tfunction::ZNSymbolFilter'),
                'sort' => $sortINPUT,
                'pid' => $pIdINPUT,
                'classifyName' => $classifyNameINPUT,
                'template' => $templateINPUT,
                'templateContent' => $templateContentINPUT], 'id="' . $idINPUT . '"');
            die(json_encode([TRUE, 0]));
        }
        die(json_encode([FALSE, 0]));
        break;
    case 6:
        //添加分集
        $Rs = $conn->select('count(id) as cid')->where('id=' . $idINPUT)->get('special_config');
        if (!empty($idINPUT) && !empty($Rs) && (!empty($Rs[0]['cid']))) {
            $conn->insert('special_classify', ['id' => trim($uuid), 'specialId' => trim($idINPUT), 'time' => microtime(TRUE), 'sort' => 0, 'pid' => 0]);
            exit(json_encode([TRUE, $uuid]));
        }
        exit(json_encode([FALSE]));
        break;
    case 7:
        //class ajax
        die(json_encode($AMNnewssubjectClassify()));
        break;
    case 8:
        $conn->delete('special_content', ['specialClassifyId' => $id, 'newsIds' => $newid]);
        die($tfunction->message($lang['mesgRemoveContentSuccess']));
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
<!--                            [<a href='?cpage=1'><?php echo $lang['newsUpdateList']; ?></a>]
                            [<a href='?cpage=1'><?php echo $lang['newsUpdateDocumentation']; ?></a>]
                            [<a href='#'><?php echo $lang['specialThemeRecycling']; ?></a>]-->
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
                                        <li>
                                            <?php echo $lang['time']; ?>
                                        </li>
                                        <li style="width: 10%">
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
                                                <a href="?cpage=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['update']; ?></a>

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
                        if (!empty($Rs[0]['specialName'])) {
                            $specialName = $Rs[0]['specialName'];
                            $introduction = tfunction::decode($Rs[0]['introduction']);
                        }
                    }
                    ?>

                    <dl>
                        <dt>
                        <?php echo $lang['specialArticleManager']; ?>
                        </dt>
                        <dd>

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
                                <button class='addclassify' data-id="<?php print(!empty($id) ? $id : $uuid); ?>" type="button">建立栏目</button>
                                <button type="button" id='fanhui'><?php echo $lang['back']; ?></button>
                            </div>
                            <fieldset>

                                <legend></legend>
                                <ul class="newscontent">
                                    <li data-id="">
                                        <strong>栏目设置
                                            <span>
                                                <a class="nodeadd" href="javascript:void(0)" >提交</a>
                                                <a class="nodedel" href="javascript:void(0)" >删除</a>
                                            </span>
                                        </strong>
                                        <p>
                                            <label>上级栏目:</label>
                                            <select class="pid" style="width: 150px;">
                                                <option value="0">主目录:</option>
                                            </select>

                                        </p>
                                        <p>
                                            <label>栏目名称:</label><input  type="text" style="width: 55%">
                                            <label>排序</label><select data-sort='0' class='sort'></select>
                                        </p>
                                        <p><label>专题列表模板:</label><input value="" type="text" style="width: 65%"></p>
                                        <p><label>专题内容模板:</label><input value="" type="text" style="width: 65%"></p>
                                        <strong>旗下文章<span class="newslist"><a href="javascript:void(0)">添加文章</a></span></strong>
                                        <p class="newlist">

                                        </p>
                                        <p class="page"><p>
                                    </li>

                                    <?php
                                    if (!empty($id)):
                                        $Rs = $conn->where('specialId=' . $id)->order_by('sort', 'desc')->get('special_classify');
                                        if ($Rs):
                                            foreach ($Rs as $k => $v):
                                                ?><li data-id="<?php echo $v['id'] ?>">
                                                    <strong class="columnSetting">栏目设置
                                                        <span>
                                                            <a class="nodeadd" href="javascript:void(0)" >提交</a>
                                                            <a class="nodedel" href="javascript:void(0)" >删除</a>
                                                        </span>
                                                    </strong>
                                                    <p>
                                                        <label>上级栏目:</label>
                                                        <select class="pid" style="width: 150px;">
                                                            <option value="0">主目录:</option>
                                                        </select>
                                                    </p>
                                                    <p>
                                                        <label>栏目名称:</label><input value="<?php echo $v['classifyName'] ?>" type="text" style="width: 55%">
                                                        <label>排序</label><select class='sort' data-sort='<?php echo empty($v['sort']) ? 0 : $v['sort'] ?>'></select>
                                                    </p>
                                                    <p><label>专题列表模板:</label><input value="<?php echo $v['template'] ?>" type="text" style="width: 65%"></p>
                                                    <p><label>专题内容模板:</label><input value="<?php echo $v['templateContent'] ?>" type="text" style="width: 65%"></p>
                                                    <strong>旗下文章<span class="newslist"><a href="javascript:void(0)">添加文章</a></span></strong>
                                                    <p class="newlist">
                                                        <?php
                                                        $CRs = $conn->where('specialClassifyId=' . $v['id'])->get('special_content');
                                                        foreach ($CRs as $cv):
                                                            ?>
                                                            <span><?php echo $cv['newTitle'] ?>
                                                                <i>
                                                                    <a class="nodedel" href="news.php?cpage=2&id=<?php echo $cv['newsIds'] ?>&newssubjectClassId=<?php echo $v['id']; ?>&newssubjectId=<?php echo $id; ?>">编辑</a>
                                                                    <a class="nodedel" href="?act=8&id=<?php echo $cv['specialClassifyId'] ?>&newid=<?php echo $cv['newsIds'] ?>">删除</a>
                                                                </i>
                                                            </span>
                                                        <?php endforeach; ?>
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

        <!--JqueryEasyui-->
        <link rel="stylesheet" href="../js/JqueryEasyui/default/easyui.css" />
        <script charset="utf-8" src="../js/JqueryEasyui/JqueryEasyui.js"></script>
        <script src="../js/qmancms.js" type="text/javascript"></script>
        <!--        <link rel="stylesheet" href="../js/Dialog/css/ui-dialog.css">
                <script type="text/javascript" src="../js/Dialog/dist/dialog-min.js"></script>-->
        <script type="text/javascript">
                                    var mesgConfirmDeletion = '<?php echo $lang['mesgConfirmDeletion']; ?>';
                                    var isNOtAjaxPid = [];
                                    $('.newscontent li').eq(0).hide();

                                    // 建立栏目 code start

                                    $('.addclassify').on('click', function () {
                                        if ($('input[name=specialName]').val() && $('textarea[name=introduction]').val()) {
                                            var id = $(this).data('id');
                                            if (confirm('是否建立栏目?')) {
                                                $.post('?act=6', {'id': id}, function (data) {
                                                    if (!data[0]) {
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
                                                                        console.log(data);
                                                                        window.location.href = '?cpage=2&id=' + id;
                                                                        // cpage=2&id=9818a68685cf5c5fbcab0212ac27394e
                                                                    });
                                                                }
                                                            });
                                                        }
                                                        return NULL;
                                                    } else {
                                                        $('.newscontent').prepend('<li data-id="' + data[1] + '">' + $('.newscontent li').html() + '</li>');
                                                    }
                                                }, 'JSON');
                                            }
                                        } else
                                        {
                                            alert('请先填写专题名稱及介绍');
                                        }
                                    });
                                    // 建立栏目 code end 

                                    // 修改的分集 code start              
                                    $('fieldset').on('click', '.nodeadd', function ($data) {
                                        var data = $(this).parents('li');
                                        var inputData = {};
                                        inputData['id'] = data.data('id');
                                        inputData['classifyName'] = data.find('input:eq(0)').val();
                                        inputData['template'] = data.find('input:eq(1)').val();
                                        inputData['templateContent'] = data.find('input:eq(2)').val();
                                        inputData['sort'] = data.find('.sort').val();
                                        inputData['pid'] = data.find('.pid').val();

                                        $.post('?act=5', inputData, function (data) {
                                            if (data[0]) {
                                                alert('修改成功');
                                                isNOtAjaxPid = [];
                                                return;
                                            }
                                            switch (data[1]) {
                                                case 0:
                                                    alert('修改失败');
                                                    break;
                                                case 1:
                                                    alert('不能将自身设置为子类');
                                                    break;
                                            }
                                            return;
                                        }, 'JSON');
                                    });
                                    // 修改的分集 code end 


                                    //删除分集 code start 
                                    $('.columnSetting').on('click', '.nodedel', function (data) {
                                        if (confirm('<?php echo $lang['mesgConfirmDeletion'] ?>')) {
                                            var data = $(this).parents('li');
                                            var inputData = {};
                                            inputData['id'] = data.data('id');
                                            $.post('?act=4', inputData, function (p) {
                                                if (p) {
                                                    alert('<?php echo $lang['mesgRemoveContentSuccess'] ?>');
                                                    data.remove();
                                                    return;
                                                }
                                                alert('<?php echo $lang['mesgRemoveContentFailed'] ?>');
                                                return;
                                            }, 'JSON');
                                        }
                                    });
                                    //删除分集 code end 

                                    //上级栏目 code start 
                                    $('fieldset').on('mouseenter', '.pid', function () {
                                        var this_ = this;
                                        if (isNOtAjaxPid[$(this).parents('li').data('id')]) {
                                            return;
                                        }
                                        isNOtAjaxPid [$(this).parents('li').data('id')] = 'true';
                                        $.get('?act=7', function (data) {
                                            $(this_).find('option').remove();
                                            var html = '<option value="0">主目录:</option>';
                                            for (var p in data) {
                                                html += '<option value="' + data[p]['id'] + '">' + data[p]['classifyName'] + '</option>'
                                            }
                                            $(this_).append(html);

                                        }, 'JSON');
                                    });
                                    //上级栏目 code end

                                    //添加旗下文章 code start 
                                    $('fieldset').on('click', '.newslist', function () {

                                        //$.messager.confirm("操作提示", "您确定要执行操作吗？", function (data) {
                                        if (confirm('是否添加旗下文章')) {
                                            var data = $(this).parents('li');
                                            var id = data.data('id');

                                            $newssubject = $(".tijiao .addclassify").data('id')

                                            window.location.href = 'news.php?cpage=3&newssubjectClassId=' + id + '&newssubjectId=' + $newssubject;
                                        }
                                    });
                                    //添加旗下文章 code end
        </script>

    </body>

</html>