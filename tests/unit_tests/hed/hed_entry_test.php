<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Post;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\NoSqlTestcase;

class HEDEntryTest extends NoSqlTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
	}
	function testEntry()
	{
		Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_entry");
		$p = dirname(__FILE__)."/data/test_entry/content.php";
		// make a HED file and object
		$obj = Skeleton::make_entry(
			$p,
			'atrip',
			'aslug',
			'adate',
			"aTitle",
			'someVehicle',
			'1234miles',
			'1212odometer',
			'12_day',
			'aplace',
			'somecountry',
			'12.3245',
			'-121.3456',
			'a_featured_image_string',
			'this is main content'
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "entry");
		$this->assertEqual($obj['trip'], "atrip");
		$this->assertEqual($obj['slug'], "aslug");
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");

		$this->assertEqual($obj['vehicle'], "someVehicle");
		$this->assertEqual($obj['miles'], "1234miles");
		$this->assertEqual($obj['odometer'], "1212odometer");
		$this->assertEqual($obj['day_number'], "12_day");
		$this->assertEqual($obj['place'], "aplace");
		$this->assertEqual($obj['country'], "somecountry");
		$this->assertEqual($obj['latitude'], "12.3245");
		$this->assertEqual($obj['longitude'], "-121.3456");


		$this->assertEqual($obj['featured_image'], "a_featured_image_string");
		$this->assertEqual($obj['main_content'], "this is main content");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "entry");
		$this->assertEqual($nobj['trip'], "atrip");
		$this->assertEqual($nobj['slug'], "aslug");
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");
		$this->assertEqual($nobj['featured_image'], "a_featured_image_string");
		$this->assertEqual($nobj['main_content'], "this is main content");

		// now lets make an Album from this hed
		$a = new Entry($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "entry");
		$this->assertEqual($a->trip, "atrip");
		$this->assertEqual($a->slug, "aslug");
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");
		$this->assertEqual($a->featured_image, "a_featured_image_string");
		$this->assertEqual($a->main_content, "this is main content");

		Trace::function_exit();
	}

}
