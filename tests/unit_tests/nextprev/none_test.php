<?php

use Database\Object as Db;
use \Database\Models\Item;


class TestNextPrevNoCreiteria extends LiteTest\TestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('120712');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($next->slug, "120714");
        $this->assertEqual($prev->slug, "120710");
	    Trace::function_exit();
    }
    function test_prev_not_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('tires');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "er-review");
	    Trace::function_exit();
    }
    function test_next_not_exist(){  return;  
	    Trace::function_entry();
        $result = Item::get_by_slug('130731');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev->slug, "130728");
        $this->assertEqual($next, null);
	    Trace::function_exit();
    }
}


?>