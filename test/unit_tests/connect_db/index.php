<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class Test_connect_db extends UnitTestCase{
	function setUp(){
	    print "test connect db\n";
	}	
	function test_connect(){
	    print "text connect db\n";
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		$this->assertNotEqual($db, null);
		$this->assertEqual(get_class($db), "Database\Object");

		$this->assertNotEqual(Db::$sql, null);
		$this->assertEqual(get_class(Db::$sql), "Database\SqlObject");

		$this->assertNotEqual(Db::$locator, null);
		$this->assertEqual(get_class(Db::$locator), "Database\Locator");
	}
}
?>