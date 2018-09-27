<?php

use Database\Object as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;

class NextPrevCountryTest extends LocalTestcase
{
	function setUp(){
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function test_next_prev_exist(){
		Trace::function_entry();
		$result = Item::get_by_slug('130413');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130414");
		$this->assertEqual($prev->slug, "130412");
		Trace::function_exit();
	}
	function test_next_prev_exist_skip(){    //other entries between
		Trace::function_entry();
		$result = Item::get_by_slug('130417');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130418");
		$this->assertEqual($prev->slug, "130416");
		Trace::function_exit();
	}
	function test_prev_not_exist(){
		Trace::function_entry();
		$result = Item::get_by_slug('130407');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($prev, null);
		$this->assertEqual($next->slug, "130408");
		Trace::function_exit();
	}
	function test_next_not_exist(){
		Trace::function_entry();
		$result = Item::get_by_slug('130716');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($prev->slug, "130713");
		$this->assertEqual($next, null);
		Trace::function_exit();
	}
}


?>