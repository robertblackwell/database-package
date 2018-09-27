<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Unittests\LocalTestcase;

class Test_hed_location extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
	}
	// Load a location type HED from a file
	function test_1()
	{
		Trace::function_entry();
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/test_location/content.php");
		$this->assert_true($o->slug === "slug");
		$this->assert_true($o->type === "location");
		$this->assert_true($o->place === "A_Place");
		$this->assert_true($o->country === "A_Country");
		$this->assert_true($o->content_ref === "ref");
		$this->assert_true($o->latitude === "12.23456");
		$this->assert_true($o->longitude === "-125.0900");
		Trace::function_exit();
		// var_dump($o->slug);
		// var_dump($o->type);
		// var_dump($o->place);
		// var_dump($o->country);
		// var_dump($o->latitude);
		// var_dump($o->longitude);
		// var_dump($o->content_ref);
		// var_dump($o);
		
		return;
	}
	// create a location type HED in raw file format, write it and relaod it. Tests the HEDFactory
	function test_2()
	{
		Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_location/out");
		$p = dirname(__FILE__)."/data/test_location/out/_content.php";
		$parms = [
			"miles" => "1234",
			"odometer" => "987654",
			"day_number" => "29",
			"latitude" => "32.12345",
			"longitude" => "-45.67890",
			"place" => "APLace",
			"country" => "ACountry",
			"content_ref" => "REF"
		];
		HEDFactory::create_location($p, 'trip_2', 'slug_2', 'adate', $parms);
	
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/test_location/out/_content.php");
		$this->assert_true($o->slug === "slug_2");
		$this->assert_true($o->type === "location");

		$this->assert_true($o->miles === "1234");
		$this->assert_true($o->odometer === "987654");
		$this->assert_true($o->day_number === "29");

		$this->assert_true($o->place === $parms['place']);
		$this->assert_true($o->country === $parms["country"]);
		$this->assert_true($o->content_ref === "REF");
		$this->assert_true($o->latitude === $parms['latitude']);
		$this->assert_true($o->longitude === $parms['longitude']);
		// var_dump($o);
		// var_dump($o->slug);
		// var_dump($o->type);
		// var_dump($o->version);
		// var_dump($o->status);

		// var_dump($o->miles);
		// var_dump($o->odometer);
		// var_dump($o->day_number);

		// var_dump($o->place);
		// var_dump($o->country);
		// var_dump($o->latitude);
		// var_dump($o->longitude);
		// var_dump($o->content_ref);
		Trace::function_exit();
	}
	
	function test_3()
	{
		$parms = [
			"latitude" => "32.12345",
			"longitude" => "-45.67890",
			"place" => "APLace",
			"country" => "ACountry",
			"content_ref" => "REF",
			'miles' => "12345",
			'odometer' => "2030405",
			"day_number" => "29",
		];
		Trace::function_entry();
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/test_location/content_2.php");
		
		$le = Database\Models\Factory::model_from_hed($o);
		$this->assertNotEqual($le, null);
		$this->assertEqual(get_class($le), "Database\Models\EntryLocation");
		
		$this->assert_true($le->slug === "slug_2");
		$this->assert_true($le->type === "location");

		$this->assert_true($le->miles === $parms['miles']);
		$this->assert_true($le->odometer === $parms['odometer']);
		$this->assert_true($le->day_number === $parms['day_number']);
		$this->assert_true($le->has_camping);

		$this->assert_true($le->place === $parms['place']);
		$this->assert_true($le->country === $parms["country"]);
		$this->assert_true($le->content_ref === "REF");
		$this->assert_true($le->latitude === $parms['latitude']);
		$this->assert_true($le->longitude === $parms['longitude']);
		Trace::function_exit();
		// var_dump($le->slug);
		// var_dump($le->type);
		// var_dump($le->miles);
		// var_dump($le->odometer);
		// var_dump($le->day_number);
		// var_dump($le->camping);
		// var_dump($le->has_camping);
		// var_dump($le->place);
		// var_dump($le->country);
		// var_dump($le->latitude);
		// var_dump($le->longitude);
		// var_dump($le->content_ref);
		// var_dump($le);
		return;
	}
	// test that we can put a location in the database and get it back
	// To do this we need to have a clean state of the database before each test
	function test4()
	{
		Trace::function_entry();
		// $loc_2 = \Database\Models\Item::get_by_slug("slug_2");
		// var_dump($loc_2);
		//         $o = new HEDObject();
		//         $o->get_from_file(dirname(__FILE__)."/data/test_location/content_2.php");
		//
		// $le = Database\Models\Factory::model_from_hed($o);
		// $le->sql_insert();
		//
		//
		// $loc_2 = \Database\Models\Item::get_by_slug("slug_2");
		// var_dump($loc_2);
		//
		// $locs = \Database\Models\EntryLocation::find();
		// var_dump($locs[0]->slug);
		// foreach($locs as $loc){
		// 	print "Slug: ".($loc->slug);
		// 	print " Type: ".($loc->type). "\n";
		// }
		Trace::function_exit();
	}
}
