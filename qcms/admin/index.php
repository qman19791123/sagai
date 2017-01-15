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
$tfunction = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>">
    </head>
    <body class="admin">
        <form method="post" autocomplete="off">
            <div class="login">
                <p>
                    <input type="text" name="name" placeholder="用户名" autocomplete="off" >
                    <input type="password" name="passwd" placeholder="密码" autocomplete="off" >
                </p>
                <button name="goto" value="1">登录</button>
            </div>
        </form>
    </body>
</html>
<?php
$name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
$passwd = trim(filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_STRING));
$goto = filter_input(INPUT_POST, 'goto', FILTER_VALIDATE_INT);
!is_numeric($goto) && exit();
empty($name) && die(tfunction::message('用户名不能为空'));
empty($passwd) && die(tfunction::message('密码不能为空'));
$conn = new conn;
$sql = 'select count(*) as count ,id from `admin` where `adminName` = \'%s\' and `adminPassWord` = \'%s\'';
$sql = sprintf($sql, $name, md5($passwd));
$rs = $conn->query($sql);
if (!empty($rs[0]['count'])) {
    $_SESSION['admin'] = true;
    $_SESSION['adminId'] = $rs[0]['id'];
    echo tfunction::gotoUrl('../admin/main.php');
} else {
    exit(tfunction::message('用户名或密码不正确'));
}
?>


