<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class ArticleTitleTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testFind()
	{
		$result = Database\Models\ArticleTitle::find();
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\ArticleTitle");
	}
	function testFindForTrip()
	{
		$trip='rtw';
		$result = Database\Models\ArticleTitle::find_for_trip($trip);
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\ArticleTitle");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
	}
}
