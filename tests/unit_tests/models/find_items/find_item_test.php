<?php

use Database\Object as Db;
use Database\Models\Item;
use Unittests\LocalTestcase;

class FindItemsFindItemTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testCampingForTripCountry()
	{
		//Trace::on();
		Trace::function_entry();
		$result = Item::find_camping_for_trip_country('rtw', 'Russia');
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		foreach ($result as $i) {
			$f = Item::get_by_trip_slug($i->trip, $i->slug);
			\Trace::debug("slug: ".$i->slug." has camping: ".(int)$f->has_camping);
			$this->assertTrue($f->has_camping);
		}
		Trace::function_exit();
	}
	function testFindThree()
	{
		Trace::function_entry();
		$result = Item::find(3);
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertEqual(count($result), 3);
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		Trace::function_exit();
		//var_dump($result);
	}
	function testFindLatest()
	{
		Trace::function_entry();
		$result = Item::find_latest();
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		Trace::function_exit();
		//var_dump($result);
	}
	function testFindForTrip()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = Item::find_for_trip($trip);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
		Trace::function_exit();
		//var_dump($result);
	}
	function testFindForCountry()
	{
		Trace::function_entry();
		$result = Item::find_for_country("Russia");
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		$this->assertEqual($result[0]->country, "Russia");
		Trace::function_exit();
		//var_dump($result);
	}
}
