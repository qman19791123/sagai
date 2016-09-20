/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * guid
 * @param {type} style 
 * 需要格式化样式
 * @returns {unresolved} 输出GUID的序号
 */
function generateUUID(style) {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = style.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}

//排序 权重 数量 设置 code start
$('.sort').append(function () {
    var html = '', t;
    for (i = -10; i <= 10; ++i) {
        t = parseInt($(this).data('sort')) === parseInt(i) ? 'selected' : '';
        html += '<option ' + t + '>' + i + '</option>';
    }
    return html;
});
//排序 权重 数量 设置 code end


//mouseover
$('.adminContent .atable ul').on('mouseover', function () {
    $(this).css({'background': '#444'});
    $(this).find('a').css({'background': '', 'color': '#fff'});
}).on('mouseout', function () {
    $(this).css({'background': '', 'color': ''});
    $(this).find('a').css({'background': '', 'color': ''});
});

//返回上一页 按钮 code start
$('#fanhui').on('click', function () {
    self.history.go(-1);
});
//返回上一页 按钮 code end

//删除提示 code start
$('.delmes').on('click', function () {
    return confirm(mesgConfirmDeletion);
});
//删除提示 code end

// KindEditor 编辑器 code start
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
        },
        afterUpload: function (data) {
            var img = $('input[name="updateImg"]');
            if (img) {
                img.val('"' + data + '",' + img.val());
            }
        }
    });
    prettyPrint();
});
// KindEditor 编辑器 code end
