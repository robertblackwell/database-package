<?php
namespace Unittests\Db;

use Database\Object as Db;
use Database\SqlObject;
use \Unittests\LocalTestcase;

// phpcs:disable

class ConnectTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = SqlObject::get_instance();
	}	
	function testConnectAndInit()
	{
	    \Trace::function_entry();
		$db = $this->db;
		$this->assertFalse($db == null);
		$this->assertEqual(get_class($db), "Database\Object");

		$this->assertFalse(Db::$sql == null);
		$this->assertEqual(get_class(Db::$sql), "Database\SqlObject");

		$this->assertFalse(Db::$locator == null);
		$this->assertEqual(get_class(Db::$locator), "Database\Locator");
	    \Trace::function_exit();
	}
}
?>