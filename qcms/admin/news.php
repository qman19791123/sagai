<?php
define('noCache', TRUE);
include '../config.php';
include lib . 'tfunction.inc.php';
include plus . 'upload/upload.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;

$classifyId = '0';
$sort = '0';
$classifyJson = [];
$adminId = $_SESSION['adminId'];

// get
$cpage = filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); //新闻编号 (有时间修改此命名)
$act = filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);



// post
// 数据添加 删除 修改 操作
$newTextINPUT = filter_input(INPUT_POST, 'newText', FILTER_CALLBACK, ['options' => 'tfunction::encode']);
$keywordsINPUT = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);
$descriptionINPUT = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$classifyIdINPUT = filter_input(INPUT_POST, 'classifyId', FILTER_VALIDATE_INT);
$sortINPUT = filter_input(INPUT_POST, 'sort', FILTER_VALIDATE_INT, ['options' => ['min_range' => -10, 'max_range' => 10]]);
$tagINPUT = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
$subtitleINPUT = filter_input(INPUT_POST, 'subtitle', FILTER_CALLBACK, ['options' => 'tfunction::encode']);
$titleINPUT = filter_input(INPUT_POST, 'title', FILTER_CALLBACK, ['options' => 'tfunction::dropQuote']);
$titlePhotoINPUT = filter_input(INPUT_POST, 'titlePhoto', FILTER_SANITIZE_STRING);
$updateImgINPUT = filter_input(INPUT_POST, 'updateImg', FILTER_UNSAFE_RAW);
$checkedINPUT = filter_input(INPUT_POST, 'checked', FILTER_VALIDATE_INT);



