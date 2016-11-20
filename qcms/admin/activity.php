<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';

include plus . 'Excel/PHPExcel.php';
include plus . 'Excel/PHPExcel/Writer/Excel2007.php';

include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;

// get
$cpageGet = (int) filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$idGet = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); //新闻编号 (有时间修改此命名)
$actGet = (int) filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);


$activityIdINPUT = filter_input(INPUT_POST, 'activityId', FILTER_SANITIZE_STRING);
$IdINPUT = (int) filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$activityValueINPUT = filter_input(INPUT_POST, 'activityValue', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$activityInputINPUT = filter_input(INPUT_POST, 'activityInput', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$activitystateINPUT = filter_input(INPUT_POST, 'activitystate', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);



$activityTitleINPUT = filter_input(INPUT_POST, 'activityTitle', FILTER_SANITIZE_STRING);
$activityContentINPUT = filter_input(INPUT_POST, 'newText', FILTER_SANITIZE_STRING);

$timeINPUT = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
$endtimeINPUT = filter_input(INPUT_POST, 'endtime', FILTER_SANITIZE_STRING);





// $sql = 'INSERT INTO activity (`activityTitle`,`activityContent`,`time`,`endtime`) VALUES("%s","%s","%s","%s")';

$AMActivityXLS = function ($id) use($conn) {

    $name = date('Y-m-d');
    error_reporting(E_ALL);
    date_default_timezone_set('Europe/London');
    $objPHPExcel = new PHPExcel();

    $sql = 'select * from activity_content where activityId = "' . $id . '"';
    $data = $conn->query($sql);




    foreach ($data as $k => $v) {

        $num = $k + 1;
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $v['p1'])
                ->setCellValue('B' . $num, $v['p2'])
                ->setCellValue('C' . $num, $v['p3'])
                ->setCellValue('D' . $num, $v['p4'])
                ->setCellValue('E' . $num, $v['p5'])
                ->setCellValue('F' . $num, $v['p6'])
                ->setCellValue('G' . $num, $v['p7'])
                ->setCellValue('H' . $num, $v['p8'])
                ->setCellValue('I' . $num, $v['p9'])
                ->setCellValue('J' . $num, $v['p10'])
                ->setCellValue('K' . $num, $v['p11'])
                ->setCellValue('L' . $num, $v['p12'])
                ->setCellValue('N' . $num, $v['p13'])
                ->setCellValue('M' . $num, $v['p14'])
                ->setCellValue('O' . $num, $v['p15'])
                ->setCellValue('P' . $num, $v['p16'])
                ->setCellValue('Q' . $num, $v['p17'])
                ->setCellValue('R' . $num, $v['p18'])
                ->setCellValue('S' . $num, $v['p19'])
                ->setCellValue('T' . $num, $v['p20']);
    }
    $objPHPExcel->getActiveSheet()->setTitle('User');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $name . '.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
};

$AMActivityConfig = function( $activityValueINPUT, $activityInputINPUT, $activitystateINPUT, $idGet) use($conn) {
    $activityCount = count($activityValueINPUT);
    $sql = 'INSERT INTO  activity_config (`activityValue`,`activityId`,`activityInput`,`activitystate`,`activityKey`) values ';
    for ($i = 0; $i < $activityCount; ++$i) {
        $sql .='("' . $activityValueINPUT[$i] . '","';
        $sql .= $idGet . '","';
        $sql .= $activityInputINPUT[$i] . '","';
        $sql .= $activitystateINPUT[$i] . '","';
        $sql .= 'p' . ($i + 1) . '"),';
    }
    $sql = mb_substr($sql, 0, -1);
    $conn->aud($sql);
};

switch ($actGet) {
    case 1:
        $sql_activity = 'INSERT INTO activity (`activityTitle`,`activityContent`,`time`,`endtime`) VALUES("%s","%s","%s","%s")';
        $rsId = $conn->aud(sprintf($sql_activity, $activityTitleINPUT, $activityContentINPUT, strtotime($timeINPUT), strtotime($endtimeINPUT)));

        $AMActivityConfig($activityValueINPUT, $activityInputINPUT, $activitystateINPUT, $rsId);
        die($tfunction->message('编辑成功', 'activity.php'));
        break;
    case 2:
        $sql_activity = 'update activity set `activityTitle`="%s" ,`activityContent`="%s" ,`time`="%s",`endtime`="%s" where id = "%s"';
        $conn->aud(sprintf($sql_activity, $activityTitleINPUT, $activityContentINPUT, strtotime($timeINPUT), strtotime($endtimeINPUT), $idGet));

        $sql_activity_config = 'delete from activity_config where activityId = "' . $idGet . '"';

        $conn->aud($sql_activity_config);

        $AMActivityConfig($activityValueINPUT, $activityInputINPUT, $activitystateINPUT, $idGet);

        die($tfunction->message('编辑成功', 'activity.php'));
        break;
    case 3:

        $sql_activity_config = 'delete from activity_config where activityId = "' . $idGet . '"';
        $conn->aud($sql_activity_config);

        $sql_activity_config = 'delete from activity where id = "' . $idGet . '"';
        $conn->aud($sql_activity_config);
        die($tfunction->message('编辑成功', 'activity.php'));

        break;
    case 4:
        if (!empty($IdINPUT) && !empty($activityIdINPUT)) {
            $sql = 'delete from activity_config where activityId = "' . $IdINPUT . '" and activityKey="' . $activityIdINPUT . '"';
            $conn->aud($sql);
            die('true');
        }
        die("FALSE");
        break;
    case 5:
        $AMActivityXLS($idGet);
        break;
    case 6:
        if (!empty($idGet)) {
            $sql = 'delete from activity_content where activityId = "' . $idGet . '"';
            $conn->aud($sql);
            die($tfunction->message('删除成功'));
        }
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
        <style>

            .activityC{
                width: 100%;
                height: 300px;
                overflow-y: scroll;
            }
            .addfun {
                width: 100%;
                background: #afafaf;
                padding:10px 20px;
                display: inline-block;
                text-align: right;

            }
            .activityC p{
                width: 100%;
                line-height: 30px;
                padding: 10px;
                display: inline-block;
            }
            .activityC p input{
                width: 300px;
                padding: 5px !important;
            }

            .activityC p select{
                padding: 5px;
            }
            .activityC p .a1{
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="adminContent">
            <?php
            switch ($cpageGet) :
                case 0:
                    ?>
                    <dl>
                        <dt>
                            活动管理-所有活动                        
                        <span class="addLink">
                            [<a href='?cpage=1'>添加活动</a>]
                        </span>
                        </dt>
                        <dd>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <li style="width: 5%">选择</li>
                                    <li>活动名称</li>
                                    <li style="width: 17%">开始时间</li>
                                    <li style="width: 17%">结束时间</li>
                                    <li style="width: 20%">操作</li>
                                </ul>
                                <?php
                                $data = $conn->get('activity');
                                foreach ($data as $rs):
                                    ?>

                                    <ul class="list atr">
                                        <li><input name="checkid" type="checkbox" value="<?php echo $rs['id'] ?>"></li>
                                        <li><?php echo $rs['activityTitle'] ?></li>
                                        <li><?php echo date('Y-m-d h:i:s', $rs['time']); ?></li>
                                        <li><?php echo date('Y-m-d h:i:s', $rs['endtime']); ?></li>
                                        <li>
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['update']; ?></a>
                                            <a class="delmes" href="?cpage=3&id=<?php echo $rs['id'] ?> ">查看活动信息</a>
                                            <a class="delmes" target="_blank" href="/index.php/activity/index/<?php echo $rs['id'] ?> ">查看活动页</a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?> "><?php echo $lang['remove']; ?></a>
                                        </li>
                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                    </dl>

                    <?php
                    break;
                case 1:
                case 2:
                    $activityTitle = '';
                    $starttime = '';
                    $endtime = '';
                    $activityContent = '';

                    if ($cpageGet == 2) {
                        $data = $conn->where(['id' => $idGet])->limit(1)->get('activity');
                        if (!empty($data)) {
                            $activityTitle = $data[0]['activityTitle'];
                            $starttime = date('Y-m-d', $data[0]['time']);
                            $endtime = date('Y-m-d', $data[0]['endtime']);
                            $activityContent = $data[0]['activityContent'];
                        }
                        $data_activity_config = $conn->where(['activityId' => $idGet])->order_by('')->get('activity_config');
                    }
                    ?>
                    <form  method="post"  action="?act=<?php echo $cpageGet ?><?php !empty($idGet) && print('&id=' . $idGet) ?>">
                        <dl>
                            <dt>活动管理  <span class="addLink"></span></dt>
                            <dd>
                                <div class="list atable">
                                    <ul class="list a20_80">
                                        <li>
                                            活动名称:
                                        </li>
                                        <li>
                                            <input name="activityTitle" value="<?php echo $activityTitle; ?>" style="width: 80%;" />
                                        </li>
                                    </ul>


                                    <ul class="list a20_80">
                                        <li>
                                            时间:
                                        </li>
                                        <li>
                                            开始时间：<input name="time" type="date" value="<?php echo $starttime; ?>" />
                                            结束时间： <input name="endtime" type="date" value="<?php echo $endtime; ?>"   />
                                        </li>
                                    </ul>   
                                    <ul class="list a20_80">
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <ul class="list a20_80">
                                        <li>
                                            活动内容:
                                        </li>
                                        <li>
                                            <textarea name="newText"><?php echo $activityContent; ?></textarea>
                                        </li>
                                    </ul>


                                    <ul class="list a20_80">
                                        <li>
                                            设置活动:
                                        </li>
                                        <li>
                                        <span class="addfun">添加</span>
                                        <div class="activityC">

                                            <?php
                                            if (!empty($data_activity_config)):
                                                foreach ($data_activity_config as $activityConfigRs):
                                                    ?>
                                                    <p id="<?php echo $activityConfigRs['activityKey'] ?>">
                                                        名称：
                                                        <input name="activityValue[]" value="<?php echo $activityConfigRs['activityValue'] ?>"  type="text">
                                                        格式：
                                                        <select class="format" data-content="<?php echo $activityConfigRs['activityInput'] ?>" name="activityInput[]">
                                                            <option>text</option>
                                                            <option>radio</option>
                                                            <option>checkbox</option>
                                                            <option>select</option>
                                                        </select>
                                                    <span class="a1">
                                                        活动状态：<input style="width: 40%;" name="activitystate[]"  value="<?php echo $activityConfigRs['activitystate'] ?>">
                                                    </span>
                                                    <span class="del">
                                                        删除
                                                    </span>
                                                    </p>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </div>
                                        </li>
                                    </ul>
                                </div>
                            </dd>
                        </dl>
                        <div class="tijiao">
                            <button type="submit"><?php echo $lang['submit']; ?></button>
                            <button type="reset"><?php echo $lang['reset']; ?></button>
                            <button type="button" id='fanhui'><?php echo $lang['back']; ?></button>
                        </div>
                    </form>

                    <?php
                    break;
                case 3:
                    $data_activity_config = $conn->where(['activityId' => $idGet])->get('activity_config');
                    $data_activity_content = $conn->where(['activityId' => $idGet])->get('activity_content');
                    ?>
                    <dl>

                        <dt>活动管理  <span class="addLink"><a href="?act=5&id=<?php echo $idGet; ?>">下载</a></span></dt>
                        <dd>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <?php foreach ($data_activity_config as $v): ?>
                                        <li><?php echo $v['activityValue'] ?></li>
                                    <?php endforeach; ?>
                                    <li>操作</li>
                                </ul>
                                <?php foreach ($data_activity_content as $vs): ?>
                                    <ul class="list atr">
                                        <?php foreach ($data_activity_config as $v): ?>
                                            <li><?php echo $vs[$v['activityKey']] ?></li>
                                        <?php endforeach; ?>
                                        <li><a href="?act=6&id=<?php echo $vs['id'] ?>">删除</a></li>
                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                    </dl>

                <?php

            endswitch;
            ?>
        </div>

        <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
        <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>
        <script charset="utf-8"  src="../js/qmancms.js" ></script>
        <!--JqueryEasyui-->
    <link rel="stylesheet" href="../js/JqueryEasyui/default/easyui.css" />
    <script charset="utf-8" src="../js/JqueryEasyui/JqueryEasyui.js"></script>
    <script>
        $('.hide').hide();

        $('.addfun').on('click', function () {

            var html = '';
            html += ("名称：");
            html += ("<input name=\'activityValue[]\'type=\'text\'>");
            html += ("格式：");
            html += ("<select class=\'format\'name=\'activityInput[]\'>");
            html += ("<option>text</option>");
            html += ("<option>hidden</option>");
            html += ("<option>radio</option>");
            html += ("<option>checkbox</option>");
            html += ("<option>select</option>");
            html += ("</select>");
            html += ("<span class=\'a1\'>");
            html += ("活动状态：<input style=\'width:40%;\'name=\'activitystate[]\'placeholder=\'radiocheckboxselect需要添加状态参数，内容以逗号隔开\',\'\'>");
            html += ("</span>");
            html += ("<span class=\'del\'>");
            html += ("删除");
            html += ("</span>");

            $('.activityC').append('<p>' + html + '</p>');
            $('.activityC').scrollTop(Number.MAX_VALUE);
        });
<?php if (!empty($idGet)): ?>
            $('.activityC').on('click', '.del', function () {
                var this_ = $(this);
                var id = this_.parent().attr('id');

                $.post('?act=4', {'activityId': id, 'id':<?php echo $idGet ?>}, function (i, p) {
                    if (i) {
                        this_.parent().remove();
                    }
                });
            });
<?php else: ?>
            $('.activityC').on('click', '.del', function () {
                var this_ = $(this);
                this_.parent().remove();
            });

<?php endif; ?>
        $('.format').each(function (i, p) {

            var p_ = p;
            $(p_).find('option').each(function (i, p) {
                if (p.text === $(p_).data('content')) {
                    $(p).attr("selected", true);
                    if ($(p_).data('content') !== "text") {
                        $(p_).next().show();
                    }
                }
            });
        });
        $('.activityC').on('change', '.format', function (e) {
            switch ($(this).val()) {

                case 'radio':

                case 'checkbox':

                case 'select':
                    $(this).next().show();
                    break;
                default:
                    $(this).next().hide();
                    break;
            }
        });
    </script>
</body>
</html>
