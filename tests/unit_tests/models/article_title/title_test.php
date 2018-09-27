<?php

use Database\Object as Db;
use Unittests\LocalTestcase;

class ArticleTitleTitleTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function test_1()
	{
		Trace::function_entry();
		$result = Database\Models\ArticleTitle::find();
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\ArticleTitle");
		Trace::function_exit();
	}
	function test_2()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = Database\Models\ArticleTitle::find_for_trip($trip);
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\ArticleTitle");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
		Trace::function_exit();
	}
}
