<?php
namespace Unittests\Import;

use Database\DbObject as Db;
use Database\Models\Album as Album;
use Unittests\LocalTestcase;
use \Trace;
use \DbPreloader;

// phpcs:disable

class AlbumTest extends LocalTestcase
{
	function setUp(){
		global $config;
		Db::init($config);
		DbPreloader::load();
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
	}
	function testGetDeleteInsert()
	{
		$slug = "spain";
		$r = Album::get_by_slug($slug);
		assert(!is_null($r));
		$this->assertNotEqual($r, null);
		$trip = $r->trip;
		$r->sql_delete();
		$r = Album::get_by_slug($slug);
		$this->assertEqual($r, null);
		$new_r = Album::get_by_trip_slug($trip, $slug);
		$new_r->sql_insert();    
		$r = Album::get_by_slug($slug);
		$this->assertNotEqual($r, null);
	}
}
?>