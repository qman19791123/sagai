<?php
include '../config.php';
include lib . 'tfunction.inc.php';
include lib . 'conn.inc.php';
include 'isadmin.php';
$tfunction = new tfunction();
$conn = $tfunction->conn;
$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
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
                                    <li style="width: 50%">名称</li>
                                    <li style="width: 17%">时间</li>
                                    <li style="width: 10%">操作</li>
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
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>">修改新闻</a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?>">删除新闻</a>
                                        </li>

                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                    </dl>

                    <?php
                    break;
                case 2:
                    ?>
                    <dl>
                        <dt>
                            分类管理
                            <span class="addLink">[ <a href='?cpage=1'>添加新闻</a>  ]</span>
                        </dt>
                        <dd>
                            <form method="post" action="?act=1">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>
                                            分类编号:
                                        </li>
                                        <li>
                                            <select style ="width:180px" name="pid">
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
                                        <li>标签:</li>
                                        <li><input style="width: 80%;" name="className" /></li>   
                                    </ul>

                                    <ul class="list a20_80">
                                        <li>标题:</li>
                                        <li><input style="width: 80%;" name="className" /></li>   
                                    </ul>

                                    <ul class="list a20_80">
                                        <li>副标题:</li>
                                        <li>
                                            <textarea style="width: 80%;height: 100px;resize: none;"></textarea>
                                        </li>   
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>标题图片:</li>
                                        <li>
                                            <input class="showfile"  readonly style="width: 80%;"><input    type="file" id="file_input" class="file"/>
                                        </li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>关键词 （SEO）:</li>
                                        <li><input style="width: 80%;" name="className" /></li>   
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>内容摘要（SEO）:</li>
                                        <li><textarea style="width: 80%;height: 100px;resize: none;"></textarea></li>   
                                    </ul>


                                    <ul class="list a20_80">
                                        <li>内容（SEO）:</li>
                                        <li><textarea name="Content" style="width: 80%;height: 100px;resize: none;"></textarea></li>   
                                    </ul>
                                </div>
                            </form>
                        </dd>
                    </dl>

                    <?php
                    break;
            endswitch;
            ?>
        </div>
        <script type="text/javascript" src="../js/file.js"></script>
        <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
        <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
        <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
        <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>
        <script>
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
            
            $(function () {
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