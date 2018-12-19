<?php
namespace Unittests\NextPrev;

use Database\DbObject as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;
use Trace;

// phpcs:disable 

class CategoryTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		Trace::disable();
	}
	function testBothExist()
	{
		Trace::disable();
		Trace::function_entry();
		$result = Item::get_by_slug('vehicle2');
		$next = $result->next(array("category"=>"vehicle"));
		$prev = $result->prev(array("category"=>"vehicle"));
		$this->assertEqual($next->slug, "vehicle3");
		$this->assertEqual($prev->slug, "vehicle1");
		Trace::function_exit();
	}
	function testNoPrev(){
		Trace::disable();
		Trace::function_entry();
		$result = Item::get_by_slug('vehicle1');
		$next = $result->next(array("category"=>"vehicle"));
		$prev = $result->prev(array("category"=>"vehicle"));
		$this->assertEqual($prev, null);
		$this->assertEqual($next->slug, "vehicle2");
		Trace::function_exit();
	}
	function testNoNext(){
		Trace::disable();
		Trace::function_entry();
		$result = Item::get_by_slug('plumbing2');
		$next = $result->next(array("category"=>"vehicle"));
		$prev = $result->prev(array("category"=>"vehicle"));
		$this->assertEqual($prev->slug, "electricalpart9");
		$this->assertEqual($next, null);
		Trace::function_exit();
	}
}


?>