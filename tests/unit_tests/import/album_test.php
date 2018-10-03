<?php
namespace Unittests\Import;

use Database\Object as Db;
use Database\Models\Album as Album;
use Unittests\LocalTestcase;
use \Trace;
use \DbPreloader;

// phpcs:disable

class AlbumTest extends LocalTestcase
{
	function setUp(){
		Trace::disable();
		global $config;
		Db::init($config);
		DbPreloader::load();
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
	}
	function testGetDeleteInsert()
	{
		Trace::function_entry();
		$r = Album::get_by_slug('scotland');
		$this->assertNotEqual($r, null);
		$r->sql_delete();
		$r = Album::get_by_slug('scotland');
		$this->assertEqual($r, null);
		$new_r = Album::get_by_trip_slug('rtw', 'scotland');
		$new_r->sql_insert();    
		$r = Album::get_by_slug('scotland');
		$this->assertNotEqual($r, null);
		Trace::function_exit();
	}
}
?>