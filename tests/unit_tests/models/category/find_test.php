<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Database\Models\TripCategory;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class CategoryRepeatTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		//        var_dump($db);exit();
	}
	function testFind()
	{
		Trace::function_entry();
		$result = TripCategory::find();
		$cats = array();
		foreach ($result as $c) {
			$cats[] = $c->category;
		}
		// var_dump($cats);
		$this->assertFalse($result === null);
		$this->assertTrue(is_array($result));
		$this->assertFalse(count($result) === 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\TripCategory");
		Trace::function_exit();
	}
	function testFindForTrip()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = TripCategory::find_for_trip($trip);
		$cats = array();
		foreach ($result as $c) {
			$cats[] = $c->category;
			$this->assertEqual($c->trip, $trip);
		}
		//        var_dump($cats);
		$this->assertFalse($result === null);
		$this->assertTrue(is_array($result));
		$this->assertFalse(count($result) === 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\TripCategory");
		Trace::function_exit();
	}
	function testExists()
	{
		Trace::function_entry();
		$result = TripCategory::exists('vehicle');
		// var_dump($result);
		$this->assertTrue($result);
		Trace::function_exit();
	}
}
