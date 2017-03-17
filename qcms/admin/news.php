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
include plus . 'upload/upload.php';
include 'isadmin.php';
include lang . $language;
$tfunction = new tfunction();
$conn = $tfunction->conn;

$classifyId = '0';
$sort = '0';
$classifyJson = [];
$adminId = $_SESSION['adminId'];

$newsStyle = 0;
// get
$cpage = (int) filter_input(INPUT_GET, 'cpage', FILTER_VALIDATE_INT);
$page = (int) filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); //新闻编号 (有时间修改此命名)
$act = (int) filter_input(INPUT_GET, 'act', FILTER_VALIDATE_INT);
$newssubjectClassId = filter_input(INPUT_GET, 'newssubjectClassId', FILTER_SANITIZE_STRING);
$newssubjectId = filter_input(INPUT_GET, 'newssubjectId', FILTER_SANITIZE_STRING);
$newsStyleGET = (int) filter_input(INPUT_GET, 'newsStyle', FILTER_VALIDATE_INT);
$delSessionGET = filter_input(INPUT_GET, 'delSession', FILTER_VALIDATE_BOOLEAN);


// post
// 数据添加 删除 修改 操作
$newTextINPUT = filter_input(INPUT_POST, 'newText', FILTER_CALLBACK, ['options' => 'tfunction::encode']);
$keywordsINPUT = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);
$descriptionINPUT = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$classifyIdINPUT = (int) filter_input(INPUT_POST, 'classifyId', FILTER_VALIDATE_INT);
$sortINPUT = (int) filter_input(INPUT_POST, 'sort', FILTER_VALIDATE_INT, ['options' => ['min_range' => -10, 'max_range' => 10]]);
$tagINPUT = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
$subtitleINPUT = filter_input(INPUT_POST, 'subtitle', FILTER_CALLBACK, ['options' => 'tfunction::encode']);
$titleINPUT = filter_input(INPUT_POST, 'title', FILTER_CALLBACK, ['options' => 'tfunction::dropQuote']);
$titlePhotoINPUT = filter_input(INPUT_POST, 'titlePhoto', FILTER_SANITIZE_STRING);
$updateImgINPUT = filter_input(INPUT_POST, 'updateImg', FILTER_UNSAFE_RAW);
$checkedINPUT = (int) filter_input(INPUT_POST, 'checked', FILTER_VALIDATE_INT);
$newssubjectDataIdINPUT = filter_input(INPUT_POST, 'newssubjectDataId', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

$newssubjectDataContentINPUT = filter_input(INPUT_POST, 'newssubjectDataContent', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

// 新闻集合参数
if (!empty($newssubjectClassId) && empty($_SESSION['newssubjectClassId'])) {
    $_SESSION['newssubjectClassId'] = $newssubjectClassId;
    $_SESSION['newssubjectId'] = $newssubjectId;
} elseif (!empty($_SESSION['newssubjectClassId'])) {
    $newssubjectClassId = $_SESSION['newssubjectClassId'];
    $newssubjectId = $_SESSION['newssubjectId'];
}

if ($delSessionGET) {
    unset($_SESSION['newssubjectClassId'], $_SESSION['newssubjectId']);
    $newssubjectId = NULL;
}

// 新闻心态
if (!empty($newsStyleGET)) {
    $_SESSION['newsStyle'] = $newsStyleGET;
    $newsStyle = $newsStyleGET;
} elseif (!empty($_SESSION['newsStyle'])) {
    $newsStyle = $_SESSION['newsStyle'];
}

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
$AMNewsImages = function($Rsid, $updateImg = '')use($conn) {

    $jsonImagesSqlT = $jsonImagesSql = '';
    if (is_array($updateImg)) {
        foreach ($updateImg as $valueJsonImages) {
            $jsonImagesSqlT .= sprintf('("%s","%s","%s","%s"),', $Rsid, $valueJsonImages, time(), 1);
        }
    } else if (is_string($updateImg) && !empty($updateImg)) {
        $jsonImagesSqlT .= sprintf('("%s","%s","%s","%s"),', $Rsid, $updateImg, time(), 1);
    }

    $jsonImagesSql = 'INSERT INTO `Images` (`IDNo`,`images`,`time`,`style`) VALUES' . trim($jsonImagesSqlT, ',');
    $conn->aud($jsonImagesSql);

    $jsonImagesSql = 'delete from images where id not in ( select id from images as a   Group By images Having count(*)>=1)';
    $conn->aud($jsonImagesSql);
};

// 一个匿名方法 作用图片上传 
$AMNewsUploadImage = function($path = '') use ($tfunction, $lang) {

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
            die($tfunction->message($lang['uploadIsJpgPngGif']));
        }
    }
    return $path;
};
// 一个匿名方法 作用发布专题文章  
$AMNewsAddSpecialContent = function($newssubjectData = '', $newssubjectDataContent) use($newssubjectClassId, $conn) {
    $sqlContent = 'insert into special_content (`specialClassifyId`,`newsIds`,`newTitle`,`sort`)values';
    foreach ($newssubjectData as $v) {
        if (is_numeric($v)) {
            $sqlContent .= sprintf('("%s","%s","%s","%s"),', $newssubjectClassId, $v, $newssubjectDataContent[$v], 0);
        }
    }
    $sql = substr($sqlContent, 0, -1);
    $conn->aud($sql);
};
// 一个匿名方法 作用返回专题文章内容  
$AMNewsAddSpecialContentUrl = function()use($newssubjectId ) {
    $url = empty($newssubjectId) ? 'news.php' : 'newssubject.php?cpage=2&id=' . $newssubjectId;
    unset($_SESSION['newssubjectClassId'], $_SESSION['newssubjectId']);
    return $url;
};

