<?php

class Mactivity extends models {

    public function __construct() {
        parent::__construct();
    }

    public function activityContent($p) {

        $Rs = $this->conn->query('select activity.id as id, activityTitle, activityValue,activityKey,activityInput,activitystate from activity left join activity_config on  activity_config.activityId = activity.id   where id = "' . $p . '"  order by  activityKey ');
        return $Rs;
    }

    public function activityAdd($data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $this->conn->aud('insert into activity_content (`' . join('`,`', $keys) . '`)  values  ("' . join('","', $values) . '")');
        return true;
    }

    public function queryActivity($user, $id) {
        $Rs = $this->conn->query('select * from activity_content where p1="' . $id . '" and id="' . $id . '"');
        return empty($Rs);
    }

    public function queryActivitySheet11($user, $count) {
        $Rs = $this->conn->query('select * from sheet1 where p1="' . $user . '"');
        if (empty($Rs)) {
            return '用户不存在';
        } else {
            if ($Rs[0]['p2'] != $count) {
                return "增仓数不正确";
            } else {
                return TRUE;
            }
        }
    }

}
