<?php

use \Database\Object as Db;
use \Database\Models\PostMonth as PostMonth;
use Unittests\LocalTestcase;

class PostMonthsPostMonthTest extends LocalTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	public function test_1()
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
	public function test_2()
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
