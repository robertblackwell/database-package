<?php
use HedTest\Tools;

use Database\DbObject as Db;
use Database\Locator;
use Database\Models\Item;
use Database\Models\Banner;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\NoSqlTestcase;

class HEDBannerTest extends NoSqlTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_banner";
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		\HedTest\Tools\ensureDoesNotExistsDir($locator->banner_dir($this->trip, $this->slug));
	}

	/**
	* This test stores HEDObjects into and reads them from a file. The locations of that
	* file is NOT dediced from Locator. Beware.
	*/
	public function testBanner()
	{
		\Trace::function_entry();
		$locator = Locator::get_instance();
		$this->trip = "rtw";
		$this->slug = "hed_test_banner";
		$p = $locator->banner_filepath($this->trip, $this->slug);
		\HedTest\Tools\ensureDoesNotExistsDir($locator->banner_dir($this->trip, $this->slug));

		// system("rm -R ".dirname(__FILE__)."/data/test_banner");
		// $p = dirname(__FILE__)."/data/test_banner/content.php";
		// make a HED file and object
		$obj = Skeleton::create_banner(
			// $p,
			$this->trip,
			$this->slug,
			'adate'
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "banner");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "banner");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");

		// now lets make an Album from this hed
		$a = new Banner($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "banner");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");

		\Trace::function_exit();
	}
	/**
	* Need a real banner with images for this test
	*/
	public function testGetImages()
	{
		$this->trip = "rtw";
		$this->slug = "england";
		$hobj = new HEDObject();
		$locator = Locator::get_instance();
		$fn = $locator->banner_filepath($this->trip, $this->slug);
		$hobj->get_from_file($fn);
		$b = new Banner($hobj);
		// var_dump($b->getImages());s
		$this->assertTrue(is_array($b->getImages()));
		$this->assertEqual(count($b->getImages()), 8);
	}
}