// 一个匿名方法 作用发布文章 
$AMNewsContent = function($Rsid, $newTextINPUT, $keywordsINPUT, $descriptionINPUT)use($conn) {
    $sqlNewsContentAddModele = 'INSERT INTO'
            . '`news_content`'
            . '(`newsId`,`newText`,`keywords`,`description`) VALUES'
            . '("%s","%s","%s","%s")';
    $sqlNewsContentAdd = sprintf($sqlNewsContentAddModele, $Rsid, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
    $conn->aud($sqlNewsContentAdd);
};

// 一个匿名方法 作用记录此新闻使用过的图片
$AMNewsImages = function($classifyIdINPUT, $Rsid, $updateImg = '')use($conn) {

    $jsonImagesSqlT = $jsonImagesSql = '';
    if (is_array($updateImg)) {
        foreach ($updateImg as $valueJsonImages) {
            $jsonImagesSqlT .= sprintf('("%s","%s","%s","%s"),', $classifyIdINPUT, $Rsid, $valueJsonImages, time());
        }
    } else if (is_string($updateImg) && !empty($updateImg)) {
        $jsonImagesSqlT .= sprintf('("%s","%s","%s","%s"),', $classifyIdINPUT, $Rsid, $updateImg, time());
    }

    $jsonImagesSql = 'INSERT INTO `Images` (`classifyId`,`newId`,`images`,`time`) VALUES' . trim($jsonImagesSqlT, ',');
    $conn->aud($jsonImagesSql);
};

// 一个匿名方法 作用图片上传 
$AMNewsUploadImage = function($path = '') use ($tfunction) {

    $file = $_FILES['file'];
    if (!empty($file['name'])) {
        $upload = Upload::factory('/file/image/' . date('Ymd'));
        $upload->set_filename(md5(time()));
        $upload->file($file);
        $upload->set_max_file_size(200);
        $upload->set_allowed_mime_types(['image/x-png', 'image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png']);
        $results = $upload->upload();
        if ($results['status'] === true) {
            $path = $results['path'];
        } else {
            die($tfunction->message('请上传 jpg png gif 格式的图片'));
        }
    }
    return $path;
};

switch ($act) {
    case 1:
// 添加部分限制代码
        empty($titleINPUT) && die($tfunction->message('信息标题不能为空'));
        empty($newTextINPUT) && die($tfunction->message('信息内容不能为空'));
        empty($classifyIdINPUT) && die($tfunction->message('栏目不能为空'));

        empty($subtitleINPUT) && $subtitleINPUT = mb_substr(filter_var($newTextINPUT, FILTER_SANITIZE_STRING), 0, 240);
        empty($descriptionINPUT) && $descriptionINPUT = $subtitleINPUT;
        !is_numeric($sortINPUT) && $sortINPUT = 0;
// 标题拼音化
        $pinyin = $tfunction->py(mb_substr($titleINPUT, 0, 20, 'utf-8')) . '-' . md5(microtime());

// 图片上传功能
        $path = $AMNewsUploadImage();

// 添加内容配置表信息
        $Rs = $conn->insert('news_config', [
            'classifyId' => $classifyIdINPUT,
            'userid' => $adminId,
            'time' => time(),
            'sort' => $sortINPUT,
            'tag' => $tagINPUT,
            'subtitle' => $subtitleINPUT,
            'title' => $titleINPUT,
            'titlePhoto' => $path,
            'checked' => $checkedINPUT,
            'pinyin' => $pinyin,
            'isdel' => 0
        ]);
// 添加内容表信息
        $AMNewsContent($Rs, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);

// 修改统计文章总数
        $RsSummary = $conn->select('summary')->where(['id' => $classifyIdINPUT])->get('classify');
        $conn->where(['id' => $classifyIdINPUT])->update('classify', ['summary' => $RsSummary[0]['summary'] + 1]);
// 记录此新闻使用过的图片
        $AMNewsImages($classifyIdINPUT, $Rs, $path);
        empty($updateImgINPUT) or $AMNewsImages($classifyIdINPUT, $RsId, json_decode('[' . trim($updateImgINPUT, ',') . ']'));

        die($tfunction->message('添加内容成功', 'news.php'));
        break;
    case 2:
// 修改部分代码s
        if (!empty($id)) {
// 添加部分限制代码
            empty($titleINPUT) && die($tfunction->message('信息标题不能为空'));
            empty($newTextINPUT) && die($tfunction->message('信息内容不能为空'));
            empty($classifyIdINPUT) && die($tfunction->message('栏目不能为空'));
// 图片上传功能
            $path = $AMNewsUploadImage($titlePhotoINPUT);
// 查询文章是否存在
            $Rsc = $conn->select('count(newsId) as C')->where(['newsId' => $id])->get('news_content');
// 不存在添加文章 存在修改文章
            if (empty($Rsc[0]['C'])) {
// 添加文章内容
                $AMNewsContent($id, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);
            } else {
// 修改文章内容
                $conn->where(['newsId' => $id])->update(
                        'news_content', ['newText' => $newTextINPUT, 'keywords' => $keywordsINPUT, 'description' => $descriptionINPUT]
                );
            }
// 文章配置进行修改
            $conn->where(['id' => $id])->update('news_config', [
                'classifyId' => $classifyIdINPUT,
                'sort' => $sortINPUT,
                'tag' => $tagINPUT,
                'subtitle' => $titleINPUT,
                'title' => $titleINPUT,
                'titlePhoto' => $path,
                'checked' => $checkedINPUT
            ]);
// 记录此新闻使用过的图片
            if (!is_bool($path) && $path !== $titlePhotoINPUT) {
                $AMNewsImages($classifyIdINPUT, $id, $path);
            }
            empty($updateImgINPUT) or $imgRs = $AMNewsImages($classifyIdINPUT, $id, json_decode('[' . trim($updateImgINPUT, ',') . ']'));
            die($tfunction->message('修改内容成功', 'news.php'));
        }
        break;
    case 3:
// 删除部分代码
        // 获取文章和栏目
        $ajaxId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$ajaxId) {
            $ajaxId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            if (!$ajaxId) {
                die('false');
            }
        }

        $classify = $conn->select(['a.folder as folder', 'a.summary as summary', 'pinyin', 'a.id as aid', 'b.id as bid'])
                ->join('classify as a', 'a.id=b.classifyId', 'left')
                ->where('b.id in(' . $ajaxId . ')')
                ->get('news_config as b');



        foreach ($classify as $k => $v) {
            //获取文件
            $file = trim(staticFloder) . '/' . trim($v['folder']) . '/' . trim($v['pinyin']) . '.html';
            //判断是否开启静态功能和是否存在静态文件
            if (StaticOpen && is_file($file)) {
                //存在则修改文件名
                rename($file, $file . 'del');
            }
            $classifyCount[$v['aid']] = $v['summary'];
            $classifyIdArr[$v['aid']] = empty($classifyIdArr[$v['aid']]) || !isset($classifyIdArr[$v['aid']]) ? 1 : $classifyIdArr[$v['aid']] + 1;
            $newidArr[] = $v['bid'];
        }
        //修改栏目下文章数量''
        foreach ($classifyIdArr as $k => $v) {
            $conn->where(['id' => $k])->update('classify', ['summary' => ($classifyCount[$k] - $v)]);
            //防止操作过于频繁造成的计算过大的问题
            sleep(0.5);
        }
        //假删文章
        $newidStr = join(',', array_unique($newidArr));
        $conn->where('id in (' . $newidStr . ')')->update('news_config', ['isdel' => 1]);
        //防止操作过于频繁造成的计算过大的问题
        sleep(0.5);

        // 特定参数
        $p = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_BOOLEAN);
        if ($p === true) {
            die('true');
        }
        die($tfunction->message('删除成功', 'news.php'));
        break;
    case 4:
