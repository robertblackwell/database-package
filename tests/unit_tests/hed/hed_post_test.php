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

class HEDPostTest extends NoSqlTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_post";
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		\HedTest\Tools\ensureDoesNotExistsDir($locator->item_dir($this->trip, $this->slug));
	}

	public function testPost()
	{
		$locator = Locator::get_instance();
		$p = $locator->item_filepath($this->trip, $this->slug);
		
		\HedTest\Tools\ensureDoesNotExistsDir($locator->item_dir($this->trip, $this->slug));

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
		$obj = Skeleton::make_post(
			$p,
			$this->trip,
			$this->slug,
			'adate',
			"aTitle",
			'cat1, cat2',
			'a_featured_image_string',
			$main_content
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "post");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");
		$this->assertEqual($obj['categories'], "cat1, cat2");
		$this->assertEqual($obj['featured_image'], "a_featured_image_string");
		$this->assertEqual($obj['main_content'], $expected);

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "post");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");
		$this->assertEqual($nobj['categories'], "cat1, cat2");
		$this->assertEqual($nobj['featured_image'], "a_featured_image_string");
		$this->assertEqual($nobj['main_content'], $expected);

		// now lets make an Album from this hed
		$a = new Post($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "post");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");
		$this->assertEqual($a->categories, ["cat1", "cat2"]);
		$this->assertEqual($a->featured_image, "a_featured_image_string");
		$this->assertEqual($a->main_content, $expected);

	}
}
