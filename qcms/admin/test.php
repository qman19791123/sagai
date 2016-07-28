<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/css/admin.css">
    <link rel="stylesheet"  type="text/css" href="../css/Font-Awesome/font-awesome.min.css">
</head>
<body>
    
<form name="example" method="post" action="demo.php">

<div class="list atable">
 <ul class="list a20_80" style="height: 450px">
                            <li class="top">分类简介:</li>
                            <li class="top">
                                <textarea name="Content" id="editor1" ></textarea>
                            </li>
                        </ul>
    </form>
</div>


    <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
    <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
    <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="../js/KindEditor/lang/zh-CN.js"></script>
    <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>


    <script>
        KindEditor.ready(function(K) {
            var editor1 = K.create('textarea[name="Content"]', {
                cssPath : '../js/KindEditor/plugins/code/prettify.css',
                uploadJson : '../js/KindEditor/php/upload_json.php',
                fileManagerJson : '../js/KindEditor/php/file_manager_json.php',
                allowFileManager : true,
                afterCreate : function() {
                    var self = this;
                    K.ctrl(document, 13, function() {
                        self.sync();
                        K('form[name=example]')[0].submit();
                    });
                    K.ctrl(self.edit.doc, 13, function() {
                        self.sync();
                        K('form[name=example]')[0].submit();
                    });
                }
            });
            prettyPrint();
        });
    </script>
</body>
</html>