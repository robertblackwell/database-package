<?php
namespace Unittests\Import;

use Database\DbObject as Db;
use Database\Models\Item as Item;
use Unittests\LocalTestcase;
use \Trace;
use \DbPreloader;

// phpcs:disable

class POstTest extends LocalTestcase
{
	function setUp()
	{
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
	    $r = Item::get_by_slug('electricalpart1');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();
        $r = Item::get_by_slug('electricalpart1');
	    $this->assertEqual($r, null);
        $new_r = Item::get_by_trip_slug('rtw', 'electricalpart1');
        $new_r->sql_insert();    
	    $r = Item::get_by_slug('electricalpart1');
	    $this->assertNotEqual($r, null);
	}
}
?>