<?php
// require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Category as Category;

class TestFindCategory extends \LiteTest\TestCase{
    function setUp(){
        // global $config;
		// Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }
    function test_1(){    
	    Trace::function_entry();
        $result = Category::find();
        $cats = array();
        foreach($result as $c){
            $cats[] = $c->category;
        }
       var_dump($cats);
        $this->assertFalse($result === null);
        $this->assertTrue(is_array($result));
        $this->assertFalse(count($result) === 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Category");
	    Trace::function_exit();
    }
    function test_1_1(){    
	    Trace::function_entry();
	    $trip='rtw';
        $result = Category::find_for_trip($trip);
        $cats = array();
        foreach($result as $c){
            $cats[] = $c->category;
            $this->assertEqual($c->trip,$trip);
        }
//        var_dump($cats);
        $this->assertFalse($result === null);
        $this->assertTrue(is_array($result));
        $this->assertFalse(count($result) === 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Category");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
        $result = Category::exists('vehicle');
        var_dump($result);
        $this->assertTrue($result);
	    Trace::function_exit();
    }
}
?>