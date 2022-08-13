<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Database\Models\Item;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class ItemFindTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		DbPreloader::load();
		$db = Db::get_instance();
	}
	function testCampingForTripCountry()
	{
		$result = Item::find_camping_for_trip_country('rtw', 'Russia');
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		foreach ($result as $i) {
			$f = Item::get_by_trip_slug($i->trip, $i->slug);
			$this->assertTrue($f->has_camping);
		}
	}
	function testFindAll()
	{
		$result = Item::find();
		foreach($result as $r) {
			$klass = get_class($r);
			// print "\n{$klass} {$r->type}  {$r->slug} fi: [{$r->featured_image}]\n";
			$this->assertEqual($klass, "Database\Models\Item");
		}
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		//var_dump($result);
	}
	function testFindThree()
	{
		$result = Item::find(3);
		foreach($result as $r) {
			$klass = get_class($r);
			// print "\n{$klass} {$r->type}  {$r->slug}\n";
			$this->assertEqual($klass, "Database\Models\Item");
			}
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertEqual(count($result), 3);
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		//var_dump($result);
	}
	function testFindLatest()
	{
		$result = Item::find_latest();
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		//var_dump($result);
	}
	function testFindForTrip()
	{
		$trip='rtw';
		$result = Item::find_for_trip($trip);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, $trip);
		}
		//var_dump($result);
	}
	function testFindForCountry()
	{
		$result = Item::find_for_country("Russia");
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\Item");
		$this->assertEqual($result[0]->country, "Russia");
		//var_dump($result);
	}
}
