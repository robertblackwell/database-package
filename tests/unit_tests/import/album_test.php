<?php

use Database\Object as Db;
use Database\Models\Album as Album;
class test_import_album extends LiteTest\TestCase{
	function setUp(){
		Trace::disable();
        global $config;
		Db::init($config);
		$this->db = Db::get_instance();
        $this->locator = \Database\Locator::get_instance();
	}
	function test_album()
	{
	    Trace::function_entry();
	    $r = Album::get_by_slug('peru');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();
        $r = Album::get_by_slug('peru');
	    $this->assertEqual($r, null);
        $new_r = Album::get_by_trip_slug('rtw', 'peru');
        $new_r->sql_insert();    
	    $r = Album::get_by_slug('peru');
	    $this->assertNotEqual($r, null);
	    Trace::function_exit();
	}
}
?>