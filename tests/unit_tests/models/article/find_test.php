<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable

class ArticleFindTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testFind()
	{
		Trace::function_entry();
		$result = Database\Models\Article::find();
		//var_dump($result);exit();
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\Article");
		//$this->assertEqual($result[3]->slug, "bolivia-1");
		Trace::function_exit();
	}
	function testFindForTrip()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = Database\Models\Article::find_for_trip($trip);
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\Article");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
		Trace::function_exit();
	}
}
