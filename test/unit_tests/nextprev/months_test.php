<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Item;


class TestNextPrevMonths extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_next_prev_exist(){    
        $result = Item::get_by_slug('130417');

        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($next->slug, "130427B");
        $this->assertEqual($prev->slug, "130416");
    }
    function test_prev_not_exist(){
        $result = Item::get_by_slug('130411');
        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "shipping");
    }
    function test_next_not_exist(){ 
        $result = Item::get_by_slug('130427B');
        $next = $result->next(array("months"=>"2013-04"));
        $prev = $result->prev(array("months"=>"2013-04"));
        $this->assertEqual($prev->slug, "130417");
        $this->assertEqual($next, null);
    }
}


?>