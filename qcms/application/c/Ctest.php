<?php 
class Ctest extends controllers {
	var $news ;
	var $newssubject ;
	var $classify;
	public function __construct() {
		parent::__construct();
		# Asked the loading frame, a loading frame will be more and more slowly
		$this->news=$this->news();
		$this->newssubject=$this->newssubject();
		$this->classify=$this->classify();
	}
	public function index() {
		$data=['title'=>'hello world','content'=>'This is the system information'];
		$this->Cout($data);
	}
}

