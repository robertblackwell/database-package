<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\EntryLocation;

class TestCategory extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
        $result = EntryLocation::find_for_trip('rtw');
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\EntryLocation");
        $this->assertEqual($result[0]->trip, "rtw");
        //var_dump($result);
    }
}
?>