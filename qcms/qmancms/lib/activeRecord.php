<?php

include lib.'/ActiveRecord/DB_driver.php';
include lib.'/ActiveRecord/CI_DB_query_builder.php';
/**
 * 
 * @author qman
 *	对CI 的程序再次封装
 */
class activeRecord extends CI_DB_query_builder{
	public  function __construct(){
		parent::__construct();
	}
}
