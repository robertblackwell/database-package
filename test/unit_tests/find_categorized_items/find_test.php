<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\CategorizedItem;

class TestCategorizedItems extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }
    function test_1(){    
        $result = CategorizedItem::find();
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\CategorizedItem");
    }
    function test_2(){    
	    print __METHOD__."\n";
        $result = CategorizedItem::exists('vehicle',"electricalpart1");
        var_dump($result);
        $this->assertTrue($result);
	    print __METHOD__."\n";
    }
}

?>