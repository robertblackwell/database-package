<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class Test_connect_db extends UnitTestCase{
	function setUp(){
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = Database\SqlObject::get_instance();
	}	
	function test_connect(){
	    print __METHOD__."\n";
		$db = $this->db;
		$this->assertNotEqual($db, null);
		$this->assertEqual(get_class($db), "Database\Object");

		$this->assertNotEqual(Db::$sql, null);
		$this->assertEqual(get_class(Db::$sql), "Database\SqlObject");

		$this->assertNotEqual(Db::$locator, null);
		$this->assertEqual(get_class(Db::$locator), "Database\Locator");
	}
}
?>