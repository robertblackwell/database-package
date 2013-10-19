<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item as Item;
class test_import_entry extends UnittestCase{
	function setUp(){
        global $config;
		Db::init($config);
		$this->db = Db::get_instance();
        $this->locator = \Database\Locator::get_instance();
		try{
		    $r = Item::get_by_slug('120708');
		    if( is_null($r) ){
                $new_r = Item::get_by_trip_slug('rtw', '120708');
                $new_r->sql_insert();            
			}
		} catch(\Exception $e)
		{
		}
	}
	function test_deport_entry(){ 
	    $r = Item::get_by_slug('120708');
	    $this->assertNotEqual($r, null);
        $r->sql_delete();    
        $r = Item::get_by_slug('120708');
	    $this->assertEqual($r, null);
	}
	function test_entry(){
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