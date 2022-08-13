<?php
namespace Unittests\Import;

use Database\DbObject as Db;
use Database\Models\Item as Item;
use Unittests\LocalTestcase;
use \Trace;
use \DbPreloader;

// phpcs:disable

class EntryTest extends LocalTestcase
{
	function setUp()
	{
        global $config;
		Db::init($config);
		DbPreloader::load();
		$this->db = Db::get_instance();
        $this->locator = \Database\Locator::get_instance();
	}
	function testGetDelete()
	{ 
	    $r = Item::get_by_slug('120708');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();    
        $r = Item::get_by_slug('120708');
	    $this->assertEqual($r, null);
	}
	function testGetDeleteInsert()
	{
	    $r = Item::get_by_slug('120708');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();
        $r = Item::get_by_slug('120708');
	    $this->assertEqual($r, null);
        $new_r = Item::get_by_trip_slug('rtw', '120708');
        $new_r->sql_insert();    
	    $r = Item::get_by_slug('120708');
	    $this->assertNotEqual($r, null);
	}
}
?>