<?php

use Database\Object as Db;
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
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
	}
	function testEditorial()
	{
		\Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_editorial");
		$p = dirname(__FILE__)."/data/test_editorial/content.php";
		// make a HED file and object
		$obj = Skeleton::make_editorial(
			$p,
			'atrip',
			'aslug',
			'adate',
			"anImage"
		);
		$this->assertEqual($obj['version'],"2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "editorial");
		$this->assertEqual($obj['trip'], "atrip");
		$this->assertEqual($obj['slug'], "aslug");
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['image_name'], "anImage");
		$this->assertEqual($obj['main_content'], "enter main content here");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'],"2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "editorial");
		$this->assertEqual($nobj['trip'], "atrip");
		$this->assertEqual($nobj['slug'], "aslug");
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['image_name'], "anImage");
		$this->assertEqual($nobj['main_content'], "enter main content here");

		// now lets make an Album from this hed
		$a = new Editorial($nobj);
		$this->assertEqual($a->version,"2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "editorial");
		$this->assertEqual($a->trip, "atrip");
		$this->assertEqual($a->slug, "aslug");
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->image_name, "anImage");
		$this->assertEqual($a->main_content, "enter main content here");

		\Trace::function_exit();
	}

}
