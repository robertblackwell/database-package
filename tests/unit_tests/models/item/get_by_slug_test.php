<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Database\Models\Item;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class ItemTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		DbPreloader::load();
		$db = Db::get_instance();
	}
	function testEntryGetByTripSlug()
	{
		\Trace::function_entry();
		$r = Item::get_by_trip_slug("rtw", "130417");
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Entry");
		$this->assertEqual($r->slug, "130417");
		Trace::function_exit();
	}
	function testEntryGetBySlug()
	{
		\Trace::function_entry();
		$r = Item::get_by_slug('130417');
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Entry");
		$this->assertEqual($r->slug, "130417");
		$this->assertTrue(is_string($r->version));
		$this->assertTrue(is_string($r->type));
		$this->assertTrue(is_string($r->trip));
		$this->assertTrue(is_string($r->slug));
		$this->assertTrue(is_string($r->status));
		$this->assertTrue(is_string($r->creation_date));
		$this->assertTrue(is_string($r->published_date));
		$this->assertTrue(is_string($r->last_modified_date));
		$this->assertTrue(is_string($r->odometer));
		$this->assertTrue(is_string($r->day_number));
		$this->assertTrue(is_string($r->miles));
		$this->assertTrue(is_string($r->place));
		$this->assertTrue(is_string($r->country));
		$this->assertTrue(is_string($r->latitude));
		$this->assertTrue(is_string($r->longitude));
		$this->assertTrue(is_string($r->featured_image));
		$this->assertTrue(is_string($r->excerpt));
		$this->assertTrue(is_string($r->title));
		$this->assertTrue(is_string($r->main_content));
		$this->assertTrue(is_bool($r->has_camping));
		$this->assertTrue(is_bool($r->has_border));
		//        print get_class($r->latitude)."\n";
		Trace::function_exit();
	}
	function testPostGetByTripSlug()
	{
		\Trace::function_entry();
		$r = Item::get_by_trip_slug('rtw', 'electricalpart1');
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Post");
		$this->assertEqual($r->slug, 'electricalpart1');
		Trace::function_exit();
	}
	function testPostGetBySlug()
	{
		\Trace::function_entry();
		$r = Item::get_by_slug('electricalpart1');
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Post");
		$this->assertEqual($r->slug, 'electricalpart1');
		Trace::function_exit();
	}
	function testArticeGetByTripSlug()
	{
		Trace::function_entry();
		$r = Item::get_by_trip_slug('rtw', 'tires');
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Article");
		$this->assertEqual($r->slug, 'tires');
		Trace::function_exit();
	}
	function testArticleGetBySlug()
	{
		Trace::function_entry();
		$r = Item::get_by_slug('tires');//var_dump($r);exit();
		$this->assertNotEqual($r, null);
		$this->assertEqual(get_class($r), "Database\Models\Article");
		$this->assertEqual($r->slug, 'tires');
		Trace::function_exit();
	}
}
