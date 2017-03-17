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
(function ($) {
    $.fn.checkFileTypeAndSize = function (options) {
        //默认设置
        var defaults = {
            allowedExtensions: [],
            maxSize: 1024, //单位是KB，默认最大文件尺寸1MB=1024KB
            success: function () { },
            extensionerror: function () { },
            sizeerror: function () { }
        };
        //合并设置
        options = $.extend(defaults, options);
        //遍历元素
        return this.each(function () {
            $(this).on('change', function () {
                //获取文件路径
                var filePath = $(this).val();

                //小写字母的文件路径
                var fileLowerPath = filePath.toLowerCase();
                //获取文件的后缀名
                var extension = fileLowerPath.substring(fileLowerPath.lastIndexOf('.') + 1);
                //判断后缀名是否包含在预先设置的、所允许的后缀名数组中
                if ($.inArray(extension, options.allowedExtensions) === -1) {
                    options.extensionerror();
                    $(this).focus();
                } else {
                    try {
                        var size = 0;

                        size = $(this)[0].files[0].size;//byte
                        size = size / 1024;//kb

                        if (size > options.maxSize) {
                            options.sizeerror();
                        } else {
                            options.success();
                        }
                    } catch (e) {
                        alert("错误：" + e);
                    }
                }
            });
        });
    };
})(jQuery);