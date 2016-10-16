<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include 'isadmin.php';
$tfunction = new tfunction();
$conn = $tfunction->conn;
//数据操作部分

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$u = filter_input(INPUT_POST, 'u', FILTER_VALIDATE_INT);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$act = (int) filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);

switch ($act) {
    case 1:


        empty($password) && die($tfunction->message('密码不能为空'));
        $sql = ($u == 0 or $u == 1 ) ? 'UPDATE user SET `userPassWord` =' : 'UPDATE admin SET `adminPassWord` =';
        $sql .='"' . md5($password) . '" where id=' . $id;
        $Rs = $conn->aud($sql);
        (is_bool($Rs)) && die($tfunction->message('密码修改成功', 'user.php'));

        break;
    case 2:
        empty($name) && die($tfunction->message('用户名不能为空'));
        empty($password) && die($tfunction->message('密码不能为空'));
        strlen($password) < 5 && die($tfunction->message('密码过短'));
        $sqlContent = ($u == 0 or $u == 1 ) ? ' select count(id) as count from `user` where userName = ' : ' select count(id) as count from `admin` where adminName = ';
        $sqlContent .='"' . $name . '"';
        $Rs = $conn->query($sqlContent);
        ($Rs[0]['count'] > 0) && die($tfunction->message('用户已存储'));
        $sql = 'INSERT INTO';
        $sql .= ($u == 0 or $u == 1 ) ? ' `user` (`userName`,`userPassWord`,`time`) VALUES ' : ' `admin` (`adminName`,`adminPassWord`,`time`) VALUES ';
        $sql .= sprintf('("%s","%s","%s");', $name, md5($password), time());
        $Rs = $conn->aud($sql);
        (is_numeric($Rs)) && die($tfunction->message('添加用户成功', 'user.php'));

        break;
    case 3:

        $id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $u = (int) filter_input(INPUT_GET, 'u', FILTER_VALIDATE_INT);
        $sql = 'delete from ';
        $sql .= ($u == 0 or $u == 1 ) ? "`user`" : "`admin`";
        $sql .= ' where id = ' . $id;
        $Rs = $conn->aud($sql);
        (is_bool($Rs)) && die($tfunction->message('删除成功', 'user.php'));

        break;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $systemName ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo '../' . $tfunction->lessc('admin.less') ?>
              ">
        <link rel="stylesheet" type="text/css" href="../css/Font-Awesome/font-awesome.min.css"></head>
    <body>
        <div class="adminContent">
            <?php
            $cpage = (int) filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
            $page = (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            $pageStart = 20;
            $page *= $pageStart;
            if ($cpage == 0 || $cpage == 1) {
                $sqlCount = 'select count(id) as count from user';
                $sql = 'select userName,id,time from user limit ' . $pageStart . ' offset ' . $page;
            } elseif ($cpage == 2) {
                $sqlCount = 'select count(id) as count from admin';
                $sql = 'select adminName as userName,id,time from admin limit ' . $pageStart . ' offset ' . $page;
            }
            switch ($cpage):
                case 0:
                case 1:
                //用户
                case 2:
                    //管理员
                    ?>
                    <dl>
                        <dt>
                            用户管理
                            <span class="addLink">[ <a href='?u=<?php echo $cpage; ?>&cpage=4'>添加用户</a>  ]</span>
                        </dt>
                        <dd>
                            <div class="qmancmsmenu">
                                <a class='<?php echo ($cpage == 0 || $cpage == 1) ? 'color' : '' ?>' href='?cpage=1'>用户</a>
                                <a class='<?php echo ($cpage == 2) ? 'color' : '' ?>' href='?cpage=2'>管理员</a>
                            </div>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <li>编号</li>
                                    <li style="width: 55%">账号</li>
                                    <li style="width: 30%">操作</li>
                                </ul>
                                <?php
                                $rs = $conn->query($sql);
                                foreach ($rs as $value):
                                    ?>
                                    <ul class="list atr">
                                        <li><?php echo $value['id'] ?></li>
                                        <li><?php echo $value['userName'] ?></li>
                                        <li>
                                            <a href='?cpage=3&u=<?php echo $cpage ?>&id=<?php echo $value['id'] ?>'>修改</a>
                                            <a class="delmes nopt" href='?id=<?php echo $value['id'] ?>&u=<?php echo $cpage ?>&act=3'>删除</a>
                                        </li>
                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                    </dl>
                    <?php
                    $rs = $conn->query($sqlCount);
                    $rcount = (int) $rs[0]['count'];
                    $p = ceil($rcount / $pageStart) - 1;
                    $pageS = $page - 1;
                    $pageS = $pageS < 0 ? $page : $pageS;
                    $pageE = $page + 1;
                    $pageE = $page > $p ? $pageE : $p;
                    ?>
                    <div class="page">
                        <a href='?cpage=<?php echo $cpage; ?>&page=0'>首页</a>
                        <a href='?cpage=<?php echo $cpage; ?>&page=<?php echo $pageS; ?>'>上一页</a>
                        <a href='?cpage=<?php echo $cpage; ?>&page=<?php echo $pageE; ?>'>下一页</a>
                        <a href='?cpage=<?php echo $cpage; ?>&page=<?php echo $p; ?>'>尾页</a>
                        <span>总数：<?php printf('%s/%s', $rcount, $p) ?> 每页显示：<?php echo $pageStart; ?></span>
                    </div>
                    <?php
                    break;
                case 3:
                    ?>
                    <dl>
                        <dt>
                            用户管理
                            <span class="addLink"></span>
                        </dt>
                        <dd>
                            <?php
                            $id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                            $u = (int) filter_input(INPUT_GET, 'u', FILTER_VALIDATE_INT);
                            $sql = (($u == 0 or $u == 1) ? 'select * from user' : 'select adminName as userName,id,time,adminPassWord as userPassWord from admin') . ' where id=%s';
                            $sql = sprintf($sql, $id);
                            $rs = $conn->query($sql);
                            ?>
                            <form method="post" action="?act=1">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>账号</li>
                                        <li><input type="text" readonly name="name" style="width: 80%;background:#ccc" value="<?php echo $rs[0]['userName'] ?>"></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>密码</li>
                                        <li><input type="password" name="password" style="width: 80%;" value=""></li>
                                    </ul>
                                </div>
                                <div class="tijiao">
                                    <input type="hidden" name='id' value="<?php echo $rs[0]['id'] ?>" />
                                    <input type="hidden" name='u' value="<?php echo $u ?>" />
                                    <button type="submit">提交</button>
                                    <button type="reset">重设</button>
                                    <button type="button" id='fanhui'>返回</button>
                                </div>
                            </form>
                        </dd>
                    </dl>
                    <?php
                    break;
                case 4:
                    ?>
                    <dl>
                        <dt>
                            用户管理
                            <span class="addLink"></span>
                        </dt>
                        <dd>
                            <form method="post" action="?act=2">
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>账号</li>
                                        <li><input type="text"  name="name" style="width: 80%;" value=""></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>密码</li>
                                        <li><input type="password" name="password" style="width: 80%;" value=""></li>
                                    </ul>
                                </div>
                                <div class="tijiao">
                                    <input type="hidden" name='u' value="<?php echo (int) filter_input(INPUT_GET, 'u', FILTER_VALIDATE_INT); ?>" />
                                    <button type="submit">提交</button>
                                    <button type="reset">重设</button>
                                    <button type="button" id='fanhui'>返回</button>
                                </div>
                            </form>
                        </dd>
                    </dl>
                    <?php
                    break;
                    ?>
            <?php endswitch; ?>
        </div>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript">
            // /onclick="javascript:return confirm('aaa')"
            $(function (argument) {
                // mouseenter/mousemove
                $('.delmes').on('click', function () {
                    return confirm('是否真的删除');
                });
                $('.atr').on({
                    'mousemove': function () {
                        if ($(this).attr('id') == 'no')
                            return;
                        $(this).css({'background': '#666', 'color': '#ddd'})
                    },
                    'mouseout': function () {
                        if ($(this).attr('id') == 'no')
                            return;
                        $('.list').removeAttr('style');
                    }
                });
                $('.page button:eq(0)').on('click', function () {
                    $('.atr input[type="checkbox"]').each(function () {
                        $(this).attr('checked', true);
                        $(this).prop('checked', true);
                    });
                });
                $('.page button:eq(1)').on('click', function () {

                });
                $('.page button:eq(2)').on('click', function () {
                    $('.atr input[type="checkbox"]').each(function () {
                        $(this).attr('checked', false);
                        $(this).prop('checked', false);
                    });
                });
                $('.page button:eq(3)').on('click', function () {
                    $('.atr input[type="checkbox"]').each(function () {
                        alert($(this).attr('checked'));
                    });
                });
                $('.menu span').on('click', function () {
                    $('.menu span').removeClass('color');
                    $(this).addClass('color');
                    // $.cookie('userlink',$(this).text());
                })
                $('#fanhui').on('click', function () {
                    self.history.go(-1);
                })
            })
        </script>
    </body>
</body>
</html>