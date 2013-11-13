<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Item;


class TestNextPrevCategory extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('vehicle2');
        $next = $result->next(array("category"=>"vehicle"));
        $prev = $result->prev(array("category"=>"vehicle"));
        $this->assertEqual($next->slug, "vehicle3");
        $this->assertEqual($prev->slug, "vehicle1");
	    Trace::function_exit();
    }
    function test_prev_not_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('vehicle1');
        $next = $result->next(array("category"=>"vehicle"));
        $prev = $result->prev(array("category"=>"vehicle"));
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "vehicle2");
	    Trace::function_exit();
    }
    function test_next_not_exist(){   
	    Trace::function_entry();
        $result = Item::get_by_slug('electricalpart8');
        $next = $result->next(array("category"=>"vehicle"));
        $prev = $result->prev(array("category"=>"vehicle"));
        $this->assertEqual($prev->slug, "electricalpart7");
        $this->assertEqual($next, null);
	    Trace::function_exit();
    }
}


?>