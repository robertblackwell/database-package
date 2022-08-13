<?php
const LOC_SLUG = "location_2";

use Database\DbObject as Db;
use Database\Models\Item as Item;
use Unittests\LocalTestcase;

class test_import_location extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
		// pre condition location item with slug == location_2 must exist in the database
		try {
			$r = Item::get_by_slug(LOC_SLUG);
			if (is_null($r)) {
				$new_r = Item::get_by_trip_slug('rtw', LOC_SLUG);
				$new_r->sql_insert();
			}
		} catch (\Exception $e) {
			var_dump($e);
		}
	}
	// test deport the item
	function test_deport_import_location()
	{
		$r = Item::get_by_slug(LOC_SLUG);
		// the pre condition ensures this will not be null
		$this->assertNotEqual($r, null);
		// delete it from the data base
		$r->sql_delete();
		// load it from the database and get NULL to verify its gone
		$r = Item::get_by_slug(LOC_SLUG);
		$this->assertEqual($r, null);
		// // now put it back in database - load from file and then insert
		$new_r = Item::get_by_trip_slug('rtw', LOC_SLUG);
		$new_r->sql_insert();
		// finally test it is in database
		$r = Item::get_by_slug(LOC_SLUG);
		$this->assertNotEqual($r, null);
	}
}