switch ($act) {
    case 1:
// 添加部分代码
// 限制代码
        empty($titleINPUT) && die($tfunction->message($lang['mesgTitleNotEmpty']));
        empty($newTextINPUT) && die($tfunction->message($lang['mesgContentNotEmpty']));
        (!$newssubjectClassId && empty($classifyIdINPUT)) && die($tfunction->message($lang['mesgColumnsNotEmpty']));

        empty($subtitleINPUT) && $subtitleINPUT = mb_substr(filter_var($newTextINPUT, FILTER_SANITIZE_STRING), 0, 240);
        empty($descriptionINPUT) && $descriptionINPUT = $subtitleINPUT;
        !is_numeric($sortINPUT) && $sortINPUT = 0;
// 标题拼音化
        $pinyin = $tfunction->py($titleINPUT, 'tfunction::ZNSymbolFilter') . '-' . md5(microtime());

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
            'checked' => $checkedINPUT >= 1 ? 999 : 1,
            'pinyin' => $pinyin,
            'isdel' => 0,
            'style' => (int) !empty($newssubjectId)
        ]);
// 添加内容表信息
        $AMNewsContent($Rs, $newTextINPUT, $keywordsINPUT, $descriptionINPUT);

// 修改统计文章总数
        $RsSummary = $conn->select('summary')->where(['id' => $classifyIdINPUT])->get('classify');
        $conn->where(['id' => $classifyIdINPUT])->update('classify', ['summary' => $RsSummary[0]['summary'] + 1]);
// 记录此新闻使用过的图片
        $AMNewsImages($Rs, $path);
        empty($updateImgINPUT) or $AMNewsImages($Rs, json_decode('[' . trim($updateImgINPUT, ',') . ']'));
// 专题文章        
        empty($newssubjectId) or $AMNewsAddSpecialContent([$Rs], [$Rs => $subtitleINPUT]);

        die($tfunction->message($lang['mesgAddContentSuccess'], $AMNewsAddSpecialContentUrl()));
        break;
    case 2:
