<?php

class Cactivity extends controllers {

    var $news;
    var $newssubject;
    var $classify;
    private $conn = '';
    private $activity;
    private $activityCookie = '';
    private $isnot;

    public function __construct() {
        parent::__construct();
        $this->conn = new Mactivity();
        $this->activity = filter_input(INPUT_GET, 'activity', FILTER_VALIDATE_INT);
    }

    public function index($p='') {
        if (!empty($p)) {
            $this->activity($p);
        }
    }

    public function content() {
        exit("aaaaa");
    }

    public function lists() {
        exit("aaaaa");
    }

    private function activity($p) {
        $this->isnot = 'isnot' . $p;
        $this->activityCookie = filter_input(INPUT_COOKIE, $this->isnot, FILTER_VALIDATE_INT);

        $data = ['title' => 'hello world', 'content' => 'This is the system information'];

        if ($this->activityCookie >= 3) {
            $data['errMes'] = '信息不可被录入，请与管理员联系';
            $this->Cout($data);
        } else {
            $data['data'] = $this->conn->activityContent($p);

            switch ($this->activity) {
                case 1:
                    $this->add($data['data']);
                    break;
            }

            $this->cout($data);
        }
    }

    private function add($data) {

        if ($this->activityCookie >= 3) {
            $data['errMes'] = '信息不可被录入，请与管理员联系';
            $this->Cout($data);
        } else {

            $this->activityCookie+=1;
            setcookie($this->isnot, $this->activityCookie, time() + 24 * 60 * 60 * 10);

            $da['activityId'] = $data[0]['id'];

            foreach ($data as $val) {
                $da[$val['activityKey']] = filter_input(INPUT_POST, $val['activityKey'], FILTER_CALLBACK, ['options' => 'tfunction::encode']);
                
            }

            $queryActivity = $this->conn->queryActivity($da);


            if ($queryActivity) {
                empty($queryActivity) || die($this->conn->message('信息已录入'));
            }

            $val = $this->conn->activityAdd($da);
            setcookie($this->isnot, '', 0);
            ($val) && die($this->conn->message("发布成功"));
        }
    }

}
