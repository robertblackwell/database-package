<?php
use HedTest\Tools;

use Database\DbObject as Db;
use Database\Locator;
use Database\Models\Item;
use Database\Models\Article;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\LocalTestcase;

class HEDArticleTest extends LocalTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_article";
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		\HedTest\Tools\ensureDoesNotExistsDir($locator->item_dir($this->trip, $this->slug));
	}

	public function testsArticle()
	{
		$locator = Locator::get_instance();
		$p = $locator->item_filepath($this->trip, $this->slug);
		\HedTest\Tools\ensureDoesNotExistsDir($locator->item_dir($this->trip, $this->slug));

		// system("rm -R ".dirname(__FILE__)."/data/test_article");
		// $p = dirname(__FILE__)."/data/test_article/content.php";
		// make a HED file and object
		$obj = Skeleton::create_article(
			// $p,
			$this->trip,
			$this->slug,
			'adate',
			"aTitle",
			"this is an abstract"
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "article");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");
		$this->assertEqual($obj['abstract'], "this is an abstract");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "article");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");
		$this->assertEqual($nobj['abstract'], "this is an abstract");

		// now lets make an Album from this hed
		$a = new Article($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "article");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");
		$this->assertEqual($a->abstract, "this is an abstract");

	}
}
