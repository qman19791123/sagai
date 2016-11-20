<?php 
class Cindex extends controllers {
//	var $news ;
//	var $newssubject ;
//	var $classify;
	public function __construct() {
		parent::__construct();
		# 请安需要装载框架,加载框架越多将会越慢
		$this->news=$this->news;
//		$this->newssubject=$this->newssubject();
		$this->classify=$this->classify;
	}
	public function index() {
		$data=['title'=>'hello world','content'=>'This is the system information'];
		$this->Cout($data);
	}
}

