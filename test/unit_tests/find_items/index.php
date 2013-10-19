<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;

class TestFindItems extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
        $result = Item::find(3);
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertEqual(count($result), 3);
        $this->assertEqual(get_class($result[0]), "Database\Models\Item");
        //var_dump($result);
    }
    function test_2(){
        $result = Item::find_latest();
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\Item");
        //var_dump($result);
    }
    function test_3(){
        $result = Item::find_for_country("Russia");
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\Item");
        $this->assertEqual($result[0]->country, "Russia");
        //var_dump($result);
    }
}
?>