// 特殊传值AJAx （设置审核流程）
        $ajaxT = filter_input(INPUT_POST, 't', FILTER_VALIDATE_INT);
        $ajaxId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$ajaxId) {
            $ajaxId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
            if (!$ajaxId) {
                die('false');
            }
        }

        $conn->where('id in (' . $ajaxId . ')')->update('news_config', ['checked' => $ajaxT]);

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
                    $inlClassify = '';
                    $where = '';
                    $pagec = ($page - 1) < 0 ? 0 : ($page - 1) * 10;
                    $query = filter_input(INPUT_GET, 'query');
                    $poUrl = '';
                    $dataClassifyClassName = '所有文章';
                    $where .=' and isdel=0 ';
                    if (!empty($query)) {
                        $timestart = filter_input(INPUT_GET, 'timestart', FILTER_SANITIZE_STRING);
                        $timeend = filter_input(INPUT_GET, 'timeend', FILTER_SANITIZE_STRING);
                        $queryselect = filter_input(INPUT_GET, 'queryselect', FILTER_VALIDATE_INT);
                        $content = filter_input(INPUT_GET, 'content', FILTER_SANITIZE_STRING);
                        $url = filter_input(INPUT_GET, 'url', FILTER_VALIDATE_INT);
//                        $recovery = filter_input(INPUT_GET, 'recovery', FILTER_VALIDATE_BOOLEAN);

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

                        if ($url) {
                            //这个可能到后期需要更改
                            $dataClassify = $conn->select('id,className')->or_where(['pid' => $url, 'id' => $url])->order_by('id')->get('classify');
                            foreach ($dataClassify as $value) {
                                $inlClassify.=',' . $value['id'];
                                (int) $value['id'] === $url && $dataClassifyClassName = $value['className'];
                            }
                            $inlClassify = ltrim($inlClassify, ',');
                            $where.=' and classifyId in (' . $inlClassify . ')';
                        }

                        $arr = filter_input_array(INPUT_GET);
                        unset($arr['page']);
                        $poUrl = 'query=yes&url=' . $url;

                        if (!empty($url)) {
                            $poUrlModele = join('=%s&', array_keys($arr));
                            $poUrl = sprintf($poUrlModele . '=%s', empty($arr['query']) ? '' : $arr['query'], empty($arr['timestart']) ? '' : $arr['timestart'], empty($arr['timeend']) ? '' : $arr['timeend'], empty($arr['queryselect']) ? '' : $arr['queryselect'], empty($arr['queryselect']) ? '' : $arr['queryselect'], empty($arr['content']) ? '' : $arr['content']
                            );
                        }
                    }
                    $classifyRsJson = [];

                    $classifyRs = $conn->select('id,className')->get('classify');

                    foreach ($classifyRs as $classifyValue) {
                        $classifyRsJson['classify_' . $classifyValue['id']] = $classifyValue['className'];
                    }

                    $classifyJson = $classifyRsJson;

                    $data = $conn->select(['checked', 'time', 'id', 'sort', 'title', 'classifyId'])->where('1=1 ' . $where)->order_by('id desc')->limit(10)->offset($pagec)->get('news_config');

                    $dataCount = $conn->select('count(id) as cid')->where('1=1 ' . $where)->get('news_config');
                    ?>
                    <dl>
                        <dt>
                            文章管理-<?php echo $dataClassifyClassName; ?>
                        <span class="addLink">
                            [<a href='?cpage=1'>添加文章</a>]
                            [<a href='javascript:void(0)' id='retrievedArticles'>检索文章</a>]
                            [<a href='javascript:void(0)' id='column'>栏目管理</a>]
                            [<a href='?cpage=1'>更新列表</a>]
                            [<a href='?cpage=1'>更新文档</a>]
                            [<a href='newsdustbin.php'>文章回收</a>]
                        </span>
                        </dt>
                        <dd>
                            <div class="list atable">
                                <ul class="list atr" id="no">
                                    <li>选择</li>
                                    <li>编号</li>
                                    <li>排序</li>
                                    <li style="width: 10%">栏目</li>
                                    <li style="width: 40%">名称</li>
                                    <li style="width: 5%">审核</li>
                                    <li style="width: 17%">时间</li>
                                    <li style="width: 20%">操作</li>
                                </ul>
                                <?php
                                foreach ($data as $rs):
                                    ?>
                                    <ul class="list atr">
                                        <li><input name="checkid" type="checkbox" value="<?php echo $rs['id'] ?>"></li>
                                        <li><?php echo $rs['id'] ?></li>
                                        <li><?php echo $rs['sort'] ?></li>
                                        <li data-id="<?php echo $rs['classifyId'] ?>"></li>
                                        <li><?php echo $rs['title'] ?></li>
                                        <li><?php
                                            if (is_numeric($rs['checked'])) {
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
                                            } else {
                                                echo '等待';
                                            }
                                            ?></li>
                                        <li><?php echo date('Y-m-d H:i:s', $rs['time']) ?></li>

                                        <li>
                                            <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>" class="checked">审核</a>
                                            <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>">阅览</a>
                                            <a href="?cpage=2&id=<?php echo $rs['id'] ?>">修改新闻</a>
                                            <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?> ?>">删除新闻</a>
                                        </li>

                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </dd>
                        <dd class='toolbar'>
                        <button type='button' id='all' onclick="javascript:$('input[name=checkid]').attr('checked', true).prop('checked', true)">全选</button>
                        <button type='button' id='cancel' onclick="javascript:$('input[name=checkid]').removeAttr('checked', '').prop('checked', false)">取消</button>
                        <button type='button' id='push'>推送</button>
                        <button type='button' id='check' >审核</button>
                        <button type='button' id='delete'>删除</button>
                        </dd>
                        <dd class='page'>
                            <a href="?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>&<?php echo $poUrl ?>">上一页</a>
                            <?php
                            $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                            for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                                ?>
                                <a href="?page=<?php echo $i ?>&<?php echo $poUrl ?>"><span style="color:<?php echo $i == $page || ($page <= 0 && $i == 1) ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                                <?php
                            endfor;
                            ?>
                            <a href='?page=<?php echo ($page < $p['pageCount'] ? $page <= 0 ? $page + 2 : $page + 1 : $page) ?>&<?php echo $poUrl ?>'>下一页</a>
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

                        $Rs = $conn
                                ->select(['classifyId', 'sort', 'tag', 'subtitle', 'title', 'titlePhoto', 'newText', 'keywords', 'description', 'checked'])
                                ->join('news_content', 'news_config.id = news_content.newsId', 'left')
                                ->where(['id' => $id])
                                ->get('news_config');

                        $sort = $Rs[0]['sort'];
                        $classifyId = $Rs[0]['classifyId'];
                        $tag = $Rs[0]['tag'];
                        $title = $Rs[0]['title'];
                        $titlePhoto = $Rs[0]['titlePhoto'];
                        //解码内容返回带有HTML标签内容
                        $newText = tfunction::decode($Rs[0]['newText']);
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
                                                <option value="<?php echo $rs['id'] ?>"  <?php !empty($rs['disabled']) && print($rs['disabled']); ?>><?php echo $rs['className'] ?></option>
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
                                    <li>内容:</li>
                                    <li><textarea name="newText" style="width: 80%;height: 50px;resize: none;"><?php echo $newText; ?></textarea></li>   
                                </ul>

                                <ul class="list a20_80">
                                    <li>
                                        审核
                                    </li>
                                    <li>


                                        通过:<input type="radio" <?php $checked == 999 && print('checked="checked"') ?>  name="checked" value="1"/>
                                        未通过:<input type="radio" <?php $checked == 0 && print('checked="checked"') ?>  name="checked" value="0"/>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" value="" name="updateImg"/>
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
                    <span>添加时间：</span> <input class="times" name='timestart'/> - <input class="times" name='timeend'/>
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

                    <div class='tree'></div>


                </form>
            </div>
        </div>
        <!--栏目 end-->

        <!--KindEditor-->
    <link rel="stylesheet" href="../js/KindEditor/themes/default/default.css" />
    <link rel="stylesheet" href="../js/KindEditor/plugins/code/prettify.css" />
    <script charset="utf-8" src="../js/KindEditor/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="../js/KindEditor/plugins/code/prettify.js"></script>
    <!--JqueryEasyui-->
    <link rel="stylesheet" href="../js/JqueryEasyui/default/easyui.css" />
    <script charset="utf-8" src="../js/JqueryEasyui/JqueryEasyui.js"></script>
    <!--dialog  -->
    <link rel="stylesheet" href="../js/Dialog/css/ui-dialog.css">
    <script type="text/javascript" src="../js/Dialog/dist/dialog-min.js"></script>
    <!--upfile  -->
    <script type="text/javascript" src="../js/file.js"></script>

    <script type="text/javascript">
