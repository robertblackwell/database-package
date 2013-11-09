<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Category as Category;

class TestFindCategory extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }
    function test_1(){    
	    print __METHOD__."\n";
        $result = Category::find();
        $cats = array();
        foreach($result as $c){
            $cats[] = $c->category;
        }
//        var_dump($cats);
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Category");
	    print __METHOD__."\n";
    }
    function test_2(){    
	    print __METHOD__."\n";
        $result = Category::exists('vehicle');
        var_dump($result);
        $this->assertTrue($result);
	    print __METHOD__."\n";
    }
}
?>