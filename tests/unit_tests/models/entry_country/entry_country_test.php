<?php
namespace Unittests\Model;

use \Database as Database;
use Database\Object as Db;
use Database\Models\EntryCountry;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class EntryCountryTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		//        var_dump($db);exit();
	}
	function testFind()
	{
		Trace::function_entry();
		$result = EntryCountry::find();
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\EntryCountry");
		Trace::function_exit();
	}
	function testFindForTrip()
	{
		Trace::function_entry();
		$trip='rtw';
		$result = EntryCountry::find_for_trip($trip);
		$this->assertNotEqual($result, null);
		$this->assertTrue(is_array($result));
		$this->assertNotEqual(count($result), 0);
		$this->assertEqual(get_class($result[0]), "Database\Models\EntryCountry");
		foreach ($result as $i) {
			$this->assertEqual($trip, $i->trip);
		}
		Trace::function_exit();
	}
}
