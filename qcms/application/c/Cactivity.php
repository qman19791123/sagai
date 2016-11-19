<?php

class Cactivity extends controllers {

    var $news;
    var $newssubject;
    var $classify;
    private $conn = '';
    private $activity;

    public function __construct() {
        parent::__construct();
        $this->conn = new Mactivity();
        $this->activity = filter_input(INPUT_GET, 'activity', FILTER_VALIDATE_INT);
    }

    public function index($p) {
        if (!empty($p)) {
            $activity = 'activity_' . $p;
            $this->$activity($activity, $p);
        }
    }

    public function content() {
        exit("aaaaa");
    }

    public function lists() {
        exit("aaaaa");
    }

    private function activity_1($value, $p) {

        $data = ['title' => 'hello world', 'content' => 'This is the system information'];
        $data['data'] = $this->conn->activityContent($p);

        switch ($this->activity) {
            case 1:
                $this->add($data['data']);
                break;
        }

        $this->Cout($data);
    }

    private function add($data) {
        $da['id'] = $data[0]['id'];
        foreach ($data as $val) {
            $da[$val['activityKey']] = filter_input(INPUT_POST, $val['activityKey'], FILTER_CALLBACK, ['options' => 'tfunction::encode']);
        }

        if ($this->conn->queryActivity($da['id'], $da['p1'])) {
            die($this->conn->message('信息已录入'));
        }
        $Rs = $this->conn->queryActivitySheet11($da['p1'], $da['p4']);
        if ($Rs === TRUE) {
            $val = $this->conn->activityAdd($da);
            ($val) && die($this->conn->message("发布成功"));
        } else {
            die($this->conn->message($Rs));
        }
    }

}
