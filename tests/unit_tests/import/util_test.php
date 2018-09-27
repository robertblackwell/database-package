<?php

use Database\Object as Db;
use Database\Models\Item as Item;
use Database\Models\Album as Album;
use Database\Utility as Utility;
use Trace as Trace;
use Unittests\LocalTestcase;

class ImportUtilsTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
        global $config;
		Db::init($config);
		$this->db = Db::get_instance();
        $this->locator = \Database\Locator::get_instance();
        $this->utility = new Utility();
		try{
		    $r = Item::get_by_slug('electricalpart1');
		    if( is_null($r) ){
                $new_r = Item::get_by_trip_slug('rtw', 'electricalpart1');
                $new_r->sql_insert();            
			}
		    $r = Item::get_by_slug('130417');
		    if( is_null($r) ){
                $new_r = Item::get_by_trip_slug('rtw', '130417');
                $new_r->sql_insert();            
			}
		    $r = Item::get_by_slug('tires');
		    if( is_null($r) ){
                $new_r = Item::get_by_trip_slug('rtw', 'tires');
                $new_r->sql_insert();            
			}
		    $r = Album::get_by_slug('peru');
		    if( is_null($r) ){
                $new_r = Album::get_by_trip_slug('rtw', 'peru');
                $new_r->sql_insert();            
			}
		} catch(\Exception $e) {
			throw $e;
		}
	}
	function test_album()
	{
	    Trace::function_entry();
	    $slug = "peru";
	    $r = Album::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	        
	    $this->utility->deport_album($slug);
	    
        $r = Album::get_by_slug($slug);
	    $this->assertEqual($r, null);

        $this->utility->import_album('rtw', $slug);
        
	    $r = Album::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	    Trace::function_exit();
	}
	function test_article(){
	    Trace::function_entry();
	    $slug = "tires";
	    $r = Item::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	        
	    $this->utility->deport_item($slug);
	    
        $r = Item::get_by_slug($slug);
	    $this->assertEqual($r, null);

        $this->utility->import_item('rtw', $slug);
        
	    $r = Item::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	    Trace::function_exit();
	}
	function test_entry(){
	    Trace::function_entry();
	    $slug = "130417";
	    $r = Item::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	        
	    $this->utility->deport_item($slug);
	    
        $r = Item::get_by_slug($slug);
	    $this->assertEqual($r, null);

        $this->utility->import_item('rtw', $slug);
        
	    $r = Item::get_by_slug($slug);
	    $this->assertNotEqual($r, null);
	    Trace::function_exit();
	}
	function test_post(){
	    Trace::function_entry();
	    $r = Item::get_by_slug('electricalpart1');
	    $this->assertNotEqual($r, null);
	        
	    $this->utility->deport_item('electricalpart1');
	    
        $r = Item::get_by_slug('electricalpart1');
	    $this->assertEqual($r, null);

        $this->utility->import_item('rtw', 'electricalpart1');
        
	    $r = Item::get_by_slug('electricalpart1');
	    $this->assertNotEqual($r, null);
	    Trace::function_exit();
	}
}
?>