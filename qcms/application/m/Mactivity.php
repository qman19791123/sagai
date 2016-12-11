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

    public function queryActivity($da) {

        $keys = array_keys($da);
        $values = array_values($da);

        $sql = '';
        foreach ($keys as $k => $v) {
            $sql .=$v . '="' . $values[$k] . '" and ';
        }

        return $this->conn->query('select * from activity_content where ' . $sql . ' 1=1');
    }

}
