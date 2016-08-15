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

$check = filter_input(INPUT_POST, 'checked', FILTER_VALIDATE_INT);
$checked = $check == 999 || $check == 0 ? $check : 1;

$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);
$adminId = $_SESSION['adminId'];

$pinyin = $tfunction->py(mb_substr($titleINPUT, 0, 20, 'utf-8'));

/* 一个匿名方法 */
$AMNewsContent = function($Rsid, $newTextINPUT, $keywordsINPUT, $descriptionINPUT)use($conn) {
    $sqlNewsContentAddModele = 'INSERT INTO'
            . '`news_content`'
            . '(`newsId`,`newText`,`keywords`,`description`) VALUES'
            . '("%s","%s","%s","%s")';
    $sqlNewsContentAdd = sprintf($sqlNewsContentAddModele, $Rsid, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
    $conn->aud($sqlNewsContentAdd);
};


switch ($act) {
    case 1:
//添加部分代码
        empty($titleINPUT) && die($tfunction->message('信息标题不能为空'));
        empty($newTextINPUT) && die($tfunction->message('信息内容不能为空'));
        empty($classifyIdINPUT) && die($tfunction->message('栏目不能为空'));

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
        $sqlNewsConfigAddModele = 'INSERT INTO `news_config` '
                . '(`classifyId`,`userid`,`time`,`sort`,`tag`,`subtitle`,`title`,`titlePhoto`,`checked`,`pinyin`,`isdel`)'
                . 'VALUES("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';
        $sqlNewsConfigAdd = sprintf($sqlNewsConfigAddModele, $classifyIdINPUT, $adminId, $time, $sortINPUT, $tagINPUT, $subtitleINPUT, $titleINPUT, $path, $checked, $pinyin, 0);
        $Rs = $conn->aud($sqlNewsConfigAdd);

// 添加内容表信息
        $AMNewsContent($Rs, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);

//修改统计文章总数
        $sqlClassifyUpdate = 'UPDATE `classify` SET summary=summary+1 where id=' . $classifyIdINPUT;
        $conn->aud($sqlClassifyUpdate);

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

            $sqlNewsContentSeletCount = 'select count(newsId) as C from news_content where newsId=' . $id;
            $Rsc = $conn->query($sqlNewsContentSeletCount);
            if (empty($Rsc[0]['C'])) {
                $AMNewsContent($id, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
            } else {
                $sqlNewsContentUpdateModele = 'update `news_content` set `newText` = "%s",`keywords` = "%s", `description`="%s" where newsId=%s';
                $sqlNewsContentUpdate = sprintf($sqlNewsContentUpdateModele, $newTextINPUT, $keywordsINPUT, $descriptionINPUT, $id);
                $conn->aud($sqlNewsContentUpdate);
            }
            $sqlNewsConfigUpdateModele = 'update `news_config` set '
                    . '`classifyId` = "%s",'
                    . '`sort` = "%s", '
                    . '`tag`="%s" , '
                    . '`subtitle` = "%s" ,'
                    . '`title` = "%s" ,'
                    . '`titlePhoto` = "%s" ,'
                    . '`checked`="%s" '
                    . ' where id=%s';
            $sqlNewsConfigUpdate = sprintf($sqlNewsConfigUpdateModele, $classifyIdINPUT, $sortINPUT, $tagINPUT, $subtitleINPUT, $titleINPUT, $path, $checked, $id);
            $conn->aud($sqlNewsConfigUpdate);
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
        $sql = 'update `news_config` set checked = ' . $ajaxT . ' where id=' . $ajaxId;
        $conn->aud($sql);
        die('true');
        break;
    case 5:
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
                            文章管理-<?php echo '所有文章' ?>
                        <span class="addLink">
                            [<a href='?cpage=1'>添加文章</a>]
                            [<a href='javascript:void(0)' id='retrievedArticles'>检索文章</a>]
                            [<a href='javascript:void(0)' id='column'>栏目管理</a>]
                            [<a href='?cpage=1'>更新列表</a>]
                            [<a href='?cpage=1'>更新文档</a>]
                            [<a href='?cpage=1'>文章回收</a>]
                        </span>
                        </dt>
                        <dd>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <li>编号</li>
                                    <li>排序</li>
                                    <li style="width: 50%">名称</li>
                                    <li style="width: 5%">审核</li>
                                    <li style="width: 17%">时间</li>
                                    <li style="width: 20%">操作</li>
                                </ul>
                                <?php
                                $pagec = ($page - 1) * 10;
                                $query = filter_input(INPUT_GET, 'query');
                                $poUrl = '';
                                if (!empty($query)) {
                                    $where = '';
                                    $timestart = filter_input(INPUT_GET, 'timestart', FILTER_SANITIZE_STRING);
                                    $timeend = filter_input(INPUT_GET, 'timeend', FILTER_SANITIZE_STRING);
                                    $queryselect = filter_input(INPUT_GET, 'queryselect', FILTER_VALIDATE_INT);
                                    $content = filter_input(INPUT_GET, 'content', FILTER_SANITIZE_STRING);
                                    
                                    if (!empty($timestart)) {
                                        $timestart = strtotime($timestart);
                                        $where .= 'and time >' . $timestart;
                                    }
                                    if (!empty($timeend)) {
                                        $timeend = strtotime($timeend);
                                        $where .= ' and time <' . $timeend;
                                    }

                                    switch ($queryselect) {
                                        case 1:
                                            $where .= ' and title like "%' . $content . '%"';
                                            break;
                                        case 2:
                                            $where .= ' and subtitle like "%' . $content . '%"';
                                            break;
                                    }

                                    $arr = filter_input_array(INPUT_GET);
                                    unset($arr['page']);
                                    $poUrl = join('=%s&', array_keys($arr));

                                    $poUrl = sprintf($poUrl . '=%s', $arr['query'], $arr['timestart'], $arr['timeend'], $arr['queryselect'], $arr['content']);
                                }
                                $sql = 'select time,id,sort,title from `news_config` where 1=1 ' . $where . ' order by id desc limit 10 Offset ' . $pagec;
                                $data = $conn->query($sql);
                                $sqlCount = 'select count(id) as cid from `news_config` where 1=1 ' . $where . '';
                                $dataCount = $conn->query($sqlCount);



                                foreach ($data as $rs):
                                    ?>
                                    <ul class="list atr">
                                        <li><?php echo $rs['id'] ?></li>
                                        <li><?php echo $rs['sort'] ?></li>
                                        <li><?php echo $rs['title'] ?></li>
                                        <li><?php
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
                                            ?></li>
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
                        </dd>
                    </dl>

                    <?php
                    break;
                case 2:
                case 1:

                    $tag = '';
                    $subtitle = '';
                    $title = '';
                    $titlePhoto = '';
                    $newText = '';
                    $keywords = '';
                    $description = '';
                    $checked = '';
                    if (!empty($id)) {
                        $sql = 'select classifyId,sort,tag,subtitle,title,titlePhoto,newText,keywords,description,checked from news_config left join news_content on news_config.id = news_content.newsId where id = ' . $id;
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
                        $checked = $Rs[0]['checked'];
                    }
                    ?>
                    <dl>
                        <dt>
                            文章管理 
                        <span class="addLink"></span>
                        </dt>
                        <dd>
                            <?php if ($checked == 1): ?>
                            <span class="informationBar">
                                <?php print('请通过审核') ?>
                                <a href="javascript:void(0)" id='off' title="关闭">X</a>
                            </span>
                        <?php endif; ?>
                        <form method="post"  enctype="multipart/form-data" action="?act=<?php echo $cpage ?><?php !empty($id) && print('&id=' . $id) ?>">
                            <div class="list atable">
                                <ul class="list a20_80">
                                    <li>
                                        栏目:
                                    </li>
                                    <li>
                                        <select style ="width:180px" name="classifyId" class="classifyId">
                                            <option value="0">主栏目</option>
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

                                <ul class="list a20_80">
                                    <li>
                                        审核
                                    </li>
                                    <li>


                                        通过:<input type="radio" <?php $checked == 999 && print('checked="checked"') ?>  name="checked" value="999"/>
                                        未通过:<input type="radio" <?php $checked == 0 && print('checked="checked"') ?>  name="checked" value="0"/>
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
            endswitch;
            ?>
        </div>
        <!--审核 start-->
        <div class='dialogMsg audit'>
            <div class='dialogContent auditContent'>
                <ul>
                    <li class="r">
                        审核
                    </li>
                    <li class="l">
                        <select>
                            <option >选择</option>
                            <option value="0">未通过</option>
                            <option value="1">等待</option>
                            <option value="999">通过</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>
        <!--审核 end-->
        <!--检索 start-->
        <div class='dialogMsg search'>
            <div class='dialogContent searchContent'>
                <form id='query'>
                    <p>
                    <span>添加时间：</span> <input name='timestart' class='timebox'/> - <input  name='timeend' class='timebox'/>
                    </p>
                    <p>
                    <span>搜索：</span>
                    <select name='queryselect'>
                        <option value="0">选择</option>
                        <option value="1">标题</option>
                        <option value="2">副标题</option>
                    </select>
                    <input name='content' />
                    </p>
                </form>
            </div>
        </div>
        <!--检索 end-->



        <!--栏目 start-->
        <div class='dialogMsg column'>
            <div class='dialogContent columnContent'>
                <form id='query'>
                    <?php
                    $classify = new tfunction($conn);
                    $data = $classify->classify();
                    foreach ($data as $rs):
                        ?>
                        <option value="<?php echo $rs['id'] ?>"><?php echo $rs['className'] ?></option>
                    <?php endforeach ?>
                </form>
            </div>
        </div>
        <!--栏目 end-->

    <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
    <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
    <link rel="stylesheet" href="../js/JqueryEasyui/default/easyui.css" />
    <script charset="utf-8" src="../js/JqueryEasyui/JqueryEasyui.js"></script>

    <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>
    <!--dialog css -->
    <link rel="stylesheet" href="../js/Dialog/css/ui-dialog.css">
    <!--dialog js -->
    <script type="text/javascript" src="../js/Dialog/dist/dialog-min.js"></script>
    <script type="text/javascript" src="../js/file.js"></script>

    <script type="text/javascript">
        $('#column').on('click', function () {
            dialog({
                backdropBackground: '',
                title: '提示',
                content: $('.column').html(),
                okValue: '确定',
                width: '700px',
                ok: function () {
                    var query = $('.searchContent').eq(1).find('#query').serialize();
                    self.location = ('?query=yes&' + query);
                    return false;
                },
                cancelValue: '取消',
                cancel: function () {}
            }).showModal();
        });
        $('#retrievedArticles').on('click', function () {
            dialog({
                backdropBackground: '',
                title: '提示',
                content: $('.search').html(),
                okValue: '确定',
                width: '700px',
                height: '180px',
                ok: function () {
                    var query = $('.searchContent').eq(1).find('#query').serialize();
                    self.location = ('?query=yes&' + query);
                    console.log(query);
                    return false;
                },
                cancelValue: '取消',
                cancel: function () {}
            }).showModal();

            $('.timebox').datebox({
            });

        });

        $('#off').on('click', function () {
            $(this).parent('span').hide();
        });
        $('.checked').on('click', function () {

            var id = $(this).attr('data-id');

            var dialog_ = dialog({
                backdropBackground: '',
                title: '提示',
                content: $('.audit').html(),
                okValue: '确定',
                width: '300px',
                height: '80px',
                ok: function () {
                    var tt = $('.auditContent').eq(1).find('select').val();
                    if (tt !== '选择') {
                        console.log(tt);
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '?act=4',
                            data: {"t": tt, "id": id},
                            success: function (e) {
                                if (e == 'true') {
                                    history.go(0)
                                }
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
                height: '250px',
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