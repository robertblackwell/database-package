<?php

use HedTest\Tools;

use Database\DbObject as Db;
use Database\Locator;
use Database\Models\Item;
use Database\Models\Post;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\NoSqlTestcase;

class HEDEntryTest extends NoSqlTestcase
{
	private $trip;
	private $slug;

	public function setUp()
	{
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_entry";
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		system("rm -R ".$locator->item_dir($this->trip, $this->slug));
	}

	public function testEntry()
	{
		$locator = Locator::get_instance();
		$this->trip = "rtw";
		$this->slug = "hed_test_entry";
		$p = $locator->item_filepath($this->trip, $this->slug);

		\HedTest\Tools\ensureDoesNotExistsDir($locator->item_dir($this->trip, $this->slug));
		// make a HED file and object
		$para1=<<<EOD
<p>This is the first para. I have made it a couple of sentences
so that it is meaningful.</p>
EOD;
		$para2 =<<<EOD
<p>This is the second para. It is also big enough to be meaningful.
Blah blahblah blah blah blah blah blah blah blah blahblah
blah blah blah blah blah blah blah blah blah blahblah
blah blah blah blah blah blah blah.
</p>
EOD;
		$main_content = trim($para1). trim($para2);
		$expected = trim($main_content);
		$para1_expect = trim($para1);
		$obj = Skeleton::create_entry(
			// $p,
			$this->trip,
			$this->slug,
			'adate',
			"aTitle",
			'someVehicle',
			'1234miles',
			'1212odometer',
			'12_day',
			'aplace',
			'BC',
			'12.3245',
			'-121.3456',
			'a_featured_image_string',
			$main_content
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "entry");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");

		$this->assertEqual($obj['vehicle'], "someVehicle");
		$this->assertEqual($obj['miles'], "1234miles");
		$this->assertEqual($obj['odometer'], "1212odometer");
		$this->assertEqual($obj['day_number'], "12_day");
		$this->assertEqual($obj['place'], "aplace");
		$this->assertEqual($obj['country'], "BC");
		$this->assertEqual($obj['latitude'], "12.3245");
		$this->assertEqual($obj['longitude'], "-121.3456");


		$this->assertEqual($obj['featured_image'], "a_featured_image_string");
		$mc = $obj["main_content"];
		$this->assertEqual($obj['main_content'], $expected);

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "entry");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");

		$this->assertEqual($nobj['vehicle'], "someVehicle");
		$this->assertEqual($nobj['miles'], "1234miles");
		$this->assertEqual($nobj['odometer'], "1212odometer");
		$this->assertEqual($nobj['day_number'], "12_day");
		$this->assertEqual($nobj['place'], "aplace");
		$this->assertEqual($nobj['country'], "BC");
		$this->assertEqual($nobj['latitude'], "12.3245");
		$this->assertEqual($nobj['longitude'], "-121.3456");

		$this->assertEqual($nobj['featured_image'], "a_featured_image_string");
		$this->assertEqual($nobj['main_content'], $expected);

		// now lets make an Album from this hed
		$a = new Entry($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "entry");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");

		$this->assertEqual($a->vehicle, "someVehicle");
		$this->assertEqual($a->miles, "1234miles");
		$this->assertEqual($a->odometer, 1212);
		$this->assertEqual($a->day_number, 12);
		$this->assertEqual($a->place, "aplace");
		$country = \Database\Models\Country::get_by_code("BC"); // demonstrates fix country
		$this->assertEqual($a->country, $country);
		$this->assertEqual($a->latitude, "12.3245");
		$this->assertEqual($a->longitude, "-121.3456");


		$this->assertEqual($a->featured_image, "a_featured_image_string");
		$this->assertEqual($a->main_content, $expected);
		$this->assertEqual(trim($a->excerpt), $para1_expect);

	}
	public function testBorderCamping()
	{
		system("rm -R ".dirname(__FILE__)."/data/test_entry");
		$p = dirname(__FILE__)."/data/entry_1/content.php";
		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		/*
		<div id="camping">
		<p>Hi this is some camping information.</p>
		</div>
		<div id="border">
		<p>Hi this is some border information.</p>
		</div>
		*/
		$this->assertEqual($nobj['camping'], "<p>Hi this is some camping information.</p>");
		$this->assertEqual($nobj['border'], "<p>Hi this is some border information.</p>");
	}
}
