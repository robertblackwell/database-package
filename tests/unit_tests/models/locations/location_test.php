<?php
namespace Unittests\Locations;

use \Database as Database;
use Database\DbObject as Db;
use \Database\Models\EntryLocation;
use Unittests\LocalTestcase;
use Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable

class FindTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
	}
	function testDateOrder()
	{
		$result = EntryLocation::find_date_order();
		$this->assertNotEqual($result, null);
		$this->assertNotEqual(count($result), 0);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\EntryLocation");
		// print_r($result[0]->getStdClass());
		// note these are in increasing cronological order and the test db is only part of the full db
		$this->assertEqual($result[0]->trip, "er");
	}
	function testForTrip()
	{
		$result = EntryLocation::find_for_trip('rtw');
		$this->assertNotEqual($result, null);
		$this->assertNotEqual(count($result), 0);
		$this->assertTrue(is_array($result));
		$this->assertEqual(get_class($result[0]), "Database\Models\EntryLocation");
		$this->assertEqual($result[0]->trip, "rtw");
		foreach ($result as $i) {
			$this->assertEqual($i->trip, "rtw");
		}
		$this->assertEqual(count($result), 19);
	}
}
