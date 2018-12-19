<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;
use Database\Models\TripCategory;

// phpcs:disable


class CategoryTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		//        var_dump($db);exit();
	}
	function categoryFind($category)
	{
		Trace::function_entry();
		$result = TripCategory::find();
		//var_dump($result);
		$found=false;
		foreach ($result as $c) {
			if ($c->category == $category) {
				$found = true;
			}
		}
		return $found;
		Trace::function_exit();
	}
	function testAddRemove()
	{
		return;
		Trace::function_entry();
	
		$this->assertFalse($this->category_exists("my_category"));
		TripCategory::add('my_category');
		$this->assertTrue($this->category_exists("my_category"));
		TripCategory::remove('my_category');
		$this->assertFalse($this->category_exists("my_category"));
		// print __METHOD__."\n";
		Trace::function_exit();
	}
}
