<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
include lang . $language;

$tfunction = new tfunction();
$conn = $tfunction->conn;

$act = (int) filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);
$id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pid = (int) filter_input(INPUT_POST, 'pid', FILTER_VALIDATE_INT);
$px = (int) filter_input(INPUT_POST, 'px', FILTER_VALIDATE_INT);
$className = filter_input(INPUT_POST, 'className', FILTER_SANITIZE_STRING);
//格式化带有HTML标签内容
$Content = filter_input(INPUT_POST, 'Content', FILTER_CALLBACK, ['options' => 'tfunction::encode']);

$ntmp = filter_input(INPUT_POST, 'ntmp', FILTER_SANITIZE_STRING);
$ctemp = filter_input(INPUT_POST, 'ctemp', FILTER_SANITIZE_STRING);
$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
$setting = (int) filter_input(INPUT_POST, 'setting', FILTER_VALIDATE_INT);
$folder = $tfunction->py($className);

// 一个匿名方法 作用删除栏目缓存数据
$AMNewsCacheClassifty = function () use($conn) {
    @unlink(install . '/cacheData/classifyArray.php');
    @unlink(install . '/cacheData/classifyStyleArray.php');
};
switch ($act) {
    case 1:
        $sql = 'select count(id) as count  from `classify` where  className = "' . $className . '"';
        $Rs = $conn->query($sql);
        if (empty($Rs[0]['count'])) {
            $sql = 'INSERT INTO';
            $sql .= ' `classify` (`pid`,`px`,`className`,`Content`,`ntmp`,`ctemp`,`url`,`setting`,`folder`) VALUES ';
            $sql .= sprintf('("%s","%s","%s","%s","%s","%s","%s","%s","%s");', $pid, $px, $className, $Content, $ntmp, $ctemp, $url, $setting, $folder);
            $Rs = $conn->aud($sql);
            if ($Rs) {
                if (StaticOpen) {
                    @mkdir(staticFloder . '/' . $folder, 0777, true);
                }
                $AMNewsCacheClassifty();
            }
            (is_numeric($Rs)) && die($tfunction->message($lang['classifyAddSuccess'], 'classify.php'));
        } else {
            (is_numeric($Rs)) && die($tfunction->message($lang['classifyErr'], 'classify.php'));
        }
        break;
    case 2:

        $sql = 'UPDATE `classify` SET';
        $sql .='`pid` = "' . $pid . '",';
        $sql .='`px` = "' . $px . '",';
        $sql .='`className` = "' . $className . '",';
        $sql .='`Content` = "' . $Content . '",';
        $sql .='`ntmp` = "' . $ntmp . '",';
        $sql .='`ctemp` = "' . $ctemp . '",';
        $sql .='`url` = "' . $url . '",';
        $sql .='`setting` = "' . $setting . '"';
        $sql .=' where id=' . $id;

        $Rs = $conn->aud($sql);
        $AMNewsCacheClassifty();
        die($tfunction->message($lang['classifyUpdateSuccess'], 'classify.php'));
        break;
    case 3:
        $id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $sql = 'SELECT COUNT(id) as count ,folder FROM `classify` where pid = ' . $id;
        $Rs = $conn->query($sql);
        if (empty($Rs[0]['count'])) {
            $sql = 'DELETE FROM `classify`';
            $sql .= ' where id = ' . $id;
            $Rs = $conn->aud($sql);
            $AMNewsCacheClassifty();
            die($tfunction->message($lang['classifyDelSuccess'], 'classify.php'));
        } else {
            die($tfunction->message($lang['classifyDelFail'], 'classify.php'));
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>"/>
        <!--字体图标 css -->
        <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css"/>
        <!--dialog css -->
        <script type="text/javascript" src="../js/jquery.min.js"></script>
    </head>
    <body>
        <div class="adminContent">
            <?php
            $cpage = (int) filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
            switch ($cpage):
                case 0:
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['classifyManagement']; ?>
                            <span class="addLink">[ <a href='?cpage=1'><?php echo $lang['classifyAdd']; ?></a>  ]</span>
                        </dt>
                        <dd>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <li><?php echo $lang['id'] ?></li>
                                    <li><?php echo $lang['sort'] ?></li>
                                    <li style="width: 47%"><?php echo $lang['name']; ?></li>
                                    <li style="width: 30%"><?php echo $lang['operation']; ?></li>
                                </ul>
                                <?php
                                $data = $tfunction->classify();
                                foreach ($data as $rs):
                                    ?>
                                    <ul class="list atr">
                                        <li><?php echo $rs['id'] ?></li>
                                        <li><?php echo $rs['px'] ?></li>
                                        <li><?php echo $rs['className'] ?></li>
                                        <li>
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['classifyUpdate']; ?></a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?>"><?php echo $lang['classifyDel']; ?></a>
                                        </li>
                                    </ul>
                                <?php endforeach ?>
                            </div>
                        </dd>
                    </dl>
                    <?php
                    break;
                case 1:
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['classifyManagement']; ?>
                            <span class="addLink">[ <a href='?cpage=1'><?php echo $lang['classifyAdd']; ?></a>  ]</span>
                        </dt>
                        <dd>
                            <form method="post" action="?act=1">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifyUp']; ?>:</li>
                                        <li>
                                            <select style ="width:180px" name="pid">
                                                <option value="0"><?php echo $lang['classifyMain']; ?>:</option>
                                                <?php
                                                $classify = new tfunction($conn);
                                                $data = $classify->classify();
                                                foreach ($data as $rs):
                                                    ?>
                                                    <option value="<?php echo $rs['id'] ?>"><?php echo $rs['className'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifySort'] ?>:</li>
                                        <li>
                                            <select style ="width:180px" name="px">
                                                <?php for ($i = -10; $i < 10; $i++) : ?>
                                                    <option value="<?php echo $i ?>" <?php $i == 0 && print('selected'); ?> ><?php echo $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </li>   
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifySet'] ?>:</li>
                                        <li>
                                            <select style ="width:180px" name="setting">
                                                <option value="0">站内</option>
                                                <option value="1">站外</option>
                                                <option value="2">单页</option>
                                            </select>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifyName']; ?>:</li>
                                        <li><input style="width: 80%;" name="className" /></li>
                                    </ul>

                                    <ul class="list a20_80">
                                        <li><?php echo $lang['templateList']; ?>:</li>
                                        <li><input style="width: 80%;" name="ntmp" /></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['templateContent']; ?>:</li>
                                        <li><input style="width: 80%;" name="ctemp" /></li>
                                    </ul>
                                    <ul class="list a20_80" style="height: 450px">
                                        <li class="top"><?php echo $lang['classifyBrief']; ?>:</li>
                                        <li class="top">
                                            <textarea name="Content" id="editor1" ></textarea>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li class="top"><?php echo $lang['address']; ?>:</li>
                                        <li><input style="width: 80%;" name="url" /></li>
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
                case 2:
                    $t_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $Rs = $conn->where(['id'=>(int)$t_id])->get('classify');
                    $setting = empty($Rs[0]['setting']) ? 0 : $Rs[0]['setting']
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['classifyManagement']; ?>
                            <span class="addLink">[ <a href='?cpage=1'><?php echo $lang['classifyAdd'] ?></a>  ]</span>
                        </dt>
                        <dd>
                            <form method="post" action="?act=2&id=<?php echo $t_id ?>">
                                <!--     <div class="menu">
                                        <a data-id="1" class="color" href="javascript:void(0)">站内</a>
                                        <a data-id="2" href="javascript:void(0)">站外</a>
                                    </div> -->
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifyUp'] ?>:</li>
                                        <li>
                                            <select style ="width:180px" >
                                                <option value="0"></option>
                                                <?php
                                                $classify = new tfunction($conn);
                                                $data = $classify->classify();
                                                foreach ($data as $rsd):
                                                    ?>
                                                    <option value="<?php echo $rsd['id'] ?>"
                                                            <?php print ($Rs[0]['pid'] == 0 && $t_id == $rsd['id'] ? 'selected' : $Rs[0]['pid'] == $rsd['id'] ? 'selected' : ''); ?>>
                                                                <?php echo $rsd['className'] ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                            <input type="hidden" name="pid" value="<?php echo $Rs[0]['pid'] ?>" />
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifySort'] ?>:</li>
                                        <li>
                                            <select style ="width:180px" name="px">
                                                <?php for ($i = -10; $i < 10; $i++) : ?>
                                                    <option value="<?php echo $i ?>" <?php $i == $Rs[0]['px'] && print('selected'); ?> ><?php echo $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifySet'] ?>:</li>
                                        <li>
                                            <select style ="width:180px" name="setting" data-setting="<?php echo $Rs[0]['setting']; ?>">
                                                <option value="0">站内</option>
                                                <option value="1">站外</option>
                                                <option value="2">单页</option>
                                            </select>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['classifyName'] ?>:</li>
                                        <li>
                                            <input style="width: 80%;" name="className" value="<?php echo $Rs[0]['className'] ?>" />
                                            <input type="hidden" name="folder" value="<?php echo $Rs[0]['className'] ?>" />
                                        </li>
                                    </ul>

                                    <ul class="list a20_80">
                                        <li><?php echo $lang['templateList'] ?>:</li>
                                        <li><input style="width: 80%;" name="ntmp" value="<?php echo $Rs[0]['ntmp'] ?>"/></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li><?php echo $lang['templateContent'] ?>:</li>
                                        <li><input style="width: 80%;" name="ctemp" value="<?php echo $Rs[0]['ctemp'] ?>"/></li>
                                    </ul>
                                    <ul class="list a20_80" style="height: 450px">
                                        <li class="top"><?php echo $lang['classifyBrief'] ?>:</li>
                                        <li class="top">
                                            <textarea name="Content" id="editor1" ><?php echo tfunction::decode($Rs[0]['Content']) ?></textarea>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li class="top"><?php echo $lang['address'] ?>:</li>
                                        <li><input style="width: 80%;" name="url" value="<?php echo $Rs[0]['url'] ?>" /></li>
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
            endswitch;
            ?>
        </div>

        <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
        <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
        <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
        <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>

        <script>
            //mouseover
            $('.adminContent .atable ul').on('mouseover', function () {
                $(this).css({'background': '#444', 'color': '#fff'});
                $(this).find('a').css({'background': '', 'color': '#fff'});
            }).on('mouseout', function () {
                $(this).css({'background': '', 'color': ''});
                $(this).find('a').css({'background': '', 'color': ''});
            });

            KindEditor.ready(function (K) {
                var editor1 = K.create('textarea[name="Content"]', {
                    cssPath: '../js/KindEditor/plugins/code/prettify.css',
                    uploadJson: '../js/KindEditor/php/upload_json.php',
                    fileManagerJson: '../js/KindEditor/php/file_manager_json.php',
                    width: '100%',
                    height: '430px',
                    resizeType: 0,
                    items: [
                        'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
                        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                        'flash', 'media', 'insertfile', 'table', 'hr', 'baidumap', 'pagebreak',
                        'link', 'unlink'
                    ],
                    allowFileManager: true,
                    afterCreate: function () {
                        var self = this;
                        // K.ctrl(document, 13, function() {
                        //     self.sync();
                        //     K('form[name=example]')[0].submit();
                        // });
                        // K.ctrl(self.edit.doc, 13, function() {
                        //     self.sync();
                        //     K('form[name=example]')[0].submit();
                        // });
                    }
                });
                prettyPrint();


            });
            $('.delmes').on('click', function () {
                return confirm('是否真的删除');
            });
            $('.list .a20_80').show();
            //
            $('#fanhui').on('click', function () {
                self.history.go(-1);
            });
            function srtting(p) {
                switch (p.toString()) {
                    case '0':
                        $('.list .a20_80:eq(-2)').hide();
                    case '2':
                        $('.list .a20_80:eq(-1)').hide();

                        break;
                    case '1':
                        $('.list .a20_80').hide();
                        $('.list .a20_80:eq(0),.list .a20_80:eq(1),.list .a20_80:eq(2),.list .a20_80:eq(3),.list .a20_80:eq(-1)').show();
                        break;
                }
            }
            var p = $('select[name="setting"]');
            var psetting = p.attr('data-setting');
            if (psetting != undefined) {
                p.find('option').each(function (i, x) {
                    $(x).attr('selected', ($(x).val() == p.attr('data-setting')));
                });
                srtting(psetting);
            } else
            {
                srtting(0);
            }

            $('select[name="setting"]').change(function (a) {
                $('.list .a20_80').show();
                srtting($(this).val());
            });
        </script>
    </body>
</html> 
