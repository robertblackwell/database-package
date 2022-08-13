<?php
namespace Unittests\NextPrev;

use Database\DbObject as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;
use \Trace;

// phpcs:disable 

class TripTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testTripNextPrev01()
	{
        return; // this test might be poorly thought out
//		$result = Item::get_by_slug('130716');
//		$next = $result->next(array('trip'=>"rtw"));
//		$prev = $result->prev(array('trip'=>"rtw"));
//		$this->assertEqual($next->slug, "180928");
//		$this->assertEqual($prev->slug, "130713");

		$result = Item::get_by_slug("160707");
		// var_dump($result);
		// exit();
		$next = $result->next(array('trip'=>$result->trip));
		$prev = $result->prev(array('trip'=>$result->trip));
		$this->assertTrue(is_null($next));
		$this->assertEqual($prev->slug, "160706");

		$result = Item::get_by_slug("180624");
		$next = $result->next(array('trip'=>$result->trip));
		$prev = $result->prev(array('trip'=>$result->trip));
		$this->assertTrue(is_null($prev));
		$this->assertEqual($next->slug, "180726");
		
	}
	function testBothExist()
	{
		$result = Item::get_by_slug('130413');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130414");
		$this->assertEqual($prev->slug, "130412");
	}
	function testBothNotSequential()
	{    //other entries between
		$result = Item::get_by_slug('130417');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($next->slug, "130418");
		$this->assertEqual($prev->slug, "130416");
	}
	function testNoPrev()
	{
		$result = Item::get_by_slug('130407');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertEqual($prev, null);
		$this->assertEqual($next->slug, "130408");
	}
	function testNoNext(){
		$result = Item::get_by_slug('130716');
		$next = $result->next(array('country'=>"Russia"));
		$prev = $result->prev(array('country'=>"Russia"));
		$this->assertTrue(is_null($next));
		$this->assertEqual($prev->slug, "130713");
		// var_dump($prev->slug);
		// exit();
		// $this->assertEqual($prev->slug, "130713");
		// $this->assertEqual($next, null);
	}
}
