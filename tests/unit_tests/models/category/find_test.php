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
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		//        var_dump($db);exit();
	}
	function testFind()
	{
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
	}
	function testFindForTrip()
	{
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
	}
	function testExists()
	{
		$result = TripCategory::exists('vehicle');
		// var_dump($result);
		$this->assertTrue($result);
	}
}
