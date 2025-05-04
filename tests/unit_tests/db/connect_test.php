<?php
namespace Unittests\Db;

use Database\DbObject as Db;
use Database\SqlObject;
use \Unittests\LocalTestcase;

// phpcs:disable

class ConnectTest extends LocalTestcase
{
	public $db;
	public $sql;
	function setUp()
	{
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = SqlObject::get_instance();
	}	
	function testConnectAndInit()
	{
		$db = $this->db;
		$this->assertFalse($db == null);
		$this->assertEqual(get_class($db), "Database\DbObject");

		$this->assertFalse(Db::$sql == null);
		$this->assertEqual(get_class(Db::$sql), "Database\SqlObject");

		$this->assertFalse(Db::$locator == null);
		$this->assertEqual(get_class(Db::$locator), "Database\Locator");
	}
}
?>