// 修改部分代码
        if (!empty($id)) {
// 添加部分限制代码
            empty($titleINPUT) && die($tfunction->message($lang['mesgTitleNotEmpty']));
            empty($newTextINPUT) && die($tfunction->message($lang['mesgContentNotEmpty']));
            (!$newssubjectClassId && empty($classifyIdINPUT)) && die($tfunction->message($lang['mesgColumnsNotEmpty']));
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
                'subtitle' => $subtitleINPUT,
                'title' => $titleINPUT,
                'titlePhoto' => $path,
                'checked' => $checkedINPUT >= 1 ? 999 : 1
            ]);
// 记录此新闻使用过的图片
            if (!is_bool($path) && $path !== $titlePhotoINPUT) {
                $AMNewsImages($id, $path);
            }
            empty($updateImgINPUT) or $imgRs = $AMNewsImages($id, json_decode('[' . trim($updateImgINPUT, ',') . ']'));


            die($tfunction->message($lang['mesgUpdateContentSuccess'], 'news.php'));
        }
        break;
    case 3:
// 删除部分代码
        // 获取文章和栏目
        $ajaxId = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
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
        die($tfunction->message($lang['mesgRemoveContentSuccess'], 'news.php'));
        break;
    case 4:
// 特殊传值AJAx （设置审核流程）
        $ajaxT = (int) filter_input(INPUT_POST, 't', FILTER_VALIDATE_INT);
        $ajaxId = (int) filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
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
        // 推送文章到专题文章下的结合中
        $newssubjectDataIdINPUT_ = join(',', $newssubjectDataIdINPUT);

        $Rs = $conn->select(['newsIds'])->where('specialClassifyId =' . $newssubjectClassId . ' and newsIds in(' . $newssubjectDataIdINPUT_ . ')')->get('special_content');
        $newssubjectDataIdArr = [];


        foreach ($Rs as $v) {
            $newssubjectDataIdArr[] = $v['newsIds'];
        }

        $newssubjectData = array_diff($newssubjectDataIdINPUT, $newssubjectDataIdArr);

        if (empty($newssubjectData)) {
            die(json_encode([false, $AMNewsAddSpecialContentUrl()]));
        }

        $AMNewsAddSpecialContent($newssubjectData, $newssubjectDataContentINPUT);

        die(json_encode([true, $AMNewsAddSpecialContentUrl()]));
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
                case 3:
                    $inlClassify = '';
                    $where = '';
                    $pagec = ($page - 1) < 0 ? 0 : ($page - 1) * 10;
                    $query = filter_input(INPUT_GET, 'query');
                    $poUrl = '';
                    $dataClassifyClassName = $lang['newsAllArticles'];

                    //获取未被假删除的文章
                    $where .= ' and isdel=0 ';
                    if (!empty($query)) {
                        $timestart = filter_input(INPUT_GET, 'timestart', FILTER_SANITIZE_STRING);
                        $timeend = filter_input(INPUT_GET, 'timeend', FILTER_SANITIZE_STRING);
                        $queryselect = (int) filter_input(INPUT_GET, 'queryselect', FILTER_VALIDATE_INT);
                        $content = filter_input(INPUT_GET, 'content', FILTER_SANITIZE_STRING);
                        $url = (int) filter_input(INPUT_GET, 'url', FILTER_VALIDATE_INT);
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
                                $inlClassify .= ',' . $value['id'];
                                (int) $value['id'] === $url && $dataClassifyClassName = $value['className'];
                            }
                            $inlClassify = ltrim($inlClassify, ',');
                            $where .= ' and classifyId in (' . $inlClassify . ')';
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

                    //获取专题或是普通文章
                    $where .= ' and style=' . (empty($newsStyle) ? 0 : ($newsStyle - 1));

                    $data = $conn->select(['checked', 'time', 'id', 'sort', 'title', 'classifyId'])->where('1=1 ' . $where)->order_by('id desc')->limit(10)->offset($pagec)->get('news_config');

                    $dataCount = $conn->select('count(id) as cid')->where('1=1 ' . $where)->get('news_config');
                    ?>

                    <dl>
                        <dt>
                            <?php echo $lang['newsArticleManager']; ?>-<?php echo $dataClassifyClassName; ?>
                        <span class="addLink">
                            [<a href='?cpage=1'><?php echo $lang['newsAddArticle']; ?></a>]
                            [<a href='javascript:void(0)' id='retrievedArticles'><?php echo $lang['newsRetrievingArticle']; ?></a>]
                            [<a href='javascript:void(0)' id='column'><?php echo $lang['newsTopicManagement']; ?></a>]
                            <?php if (!isset($newssubjectId)): ?>
                                [<a href='?cpage=1'><?php echo $lang['newsUpdateList']; ?></a>]
                                [<a href='?cpage=1'><?php echo $lang['newsUpdateDocumentation']; ?></a>]
                                [<a href='newsdustbin.php'><?php echo $lang['newsArticlesRecycling']; ?></a>]
                            <?php endif; ?>  
                        </span>
                        </dt>
                        <dd <?php empty($data) && print( 'class = "notInfo"'); ?>>
                            <div class="qmancmsmenu">
                                <a class='<?php echo ($newsStyle == 1 || $newsStyle == 0) ? 'color' : '' ?>' href='?newsStyle=1'>普通文章</a>
                                <a class='<?php echo ($newsStyle == 2) ? 'color' : '' ?>' href='?newsStyle=2'>专题文章</a>
                            </div>
                            <?php if (!empty($data)): ?> 
                                <div class="list atable">
                                    <ul class="list atr" id="no">
                                        <li><?php echo $lang['choose']; ?></li>
                                        <li><?php echo $lang['id']; ?></li>
                                        <li><?php echo $lang['sort']; ?></li>

                                        <?php if (($newsStyle - 1) <= 0): ?> <li style="width: 10%"><?php echo $lang['columns']; ?></li><?php endif ?>
                                        <li style="width: 40%"><?php echo $lang['name']; ?></li>
                                        <li style="width: 5%"><?php echo $lang['check']; ?></li>
                                        <li style="width: 17%"><?php echo $lang['time']; ?></li>
                                        <li style="width: 20%"><?php echo $lang['operation']; ?></li>
                                    </ul>
                                    <?php
                                    foreach ($data as $rs):
                                        ?>
                                        <ul class="list atr">
                                            <li><input name="checkid" type="checkbox" value="<?php echo $rs['id'] ?>"></li>
                                            <li><?php echo $rs['id'] ?></li>
                                            <li><?php echo $rs['sort'] ?></li>
                                            <?php if (($newsStyle - 1) <= 0): ?> <li data-id="<?php echo $rs['classifyId'] ?>"></li><?php endif ?>
                                            <li><?php echo $rs['title'] ?></li>
                                            <li><?php
                                                if (is_numeric($rs['checked'])) {
                                                    switch ($rs['checked']) {
                                                        case 0 :
                                                            echo $lang['notPass'];
                                                            break;
                                                        case 1:
                                                            echo $lang['wait'];
                                                            break;
                                                        case 999:
                                                            echo $lang['pass'];
                                                            break;
                                                    }
                                                } else {
                                                    echo $lang['wait'];
                                                }
                                                ?></li>
                                            <li><?php echo date('Y-n-d G:i', $rs['time']) ?></li>
                                            <li>
                                                <?php if (!empty($newssubjectId)): ?> 
                                                    <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>" class="checked"><?php echo $lang['check']; ?></a>
                                                    <a href="javascript:void(0)" data-id="<?php echo $rs['id'] ?>"><?php echo $lang['reading']; ?></a>
                                                <?php endif; ?>
                                                <a href="?cpage=2&id=<?php echo $rs['id'] ?>"><?php echo $lang['update']; ?></a>
                                                <a class="delmes nopt" href="?act=3&id=<?php echo $rs['id'] ?> "><?php echo $lang['remove']; ?></a>
                                            </li>
                                        </ul>
                                    <?php endforeach; ?>
                                </div>
                            </dd>
                            <dd class='toolbar'>
                            <button type='button' id='all' onclick="javascript:$('input[name=checkid]').attr('checked', true).prop('checked', true)"><?php echo $lang['selectAll']; ?></button>
                            <button type='button' id='cancel' onclick="javascript:$('input[name=checkid]').removeAttr('checked', '').prop('checked', false)"><?php echo $lang['cancel']; ?></button>
                            <?php if (!empty($newssubjectId)): ?>
                                <button type='button' id='push'><?php echo $lang['push']; ?></button>
                            <?php endif; ?>
                            <button type='button' id='check' ><?php echo $lang['check']; ?></button>
                            <button type='button' id='delete'><?php echo $lang['remove']; ?></button>

                            </dd>

                            <dd class='page'>
                                <a href="?page=<?php echo $page <= 1 ? 1 : $page - 1 ?>&<?php echo $poUrl ?>"><?php echo $lang['lastPage'] ?></a>
                                <?php
                                $p = $tfunction->Page($dataCount[0]['cid'], 1, 10, $page);
                                for ($i = $p['pageStart']; $i <= $p['pageEnd']; $i++):
                                    ?>
                                    <a href="?page=<?php echo $i ?>&<?php echo $poUrl ?>"><span style="color:<?php echo $i == $page || ($page <= 0 && $i == 1) ? "#FF0000 " : "#000000" ?>"><?php echo $i ?></span></a>
                                    <?php
                                endfor;
                                ?>
                                <a href='?page=<?php echo ($page < $p['pageCount'] ? $page <= 0 ? $page + 2 : $page + 1 : $page) ?>&<?php echo $poUrl ?>'><?php echo $lang['nextPage'] ?></a>
                            <?php else: ?>
                            <span><?php echo $lang['notInfo'] ?></span>
                        <?php endif; ?>
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
                    $style = 0;
                    if (!empty($id)) {

                        $Rs = $conn
                                ->select(['classifyId', 'style', 'sort', 'tag', 'subtitle', 'title', 'titlePhoto', 'newText', 'keywords', 'description', 'checked'])
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
                        $style = $Rs[0]['style'];
                    }
                    ?>
                    <dl>
                        <dt>
                            <?php echo $lang['newsArticleManager']; ?>
                        <span class="addLink"></span>
                        </dt>
                        <dd>
                            <?php if ($checked == 1): ?>
                            <span class="informationBar">
                                <?php print($lang['mesgPleaseReview']); ?>
                                <a href="javascript:void(0)" id='off' title="关闭">X</a>
                            </span>
                        <?php endif; ?>
                        <form method="post"  enctype="multipart/form-data" action="?act=<?php echo $cpage ?><?php !empty($id) && print('&id=' . $id) ?>">
                            <div class="list atable">
                                <?php if ($style <= 0): ?>
                                    <ul class="list a20_80">
                                        <li>
                                            <?php echo $lang['classifyName']; ?>:
                                        </li>
                                        <li>
                                            <select style ="width:250px" name="classifyId" class="classifyId">
                                                <option value="0"><?php $lang['classifyMain'] ?></option>
                                                <?php
                                                $classify = new tfunction($conn);
                                                $data = $classify->classify();
                                                foreach ($data as $rs):
                                                    if (empty($rs['setting'])):
                                                        ?>
                                                        <option value="<?php echo $rs['id'] ?>"  <?php !empty($rs['disabled']) && print($rs['disabled']); ?>><?php echo $rs['className'] ?></option>
                                                        <?php
                                                    endif;
                                                endforeach
                                                ?>
                                            </select>
                                        </li>
                                    </ul>
                                <?php endif; ?>

                                <ul class="list a20_80">
                                    <li><?php echo $lang['sort']; ?></li>
                                    <li>
                                        <select name="sort" class="sort" data-sort='<?php echo $sort ?>'></select>
                                    </li>
                                </ul>


                                <ul class="list a20_80">
                                    <li><?php echo $lang['tag']; ?>:</li>
                                    <li><input style="width: 80%;" name="tag" value="<?php echo $tag; ?>"/></li>
                                </ul>

                                <ul class="list a20_80">
                                    <li><?php echo $lang['title']; ?>:</li>
                                    <li><input style="width: 80%;" name="title" value="<?php echo $title; ?>"/></li>
                                </ul>

                                <ul class="list a20_80">
                                    <li><?php echo $lang['subtitle']; ?>:</li>
                                    <li>
                                        <textarea style="width: 80%;height: 100px;resize: none;" name="subtitle"><?php echo $subtitle; ?></textarea>
                                    </li>   
                                </ul>
                                <ul class="list a20_80">
                                    <li><?php echo $lang['titlePhoto']; ?>:</li>
                                    <li>
                                        <input class="showfile" readonly style="width: 80%;" name="titlePhoto" value="<?php echo $titlePhoto; ?>">
                                        <input type="file" id="file_input" class="file" name="file"/>
                                    </li>
                                </ul>
                                <ul class="list a20_80">
                                    <li><?php echo $lang['keyWords']; ?> （SEO）:</li>
                                    <li><input style="width: 80%;" name="keywords" value="<?php echo $keywords; ?>"/></li>
                                </ul>
                                <ul class="list a20_80">
                                    <li><?php echo $lang['description']; ?>（SEO）:</li>
                                    <li><textarea style="width: 80%;height: 100px;resize: none;" name="description"><?php echo $description; ?></textarea></li>
                                </ul>


                                <ul class="list a20_80">
                                    <li><?php echo $lang['content']; ?>:</li>
                                    <li><textarea name="newText" style="width: 80%;height: 50px;resize: none;"><?php echo $newText; ?></textarea></li>   
                                </ul>

                                <ul class="list a20_80">
                                    <li>
                                        <?php echo $lang['check']; ?>
                                    </li>
                                    <li>
                                        <?php echo $lang['pass']; ?>:<input type="radio" <?php $checked == 999 && print('checked="checked"') ?>  name="checked" value="1"/>
                                        <?php echo $lang['wait']; ?>:<input type="radio" <?php ($checked == 1 || $checked == 0) && print('checked="checked"') ?>  name="checked" value="0"/>
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
                        <?php echo $lang['check']; ?>
                    </li>
                    <li class="l">
                        <select>
                            <option ><?php echo $lang['choose']; ?></option>
                            <option value="0"><?php echo $lang['notPass']; ?></option>
                            <option value="1"><?php echo $lang['wait']; ?></option>
                            <option value="999"><?php echo $lang['pass']; ?></option>
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
                    <span><?php echo $lang['time']; ?>：</span> <input class="times" name='timestart'/> - <input class="times" name='timeend'/>
                    </p>
                    <p>
                    <span><?php echo $lang['search']; ?>：</span>
                    <select name='queryselect'>
                        <option value="0"><?php echo $lang['choose']; ?></option>
                        <option value="1"><?php echo $lang['title']; ?></option>
                        <option value="2"><?php echo $lang['subtitle']; ?></option>
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
                                var mesgConfirmDeletion = '<?php echo $lang['mesgConfirmDeletion']; ?>';
                                $('.atable ul li').each(function (i, p) {
                                    if ($(p).attr('data-id'))
                                    {
                                        $(p).text(classifyJson['classify_' + $(p).attr('data-id')]);
                                    }
                                });



                                $('#delete').on('click', function () {
                                    dialog({
                                        backdropBackground: '',
                                        title: '<?php echo $lang['prompt']; ?>',
                                        content: '<?php echo $lang['mesgIsRemoveSomeContent']; ?>',
                                        okValue: '<?php echo $lang['ok']; ?>',
                                        width: '500px',
                                        ok: function () {
                                            this.title('<?php echo $lang['mesgDeletion']; ?>');
                                            this.content("<?php echo $lang['mesgRemovePleaseWait']; ?>");
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
                                        cancelValue: '<?php echo $lang['cancel']; ?>',
                                        cancel: function () {}
                                    }).showModal();
                                });

                                //推送文章到专题文章下的结合中 code start
                                $('.toolbar').on('click', '#push', function () {
                                    var dataj = [];
                                    var datajC = {};
                                    $('.list input[name=checkid]').each(function (i, p) {
                                        if ($(p).is(':checked')) {
                                            dataj.push($(p).val());

<?php if (($newsStyle - 1) <= 0): ?>
                                                datajC[ $(p).val()] = $(this).parent().parent().find('li:eq(4)').text();
<?php else: ?>
                                                datajC[ $(p).val()] = $(this).parent().parent().find('li:eq(3)').text();
<?php endif ?>

                                        }
                                    });

                                    if (dataj.length > 0) {
                                        var data = {'newssubjectDataId[]': dataj, 'newssubjectDataContent': datajC};
                                        $.post('?act=5&newssubjectClassId=<?php echo $newssubjectClassId ?>', data, function (data) {
                                            alert(data[0] === true ? '推送成功' : '信息已存在');
                                            window.location.href = data[1]
                                        }, 'JSON');
                                    }
                                });
                                //推送文章到专题文章下的结合中 code end

                                //指定文章输出栏目 [栏目管理] code start
                                $('#column').on('click', function () {
                                    dialog({
                                        backdropBackground: '',
                                        title: '<?php echo $lang['prompt']; ?>',
                                        content: $('.column').html(),
                                        okValue: '<?php echo $lang['ok']; ?>',
                                        width: '700px',
                                        ok: function () {
                                            var query = $('.searchContent').eq(1).find('#query').serialize();
                                            self.location = ('?query=yes&' + query);
                                            return false;
                                        },
                                        cancelValue: '<?php echo $lang['cancel']; ?>',
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
                                        title: '<?php echo $lang['prompt']; ?>',
                                        content: $('.search').html(),
                                        okValue: '<?php echo $lang['ok']; ?>',
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
                                        cancelValue: '<?php echo $lang['cancel']; ?>',
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
                                        title: '<?php echo $lang['prompt']; ?>',
                                        content: $('.audit').html(),
                                        okValue: '<?php echo $lang['ok']; ?>',
                                        width: '300px',
                                        height: '80px',
                                        ok: function () {
                                            var tt = $('.auditContent').eq(1).find('select').val();
                                            if (tt !== '<?php echo $lang['choose']; ?>') {
                                                this.title('<?php echo $lang['mesgSubmitting']; ?>');
                                                this.title('<?php echo $lang['mesgSubmittPleaseWait']; ?>');
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
                                        cancelValue: '<?php echo $lang['cancel']; ?>',
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



                                //定位文章 栏目 select selected 挑选出来的选项位置 code start
                                $('.classifyId option').each(function (i, v) {
                                    t = (parseInt($(v).val()) === parseInt(<?php echo $classifyId ?>));
                                    $(v).attr('selected', t);
                                });
                                //定位文章 栏目 select selected 挑选出来的选项位置 code end


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
                                            alert('<?php echo $lang['mesgPhotoNotEmpty']; ?>');
                                            return;
                                        },
                                        sizeerror: function () {
                                            alert('<?php echo $lang['mesgPhotoNotMax']; ?>');
                                            return;
                                        }
                                    });
                                });
                                // 图片选择 code end
    </script>
    <script src="../js/qmancms.js" type="text/javascript"></script>
</body>

</html>
