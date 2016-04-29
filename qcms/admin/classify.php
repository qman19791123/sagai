<?php
include '../config.php';
include lib.'tfunction.inc.php';
include lib.'conn.inc.php';
include 'isadmin.php';
$tfunction = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $systemName?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo '../'.$tfunction->lessc('admin.less')?>
    ">
    <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css">

    </head>
<body>
    <div class="adminContent">
        <?php
            $cpage = (int)filter_input(INPUT_GET,'cpage',FILTER_VALIDATE_INT);
            switch ($cpage):
            case 0:
        ?>
        <dl>
            <dt>
                分类管理
                <span class="addLink">[ <a href='?cpage=1'>添加分类</a>  ]</span>
            </dt>
            <dd>
                <div class="list atable">
                    <ul class="list atr" id="no">
                        <li>编号</li>
                        <li>排序</li>
                        <li style="width: 47%">名称</li>
                        <li style="width: 30%">操作</li>
                    </ul>
                    <?php
                        $classify = new tfunction($conn);
                        $data = $classify->classify();
                        foreach($data as $rs):
                    ?>
                    <ul class="list atr">
                        <li><?php echo $rs['id']?></li>
                        <li><?php echo $rs['px']?></li>
                        <li><?php echo $rs['className']?></li>
                        <li>
                            <a>添加子类</a>
                            <a>修改分类</a>
                            <a class="delmes nopt">删除分类</a>
                        </li>
                    </ul>
                <?php endforeach?>
                </div>
            </dd>
        </dl>
        <?php
        break;
        case 1:
        ?>
        <dl>
            <dt>
                分类管理
                <span class="addLink">[ <a href='?cpage=1'>添加分类</a>  ]</span>
            </dt>
            <dd>
            <form>
                <div class="menu">
                    <a data-id="1" class="color" href="javascript:void(0)">站内</a>
                    <a data-id="2" href="javascript:void(0)">站外</a>
                </div>
                <div class="list atable">
                        <ul class="list a20_80">
                            <li>上级分类:</li>
                            <li>
                                <select style ="width:180px">
                                    <option></option>
                                    <?php
                                    $classify = new tfunction($conn);
                                    $data = $classify->classify();
                                    foreach($data as $rs):
                                    ?>
                                    <option value="<?php echo $rs['id']?>"><?php echo $rs['className']?></option>
                                    <?php endforeach?>
                                </select>
                            </li>
                        </ul>
                        <ul class="list a20_80">
                            <li>分类名称:</li>
                            <li><input style="width: 80%;"/></li>
                        </ul>
                        
                        <ul class="list a20_80">
                            <li>分类模板:</li>
                            <li><input style="width: 80%;"/></li>
                        </ul>
                        <ul class="list a20_80">
                            <li>内容模板:</li>
                            <li><input style="width: 80%;"/></li>
                        </ul>
                        <ul class="list a20_80">
                            <li>分类关键字:</li>
                            <li><input style="width: 80%;"/></li>
                        </ul>
                        <ul class="list a20_80" style="height: 450px">
                            <li class="top">分类简介:</li>
                            <li class="top">
                                <textarea name="editor1" id="editor1" ></textarea>
                            </li>
                        </ul>
                        <ul class="list a20_80">
                            <li class="top">地址:</li>
                            <li></li>
                        </ul>

                    </div>
                
                   <div class="tijiao">
                        <input type="hidden" name='u' value="0" />
                        <button type="submit">提交</button>
                        <button type="reset">重设</button>
                        <button type="button" id='fanhui'>返回</button>
                    </div>

                </form>
            </dd>
        </dl>
        <script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
            CKEDITOR.editorConfig = function( config ) {
                config.skin = 'office2013';
                config.height='320';
                config.toolbar = 'Full';
                config.startupOutlineBlocks = true;
                config.toolbar_Full =[
                    { name: 'document',    items : [ 'Source','-','Save','Preview' ] },
                    { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
                    { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
                    '/',
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
                    { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
                    { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak' ] },
                    '/',
                    { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
                    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                    { name: 'tools',       items : [ 'Maximize', 'ShowBlocks' ] }
                ];
            };
            var editor = CKEDITOR.replace( 'editor1' ,{  
                filebrowserImageUploadUrl : 'uploadPic.do',
            });
            // editor.on("instanceReady", function (evt) { 
            //         editor.addCommand("save", { modes: { wysiwyg: 1, source: 1 }, exec: function (editor) 
            //         {
            //             alert("asdasd");  
            //             return false;
            //         }
            //     });  
            // });  
        </script>
        <?php
        break;
        endswitch;
        ?>
    </div>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript">
        $('.list .a20_80').show();
        $('.list .a20_80:eq(-1)').hide();
        $('#fanhui').on('click',function(){
                self.history.go(-1);
        })
        $('.menu a').on('click',function(a){
            if($(this).attr('data-id')==2){
                $('.list .a20_80').hide();
                $('.list .a20_80:eq(0),.list .a20_80:eq(1),.list .a20_80:eq(-1)').show();

            }
            else
            {
                $('.list .a20_80').show();
                $('.list .a20_80:eq(-1)').hide();
            }
        });
    </script>
</body>
</html>