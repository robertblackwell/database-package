<?php
namespace Unittests\Db;

// require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\DbObject as Db;
use database\SqlObject;
use Unittests\LocalTestcase;
use Trace;

// phpcs:disable 

class DbSelectTest extends LocalTestcase
{
	function setUp()
	{
		//print "test connect db\n";
		\Trace::disable();
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = SqlObject::get_instance();
	}	
	function testSelect()
	{
		Trace::function_entry();
		$r = $this->sql->select('my_items', "where slug='130417'");
		//var_dump($r);
		if( is_object($r) )
			$this->assertEqual("mysqli_result", get_class($r));      
		else
			$this->assertEqual("mysql result", get_resource_type($r));
		Trace::function_exit();
	}
	function testSelectObjects()
	{
		Trace::function_entry();
		$r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='130417'", false);
		//var_dump($r);
		$this->assertEqual("Database\Models\Item", get_class($r));
		Trace::function_exit();
	}
	function testSelectArrayOfObjects()
	{
		Trace::function_entry();
		$r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='130417'", true);
		//var_dump($r);
		$this->assertEqual("Database\Models\Item", get_class($r[0]));
		Trace::function_exit();
	}
	function testSelectObjectsNone_Flse()
	{
		Trace::function_entry();
		$r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='110417'", false);
		//var_dump($r);
		$this->assertTrue(is_null($r));
		Trace::function_exit();
	}
	function testSelectObjectsNoneTrue()
	{
		Trace::function_entry();
		$r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='110417'", true);
		//var_dump($r);
		$this->assertTrue(is_array($r));
		$this->assertTrue((count($r) == 0));
		Trace::function_exit();
	}
}
?>