<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Item;


class TestNextPrevCountry extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('130413');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($next->slug, "130415");
        $this->assertEqual($prev->slug, "130411");
	    Trace::function_exit();
    }
    function test_next_prev_exist_skip(){    //other entries between
	    Trace::function_entry();
        $result = Item::get_by_slug('130417');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($next->slug, "130709");
        $this->assertEqual($prev->slug, "130416");
	    Trace::function_exit();
    }
    function test_prev_not_exist(){  
	    Trace::function_entry();
        $result = Item::get_by_slug('130411');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "130413");
	    Trace::function_exit();
    }
    function test_next_not_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('130716');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($prev->slug, "130713");
        $this->assertEqual($next, null);
	    Trace::function_exit();
    }
}


?>