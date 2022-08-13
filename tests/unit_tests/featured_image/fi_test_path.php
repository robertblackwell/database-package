<?php
namespace Unittests\FeaturedImage;

use Database\DbObject as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\HED\Skeleton;
use Unittests\LocalTestcase;
use Database\Models\FeaturedImage;

// phpcs:disable

/**
* test FeaturedImage::fromPathAndText() on 4 cases.
*
*/
class FromPathAndTextTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
	}
	/**
	* featured_image = "[2]"
	*/
	function testDefaultGallery()
	{
		$p = dirname(__FILE__)."/data/featured_image_entry_1/content.php";
		$itemDir = dirname(__FILE__)."/data/featured_image_entry_1/";
		$correct = dirname(__FILE__)."/data/featured_image_entry_1/Thumbnails/pict-3.jpg";
		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$fi1 = FeaturedImage::fromPathAndText($itemDir, "[2]");
		$this->assertEqual($fi1, $correct);
	}
	/**
	* featured_image = "[picts, 2]"
	*/
	function testSpecificGallery()
	{
		$p = dirname(__FILE__)."/data/featured_image_entry_2/content.php";
		$itemDir = dirname(__FILE__)."/data/featured_image_entry_2/";
		$correct = dirname(__FILE__)."/data/featured_image_entry_2/picts/Thumbnails/pict-3.jpg";
		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$fi1 = FeaturedImage::fromPathAndText($itemDir, "[picts, 2]");
		$this->assertEqual($fi1, $correct);
	}
	/**
	* featured_image = ""
	*/
	function testBlank()
	{
		$p = dirname(__FILE__)."/data/featured_image_entry_3/content.php";
		$itemDir = dirname(__FILE__)."/data/featured_image_entry_3/";
		$correct = dirname(__FILE__)."/data/featured_image_entry_3/Thumbnails/pict-1.jpg";
		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$fi1 = FeaturedImage::fromPathAndText($itemDir, "");
		$this->assertEqual($fi1, $correct);
	}
	/**
	* featured_image = "picts/Thumbnails/pict-5.jpg"
	*/
	function testPartialUrl()
	{
		$p = dirname(__FILE__)."/data/featured_image_entry_4/content.php";
		$itemDir = dirname(__FILE__)."/data/featured_image_entry_4/";
		$correct = dirname(__FILE__)."/data/featured_image_entry_4/picts/Thumbnails/pict-5.jpg";
		$nobj = new HEDObject();
		$nobj->get_from_file($p);
		$fi1 = FeaturedImage::fromPathAndText($itemDir, "picts/Thumbnails/pict-5.jpg");
		$this->assertEqual($fi1, $correct);
	}

}
