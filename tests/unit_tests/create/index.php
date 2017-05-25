<?php
use Database\Object as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;

class test_create extends \LiteTest\TestCase{
	function setUp(){
		print "setup fired \n";
		global $config;
		$this->db = new Db($config);
		
		$fn1 = Locator::get_instance()->item_dir("rtw", "111111");
		$fn2 = Locator::get_instance()->item_dir("rtw", "111112");
		$fn3 = Locator::get_instance()->item_dir("rtw", "111113");
		system("rm -Rv $fn1");
		system("rm -Rv $fn2");
		system("rm -Rv $fn3");
	}
	function test_create_post()
	{
		Factory::create_post('rtw', '111112', array());
	}
}
?>