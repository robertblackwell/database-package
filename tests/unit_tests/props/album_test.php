<?php
namespace Unittests\Props;

use Database\DbObject as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Database\Locator;
use Unittests\LocalTestcase;

// phpcs:disable

class AlbumTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$this->trip = "rtw";
		$this->slug = "hed_test_album";
		$locator = Locator::get_instance();
		$tmp = $locator->album_dir($this->trip, $this->slug);
		if (file_exists($tmp)) {
			$output = system("rm -R {$tmp}");
		}
	}
	public function tearDown()
	{
		$locator = Locator::get_instance();
		$tmp = $locator->album_dir($this->trip, $this->slug);
		if (file_exists($tmp)) {
			system("rm -R ".$locator->album_dir($this->trip, $this->slug));
		}
	}
	function testAlbum()
	{
		\Trace::function_entry();
		$locator = Locator::get_instance();

		$p = $locator->album_filepath($this->trip, $this->slug);

		// make a HED file and object
		$obj = Skeleton::create_album(
			$this->trip,
			$this->slug,
			'adate',
			"aTitle"
		);
		$this->assertEqual($obj['version'],"2.0.skel");
		$this->assertEqual($obj['status'], "draft");
		$this->assertEqual($obj['type'], "album");
		$this->assertEqual($obj['trip'], $this->trip);
		$this->assertEqual($obj['slug'], $this->slug);
		$this->assertEqual($obj['published_date'], "adate");
		$this->assertEqual($obj['title'], "aTitle");

		// now read it back and check we got the right thing

		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$this->assertEqual($nobj['version'],"2.0.skel");
		$this->assertEqual($nobj['status'], "draft");
		$this->assertEqual($nobj['type'], "album");
		$this->assertEqual($nobj['trip'], $this->trip);
		$this->assertEqual($nobj['slug'], $this->slug);
		$this->assertEqual($nobj['published_date'], "adate");
		$this->assertEqual($nobj['title'], "aTitle");

		// now lets make an Album from this hed
		$a = new Album($nobj);
		$this->assertEqual($a->version,"2.0.skel");
		$this->assertEqual($a->status, "draft");
		$this->assertEqual($a->type, "album");
		$this->assertEqual($a->trip, $this->trip);
		$this->assertEqual($a->slug, $this->slug);
		$this->assertEqual($a->published_date, "adate");
		$this->assertEqual($a->title, "aTitle");

		\Trace::function_exit();
	}
	public function testGetgallery()
	{
		$trip = "rtw";
		$slug = "england";
		$locator = Locator::get_instance();
		$fn = $locator->album_filepath($trip, $slug);
		// var_dump($fn);
		$hobj = new HEDObject();
		$hobj->get_from_file($fn);
		$album = new Album($hobj);
		// print_r($album);
	}

}
