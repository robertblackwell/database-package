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
        $result = Item::get_by_slug('130413');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($next->slug, "130415");
        $this->assertEqual($prev->slug, "130411");
    }
    function test_next_prev_exist_skip(){    //other entries between
        $result = Item::get_by_slug('130417');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($next->slug, "130713");
        $this->assertEqual($prev->slug, "130416");
    }
    function test_prev_not_exist(){  
        $result = Item::get_by_slug('130411');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        var_dump($next->slug);
        var_dump($prev);
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "130413");
    }
    function test_next_not_exist(){    
        $result = Item::get_by_slug('130716');
        $next = $result->next(array('country'=>"Russia"));
        $prev = $result->prev(array('country'=>"Russia"));
        $this->assertEqual($prev->slug, "130713");
        $this->assertEqual($next, null);
    }
}


?>