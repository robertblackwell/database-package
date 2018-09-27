<?php

use Database\Object as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;

class NextPrevNoneTest extends LocalTestcase
{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('110621');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($next->slug, "tires");
        $this->assertEqual($prev->slug, "110620");
	    Trace::function_exit();
    }
    function test_prev_not_exist(){    
	    Trace::function_entry();
        $result = Item::get_by_slug('bolivia-1');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "mog");
	    Trace::function_exit();
    }
    function test_next_not_exist()
    {
	    Trace::function_entry();
        $result = Item::get_by_slug('180727');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev->slug, "180726");
        $this->assertEqual($next, null);
	    Trace::function_exit();
    }
}


?>