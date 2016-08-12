<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include lib . 'conn.inc.php';
include plus . 'upload/upload.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;
$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$classifyId = '0';
$sort = '0';

/**
 * 数据添加 删除 修改 操作
 */
//$newTextINPUT = filter_input(INPUT_POST, 'newText', FILTER_SANITIZE_MAGIC_QUOTES);

$newTextINPUT = filter_input(INPUT_POST, 'newText', FILTER_CALLBACK, ['options' => 'conn::encode']);
$keywordsINPUT = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);
$descriptionINPUT = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

$time = time();
$classifyIdINPUT = filter_input(INPUT_POST, 'classifyId', FILTER_VALIDATE_INT);
$sortINPUT = filter_input(INPUT_POST, 'sort', FILTER_VALIDATE_INT, ['options' => ['min_range' => -10, 'max_range' => 10]]);

$tagINPUT = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
$subtitleINPUT = filter_input(INPUT_POST, 'subtitle', FILTER_CALLBACK, ['options' => 'conn::encode']);
$titleINPUT = filter_input(INPUT_POST, 'title', FILTER_CALLBACK, ['options' => 'conn::dropQuote']);
$titlePhotoINPUT = filter_input(INPUT_POST, 'titlePhoto', FILTER_SANITIZE_STRING);

$checked = 1;
$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);
$adminId = $_SESSION['adminId'];

$pinyin = $tfunction->py(mb_substr($titleINPUT, 0, 20, 'utf-8'));

