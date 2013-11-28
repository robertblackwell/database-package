<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\EntryCountry;
class TestVOClass extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }
    function test_1(){    
	    Trace::function_entry();
        $result = EntryCountry::find();
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\EntryCountry");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
	    $trip='rtw';
        $result = EntryCountry::find_for_trip($trip);
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\EntryCountry");
        foreach($result as $i){
            $this->assertEqual($trip, $i->trip);
        }
	    Trace::function_exit();
    }

}
?>