<?php

use HedTest\Tools;

use Database\DbObject as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\Locator;
use Unittests\LocalTestcase;

class Test_Factory extends LocalTestcase
{
	public function setUp()
	{
		global $config;
		Db::init($config);
//        $this->db = new Db($config);
		//var_dump(Locator::get_instance()->doc_root());//exit();
	}
	public function test_default_gal()
	{
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/featured_image_entry_1/content.php");
		//var_dump($o);exit();
		$e = Database\Models\Factory::model_from_hed($o);
		$this->assertNotEqual($e, null);
		$this->assertEqual(get_class($e), "Database\Models\Entry");
	}
	public function test_explicit_gal()
	{
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/featured_image_entry_2/content.php");
		//var_dump($o);exit();
		$e = Database\Models\Factory::model_from_hed($o);
		$this->assertNotEqual($e, null);
		$this->assertEqual(get_class($e), "Database\Models\Entry");
	}
	public function test_default_everything()
	{
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/featured_image_entry_3/content.php");
		//var_dump($o);exit();
		$e = Database\Models\Factory::model_from_hed($o);
		$this->assertNotEqual($e, null);
		$this->assertEqual(get_class($e), "Database\Models\Entry");
	}
	public function test_partial_path()
	{
		$o = new HEDObject();
		$o->get_from_file(dirname(__FILE__)."/data/featured_image_entry_4/content.php");
		//var_dump($o);exit();
		$e = Database\Models\Factory::model_from_hed($o);
		$this->assertNotEqual($e, null);
		$this->assertEqual(get_class($e), "Database\Models\Entry");
	}
}
