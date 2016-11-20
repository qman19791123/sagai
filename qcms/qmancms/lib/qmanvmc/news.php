<?php

/**
 * Description of news
 *
 * @author qman
 */
class news extends tfunction {

    public function __construct() {
        parent::__construct();
    }

    public function test() {
        return 'test';
    }

    /**
     * 新闻列表
     * @param type $classId
     */
    public function newList($classId = '') {
        return $this->newPage($classId);
    }

    /**
     * 新闻内容
     * @param type $id
     * @return type
     */
    public function newContent($id) {
        $Rs = '';
        if ($id == '') {
            $Rs = $this->conn
                    ->select(['id','classifyId', 'style', 'sort', 'tag', 'subtitle', 'title', 'titlePhoto', 'newText', 'keywords', 'description', 'checked'])
                    ->join('news_content', 'news_config.id = news_content.newsId', 'left')
                    ->where(['id' => $id])
                    ->get('news_config');
            return $Rs;
        }
        return $Rs;
    }

    /**
     * 新闻列表（迷你）
     * @param type $classId
     * @param type $pageCount
     */
    public function newMiniList($classId, $pageCount) {
        return $this->newPage($classId, FALSE, $pageCount);
    }

    /**
     * 新闻分页
     * @param type $classId
     * @param type $pageCount
     * @param type $pageSum
     * @param type $Page
     * @return type
     */
    public function newPage($classId, $isPage = FALSE, $pageSum = 10, $pageCount = 0, &$Page = '') {
        $rsConn = $this->conn;

        if ($classId != '') {
            $rsConn = $rsConn->where(['classifyId' => $classId]);
        }
        
        $Data = $rsConn->limit($pageSum)->offset($pageCount)->get('news_config');
        $isPage && $Page = $this->conn->select('count(id) as cid')->where(['classifyId' => $classId])->get('news_config');
        
        return $Data;
    }

}
