<?php

use HedTest\Tools;

use Database\DbObject as Db;
use Database\Locator;
use Database\Models\Item;
use Database\Models\Editorial;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\LocalTestcase;
use Unittests\NoSqlTestcase;

class HEDEditorialTest extends NoSqlTestcase
{
	private $trip;
	private $slug;

	public function setUp()
	{
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_editorial";
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		\HedTest\Tools\ensureDoesNotExistsDir($locator->editorial_dir($this->trip, $this->slug));
	}

	public function testEditorial()
	{
		$locator = Locator::get_instance();
		$p = $locator->editorial_filepath($this->trip, $this->slug);
		\HedTest\Tools\ensureDoesNotExistsDir($locator->editorial_dir($this->trip, $this->slug));

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

		// make a HED file and object
		$obj = Skeleton::create_editorial(
			// $p,
			$this->trip,
			$this->slug,
			'adate',
			"anImage",
			$main_content
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "editorial");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['image_name'], "anImage");
		$this->assertEqual($obj['main_content'], $expected);

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "editorial");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['image_name'], "anImage");
		$this->assertEqual($nobj['main_content'], $expected);

		// now lets make an Album from this hed
		$a = new Editorial($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "editorial");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->image_name, "anImage");
		$this->assertEqual($a->main_content, $expected);

	}
	public function testImageUrl()
	{
		$this->trip = "rtw";
		$this->slug = "scotland";
		$hobj = new HEDObject();
		$locator = Locator::get_instance();
		$fn = $locator->editorial_filepath($this->trip, $this->slug);
		$hobj->get_from_file($fn);
		$editorial = new Editorial($hobj);

		// var_dump($editorial->image_name);
		// var_dump($editorial->image_url);
		$this->assertEqual($editorial->image_name, "scotland.jpg");
		$this->assertEqual($editorial->image_url, "/data/rtw/editorial/scotland/scotland.jpg");
	}
}
