<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Database\Locator;
use Unittests\LocalTestcase;

class HEDAlbumTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
	}
	function testAlbum()
	{
		\Trace::function_entry();
		system("rm -R ".dirname(__FILE__)."/data/test_album");
		$p = dirname(__FILE__)."/data/test_album/content.php";
		// make a HED file and object
		$obj = Skeleton::make_album(
			$p,
			'atrip',
			'aslug',
			'adate',
			"aTitle"
		);
		$this->assertEqual($obj['version'],"2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "album");
		$this->assertEqual($obj['trip'], "atrip");
		$this->assertEqual($obj['slug'], "aslug");
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'],"2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "album");
		$this->assertEqual($nobj['trip'], "atrip");
		$this->assertEqual($nobj['slug'], "aslug");
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");

		// now lets make an Album from this hed
		$a = new Album($nobj);
		$this->assertEqual($a->version,"2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "album");
		$this->assertEqual($a->trip, "atrip");
		$this->assertEqual($a->slug, "aslug");
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");

		\Trace::function_exit();
	}
	public function testGetgallery()
	{
		$trip = "rtw";
		$slug = "scotland";
		$locator = Locator::get_instance();
		$fn = $locator->album_filepath($trip, $slug);
		$hobj = new HEDObject();
		$hobj->get_from_file($fn);
		$album = new Album($hobj);
		// print_r($album);
	}

}
