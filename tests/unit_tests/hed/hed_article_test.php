<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Article;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\LocalTestcase;

class HEDArticleTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
	}
	function testsArticle()
	{
		\Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_article");
		$p = dirname(__FILE__)."/data/test_article/content.php";
		// make a HED file and object
		$obj = Skeleton::make_article(
			$p,
			'atrip',
			'aslug',
			'adate',
			"aTitle",
			"this is an abstract"
		);
		$this->assertEqual($obj['version'], "2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "article");
		$this->assertEqual($obj['trip'], "atrip");
		$this->assertEqual($obj['slug'], "aslug");
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");
		$this->assertEqual($obj['abstract'], "this is an abstract");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'], "2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "article");
		$this->assertEqual($nobj['trip'], "atrip");
		$this->assertEqual($nobj['slug'], "aslug");
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");
		$this->assertEqual($nobj['abstract'], "this is an abstract");

		// now lets make an Album from this hed
		$a = new Article($nobj);
		$this->assertEqual($a->version, "2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "article");
		$this->assertEqual($a->trip, "atrip");
		$this->assertEqual($a->slug, "aslug");
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");
		$this->assertEqual($a->abstract, "this is an abstract");

		\Trace::function_exit();
	}

}
