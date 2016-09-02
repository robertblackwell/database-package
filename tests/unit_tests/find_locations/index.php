<?php

use Database\Object as Db;
use \Database\Models\EntryLocation;

class TestCategory extends \LiteTest\TestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
	    Trace::function_entry();
        $result = EntryLocation::find();
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\EntryLocation");
        $this->assertEqual($result[0]->trip, "rtw");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
        $result = EntryLocation::find_for_trip('rtw');
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\EntryLocation");
        $this->assertEqual($result[0]->trip, "rtw");
        foreach($result as $i){
            $this->assertEqual($i->trip, "rtw");
        }
		$this->assertEqual(count($result), 42);
	    Trace::function_exit();
    }
}
?>