<?php printf('var classifyJson =%s;', json_encode($classifyJson)); ?>
                    //mouseover
                    $('.adminContent .atable ul').on('mouseover', function () {
                        $(this).css({'background': '#444'});
                        $(this).find('a').css({'background': '', 'color': '#fff'});
                    }).on('mouseout', function () {
                        $(this).css({'background': '', 'color': ''});
                        $(this).find('a').css({'background': '', 'color': ''});
                    });


                    $('.atable ul li').each(function (i, p) {
                        if ($(p).attr('data-id'))
                        {
                            $(p).text(classifyJson['classify_' + $(p).attr('data-id')]);
                        }
                    });



                    $('#delete').on('click', function () {
                        dialog({
                            backdropBackground: '',
                            title: '提示',
                            content: '确认是否删除此些文章',
                            okValue: '确定',
                            width: '500px',
                            ok: function () {
                                this.title('删除中…');
                                this.content("删除中请等待");
                                var checkboxp = '';
                                $('input[name=checkid]:checked').each(function (i, p) {
                                    checkboxp += $(p).val() + ',';
                                });
                                if (checkboxp) {
                                    checkboxp = checkboxp.substr(0, checkboxp.length - 1);
                                    $.ajax({
                                        type: "GET",
                                        data: {'id': checkboxp},
                                        url: '?p=on&act=3',
                                        success: function (e) {
                                            if (Boolean(e) === true) {
                                                history.go(0);
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

                    //指定文章输出栏目 [栏目管理] code start
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

                        $('.columnContent').eq(1).find('.tree').tree({
                            data: <?php echo json_encode($tfunction->classifyArray()) ?>,
                            onClick: function (node) {
                                self.location = ('?query=yes&url=' + node.id);
                            }
                        });
                    });
                    //指定文章输出栏目 [栏目管理] code end

                    //检索（查询） 文章 code start
                    $('#retrievedArticles').on('click', function () {
                        $('.combo').hide();
                        dialog({
                            backdropBackground: '',
                            title: '提示',
                            content: $('.search').html(),
                            okValue: '确定',
                            width: '700px',
                            height: '180px',
                            ok: function () {
                                var query = $('.searchContent').eq(1).find('#query');
                                // if(query.find('select[name="queryselect"]').val()<=0){
                                //     alert('请选择搜索的范围');
                                //     return false;
                                // }
                                self.location = ('news.php?query=yes&' + query.serialize());
                                return false;
                            },
                            cancelValue: '取消',
                            cancel: function () {}
                        }).showModal();
                        $('.searchContent .times').datebox({});
                    });
                    //检索（查询） 文章 code end


                    //文章审核code start
                    // 文章 审核 提醒
                    $('#off').on('click', function () {
                        $(this).parent('span').hide();
                    });
                    //对单个文章进行审核设置
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
                                            if (Boolean(e) === true) {
                                                history.go(0);
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
                    //对文章批量确定审核
                    $('#check').on('click', function () {
                        var checkboxp = '';
                        $('input[name=checkid]:checked').each(function (i, p) {
                            checkboxp += $(p).val() + ',';
                        });
                        if (checkboxp) {

                            checkboxp = checkboxp.substr(0, checkboxp.length - 1)
                            $.ajax({
                                type: "POST",
                                url: '?act=4',
                                data: {'t': '999', 'id': checkboxp},
                                success: function (e) {
                                    if (Boolean(e) === true) {
                                        history.go(0);
                                    }
                                }
                            });
                        }
                        return false;
                    });

                    //文章审核code end

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
                                img.val('"' + data + '",' + img.val());
                            }
                        });
                        prettyPrint();
                    });
                    // KindEditor 编辑器 code end

                    //返回上一页 按钮 code start
                    $('#fanhui').on('click', function () {
                        self.history.go(-1);
                    });
                    //返回上一页 按钮 code end

                    //排序 权重 数量 设置 code start
                    $('.sort').append(function () {
                        var html = '', t;
                        for (i = -10; i <= 10; ++i) {
                            t = parseInt(<?php echo $sort ?>) === parseInt(i) ? 'selected' : '';
                            html += '<option ' + t + '>' + i + '</option>';
                        }
                        return html;
                    });
                    //排序 权重 数量 设置 code end

                    //定位文章 栏目 select selected 挑选出来的选项位置 code start
                    $('.classifyId option').each(function (i, v) {
                        t = (parseInt($(v).val()) === parseInt(<?php echo $classifyId ?>));
                        $(v).attr('selected', t);
                    });
                    //定位文章 栏目 select selected 挑选出来的选项位置 code end

                    //删除提示 code start
                    $('.delmes').on('click', function () {
                        return confirm('是否真的删除');
                    });
                    //删除提示  code end

                    // 图片选择 code start
                    $('.file').hide();
                    $('.showfile').on('click', function () {
                        var th = $(this);
                        $('#file_input').click();
                        $('#file_input').checkFileTypeAndSize({
                            allowedExtensions: ['jpg', 'png', 'gif'],
                            maxSize: 500,
                            success: function () {
                                th.val($('#file_input').val());
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
                    // 图片选择 code end
    </script>
</body>

</html>
