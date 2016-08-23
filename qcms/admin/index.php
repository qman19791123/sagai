<?php 
include '../config.php';
include lib.'tfunction.inc.php';
$tfunction  = new tfunction();
$conn = $tfunction->conn;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $systemName?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo '../'.$tfunction->lessc('admin.less')?>">
</head>
<body class="admin">
    <form method="post">
        <div class="login">
            <p>
            <input type="text" name="name" placeholder="用户名">
            <input type="password" name="passwd" placeholder="密码">
            </p>
            <button name="goto" value="1">登录</button>
        </div>
    </form>
</body>
</html>
<?php
    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $passwd = filter_input(INPUT_POST,'passwd',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $goto = filter_input(INPUT_POST,'goto',FILTER_VALIDATE_INT);
    !is_numeric($goto) && exit();
    empty($name) && die(tfunction::message('用户名不能为空'));
    empty($passwd) && die(tfunction::message('密码不能为空'));
    $conn  = new conn;
    $sql = 'select count(*) as count ,id from `admin` where `adminName` = \'%s\' and `adminPassWord` = \'%s\'';
    $sql = sprintf($sql,$name , md5($passwd));
    $rs = $conn->query($sql);
    if(!empty($rs[0]['count'])){
        $_SESSION['admin'] = true;
        $_SESSION['adminId'] = $rs[0]['id'];
        echo tfunction::gotoUrl('../admin/main.php');
    }
    else{
       exit(tfunction::message('用户名或密码不正确'));
    }
?>


