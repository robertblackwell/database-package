<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use \Database\Object as Db;
use \Database\Models\PostMonth as PostMonth;

class TestPostMonth extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
	    Trace::function_entry();
        $result = PostMonth::find();
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\PostMonth");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
	    $trip='rtw';
        $result = PostMonth::find_for_trip($trip);
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\PostMonth");
        foreach($result as $i){
            $this->assertEqual($i->trip, $trip);
        }
	    Trace::function_exit();
    }
}
?>