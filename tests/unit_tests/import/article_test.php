<?php
namespace Unittests\Import;

use Database\DbObject as Db;
use Database\Models\Item as Item;
use Unittests\LocalTestcase;
use \Trace;
use \DbPreloader;

// phpcs:disable

class ArticleTest extends LocalTestcase
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
	    $r = Item::get_by_slug('tires');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();
        $r = Item::get_by_slug('tires');
	    $this->assertEqual($r, null);
        $new_r = Item::get_by_trip_slug('rtw', 'tires');
        $new_r->sql_insert();    
	    $r = Item::get_by_slug('tires');
	    $this->assertNotEqual($r, null);
	}
}
?>