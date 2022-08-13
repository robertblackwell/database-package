<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Database\Models\CategorizedItem;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class CategorizedItemsTest extends LocalTestcase
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
		$result = CategorizedItem::find();
		// print_r($result);
		$this->assertTrue($result !== null);
		$this->assertTrue(is_array($result));
		$this->assertTrue(count($result) !== 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\CategorizedItem");
	}
	function testRelationshipExists()
	{
		$result = CategorizedItem::exists('vehicle', "electricalpart1");
		$this->assertTrue($result);
	}
}
