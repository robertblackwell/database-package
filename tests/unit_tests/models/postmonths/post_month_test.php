<?php
namespace Unittests\Model;

use \Database as Database;
use Database\Object as Db;
use Database\Models\PostMonth;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable

class PostMonthTest extends LocalTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	public function testFind()
	{
		Trace::function_entry();
		$result = PostMonth::find();
		//        print_r($result);
		$this->assertNotEqual($result, null);
		$this->assertNotEqual(count($result), 0);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\PostMonth");
		Trace::function_exit();
	}
	public function testFindForTrip()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = PostMonth::find_for_trip($trip);
		$this->assertNotEqual($result, null);
		$this->assertNotEqual(count($result), 0);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\PostMonth");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
		Trace::function_exit();
	}
}
