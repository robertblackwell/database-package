<?php

use Database\Object as Db;

class Test_connect_db extends \LiteTest\TestCase{
	function setUp(){
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = Database\SqlObject::get_instance();
	}	
	function test_connect(){
	    Trace::function_entry();
		$db = $this->db;
		$this->assertFalse($db == null);
		$this->assertEqual(get_class($db), "Database\Object");

		$this->assertFalse(Db::$sql == null);
		$this->assertEqual(get_class(Db::$sql), "Database\SqlObject");

		$this->assertFalse(Db::$locator == null);
		$this->assertEqual(get_class(Db::$locator), "Database\Locator");
	    Trace::function_exit();
	}
}
?>