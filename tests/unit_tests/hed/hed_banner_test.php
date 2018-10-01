<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Banner;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\NoSqlTestcase;

class HEDBannerTest extends NoSqlTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
	}
	function testBanner()
	{
		\Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_banner");
		$p = dirname(__FILE__)."/data/test_banner/content.php";
		// make a HED file and object
		$obj = Skeleton::make_banner(
			$p,
			'atrip',
			'aslug',
			'adate'
		);
		$this->assertEqual($obj['version'],"2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "banner");
		$this->assertEqual($obj['trip'], "atrip");
		$this->assertEqual($obj['slug'], "aslug");
		$this->assertEqual($obj['published_date'], "adate");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'],"2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "banner");
		$this->assertEqual($nobj['trip'], "atrip");
		$this->assertEqual($nobj['slug'], "aslug");
		$this->assertEqual($nobj['published_date'], "adate");

		// now lets make an Album from this hed
		$a = new Banner($nobj);
		$this->assertEqual($a->version,"2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "banner");
		$this->assertEqual($a->trip, "atrip");
		$this->assertEqual($a->slug, "aslug");
		$this->assertEqual($a->published_date, "adate");

		\Trace::function_exit();
	}

}
