<?php

use Database\Object as Db;
use \Database\Models\Item;


class TestNextPrevMonths extends LiteTest\TestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('130417');

        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($next->slug, "130427B");
        $this->assertEqual($prev->slug, "130416");
	    Trace::function_exit();
    }
    function test_prev_not_exist(){
	    Trace::function_entry();
        $result = Item::get_by_slug('130411');
        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "shipping");
	    Trace::function_exit();
    }
    function test_next_not_exist(){ 
	    Trace::function_entry();
        $result = Item::get_by_slug('130427B');
        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($prev->slug, "130417");
        $this->assertEqual($next, null);
	    Trace::function_exit();
    }
}


?>