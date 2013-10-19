<?php
require_once(dirname(dirname(__FILE__))."/vendor/Autoloader.php");
require_once(dirname(dirname(__FILE__))."/config.php");

$loader = new \Autoloader\loader(dirname(dirname(dirname(__FILE__)))."/src");

use Database\Object as Db;
class test_create extends UnittestCase{
	function setUp(){
		print "setup fired \n";
		global $config;
		$this->db = new Db($config);
		$fn1 = $this->db->locator->item_dir("rtw", "111111");
		$fn2 = $this->db->locator->item_dir("rtw", "111112");
		$fn3 = $this->db->locator->item_dir("rtw", "111113");
		system("rm -Rv $fn1");
		system("rm -Rv $fn2");
		system("rm -Rv $fn3");
	}
	function test_create_post(){
		$creator = new \Database\Utilities\Factory($this->db);
		$creator->create_journal_entry('rtw', '111111', array());
		$creator->create_post('rtw', '111112', array());
		$creator->create("\Database\VO\Article", 'rtw', '111113', array());
	}
}
?>