switch ($act) {
    case 1:
        //添加部分代码
        empty($titleINPUT) && die($tfunction->message('信息标题不能为空'));
        empty($newTextINPUT) && die($tfunction->message('信息内容不能为空'));
        empty($classifyIdINPUT) && die($tfunction->message('分类不能为空'));

        empty($subtitleINPUT) && $subtitleINPUT = mb_substr(filter_var($newTextINPUT, FILTER_SANITIZE_STRING), 0, 240);
        empty($descriptionINPUT) && $descriptionINPUT = $subtitleINPUT;
        !is_numeric($sortINPUT) && $sortINPUT = 0;
        $path = '';
        $file = $_FILES['file'];
        if (!empty($file['name'])) {
            $upload = Upload::factory('/files');
            $upload->set_filename(md5(time()));
            $upload->file($file);
            $upload->set_max_file_size(200);
            $results = $upload->upload();
            $path = $titlePhotoINPUT;
        }
        if (empty($results['errors'])) {
            $path = $results['path'];
        }
        // 添加内容配置表信息
        $sql = 'INSERT INTO `news_config` '
                . '(`classifyId`,`userid`,`time`,`sort`,`tag`,`subtitle`,`title`,`titlePhoto`,`checked`,`pinyin`,`isdel`)'
                . 'VALUES("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';
        $sql = sprintf($sql, $classifyIdINPUT, $adminId, $time, $sortINPUT, $tagINPUT, $subtitleINPUT, $titleINPUT, $path, $checked, $pinyin, 0);
        $Rs = $conn->aud($sql);
        // 添加内容表信息
        $sql = 'INSERT INTO'
                . '`news_content`'
                . '(`newsId`,`newText`,`keywords`,`description`) VALUES'
                . '("%s","%s","%s","%s")';
        $sql = sprintf($sql, $Rs, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
        $conn->aud($sql);

        //修改统计文章总数
        $sql = 'UPDATE `classify` SET summary=summary+1 where id=' . $classifyIdINPUT;
        $conn->aud($sql);

        die($tfunction->message('添加内容成功', 'news.php'));
        break;
    case 2:
        //修改部分代码
        if (!empty($id)) {
            $path = $titlePhotoINPUT;
            $file = $_FILES['file'];
            if (!empty($file['name'])) {
                $upload = Upload::factory('/files');
                $upload->set_filename(md5(time()));
                $upload->file($file);
                $upload->set_max_file_size(200);
                $results = $upload->upload();
            }
            if (empty($results['errors'])) {
                $path = $results['path'];
            }

            $sql = 'select count(newsId) as C from news_content where newsId=' . $id;
            $Rs = $conn->query($sql);
            if (empty($Rs[0]['C'])) {
                $sql = 'INSERT INTO'
                        . '`news_content`'
                        . '(`newsId`,`newText`,`keywords`,`description`) VALUES'
                        . '("%s","%s","%s","%s")';
                $sql = sprintf($sql, $id, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
            } else {
                $sql = 'update `news_content` set `newText` = "%s",`keywords` = "%s", `description`="%s" where newsId=%s';
                $sql = sprintf($sql, $newTextINPUT, $keywordsINPUT, $descriptionINPUT, $id);
            }
            $conn->aud($sql);

            $sql = 'update `news_config` set '
                    . '`classifyId` = "%s",'
                    . '`sort` = "%s", '
                    . '`tag`="%s" , '
                    . '`subtitle` = "%s" ,'
                    . '`title` = "%s" ,'
                    . '`titlePhoto` = "%s"'
                    . ' where id=%s';
            $sql = sprintf($sql, $classifyIdINPUT, $sortINPUT, $tagINPUT, $subtitleINPUT, $titleINPUT, $path, $id);
            $conn->aud($sql);
            die($tfunction->message('修改内容成功', 'news.php'));
        }
        break;
    case 3:
        //删除部分代码
        echo $act;

        break;
    case 4:
        //特殊传值AJAx
        $ajaxT = filter_input(INPUT_POST, 't', FILTER_VALIDATE_INT);
        $ajaxId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        var_dump($ajaxId,$ajaxT);
        exit();
        break;
}
?>
<html>
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>"/>
        <!--字体图标 css -->
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
                                    <li style="width: 50%">名称</li>
                                    <li style="width: 17%">时间</li>
                                    <li style="width: 20%">操作</li>
                                </ul>
                                <?php
                                $pagec = ($page - 1) * 10;
                                $sql = 'select * from `news_config` order by id desc limit 10 Offset ' . $pagec;
                                $data = $conn->query($sql);
                                $sqlCount = 'select count(id) as cid from `news_config`';
                                $dataCount = $conn->query($sqlCount);
                                foreach ($data as $rs):
                                    ?>
                                    <ul class="list atr">
                                        <li><?php echo $rs['id'] ?></li>
                                        <li><?php echo $rs['sort'] ?></li>
                                        <li><?php echo $rs['title'] ?></li>
                                        <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>

                                        <li>
                                            <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>" class="checked">审核</a>
                                            <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>">阅览</a>
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>">修改新闻</a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?>">删除新闻</a>
                                        </li>

                                    </ul>
                                <?php endforeach; ?>
                            </div>
                            <div class='page'>
                                <a href='?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>'>上一页</a>
                                <?php
                                $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                                for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                                    ?>
                                    <a href="?page=<?php echo $i ?>"><span style="color:<?php echo $i == $page ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                                    <?php
                                endfor;
                                ?>
                                <a href='?page=<?php echo ($page < $p['pageCount'] ? $page + 1 : $page) ?>'>下一页</a>
                            </div>
                        </dd>
                    </dl>

                    <?php
                    break;
                case 2:
                case 1:
                    ?>
                    <dl>
                        <dt>
                            分类管理
                        <span class="addLink">[ <a href='?cpage=1'>添加新闻</a>  ]</span>
                        </dt>
                        <dd>
                            <form method="post"  enctype="multipart/form-data" action="?act=<?php echo $cpage ?><?php !empty($id) && print('&id=' . $id) ?>">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>
                                            分类编号:
                                        </li>
                                        <li>
                                            <select style ="width:180px" name="classifyId" class="classifyId">
                                                <option value="0">主分类</option>
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
                                        <li>排序</li>
                                        <li>
                                            <select name="sort" class="sort">
                                            </select>
                                        </li>
                                    </ul>

                                    <?php
                                    $tag = '';
                                    $subtitle = '';
                                    $title = '';
                                    $titlePhoto = '';
                                    $newText = '';
                                    $keywords = '';
                                    $description = '';
                                    if (!empty($id)) {
                                        $sql = 'select classifyId,sort,tag,subtitle,title,titlePhoto,newText,keywords,description from news_config left join news_content on news_config.id = news_content.newsId where id = ' . $id;
                                        $Rs = $conn->query($sql);
                                        $sort = $Rs[0]['sort'];
                                        $classifyId = $Rs[0]['classifyId'];
                                        $tag = $Rs[0]['tag'];
                                        $title = $Rs[0]['title'];
                                        $titlePhoto = $Rs[0]['titlePhoto'];
                                        //解码内容返回带有HTML标签内容
                                        $newText = conn::decode($Rs[0]['newText']);
                                        $keywords = $Rs[0]['keywords'];
                                        $description = $Rs[0]['description'];
                                        $subtitle = $Rs[0]['subtitle'];
                                    }
                                    ?>
                                    <ul class="list a20_80">
                                        <li>标签:</li>
                                        <li><input style="width: 80%;" name="tag" value="<?php echo $tag; ?>"/></li>
                                    </ul>

                                    <ul class="list a20_80">
                                        <li>标题:</li>
                                        <li><input style="width: 80%;" name="title" value="<?php echo $title; ?>"/></li>
                                    </ul>

                                    <ul class="list a20_80">
                                        <li>副标题:</li>
                                        <li>
                                            <textarea style="width: 80%;height: 100px;resize: none;" name="subtitle"><?php echo $subtitle; ?></textarea>
                                        </li>   
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>标题图片:</li>
                                        <li>
                                            <input class="showfile" readonly style="width: 80%;" name="titlePhoto" value="<?php echo $titlePhoto; ?>">
                                            <input type="file" id="file_input" class="file" name="file"/>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>关键词 （SEO）:</li>
                                        <li><input style="width: 80%;" name="keywords" value="<?php echo $keywords; ?>"/></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>内容摘要（SEO）:</li>
                                        <li><textarea style="width: 80%;height: 100px;resize: none;" name="description"><?php echo $description; ?></textarea></li>
                                    </ul>


                                    <ul class="list a20_80">
                                        <li>内容（SEO）:</li>
                                        <li><textarea name="newText" style="width: 80%;height: 50px;resize: none;"><?php echo $newText; ?></textarea></li>   
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

        <div class='dialogMsg'>
            <div class='dialogContent'>
                <ul>
                    <li class="r">
                        审核
                    </li>
                    <li class="l">
                        <select>
                            <option readonly>选择</option>
                            <option value="0">未通过审核</option>
                            <option value="1">等待审核</option>
                            <option value="999">审核</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>


    <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
    <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
    <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>
    <!--dialog css -->
    <link rel="stylesheet" href="../js/Dialog/css/ui-dialog.css">
    <!--dialog js -->
    <script type="text/javascript" src="../js/Dialog/dist/dialog-min.js"></script>
    <script type="text/javascript" src="../js/file.js"></script>

    <script type="text/javascript">


        $('.checked').on('click', function () {

            var id = $(this).attr('data-id');
            $('.dialogContent .l option').eq(0).css({'background': '#ccc', 'padding': '5px'});
            dialog({
                backdropBackground: '',
                title: '提示',
                content: $('.dialogMsg').html(),
                okValue: '确定',
                width: '300px',
                height: '80px',
                ok: function () {
                    var tt = $('.dialogContent').eq(1).find('select').val();
                    if (tt !== '选择') {
                        console.log(tt);
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '?act=4',
                            data: {"t": tt, "id": id},
                            success: function (e) {
                                alert(e);
                            }
                        });
                    }
                    return false;
                },
                cancelValue: '取消',
                cancel: function () {}
            }).showModal();
        });

        /*
         *  KindEditor 编辑器
         */
        KindEditor.ready(function (K) {
            var editor1 = K.create('textarea[name="newText"]', {
                cssPath: '../js/KindEditor/plugins/code/prettify.css',
                uploadJson: '../js/KindEditor/php/upload_json.php',
                fileManagerJson: '../js/KindEditor/php/file_manager_json.php',
                width: '100%',
                height: '280px',
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

        $(function () {

            $('#fanhui').on('click', function () {
                self.history.go(-1);
            });

            $('.sort').append(function () {
                var html = '', t;
                for (i = -10; i <= 10; ++i) {
                    t = <?php echo $sort ?> == i ? 'selected' : '';
                    html += '<option ' + t + '>' + i + '</option>';
                }
                return html;
            });

            $('.classifyId option').each(function (i, v) {
                t = ($(v).val() == <?php echo $classifyId ?>);
                $(v).attr('selected', t);
            });

            $('.file').hide();
            $('.showfile').on('click', function () {
                var th = $(this);
                $('#file_input').click();
                $('#file_input').checkFileTypeAndSize({
                    allowedExtensions: ['jpg', 'png', 'gif'],
                    maxSize: 500,
                    success: function () {
                        th.val($('#file_input').val())
                    },
                    extensionerror: function () {
                        alert('格式不正确或不能为空');
                        return;
                    },
                    sizeerror: function () {
                        alert('最大尺寸请在200kb以内');
                        return;
                    }
                });
            });
        });
    </script>
</body>

</html>