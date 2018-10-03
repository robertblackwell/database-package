<?php
namespace Unittests\NextPrev;

use Database\Object as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;
use \Trace;

// phpcs:disable 

class CountryTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testBothExist()
	{
		Trace::function_entry();
		$result = Item::get_by_slug('130413');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130414");
		$this->assertEqual($prev->slug, "130412");
		Trace::function_exit();
	}
	function testBothNotSequential()
	{    //other entries between
		Trace::function_entry();
		$result = Item::get_by_slug('130417');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130418");
		$this->assertEqual($prev->slug, "130416");
		Trace::function_exit();
	}
	function testNoPrev()
	{
		Trace::function_entry();
		$result = Item::get_by_slug('130407');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($prev, null);
		$this->assertEqual($next->slug, "130408");
		Trace::function_exit();
	}
	function testNoNext(){
		Trace::function_entry();
		$result = Item::get_by_slug('130716');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($prev->slug, "130713");
		$this->assertEqual($next, null);
		Trace::function_exit();
	}
}
